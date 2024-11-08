@extends('backoffice.layouts.app')

@section('title', 'Laporan Inspeksi Kapal')

@section('content')
<div class="content flex-row-fluid" id="kt_content">
    {{-- breadcrumb --}}
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">
            <div class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </div>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-500">Laporan Inspeksi Kapal</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Detail Inspeksi Kapal</h3>

    <section class="py-5 card p-5 rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <h3>{{$inspection->masterShip->name}}</h3>
                <p>PIC: {{$inspection->masterUser->name}}</p>
                <p>{{$inspection->formatted_created_at}}</p>
            </div>
        </div>

        <hr>

        <h3 class="my-3">Kru Kapal</h3>
        <div class="row">
        @foreach ($inspection->inspectionCrews as $inspectionCrew)
            <div class="col-12 col-md-2">
                <p>{{$inspectionCrew->name}} - {{$inspectionCrew->position}}</p>
            </div>
        @endforeach
        </div>

        <hr>

        <h3 class="my-3">Kondisi Dek</h3>
        <div class="row">
        @foreach ($inspectionDetailsDeck as $inspectionDetailDeck)
            <div class="col-12 col-md-4">
                <img width="200" height="200" style="object-fit: contain" src="{{asset($inspectionDetailDeck->photo)}}">
                <p>{{$inspectionDetailDeck->description}}</p>
            </div>
        @endforeach
        </div>

        <hr>

        <h3 class="my-3">Kondisi Anjungan</h3>
        <div class="row">
        @foreach ($inspectionDetailsPlatform as $inspectionDetailPlatform)
            <div class="col-12 col-md-4">
                <img width="200" height="200" style="object-fit: contain" src="{{asset($inspectionDetailPlatform->photo)}}">
                <p>{{$inspectionDetailPlatform->description}}</p>
            </div>
        @endforeach
        </div>

        <hr>

        <h3 class="my-3">Kondisi Dapur</h3>
        <div class="row">
        @foreach ($inspectionDetailsKitchen as $inspectionDetailKitchen)
            <div class="col-12 col-md-4">
                <img width="200" height="200" style="object-fit: contain" src="{{asset($inspectionDetailKitchen->photo)}}">
                <p>{{$inspectionDetailKitchen->description}}</p>
            </div>
        @endforeach
        </div>

        <hr>

        <h3 class="my-3">Kondisi Kamar Mesin</h3>
        <div class="row">
        @foreach ($inspectionDetailsMeachine as $inspectionDetailMeachine)
            <div class="col-12 col-md-4">
                <img width="200" height="200" style="object-fit: contain" src="{{asset($inspectionDetailMeachine->photo)}}">
                <p>{{$inspectionDetailMeachine->description}}</p>
            </div>
        @endforeach
        </div>

        <hr>

        <h3 class="my-3">Kondisi Alat Keselamatan</h3>
        <div class="row">
        @foreach ($inspectionDetailsSafety as $inspectionDetailSafety)
            <div class="col-12 col-md-4">
                <img width="200" height="200" style="object-fit: contain" src="{{asset($inspectionDetailSafety->photo)}}">
                <p>{{$inspectionDetailSafety->description}}</p>
            </div>
        @endforeach
        </div>

        <hr>

        <h3 class="my-3">Kondisi Alat Navigasi</h3>
        <div class="row">
        @foreach ($inspectionDetailsNavigation as $inspectionDetailNavigation)
            <div class="col-12 col-md-4">
                <img width="200" height="200" style="object-fit: contain" src="{{asset($inspectionDetailNavigation->photo)}}">
                <p>{{$inspectionDetailNavigation->description}}</p>
            </div>
        @endforeach
        </div>

        <hr>

        <h3 class="my-3">Kondisi Obat - Obatan</h3>
        <div class="row">
        @foreach ($inspectionDetailsMedicine as $inspectionDetailMedicine)
            <div class="col-12 col-md-4">
                <img width="200" height="200" style="object-fit: contain" src="{{asset($inspectionDetailMedicine->photo)}}">
                <p>{{$inspectionDetailMedicine->description}}</p>
            </div>
        @endforeach
        </div>
    </section>
</div>
@endsection
