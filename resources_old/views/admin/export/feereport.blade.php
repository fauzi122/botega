<table style="border:1px solid black">
    <thead>
        <tr>
            <th colspan="15"><h1>Fee Professional</h1></th>
        </tr>
        <tr>
            <th colspan="3">Nomor Fee</th>
            <th colspan="12">: {{$fee_number ?? ''}}</th>
        </tr>
        <tr>
            <th colspan="3">Nama Professional</th>
            <th colspan="12">: {{$user->first_name}} {{$user->last_name}} ({{$user->id_no}})</th>
        </tr>
        <tr>
            <th colspan="3">NPWP</th>
            <th colspan="12">: {{$user->npwp}}</th>
        </tr>
        <tr>
            <th colspan="3">Periode</th>
            <th colspan="12">: {{$periode}}</th>
        </tr>

        <tr>
            <th colspan="15"></th>
        </tr>
        <tr>
            <th>NO.</th>
            <th>INVOICE #</th>
            <th>TANGGAL INVOICE</th>
            <th>NOMOR SO</th>
            <th>NOMOR SJ</th>
            <th>CUSTOMER</th>
            <th>NAMA BARANG</th>
            <th>HARGA SATUAN</th>
            <th>TOTAL PENJUALAN</th>
            <th>% DISKON</th>
            <th>DPP PENJUALAN</th>
            <th>FEE (%)</th>
            <th>FEE (IDR)</th>
            <th>PPH {{ intval($user->is_perusahaan) == 1 ? '23' : '21'}} (%)</th>
            <th>PPH  {{ intval($user->is_perusahaan) == 1 ? '23' : '21'}} (IDR)</th>
            <th>TOTAL PEMBAYARAN </th>
        </tr>
    </thead>
    <tbody>
    @php
        $no = 1;
        $total = 0;
        $totaljual = 0;
        $totaldpp = 0;
        $totalfee = 0;
        $totalpph = 0;
    @endphp

        @foreach($sum as $r)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$r->invoice_number}}</td>
                <td>{{$r->invoice_date}}</td>
                <td>{{$r->nomor_so}}</td>
                <td>{{$r->sj_number}}</td>
                <td>{{$r->customer}}</td>
                <td>{{$r->product}}</td>
                <td data-format="#,##0">{{$r->sale_price}}</td>
                <td data-format="#,##0">{{$r->total_price}}</td>
                <td >{{$r?->item_disc_percent}}</td>
                <td data-format="#,##0">{{$r->dpp_amount}}</td>
                <td  >{{$r->fee_percent}}</td>
                <td data-format="#,##0">{{$r->fee_amount}}</td>
                <td  >{{$r->pph_percent}}</td>
                <td data-format="#,##0">{{$r->pph_amount}}</td>
                <td data-format="#,##0">{{$r->total_pembayaran}}</td>
            </tr>
            @php
                $total += $r->total_pembayaran;
                $totaljual += $r->total_price;
                $totaldpp += $r->dpp_amount;
                $totalfee += $r->fee_amount;
                $totalpph += $r->pph_amount;
            @endphp
        @endforeach
    @php
        $potongan = 0;
    @endphp
        @foreach($paid as $pd)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$pd->no_inv}}</td>
                <td></td>
                <td>{{$pd->no_so}}</td>
                <td>{{$pd->no_sj}}</td>
                <td colspan="10"> {{ ($pd->keterangan == null ? 'Pembayaran Fee yang Telah dilakukan pada '.$pd->fee_date : $pd->keterangan)   }}</td>
                <td data-format="#,##0">{{$pd->nominal}}</td>
            </tr>
            @php
                $potongan += $pd->nominal;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="8" style="text-align: center">TOTAL</th>
            <th data-format="#,##0.-">{{$totaljual}}</th>
            <th data-format="#,##0.-"></th>

            <th data-format="#,##0.-">{{$totaldpp}}</th>
            <th data-format="#,##0.-"></th>
            <th data-format="#,##0.-">{{$totalfee}}</th>
            <th data-format="#,##0.-"></th>
            <th data-format="#,##0.-">{{$totalpph}}</th>
            <th data-format="#,##0.-">{{$total - $potongan}}</th>
        </tr>
    </tfoot>
</table>
