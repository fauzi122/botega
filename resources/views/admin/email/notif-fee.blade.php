<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOTTEGA: Notifikasi Pengajuan Fee {{$fee->nomor}}</title>
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
    <h1>Notifikasi Pengajuan Fee</h1>
    <p>Halo {{$member->first_name}} {{$member->last_name}},</p>
    <p>Ini adalah pemberitahuan bahwa pengajuan fee Anda telah diterima dan {{$status}}.</p>
    <p>Berikut adalah detail pengajuan fee:</p>
    <ul>
        <li><strong>Nomor Pengajuan:</strong> {{$fee->nomor}}</li>
        <li><strong>Tanggal Pengajuan:</strong> {{$fee->dt_pengajuan}}</li>
        <li><strong>Jumlah Fee:</strong> {{number_format($fee->total,2)}}</li>
        <li><strong>Status:</strong> {{$status}}</li>
    </ul>
    <p>Kami akan segera memberi tahu Anda tentang status selanjutnya.</p>
    <p>Terima kasih.</p>
    <div class="footer">
        <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
    </div>
</div>
</body>
</html>
