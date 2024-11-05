<div>
    @php
        $totalfee = $data->sum("fee_amount");
        $kategori = [];
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
            <td colspan="4">IDR {{  number_format( $totalfee )  }}</td>
        </tr>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td>TANGGAL</td>
            <td  style="width: 100px;" colspan="2">NOMOR FEE</td>
            <td>KATEGORI</td>
            <td>TOTAL DPP</td>
            <td>TOTAL FEE</td>
            <td style="width: 50px">%</td>
        </tr>

        @foreach ($data as $d)
            <tr>
                <td>{{ \Illuminate\Support\Carbon::parse( $d->dt_acc )->format("d/m/Y") }}</td>
                <td colspan="2">{{ $d->nomor }}</td>
                <td>{{ $d->category }}</td>
                <td style="text-align: right">{{ number_format( doubleval( $d->dpp_amount ) ) }}</td>
                <td  style="text-align: right">{{ number_format( doubleval( $d->fee_amount ) ) }}</td>
                <td  style="text-align: right">{{ number_format( doubleval( ($d->fee_amount / $totalfee) * 100 ) ) }}%</td>
            </tr>
            @php
                $kategori[$d->category] = ($kategori[$d->category] ?? 0) + $d->fee_amount;
            @endphp
        @endforeach
        @foreach($kategori as $k => $v)
            <tr>
                <td colspan="5">Total Kategori {{$k}} </td>
                <td style="text-align: right">IDR {{  number_format( doubleval( $v ))  }}</td>
                <td style="text-align: right">{{ number_format( doubleval( ($v / $totalfee) * 100 ) ) }}%</td>
            </tr>
        @endforeach

    </table>
</div>
