@extends('client.layouts.app')

@section('title', 'Cetak Barcode')

@section('content')
<main class="p-4">
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">
            <a href="{{route('home')}}" class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
            <a href="{{route('home')}}" class="text-secondary text-decoration-none">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('stock-inventory-office')}}" class="text-secondary text-decoration-none">Stok & Inventaris
                kantor</a>
        </li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Cetak Barcode</h2>

    <section class="rounded-2xl shadow-sm card" id="download-area" style="width: 300px; height: 400px; margin: auto;">
        <div class="d-flex flex-column align-items-center">
            <img class="mb-3 mt-5" width="80" src="{{ asset('assets/media/logos/blueLogo.svg') }}" alt="HOB Logo">
            <img src="{{$officeStock->barcode}}" width="100" height="185" class="w-75 px-4" alt="">
        </div>
        <div class="text-center mt-5">
            <h5>{{$officeStock->masterItem->name}}</h5>
            <h6 class="text-secondary">{{$officeStock->code}}</h6>
        </div>
    </section>

    <button id="download-barcode" class="btn btn-primary w-100 mt-5">Cetak Barcode</button>

</main>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    $(document).ready(function() {
        $('#download-barcode').on('click', function() {
            html2canvas($('#download-area')[0]).then(function(canvas) {
                // Convert the canvas to a data URL
                var imgData = canvas.toDataURL('image/jpeg');
                // Create a link element
                var link = $('<a></a>').attr({
                    href: imgData,
                    download: '{{$officeStock->code}}.jpg'
                });
                // Append the link to the document body
                $('body').append(link);
                // Programmatically click the link to trigger the download
                link[0].click();
                // Remove the link from the document
                link.remove();
            });
        });
    });
</script>
@endsection

