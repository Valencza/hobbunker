@extends('client.layouts.app')

@section('title', 'Detail Pengumuman')

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
            <a href="{{route('announcement.index')}}" class="text-secondary text-decoration-none">Pengumuman</a>
        </li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Detail Pengumuman</h2>

    <section>
        <div class="card shadow-sm rounded-2xl p-3">
            <img src="{{ asset($announcement->image) }}" class="rounded-2xl mb-5" height="200">

            <h4 class="mb-1">Judul Pengumuman</h4>
            <span>{{$announcement->title}}</span>
            <div class="border-t border-1 border-dashed my-2"></div>

            <h4 class="mb-1">Deskripsi</h4>
            <span>{{$announcement->description}}</span>
            <div class="border-t border-1 border-dashed my-2"></div>

            <h4 class="mb-1">Jenis Pengumuman</h4>
            <span>{{ucwords($announcement->type)}}</span>
            <div class="border-t border-1 border-dashed my-2"></div>

            <h4 class="mb-1">Tanggal Mulai</h4>
            <span>{{$announcement->formatted_start_date}}</span>
            <div class="border-t border-1 border-dashed my-2"></div>

            <h4 class="mb-1">Tanggal Akhir</h4>
            <span>{{$announcement->formatted_end_date}}</span>
        </div>
    </section>

</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#kt_modal_1').modal('show');
    });

</script>
@endpush
