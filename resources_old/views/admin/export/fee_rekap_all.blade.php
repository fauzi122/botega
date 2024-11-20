@php
    $prd = [];
    foreach($data as $d){
        $prd[$d->periode] = $d->periode;
    }
    $periode = implode(', ', $prd);
    $periodes = explode(', ', $periode);
    $prd = [];
    foreach($periodes as $d){
        $prd[$d] = $d;
    }
    $periode = implode(', ', $prd);

@endphp
<table>
    <tr> <th style="font-weight: bold; font-size: 18px" colspan="12">PT BERKAT ARTISAN INDONESIA</th> </tr>
    <tr> <th style="font-weight: bold; font-size: 13px" colspan="12">HITUNGAN FEE PROFESIONAL</th> </tr>
    <tr> <th style="font-weight: bold; font-size: 13px" colspan="12">PERIODE: {{$periode}}</th> </tr>
    <tr> <th colspan="12"> </th> </tr>
    <tr> <th colspan="12"> </th> </tr>

    <tr>
        <th>NO</th>
        <th>NAMA PROFESIONAL</th>
        <th>NPWP</th>
        <th>PERIODE PENJUALAN</th>
        <th>NAMA SALES</th>
        <th>DPP PENJUALAN</th>
        <th>FEE</th>
        <th>PPh 21</th>
        <th>PPh 23</th>
        <th>TOTAL PEMBAYARAN</th>
        <th>NAMA BANK</th>
        <th>NOMOR REKENING</th>
        <th>NAMA REKEKNING</th>
    </tr>
    @php
        $no = 1;
        $total_dpp = 0;
        $total_fee = 0;
        $total_pph21 = 0;
        $total_pph23 = 0;
        $total = 0;
    @endphp
    @foreach($data as $row)
        <tr>
            <td>{{$no++}}</td>
            <td>{{$row->first_name}} {{$row->last_name}}</td>
            <td >{{ formatNPWP( $row->npwp ) }}</td>
            <td>{{$row->periode}}</td>
            <td>{{$row->salesname}}</td>
            <td data-format="#,##0">{{$row->dpp_amount}}</td>
            <td data-format="#,##0">{{$row->fee_amount}}</td>
            <td data-format="#,##0">-{{ intval($row->is_perusahaan) == 1 ? '' : $row->pph_amount}} </td>
            <td data-format="#,##0">-{{ intval($row->is_perusahaan) == 1 ?  $row->pph_amount : ''}}</td>
            <td data-format="#,##0">{{$row->total_pembayaran}}</td>
            <td>{{$row->nama_bank}}</td>
            <td>{{$row->no_rekening}}</td>
            <td>{{$row->an_rekening}}</td>
        </tr>
        @php
            $total_dpp += $row->dpp_amount;
            $total_fee += $row->fee_amount;
            $total += $row->total_pembayaran;
            $total_pph21 += intval($row->is_perusahaan) == 1 ? 0 : $row->pph_amount;
            $total_pph23 += intval($row->is_perusahaan) == 1 ?  $row->pph_amount : 0;
        @endphp
    @endforeach
    <tfoot>
        <tr>
            <th></th>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th data-format="#,##0.-">{{ $total_dpp  }}</th>
            <th data-format="#,##0.-">{{ $total_fee }}</th>
            <th data-format="#,##0.-">{{ $total_pph21  }}</th>
            <th data-format="#,##0.-">{{ $total_pph23 }}</th>
            <th data-format="#,##0.-">{{ $total  }}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
