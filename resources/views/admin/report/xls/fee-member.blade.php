<div>
    @php
    $totalfee = $data->sum("fee_amount");
    $kategori = [];
    $kategoriPercent = [];
    $totalPercentRounded = 0;
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
            <td colspan="4">IDR {{ number_format($totalfee, 2) }}</td>
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
        $percentRounded = ceil($percent * 100) / 100; // Membulatkan ke atas 2 desimal
        $kategoriPercent[$d->category] = ($kategoriPercent[$d->category] ?? 0) + $percentRounded;
        $totalPercentRounded += $percentRounded;
        @endphp
        <tr>
            <td>{{ \Illuminate\Support\Carbon::parse($d->dt_acc)->format("d/m/Y") }}</td>
            <td colspan="2">{{ $d->nomor }}</td>
            <td>{{ $d->category }}</td>
            <td style="text-align: right">{{ number_format($d->dpp_amount, 2) }}</td>
            <td style="text-align: right">{{ number_format($d->total_pembayaran, 2) }}</td>
            <td style="text-align: right">{{ number_format($percentRounded, 2) }}%</td>
        </tr>
        @php
        $kategori[$d->category] = ($kategori[$d->category] ?? 0) + $d->total_pembayaran;
        @endphp
        @endforeach

        @php
        $diffPercent = 100 - $totalPercentRounded; // Hitung selisih persen untuk menjadi 100%
        if ($diffPercent > 0 && count($kategoriPercent) > 0) {
        $firstCategory = array_key_first($kategoriPercent);
        $kategoriPercent[$firstCategory] += $diffPercent;
        }
        @endphp

        @foreach($kategori as $k => $v)
        <tr>
            <td colspan="5">Total Kategori {{$k}}</td>
            <td style="text-align: right">IDR {{ number_format($v, 2) }}</td>
            <td style="text-align: right">{{ number_format($kategoriPercent[$k] ?? 0, 2) }}%</td>
        </tr>
        @endforeach
    </table>
</div>