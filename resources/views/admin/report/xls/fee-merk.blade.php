<div>
    @php
        $totalfee = $data->sum("fee_amount");
    @endphp
    <table>
        <tr>
            <td colspan="2">Kategori</td>
            <td style="width: 9px">:</td>
            <td colspan="4">{{$kategori->category}}</td>
        </tr>
        <tr>
            <td colspan="2">Merk</td>
            <td style="width: 9px">:</td>
            <td colspan="4">{{ implode(", ", $merk) }}</td>
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
            <td>MERK</td>
            <td>TOTAL DPP</td>
            <td>TOTAL FEE</td>
            <td style="width: 50px">%</td>
        </tr>
        @php 
            $coll = [];
        @endphp
        @foreach ($data as $d)
            <tr>
                <td>{{ \Illuminate\Support\Carbon::parse( $d->dt_acc )->format("d/m/Y") }}</td>
                <td colspan="2">{{ $d->nomor }}</td>
                <td>{{ $d->merk }} </td>
                <td style="text-align: right">{{ number_format( doubleval( $d->dpp_amount ) ) }}</td>
                <td  style="text-align: right">{{ number_format( doubleval( $d->fee_amount ) ) }}</td>
                <td  style="text-align: right">{{ number_format( doubleval( ($d->fee_amount / $totalfee) * 100 ), 2 ) }}%</td>
            </tr>
            @php 
                $coll[$d->merk] = ($coll[$d->merk] ?? 0) + $d->fee_amount;
            @endphp
        @endforeach

        @foreach ($coll as $m=>$v)
            <tr>
                <td colspan="5">Total merk {{$m}}</td>
                <td style="text-align: right">IDR {{  number_format( $v ?? 0 )  }}</td>
                <td style="text-align: right"> {{ number_format($v / $totalfee * 100,2) }}%</td>
            </tr>
        @endforeach



    </table>
</div>
