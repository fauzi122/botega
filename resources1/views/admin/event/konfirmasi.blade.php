<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <title>Bottega & Artisan - Konfirmasi Kehadiran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .logo {
            color: #1a9c61;
            font-size: 1.5rem;
        }
        .image-placeholder {
            width: 200px;
            height: auto;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
<div class="container text-center py-5">
    <div class="logo mb-4 font-weight-bold"><img src="{{asset('assets/images/bottega-brown.png')}}"  style="max-width: 20%; height: auto;"></div>
    <div class="image-placeholder mx-auto mb-4">
        @if($text == 'success')
            <img src="{{asset('assets/images/success.jpg')}}" style="max-width: 100%; height: auto;">
        @else
            <img src="{{asset('assets/images/failed.jpg')}}" style="max-width: 100%; height: auto;">
        @endif
    </div>
    <h2 class="text-{{$text}} mb-3">Halo, {{$nama}}</h2>
    <p class="mb-4">Terima kasih atas konfirmasi kehadiran Anda pada event Bottega & Artisan <b> "{{$event}}"</b>. Kami sangat menghargai waktu Anda dan berharap dapat menyambut kehadiran Anda pada event tersebut.</p>
    <a href="{{url('')}}" style="border-radius: 40px" class="btn btn-{{$text}} btn-lg">{{$button}}</a>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
