<div>
    <style>
        /* public/css/style.css */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            padding: 20px;
            text-align: center;
        }

        .card-header {
            margin-bottom: 20px;
        }

        .card-title {
            color: #ff5722;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card-body p {
            font-size: 18px;
            line-height: 1.6;
        }

        .card-footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }

    </style>
    <div class="card">
        <div class="card-header">
            <img src="{{ asset('assets/images/bottega-brown.png') }}" alt="Bottega & Artisan Indonesia" style="width: 150px; height: auto;">
            <h1 class="card-title">Selamat Ulang Tahun!</h1>
        </div>

        <div class="card-body">
            <p>Halo, <strong>{{ $user->first_name }} {{$user->last_name}}</strong>!</p>
            <p>Kami dari <strong>Bottega & Artisan Indonesia</strong> mengucapkan selamat ulang tahun yang penuh kebahagiaan dan kesehatan.
                Semoga hari ini menjadi awal dari tahun yang penuh dengan pencapaian baru dan kesuksesan yang lebih besar!</p>

            <p>Terima kasih telah menjadi bagian dari perjalanan kami. Kami berharap Anda tetap bersama kami di masa depan yang lebih cerah.</p>

            <p>Salam hangat,<br>
                <strong>Bottega & Artisan Indonesia</strong></p>
        </div>

        <div class="card-footer">
            <p>&copy; {{ date('Y') }} Bottega & Artisan Indonesia. All rights reserved.</p>
        </div>
    </div><!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
</div>
