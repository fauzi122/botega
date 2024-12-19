<div>
    @php
    $totalfee = 0;
    $kategori = [];
    $kategoriPercent = [];
    $totalPercentRounded = 0;

    foreach ($data as $d) {
    $totalfee += $d->total_pembayaran; // Gunakan total pembayaran dari iterasi
    $kategori[$d->category] = ($kategori[$d->category] ?? 0) + $d->total_pembayaran;
    }

    foreach ($kategori as $k => $v) {
    $percent = ($v / $totalfee) * 100;
    $kategoriPercent[$k] = round($percent, 2);
    $totalPercentRounded += $kategoriPercent[$k];
    }

    // Hitung selisih persentase untuk memastikan total 100%
    $diffPercent = round(100 - $totalPercentRounded, 2);
    if ($diffPercent != 0 && count($kategoriPercent) > 0) {
    $firstCategory = array_key_first($kategoriPercent);
    $kategoriPercent[$firstCategory] += $diffPercent;
    }
    @endphp


    <table>
        <tr>
            <td colspan="2">Nama Professional</td>
            <td style="width: 9px">:</td>
            <td colspan="4">{{$user->first_name}} {{$user->last_name}} ({{$user->id_no}})</td>
        </tr>
        <tr>
            <td colspan="2">Periode</td>
            <td>:</td>
            <td colspan="4">{{\Carbon\Carbon::parse($periode_awal)->translatedFormat("d/m/Y") }} - {{\Carbon\Carbon::parse($periode_akhir)->format("d/m/Y")}}</td>
        </tr>
        <tr>
            <td colspan="2">Total Fee</td>
            <td>:</td>
            <td colspan="4">IDR {{ number_format($totalfee, 2) }}</td> <!-- Konsisten dengan iterasi -->
        </tr>

        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td>TANGGAL</td>
            <td style="width: 100px;" colspan="2">NOMOR FEE</td>
            <td>KATEGORI</td>
            <td>TOTAL DPP</td>
            <td>TOTAL FEE</td>
            <td style="width: 50px">%</td>
        </tr>

        @foreach ($data as $d)
        @php
        $percent = doubleval(($d->total_pembayaran / $totalfee) * 100);
        $percentRounded = round($percent, 2);
        @endphp
        <tr>
            <td>{{ \Illuminate\Support\Carbon::parse($d->dt_acc)->format("d/m/Y") }}</td>
            <td colspan="2">{{ $d->nomor }}</td>
            <td>{{ $d->category }}</td>
            <td style="text-align: right">{{ number_format($d->dpp_amount, 2) }}</td>
            <td style="text-align: right">{{ number_format($d->total_pembayaran, 2) }}</td>
            <td style="text-align: right">{{ number_format($percentRounded, 2) }}%</td>
        </tr>
        @endforeach

        @foreach($kategori as $k => $v)
        <tr>
            <td colspan="5">Total Kategori {{$k}}</td>
            <td style="text-align: right">IDR {{ number_format($v, 2) }}</td>
            <td style="text-align: right">{{ number_format($kategoriPercent[$k] ?? 0, 2) }}%</td>
        </tr>
        @endforeach
    </table>
</div>