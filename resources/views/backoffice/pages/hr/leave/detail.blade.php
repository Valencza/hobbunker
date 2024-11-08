@extends('backoffice.layouts.app')

@section('title', 'Detail Cuti')

@section('content')
<div class="content flex-row-fluid" id="kt_content">
    {{-- breadcrumb --}}
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">
            <a href="{{route('home')}}" class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-secondary">Laporan</li>
        <li class="breadcrumb-item">
            <a href="{{route('leave-report')}}" class="text-secondary text-decoration-none">Laporan Cuti</a>
        </li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Detail Cuti</h3>

    <section class="row ">
        <div class="col-4">
            <div class="card rounded-3 px-4 py-3 bg-white">
                <div class="img-profile rounded-circle w-100px h-100px mx-auto">
                    <x-acronym text="{{$user->name}}"></x-acronym>
                </div>
                <div class="text-center">
                    <h3 class="mt-2">{{$user->name}}</h3>
                    <p class="text-gray">{{$user->email}}</p>
                </div>
                <div>
                    <div class="px-3">
                        <h4 class="border-bottom-dashed border-gray border-1 pb-3 fw-normal mt-5">
                            Details</h4>

                        <h5 class="fw-normal">Nomor Induk Karyawan</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">ID-{{$user->nik}}</h5>

                        <h5 class="fw-normal">Jabatan</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->masterRole->name}}</h5>

                        <h5 class="fw-normal">Telepon</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->phone}}</h5>

                        {{-- <h5 class="fw-normal">Jumlah Tanggungan</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">4</h5> --}}

                        <h5 class="fw-normal">Alamat</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->address}}</h5>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-8">
            <div class="d-flex gap-3 mb-5">
                <div class="card bg-white w-100 rounded-3 p-4">
                    <i class="bi bi-stopwatch text-green fs-2"></i>
                    <h1 class="text-green my-3">{{$countLeaveBalanceYear}}</h1>
                    <h4 class="text-gray-400 fw-normal">Saldo Cuti</h4>
                </div>
                <div class="card bg-white w-100 rounded-3 p-4">
                    <i class="bi bi-stopwatch text-warning fs-2"></i>
                    <h1 class="text-warning my-3">{{$user->leave_request}}</h1>
                    <h4 class="text-gray-400 fw-normal">Pengajuan</h4>
                </div>
                <div class="card bg-white w-100 rounded-3 p-4">
                    <i class="bi bi-stopwatch text-danger fs-2"></i>
                    <h1 class="text-danger my-3">{{$user->leave_accepted}}</h1>
                    <h4 class="text-gray-400 fw-normal">Cuti Disetujui</h4>
                </div>
                <div class="card bg-white w-100 rounded-3 p-4">
                    <i class="bi bi-stopwatch text-success fs-2"></i>
                    <h1 class="text-success my-3">{{$user->leave_rejected}}</h1>
                    <h4 class="text-gray-400 fw-normal">Ditolak</h4>
                </div>
            </div>

            <div class="py-5 card p-4 rounded-3 shadow-sm">
                <div class="d-flex mb-3 justify-content-between">
                    <h3 class="fw-bold">Riwayat Cuti</h3>

                    <a href="{{ route('leave-report.pdf-detail', $user->uuid)}}"
                        class="d-flex gap-3 align-items-center btn btn-light-success px-3 py-2">
                        <i class="bi bi-upload"></i>
                        <span>Export Report</span>
                    </a>
                </div>

                <div class="d-flex justify-content-between border-2 pb-3 border-bottom">
                    <ul class="nav nav-line-tabs-2x fs-6">
                        <li class="nav-item">
                            <a class="nav-link pb-5 {{$status == '' ? 'text-primary fw-bold' : ''}}"
                                href="{{
                                route('leave-report.detail', [
                                    'uuid' => $user->uuid,
                                    'status' => ''
                                ])}}">Semua</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pb-5 {{$status == 'menunggu' ? 'text-primary fw-bold' : ''}}"
                                href="{{
                                route('leave-report.detail', [
                                    'uuid' => $user->uuid,
                                    'status' => 'menunggu'
                                ])}}">Menunggu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pb-5 {{$status == 'disetujui' ? 'text-primary fw-bold' : ''}}"
                                href="{{
                                route('leave-report.detail', [
                                    'uuid' => $user->uuid,
                                    'status' => 'disetujui'
                                ])}}">Disetujui</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pb-5 {{$status == 'ditolak' ? 'text-primary fw-bold' : ''}}"
                                href="{{
                                route('leave-report.detail', [
                                    'uuid' => $user->uuid,
                                    'status' => 'ditolak'
                                ])}}">Ditolak</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pb-5 {{$status == 'expired' ? 'text-primary fw-bold' : ''}}"
                                href="{{
                                route('leave-report.detail', [
                                    'uuid' => $user->uuid,
                                    'status' => 'expired'
                                ])}}">Expired</a>
                        </li>
                    </ul>
                </div>

                        <table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                                <tr class="fw-bolder fs-6 text-gray-800">
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tipe</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    @if ($status == '')
            <th>Disetujui</th>
            @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                <tr>
                                    <td>{{$leave->formatted_created_at}}</td>
                                    <td>{{$leave->type}}</td>
                                    <td>{{$leave->formatted_start_date}}</td>
                                    <td>{{$leave->formatted_start_date}}</td>
                                    @if ($status == '')
                                    <td>
                                        @switch($status)
                                        @case('pending')
                                        <span class="badge badge-primary text-primary">{{ucwords($leave->status)}}</span>
                                        @break
                                        @case('disetujui')
                                        <span class="badge badge-success text-success">{{ucwords($leave->status)}}</span>
                                        @break
                                        @case('ditolak')
                                        <span class="badge badge-danger text-danger">{{ucwords($leave->status)}}</span>
                                        @break
                                        @case('expired')
                                        <span class="badge badge-dark text-dark">{{ucwords($leave->status)}}</span>
                                        @break
                                        @endswitch
                                        {{ucwords($leave->status)}}
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{$leaves->links('vendor.pagination.bootstrap-5')}}

                        {{-- empty --}}
                        @if (count($leaves) == 0)
                        <img class="img-fluid mx-auto d-block" height="50"
                            src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                        @endif
                        {{-- end empty --}}

            </div>
        </div>
    </section>
</div>
@endsection
