<?php

namespace App\Jobs;

use App\Library\APIAccurate;
use App\Models\FeeNumberModel;
use App\Models\FeeNumberDP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDownPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $data;
    private $date1;
    private $date2;

    /**
     * Create a new job instance.
     *
     * @param string|null $date
     * @param array|null $data
     */
    public function __construct($date1 = null, $date2 = null, $data = null, $id = null)
    {
        $this->id = $id;
        $this->date1 = $date1;
        $this->date2 = $date2;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!empty($this->id)) {
            $this->deleteFeeNumberDP();
        } elseif (!empty($this->data)) {
            $this->createFeeNumberDP();
        } elseif (!empty($this->date1)) {
            $this->updateFeeNumberDPByDate();
        } else {
            Log::warning('SendDownPaymentJob dijalankan tanpa parameter yang valid.');
        }
    }

    /**
     * Membuat atau memperbarui data FeeNumberDP berdasarkan data yang diberikan.
     */
    public function deleteFeeNumberDP(): void
    {
        $api = new APIAccurate();

        try {
            // Endpoint untuk menghapus data berdasarkan ID
            $url = '/api/sales-invoice/delete.do?id=' . $this->id;
            // Panggil API DELETE
            $response = $api->delete($url);
            // dd($response);

            // Periksa hasil respons
            if ($response->status() === 200) {
                Log::info('Berhasil menghapus Down Payment.', [
                    'id' => $this->id,
                ]);
            } else {
                Log::error('Gagal menghapus Down Payment.', [
                    'id' => $this->id,
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus Down Payment.', [
                'id' => $this->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function createFeeNumberDP(): void
    {
        $api = new APIAccurate();

        try {
            $url = '/api/sales-invoice/create-down-payment.do';
            $response = $api->post($url, $this->data);

            if ($response->status() === 200) {
                $result = json_decode($response->body(), true);

                // Ekstraksi nilai penting dari respons API
                $dpId = $result['r']['id'] ?? null;
                $number = $result['r']['number'] ?? null;
                $status = $result['r']['approvalStatus'] ?? null;
                $poNumber = $result['r']['poNumber'] ?? null;

                if ($dpId && $number && $poNumber) {
                    $feeNumber = FeeNumberModel::where('nomor', $poNumber)->first();

                    if ($feeNumber) {
                        $feeNumberId = $feeNumber->id;

                        // Buat atau perbarui FeeNumberDP
                        FeeNumberDP::updateOrCreate(
                            ['fee_number_id' => $feeNumberId],
                            [
                                'dp_id' => $dpId,
                                'number' => $number,
                                'status' => $status,
                                'updated_at' => now(),
                            ]
                        );

                        Log::info('Down Payment berhasil dibuat/diperbarui.', [
                            'fee_number_id' => $feeNumberId,
                            'dp_id' => $dpId,
                            'number' => $number,
                            'status' => $status,
                        ]);
                    } else {
                        Log::warning('FeeNumberModel tidak ditemukan untuk poNumber.', [
                            'poNumber' => $poNumber,
                        ]);
                    }
                }
            } else {
                Log::error('Gagal membuat Down Payment.', [
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Kesalahan pada createFeeNumberDP.', [
                'message' => $e->getMessage(),
                'data' => $this->data,
            ]);
        }
    }

    /**
     * Memperbarui data FeeNumberDP berdasarkan tanggal.
     */
    public function updateFeeNumberDPByDate(): void
    {
        $api = new APIAccurate();

        try {
            // Mengambil daftar data berdasarkan tanggal
            $listUrl = '/api/sales-invoice/list.do?fields=' . urlencode('id,number') . '&filter.invoiceDp=true&filter.transDate.op&filter.transDate.val[0]=' . urlencode($this->date1) . '&filter.transDate.val[1]=' . urlencode($this->date2);
            $listResponse = $api->get($listUrl);
            if ($listResponse->status() === 200) {
                $listData = json_decode($listResponse->body(), true);

                if (isset($listData['d']) && count($listData['d']) > 0) {
                    foreach ($listData['d'] as $item) {
                        $dpId = $item['id'] ?? null;

                        if ($dpId) {
                            // Mengambil detail berdasarkan dpId
                            $detailUrl = '/api/sales-invoice/detail.do?id=' . urlencode($dpId);
                            $detailResponse = $api->get($detailUrl);

                            if ($detailResponse->status() === 200) {
                                $detail = json_decode($detailResponse->body(), true);

                                $approvalStatus = $detail['d']['approvalStatus'] ?? null;
                                $number = $detail['d']['number'] ?? null;

                                if ($approvalStatus && $number) {
                                    $feeDP = FeeNumberDP::where('dp_id', $dpId)->first();
                                    // dd($feeDP);

                                    if ($feeDP) {
                                        $feeDP->update([
                                            'status' => $approvalStatus,
                                            'number' => $number,
                                            'updated_at' => now(),
                                        ]);

                                        Log::info('Berhasil memperbarui FeeNumberDP.', [
                                            'dp_id' => $dpId,
                                            'status' => $approvalStatus,
                                            'number' => $number,
                                        ]);
                                    } else {
                                        Log::warning('FeeNumberDP tidak ditemukan untuk dpId.', ['dp_id' => $dpId]);
                                    }
                                } else {
                                    Log::warning('Data detail dari API tidak lengkap.', [
                                        'dp_id' => $dpId,
                                        'response' => $detail,
                                    ]);
                                }
                            } else {
                                Log::error('Gagal mengambil detail untuk dpId.', [
                                    'dp_id' => $dpId,
                                    'response' => $detailResponse->body(),
                                ]);
                            }
                        }
                    }
                } else {
                    Log::warning('Tidak ada data untuk tanggal yang diberikan.', ['date' => $this->date]);
                }
            } else {
                Log::error('Gagal mengambil daftar data untuk tanggal.', [
                    'date' => $this->date,
                    'response' => $listResponse->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Kesalahan pada updateFeeNumberDPByDate.', [
                'message' => $e->getMessage(),
                'date' => $this->date,
            ]);
        }
    }
}
