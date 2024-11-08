@php
    $tangga = '2024-04-20'
@endphp

Hari ini adalah hari {{\Carbon\Carbon::parse($tangga)->locale('id_ID')->format('l')}} tanggal {{\Carbon\Carbon::parse($tangga)->locale('id_ID')->isoFormat('d F Y')}}
