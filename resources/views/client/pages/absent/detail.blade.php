@extends('client.layouts.app')

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
        <li class="breadcrumb-item text-gray-500">Home</li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Detail Absensi</h2>

    <section>
        <div class="card shadow-sm rounded-2xl py-3">
            <h2 class="anchor border-bottom px-4 pb-3">Detail Absensi</h2>
            <div class="px-4 pt-3">
                <h4 class="mb-1">Tanggal</h4>
                <span>12/12/2023</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Absen Masuk</h4>
                <span>07.35 WIB</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Lokasi Absen Masuk</h4>
                <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor alias possimus quod aliquid aperiam
                    inventore, numquam soluta dolorum voluptatum at repellendus amet, non fugit suscipit odit ipsa totam
                    fuga voluptas.</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Absen Pulang</h4>
                <span>10 Agustus 2000 </span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Tanggal Akhir</h4>
                <span>10 Agustus 2000 </span>
            </div>
        </div>
    </section>

</main>
@endsection
