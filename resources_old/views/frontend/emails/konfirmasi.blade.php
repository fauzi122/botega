@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun</title>
    <!-- Bootstrap CSS (Inline) -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Roboto, sans-serif;
            background-color: #4e555b;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            margin-top: 50px;
            width: 100%;
            max-width: 600px; /* Sesuaikan lebar maksimal sesuai kebutuhan */
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .logo {
            width: 40%;
        }

        .header {
            background-color: saddlebrown;
            color: #fff;
            padding: 10px;
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
            padding: 20px;
            background-color: white;
            align-items: center;
        }

        .card-title {
            font-size: 1.2em;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 1em;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: saddlebrown;
            border: 1px solid saddlebrown;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            text-decoration: none;
        }
    </style>
</head>


<body style="  margin: 0;
            padding: 0;
            font-family: Roboto, sans-serif;
            background-color: #b7bbbb;
            display: flex;
            align-items: center;
            justify-content: center;
           ">
<div class="container" style="margin-bottom: 20px">
    <div class="card">
        <div class="header">
            <center>
                <img src="https://www.bottegaartisan.com/images/logobottega.png" alt="" class="logo">
            </center>
        </div>
        <div class="card-body">
            <h5 class="card-title">Halo, {{$user}}</h5>
            <p class="card-text">Kami dari tim Bottega & Artisan akan mengadakan event sebagai berikut.</p>
            <table style="width: 100%; " border="0">
                <tr>
                    <th style="width: 30px; text-align: left; vertical-align: top">Event</th>
                    <td>:</td>
                    <td style="">{{$judul}}</td>
                </tr>
                <tr>
                    <th style="text-align: left; vertical-align: top">Tanggal</th>
                    <td>:</td>
                    <td style="">{{date('d M Y', strtotime($start))}}
                        s.d {{date('d M Y', strtotime($end))}}</td>
                </tr>

                <tr>
                    <th style="text-align: left; vertical-align: top">Keterangan</th>
                    <td style="text-align: left; vertical-align: top">:</td>
                    <td style="">{!! $descriptions !!}</td>
                </tr>
            </table>

            <br>

            <center>
                <a href="{{$resetLink}}" class="btn"
                   style="background-color: saddlebrown; color: #fff; text-decoration: none;">Konfrimasi Hadir</a> <br>
                <small>*Jika Anda tidak merasa melakukan permintaan ini, Anda dapat mengabaikan email ini.</small>
            </center>

            <p>Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin dan menempel tautan berikut di browser
                Anda:</p>
            <p>{{$resetLink}}</p>

            <p>Terima kasih atas partisipasi Anda. Kami sangat menghargai kehadiran Anda di Komunitas kami.</p>
            <p>Salam hangat,<br> Tim Program Bottega & Artisan</p>
        </div>
    </div>
</div>


</body>
</html>
