<div>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-size: 10px;
            font-family: Roboto, sans-serif;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            margin-top: 10px;
            width: 100%;
             margin-left: auto;
            margin-right: auto;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .logo {
            width: 30%;
        }

        .header {
            background-color: saddlebrown;
            color: #fff;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header h3 {
            margin-left: 10px;
            font-size: 1.5em;
            margin: 0;
        }

        .card-body {
            padding: 10px;
            background-color: white;
            align-items: center;
        }
        table{
            border-collapse: collapse;
        }
        table tr.b th, table tr.b td{
            border-collapse: collapse;
            border: 1px solid #c7c5cb;
        }

    </style>

    <div class="container" style="margin-bottom: 20px">
        <div class="card">
            <div class="header">
                <center>
                    <img src="https://www.bottegaartisan.com/images/logobottega.png" alt="" class="logo">
                </center>
            </div>
            <div class="card-body">

                <table class="email-table">

                        <thead>
                        <tr>
                            <td colspan="3">ID MEMBER PROFESSIONAL</td>
                            <td colspan="14">: {{$member->id_no}}</td>
                        </tr>
                        <tr>
                            <td colspan="3">NAMA LENGKAP</td>
                            <td colspan="14">: {{$member->first_name}} {{$member->last_name}}</td>
                        </tr>
                        <tr>
                            <td colspan="3">PERIODE PEMBAYARAN FEE</td>
                            <td colspan="14">: {{$fee->periode}} </td>
                        </tr>
                        <tr>
                           <td colspan="17">&nbsp; <br/></td>
                        </tr>
                        <tr class="b">
                            <th style="width:30px">NO</th>
                            <th >INVOICE NO</th>
                            <th>TANGGAL INVOICE</th>
                            <th>NOMOR SO</th>
                            <th >NOMOR SJ</th>
                            <th >CUSTOMER</th>
                            <th >BARANG</th>
                            <th>QTY</th>
                            <th>HARGA SATUAN</th>
{{--                            <th>DISKON</th>--}}
{{--                            <th>TOTAL PENJUALAN</th>--}}
                            <th>DPP PENJUALAN</th>
                            <th>FEE (%)</th>
                            <th>FEE (IDR)</th>
                            <th>PPH</th>
                            <th>(%) BAYAR</th>
                            <th>TOTAL BAYAR</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $no = 1;
                            $total = 0;
                            $totalpph = 0;
                            $totaljual = 0;
                            $totaldpp = 0;
                            $paymentmade = 0;
                        @endphp
                        @foreach($feepro as $d)

                            <tr class="b">
                                <td>{{$no++}}</td>
                                <td>
                                    {{$d->invoice_number}}

                                </td>
                                <td>{{$d->invoice_date}}</td>
                                <td>{{$d->nomor_so}}</td>
                                <td>
                                    {{ $d->sj_number }}

                                </td>
                                <td>{{$d->customer}}</td>
                                <td>{{$d->product}}</td>

                                <td style="text-align: right">{{ number_format($d->pqty,2)  }}</td>
                                <td style="text-align: right">{{ number_format($d->sale_price,2)  }}</td>
{{--                                <td style="text-align: right">{{ number_format($d->discount,2)  }}</td>--}}
{{--                                <td style="text-align: right">{{ number_format($d->total_price,2)  }}</td>--}}
                                <td style="text-align: right">{{ number_format($d->dpp_amount,2)  }}</td>
                                <td style="text-align: right;">
                                    {{$d->fee_percent}}
                                </td>
                                <td style="text-align: right">{{ number_format($d->fee_amount,2)  }}</td>
                                <td style="text-align: right">{{ number_format($d->pph_amount,2)  }} ({{$d->pph_percent}}%)</td>
                                <td>
                                    {{$d->percentage_fee}} %
                                </td>
                                <td style="text-align: right">
                                    {{ number_format($d->total_pembayaran,2)  }} <br/>

                                </td>

                            </tr>
                            @php
                                $total += $d->total_pembayaran;
                                $totalpph += $d->pph_amount;
                                $totaljual += $d->total_price;
                                $totaldpp += $d->dpp_amount
                            @endphp
                        @endforeach
                        @foreach($paid as $pd)

                            <tr class="b">
                                <td>{{$no++}}</td>
                                <td>
                                    {{$pd->no_inv}}

                                </td>
                                <td> </td>
                                <td>{{$pd->no_so}}</td>
                                <td>
                                    {{ $pd->no_sj }}

                                </td>
                                <td colspan="9">Fee kelebihan sebelumnya yang telah terbayarkan pada {{$pd->fee_date}} </td>

                                <td style="text-align: right">
                                    {{ number_format($pd->nominal,2)  }} <br/>

                                </td>

                            </tr>
                            @php
                                $paymentmade += $pd->nominal;
                            @endphp
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="b">
                            <th colspan="11">TOTAL</th>
                            <th></th>
                            <th>{{ number_format($totalpph)  }}</th>
                            <th></th>
                            <th>{{ number_format($total - $paymentmade)  }}</th>
                        </tr>
                        <tr>
                            <td colspan="15">
                                Tanggal Cetak {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y, H:i:s')  }} WIB
                            </td>
                        </tr>
                        </tfoot>

                </table>

            </div>
        </div>

</div>
