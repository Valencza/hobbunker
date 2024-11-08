@extends('backoffice.layouts.app')

@section('title', 'Detail Absensi')

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
            <a href="{{route('absent-report')}}" class="text-secondary text-decoration-none">Laporan Absensi</a>
        </li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Detail Karyawan</h3>

    <section class="row">
        <div class="col-md-4 col-12 mb-3 md-mb-0">
            <div class="card rounded-3 px-4 py-3 bg-white">
                <div class="img-profile rounded-circle w-100px h-100px mx-auto">
                    <x-acronym text="{{$user->name}}"></x-acronym>
                </div>
                <div class="text-center">
                    <h3 class="mt-2">{{$user->name}}</h3>
                    <p class="text-gray">{{$user->email}}</p>
                </div>
                <div>
                    <div class="d-flex align-items-center flex-column mt-5 px-3">
                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                            <span class="fs-6">Presentase Absensi</span>
                            <span class="fw-bolder fs-6">{{$percentage}}%</span>
                        </div>
                        <div class="h-5px mx-3 w-100 bg-light mb-3">
                            <div class="bg-success rounded h-5px" role="progressbar" style="width: {{$percentage}}%;"
                                aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="px-3">
                        <small class="border-bottom-dashed border-gray border-1 pb-3 fw-normal mt-5">
                            Details</small>

                        <h5 class="fw-normal">Nomor Induk Karyawan</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">ID-{{$user->nik}}</h5>

                        <h5 class="fw-normal">Jabatan</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->masterRole->name}}</h5>

                        <h5 class="fw-normal">Telepon</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->phone}}</h5>

                        <h5 class="fw-normal">Kontak Darurat</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->emergency_phone}}</h5>

                        <h5 class="fw-normal">Jumlah Tanggungan</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->dependent}}</h5>

                        <h5 class="fw-normal">Alamat</h5>
                        <h5 class="text-gray-400 fw-normal mb-5">{{$user->address}}</h5>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="">
                <div class="d-flex gap-3">
                    <div class="card bg-white w-100 rounded-3 p-4">
                        <i class="bi bi-stopwatch text-green fs-2"></i>
                        <h1 class="text-green my-3">{{$countAbsentsOnTime}}</h1>
                        <small class="text-gray-400 fw-normal">Tepat Waktu</small>
                    </div>
                    <div class="card bg-white w-100 rounded-3 p-4">
                        <i class="bi bi-stopwatch text-warning fs-2"></i>
                        <h1 class="text-warning my-3">{{$countAbsentsLate}}</h1>
                        <small class="text-gray-400 fw-normal">Terlambat</small>
                    </div>
                    <div class="card bg-white w-100 rounded-3 p-4">
                        <i class="bi bi-stopwatch text-danger fs-2"></i>
                        <h1 class="text-danger my-3">{{$countAbsentsNotPresent}}</h1>
                        <small class="text-gray-400 fw-normal">Tidak Hadir</small>
                    </div>
                    <div class="card bg-white w-100 rounded-3 p-4">
                        <i class="bi bi-stopwatch text-success fs-2"></i>
                        <h1 class="text-success my-3">{{$countAbsentsLeave}}</h1>
                        <small class="text-gray-400 fw-normal">Cuti</small>
                    </div>
                </div>
                <div class="card rounded-3 bg-white p-4 mt-5">
                    <h3 class="fw-bold  mb-3">Riwayat Absensi</h3>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex gap-3 justify-content-between w-100">
                            <form action="" method="GET" onchange="this.submit()" class="d-flex gap-3">
                                <input class="form-control" type="date" value="{{$startDate}}" name="start_date"
                                    id="start_date">
                                <input class="form-control" type="date" value="{{$endDate}}" name="end_date"
                                    id="end_date">
                            </form>
                        </div>
                    </div>
                    <div class="overflow-auto">
                        <table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                                <tr class="fw-bolder fs-6 text-gray-800">
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Masuk</th>
                                    <th class="text-center">Lokasi</th>
                                    <th class="text-center">Pekerjaan</th>
                                    <th class="text-center">Pulang</th>
                                    <th class="text-center">Lokasi</th>
                                    <th class="text-center">Pekerjaan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Jarak</th>
                                    <th class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absents as $absent)
                                <tr>
                                    <td class="text-center">{{$absent->formatted_created_at}}</td>
                                    <td class="text-center">{{$absent->checkin_clock}}</td>
                                    <td class="text-center">{{$absent->checkin_location}}</td>
                                    <td class="text-center">{{$absent->checkin_detail_job}}</td>
                                    <td class="text-center">{{$absent->checkout_clock ?? '--:--:--'}}</td>
                                    <td class="text-center">{{$absent->checkout_location ?? '-'}}</td>
                                    <td class="text-center">{{$absent->checkout_detail_job ?? '-'}}</td>
                                    <td class="text-center">
                                        <form action="{{route('absent-report.update', $absent->uuid)}}" method="POST"
                                            onchange="this.submit()">
                                            @csrf
                                            @method('PUT')
    
                                            <select name="status" class="form-select text-center px-3 py-3">
                                                <option value="hadir" @selected($absent->status == 'hadir')>Hadir</option>
                                                <option value="tidak hadir" @selected($absent->status == 'tidak
                                                    hadir')>Tidak Hadir</option>
                                                <option value="cuti" @selected($absent->status == 'cuti')>Cuti</option>
                                                <option value="terlambat" @selected($absent->status ==
                                                    'terlambat')>Terlambat</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center">{{ucwords($absent->checkin_status_radius)}}</td>
                                    <td class="text-center"><button class="btn btn-icon rounded-circle btn-light-primary"
                                            data-bs-toggle="modal" data-bs-target="#detailAbsentModal-${{$absent->uuid}}">
                                            <i class="fa fa-eye"></i>
                                        </button></td>
                                </tr>
    
                                <div class="modal fade" id="detailAbsentModal-${{$absent->uuid}}" tabindex="-1"
                                    aria-labelledby="detailAbsentModalLabel-${{$absent->uuid}}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailAbsentModalLabel-${{$absent->uuid}}">
                                                    Detail Absensi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
    
                                            <div class="modal-body">
                                                <h5 class="fw-normal">Tanggal</h5>
                                                <h5 class="text-gray-400 fw-normal mb-5 border-bottom pb-4">
                                                    {{$absent->formatted_created_at}}</h5>
                                                <div class="mb-10">
                                                    <label for="checkin_status_radius" class="form-label">Status
                                                        Jarak</label>
                                                    <select class="form-select border" readonly name="checkin_status_radius"
                                                        id="checkin_status_radius" aria-label="Select example" disabled>
                                                        <option value="dalam radius" @if ($absent->checkin_status_radius ==
                                                            'dalam radius')
                                                            selected
                                                            @endif >Dalam Radius</option>
                                                        <option value="diluar radius" @if ($absent->checkin_status_radius ==
                                                            'diluar radius')
                                                            selected
                                                            @endif >Diluar Radius</option>
                                                    </select>
                                                </div>
    
                                                <div class="mb-10">
                                                    <label for="checkin_clock" class="form-label">Absen
                                                        Masuk</label>
                                                    <input type="time" name="checkin_clock" id="checkin_clock" readonly
                                                        class="form-control  border"
                                                        value="{{$absent->checkin_clock}}" />
                                                </div>
    
                                                <h5 class="fw-normal">Lokasi Absen Masuk</h5>
                                                <h5 class="text-gray-400 fw-normal mb-5 border-bottom pb-4">
                                                    {{$absent->checkin_location}}</h5>
    
                                                <h5 class="fw-normal">Detail Pekerjaan</h5>
                                                <h5 class="text-gray-400 fw-normal mb-5 border-bottom pb-4">
                                                    {{$absent->checkin_detail_job}}</h5>
    
                                                <div class="mb-10">
                                                    <label for="checkout_clock" class="form-label">Absen`
                                                        Pulang</label>
                                                    <input type="time" name="checkout_clock" id="checkout_clock" readonly
                                                        class="form-control  border" `
                                                        value="{{$absent->checkout_clock ?? '-'}}" />`
                                                </div>
    
                                                <h5 class="fw-normal">Lokasi Absen Pulang</h5>
                                                <h5 class="text-gray-400 fw-normal mb-5 border-bottom pb-4">
                                                    {{$absent->checkout_location ?? '-'}}</h5>
    
                                                <h5 class="fw-normal">Detail Pekerjaan</h5>
                                                <h5 class="text-gray-400 fw-normal mb-5 border-bottom pb-4">
                                                    {{$absent->checkout_detail_job ?? '-'}}</h5>
                                            </div>
                                            {{--                                    <div class="modal-footer">--}}
                                            {{--                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close--}}
                                            {{--                                        </button>--}}
                                            {{--                                        <button type="button" class="btn btn-primary">Save changes</button>--}}
                                            {{--                                    </div>--}}
                                        </div>
                                    </div>
                                </div>
    
                                @endforeach
                            </tbody>
                        </table>
    
                        {{$absents->links('vendor.pagination.bootstrap-5')}}
                    </div>

                    {{-- empty --}}
                    @if (count($absents) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
