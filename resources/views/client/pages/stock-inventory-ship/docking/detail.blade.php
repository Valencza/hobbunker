@extends('client.layouts.app')

@section('title', 'Detail Docking')

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
            <li class="breadcrumb-item text-gray-500">Jadwal Docking</li>
            <!--end::Item-->
        </ul>

        <h2 class="anchor mb-5 mt-3">Detail Riwayat Docking</h2>

        <section class="card shadow-sm rounded-2xl mb-3">
            <h3 class=" border-1 border-bottom p-4">{{$docking->masterShip->name}}</h3>
            <div class="px-4 py-3">
                <h4 class="mb-1 text-capitalize">Judul</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$docking->title}}</h5>

                <h4 class="mb-1">Tipe Docking</h4>
                <h5 class="pb-4 text-secondary mt-2x">{{ucwords($docking->type)}}</h5>

                <h4 class="mb-1 text-capitalize">Keterangan</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$docking->description}}</h5>

                @if ($dockingAdmninistrations)
                <h4 class="mb-1 text-capitalize">File Administrasi</h4>
                <div class="row my-3">
                    @foreach ($dockingAdmninistrations as $index => $dockingAdmninistration)
                    <a href="{{asset($dockingAdmninistration->file)}}" download="File Administrasi ({{$index+1}}) Docking {{$docking->title}}" class="col-3 col-md-1">
                        <div class="btn btn-success p-3">
                            <i class="bi bi-file-earmark fs-1 text-light"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                @if (count($dockingReports) > 0)
                <h4 class="mb-1 text-capitalize">Dokumen Laporan Docking</h4>
                <div class="row my-3">
                    @foreach ($dockingReports as $index => $dockingReport)
                    <a href="{{asset($dockingReport->file)}}" download="Dokumen Laporan Docking ({{$index+1}}) Docking {{$docking->title}}" class="col-3 col-md-1">
                        <div class="btn btn-success p-3">
                            <i class="bi bi-file-earmark fs-1 text-light"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                @if (count($dockingCertificates) > 0)
                <h4 class="mb-1 text-capitalize">Sertifikat Docking</h4>
                <div class="row my-3">
                    @foreach ($dockingCertificates as $index => $dockingCertificate)
                    <a href="{{asset($dockingCertificate->file)}}" download="Sertifikat Docking ({{$index+1}}) Docking {{$docking->title}}" class="col-3 col-md-1">
                        <div class="btn btn-success p-3">
                            <i class="bi bi-file-earmark fs-1 text-light"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                @if (count($dockingOffers1) > 0)
                <h4 class="mb-1 text-capitalize">Penawaran I</h4>
                <div class="row my-3">
                    @foreach ($dockingOffers1 as $index => $dockingOffer)
                    <a href="{{asset($dockingOffer->file)}}" download="Penawaran I ({{$index+1}}) Docking {{$docking->title}}" class="col-3 col-md-1">
                        <div class="btn btn-success p-3">
                            <i class="bi bi-file-earmark fs-1 text-light"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                @if (count($dockingOffers2) > 0)
                <h4 class="mb-1 text-capitalize">Penawaran II</h4>
                <div class="row my-3">
                    @foreach ($dockingOffers2 as $index => $dockingOffer)
                    <a href="{{asset($dockingOffer->file)}}" download="Penawaran II ({{$index+1}}) Docking {{$docking->title}}" class="col-3 col-md-1">
                        <div class="btn btn-success p-3">
                            <i class="bi bi-file-earmark fs-1 text-light"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                @if (count($dockingOffers3) > 0)
                <h4 class="mb-1 text-capitalize">Penawaran III</h4>
                <div class="row my-3">
                    @foreach ($dockingOffers3 as $index => $dockingOffer)
                    <a href="{{asset($dockingOffer->file)}}" download="Penawaran III ({{$index+1}}) Docking {{$docking->title}}" class="col-3 col-md-1">
                        <div class="btn btn-success p-3">
                            <i class="bi bi-file-earmark fs-1 text-light"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                @if (count($dockingFixs) > 0)
                <h4 class="mb-1 text-capitalize">Repair List</h4>
                <div class="row my-3">
                    @foreach ($dockingFixs as $index => $dockingFix)
                    <a href="{{asset($dockingFix->file)}}" download="Repair List ({{$index+1}}) Docking {{$docking->title}}" class="col-3 col-md-1">
                        <div class="btn btn-success p-3">
                            <i class="bi bi-file-earmark fs-1 text-light"></i>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </section>

    </main>
@endsection
