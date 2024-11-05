<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOTTEGA: Notifikasi Pembayaran Fee {{$fee->nomor}}</title>
    <style>
        /* CSS untuk styling email */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Notifikasi Pengajuan Fee Telah Dibayarkan</h1>
    <p>Halo {{$member->first_name}} {{$member->last_name}},</p>
    <p>Ini adalah pemberitahuan bahwa pengajuan fee Anda telah dibayarkan.</p>
    <p>Berikut adalah detail pembayaran fee:</p>
    <ul>
        <li><strong>Nomor Pengajuan:</strong> {{$fee->nomor}}</li>
        <li><strong>Tanggal Pengajuan:</strong> {{$fee->dt_pengajuan}}</li>
        <li><strong>Jumlah Fee:</strong> Rp. {{number_format($fee->total,2)}}</li>
        <li><strong>Bank:</strong> {{$fee->bank}}</li>
        <li><strong>No. Rekening:</strong> {{$fee->no_rekening}}</li>
        <li><strong>Atas Nama :</strong> {{$fee->an_rekening}}</li>
        <li><strong>No. Faktur :</strong> {{$fee->no_faktur}}</li>
    </ul>
    <style>
        /* Style for table */
        .email-table {
            display: flex;
            flex-direction: column;
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        /* Style for table row */
        .email-table-row {
            display: flex;
            width: 100%;
            border-bottom: 1px solid #ddd;
            background-color: #f2f2f2; /* Warna default */
        }

        /* Style for table row ketika index genap */
        .email-table-row:nth-child(even) {
            background-color: #e6e6e6; /* Warna untuk baris genap */
        }

        /* Style for table data */
        .email-table-data {
            flex: 1;
            padding: 10px;
            border-right: 1px solid #ddd;
        }

        /* Style for last table data */
        .email-table-data:last-child {
            border-right: none;
        }
    </style>
    <p>
        No. SO : <b>{{$feepro->first->nomor_so}}</b> telah dibayarkan pada tanggal {{  \Illuminate\Support\Carbon::parse($feepro->dt_finisih)->translatedFormat('E, d-M-Y')  }} <br/>

    <table class="email-table">
        <tr class="email-table-row">
            <thead>
            <tr>
                <th style="width:40px">NO</th>
                <th style="width:200px">INVOICE NO</th>
                <th>TANGGAL INVOICE</th>
                <th>NOMOR SO</th>
                <th style="width:200px">NOMOR SJ</th>
                <th style="width:300px">CUSTOMER</th>
                <th style="width:300px">BARANG</th>
                <th>QTY</th>
                <th>HARGA SATUAN</th>
                <th>DISKON</th>
                <th>TOTAL PENJUALAN</th>
                <th>DPP PENJUALAN</th>
                <th>FEE (%)</th>
                <th>FEE (IDR)</th>
                <th>PPH</th>
                <th style="width:80px">PERSENTASE PEMBAYARAN</th>
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

                <tr>
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
                    <td style="text-align: right">{{ number_format($d->discount,2)  }}</td>
                    <td style="text-align: right">{{ number_format($d->total_price,2)  }}</td>
                    <td style="text-align: right">{{ number_format($d->dpp_amount,2)  }}</td>
                    <td style="text-align: right; width: 100px">
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

                <tr>
                    <td>{{$no++}}</td>
                    <td>
                        {{$pd->no_inv}}

                    </td>
                    <td> </td>
                    <td>{{$pd->no_so}}</td>
                    <td>
                        {{ $pd->no_sj }}

                    </td>
                    <td colspan="11">Fee kelebihan sebelumnya yang telah terbayarkan pada {{$pd->fee_date}} </td>

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
            <tr>
                <th colspan="11">TOTAL</th>
                <th>{{ number_format($totaljual)  }}</th>
                <th>{{ number_format($totaldpp)  }}</th>
                <th></th>
                <th>{{ number_format($totalpph)  }}</th>
                <th>{{ number_format($total - $paymentmade)  }}</th>
            </tr>
            </tfoot>
        </tr>
    </table>

    </p>
    <p>Terima kasih.</p>
    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
    </div>
</div>
</body>
</html>
