<?php

namespace App\Jobs;

use App\Models\ArticleModel;
use App\Models\FeeProfessionalModel;
use App\Models\ResumeDashboardModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResumeDashboarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $jmlmember = UserModel::query()->where('user_type','member')->count();
        $jmlmemberbaru = UserModel::query()->where('user_type', 'member')
                            ->where('created_at', '>', Carbon::now()->subWeeks(1))->count();
        $this->setN(ResumeDashboardModel::JML_MEMBER, $jmlmember);
        $this->setN(ResumeDashboardModel::JML_MEMBER_BARU, $jmlmemberbaru);

        $jmlpenjuaan = TransactionModel::query()->count();
        $jmlpenjualan2PekanLalu = TransactionModel::query()
                                    ->whereRaw('(tgl_invoice IS NOT NULL OR LENGTH(tgl_invoice) > 5 )')
                                    ->whereBetween('trx_at', [Carbon::now()->subWeeks(3), Carbon::now()->subWeeks(2)])->count();
        $jmlpenjualanPekanLalu = TransactionModel::query()
                                    ->whereRaw('(tgl_invoice IS NOT NULL OR LENGTH(tgl_invoice) > 5 )')
                                    ->whereBetween('trx_at', [Carbon::now()->subWeeks(2), Carbon::now()->subWeeks(1)])->count();
        $this->setN(ResumeDashboardModel::JML_PENJUALAN, $jmlpenjuaan);
        $this->setN(ResumeDashboardModel::JML_PENJUALAN_2PEKAN_LALU, $jmlpenjualan2PekanLalu);
        $this->setN(ResumeDashboardModel::JML_PENJUALAN_PEKAN_LALU, $jmlpenjualanPekanLalu);

        $totalPenjualan = TransactionModel::query()
                            ->whereRaw('(tgl_invoice IS NOT NULL OR LENGTH(tgl_invoice) > 5 )')->sum('total');
        $totalPenjualanTahunINi = TransactionModel::query()
                                    ->whereRaw('(tgl_invoice IS NOT NULL OR LENGTH(tgl_invoice) > 5 )')
                                    ->whereBetween('trx_at', [Carbon::now()->firstOfYear(), Carbon::now()])->sum('total');

        $this->setN(ResumeDashboardModel::TOTAL_PENJUALAN, $totalPenjualan);
        $this->setN(ResumeDashboardModel::TOTAL_PENJUALAN_TAHUN_INI, $totalPenjualanTahunINi);

        $feeTotal = FeeProfessionalModel::query()
            ->whereBetween('dt_finish', [Carbon::now()->firstOfYear(), Carbon::now()])->sum('total_pembayaran');
        $feeBlmDibayar = FeeProfessionalModel::query()
            ->where('harus_dibayar', '>', 0)
            ->whereBetween('dt_acc', [Carbon::now()->firstOfYear(), Carbon::now()])->sum('harus_dibayar');

        $this->setN(ResumeDashboardModel::TOTAL_FEE, $feeTotal);
        $this->setN(ResumeDashboardModel::TOTAL_FEE_BELUM_DIBAYAR, $feeBlmDibayar);

        $jmlArtikel = ArticleModel::query()->count();
        $jmlArtikelBaru = ArticleModel::query()
                            ->where('created_at', '>', Carbon::now()->subWeeks(1))->count();

        $this->setN(ResumeDashboardModel::JML_ARTIKEL, $jmlArtikel);
        $this->setN(ResumeDashboardModel::JML_ARTIKEL_BARU, $jmlArtikelBaru);

    }

    private function setN($kunci, $nilai){
        ResumeDashboardModel::query()->updateOrInsert([
            'kunci' => $kunci
        ],[
            'nilai' => $nilai
        ]);
    }
}
