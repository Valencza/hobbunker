@extends('client.layouts.app')

@section('title','Laporan Cuti')

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

    <h2 class="anchor mb-5 mt-3">Cuti</h2>

    <section class="rounded-2xl shadow-sm position-relative card-attendance">
        <img src="{{ asset('assets/media/illustrations/cuti_background.svg') }}" alt=""
            class="position-absolute rounded-4 w-100">
        <div class="position-absolute z-2 text-white p-4 w-100 border rounded-4 shadow-sm">
            <div class="d-flex justify-content-end">
                <a href="#" class="btn btn-success fw-bolder" id="modal" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_1">
                    <i class="bi bi-plus-square fs-4"></i>
                    Ajukan Cuti
                </a>
            </div>

            <div class="text-white fw-bolder mt-5 mb-4">
                <h3 class="fw-bolder mb-4">Data Cuti Tahunan</h3>
            </div>

            <div class="row pb-2">
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-arrow-clockwise text-primary fs-3"></i>
                        <h1 class="text-primary text-2xl mt-3">{{$user->leave_total}}/{{$settingLeave->balance_year}}
                        </h1>
                        <p style="height: 20px" class="text-secondary">Saldo Cuti</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-file-earmark-check-fill text-success fs-3"></i>
                        <h1 class="text-success text-2xl mt-3">{{$user->leave_accepted}}</h1>
                        <p style="height: 20px" class="text-secondary">Cuti Disetujui</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-card-text text-warning fs-3"></i>
                        <h1 class="text-warning text-2xl mt-3">{{$user->leave_request}}</h1>
                        <p style="height: 20px" class="text-secondary">Pengajuan Cuti</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-file-earmark-excel fs-3 text-danger"></i>
                        <h1 class="text-2xl text-danger mt-3">{{$user->leave_rejected}}</h1>
                        <p style="height: 20px" class="text-secondary">Cuti Ditolak</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="card shadow-sm p-4 rounded-2xl" >
        <h2 class="anchor mb-3 ">Riwayat Cuti</h2>

        <div style="width: 100%; overflow: auto">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" style="width: 200%; overflow: auto">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Semua</a>
                </li>
                <li class="nav-item">
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Disetujui</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">Ditolak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Expired</a>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="kt_tab_pane_1">
                @foreach ($leaves as $leave)
                <div class="card rounded-2xl p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="anchor mb-3 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week-fill fw-bolder fs-5 text-black text-medium"></i>
                            <span class="fs-4 text-medium">{{$leave->formatted_start_date}}</span>
                        </div>
                        <div>
                            @switch($leave->status)
                            @case('pending')
                            <span
                                class="btn btn-light-warning text-warning px-3 py-1">{{ucwords($leave->status)}}</span>
                            @break
                            @case('disetujui')
                            <span
                                class="btn btn-light-success text-success px-3 py-1">{{ucwords($leave->status)}}</span>
                            @break
                            @case('ditolak')
                            <span class="btn btn-light-danger text-danger px-3 py-1">{{ucwords($leave->status)}}</span>
                            @break
                            @case('expired')
                            <span class="btn btn-light-dark text-dark px-3 py-1">{{ucwords($leave->status)}}</span>
                            @break
                            @endswitch
                            </td>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Jumlah Hari</h6>
                                <h6 class="fw-bold">{{$leave->total_date}} Hari</h6>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Disetujui Oleh</h6>
                                <h6 class="fw-bold">
                                    {{$leave->approverMasterUser ? $leave->approverMasterUser->name : '-'}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{$leaves->links('vendor.pagination.bootstrap-5')}}

                {{-- empty --}}
                @if (count($leaves) == 0)
                <img class="img-fluid mx-auto d-block" height="50"
                    src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                @endif
                {{-- end empty --}}
            </div>
            <div class="tab-pane fade show" id="kt_tab_pane_2">
                @foreach ($leavesRequest as $leaveRequest)
                <div class="card rounded-2xl p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="anchor mb-3 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week-fill fw-bolder fs-5 text-black text-medium"></i>
                            <span class="fs-4 text-medium">{{$leaveRequest->formatted_start_date}}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Jumlah Hari</h6>
                                <h6 class="fw-bold">{{$leaveRequest->total_date}} Hari</h6>
                            </div>
                        </div>
    
                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Disetujui Oleh</h6>
                                <h6 class="fw-bold">
                                    {{$leaveRequest->approverMasterUser ? $leaveRequest->approverMasterUser->name : '-'}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{$leavesRequest->links('vendor.pagination.bootstrap-5')}}

                {{-- empty --}}
                @if (count($leavesRequest) == 0)
                <img class="img-fluid mx-auto d-block" height="50"
                    src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                @endif
                {{-- end empty --}}
            </div>
            <div class="tab-pane fade show" id="kt_tab_pane_3">
                @foreach ($leavesAccepted as $leaveAccepted)
                <div class="card rounded-2xl p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="anchor mb-3 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week-fill fw-bolder fs-5 text-black text-medium"></i>
                            <span class="fs-4 text-medium">{{$leaveAccepted->formatted_start_date}}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Jumlah Hari</h6>
                                <h6 class="fw-bold">{{$leaveAccepted->total_date}} Hari</h6>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Disetujui Oleh</h6>
                                <h6 class="fw-bold">
                                    {{$leaveAccepted->approverMasterUser ? $leaveAccepted->approverMasterUser->name : '-'}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{$leavesAccepted->links('vendor.pagination.bootstrap-5')}}

                {{-- empty --}}
                @if (count($leavesAccepted) == 0)
                <img class="img-fluid mx-auto d-block" height="50"
                    src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                @endif
                {{-- end empty --}}
            </div>
            <div class="tab-pane fade show" id="kt_tab_pane_4">
                @foreach ($leavesRejected as $leaveRejected)
                <div class="card rounded-2xl p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="anchor mb-3 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week-fill fw-bolder fs-5 text-black text-medium"></i>
                            <span class="fs-4 text-medium">{{$leaveRejected->formatted_start_date}}</span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Jumlah Hari</h6>
                                <h6 class="fw-bold">{{$leaveRejected->total_date}} Hari</h6>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Disetujui Oleh</h6>
                                <h6 class="fw-bold">
                                    {{$leaveRejected->approverMasterUser ? $leaveRejected->approverMasterUser->name : '-'}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{$leavesRejected->links('vendor.pagination.bootstrap-5')}}

                {{-- empty --}}
                @if (count($leavesRejected) == 0)
                <img class="img-fluid mx-auto d-block" height="50"
                    src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                @endif
                {{-- end empty --}}
            </div>
            <div class="tab-pane fade show" id="kt_tab_pane_5">
                @foreach ($leavesExpired as $leaveExpired)
                <div class="card rounded-2xl p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="anchor mb-3 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week-fill fw-bolder fs-5 text-black text-medium"></i>
                            <span class="fs-4 text-medium">{{$leaveExpired->formatted_start_date}}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Jumlah Hari</h6>
                                <h6 class="fw-bold">{{$leaveExpired->total_date}} Hari</h6>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="border-1 border-dashed rounded-2xl p-3">
                                <h6 class="text-body-tertiary">Disetujui Oleh</h6>
                                <h6 class="fw-bold">
                                    {{$leaveExpired->approverMasterUser ? $leaveExpired->approverMasterUser->name : '-'}}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{$leavesExpired->links('vendor.pagination.bootstrap-5')}}

                {{-- empty --}}
                @if (count($leavesExpired) == 0)
                <img class="img-fluid mx-auto d-block" height="50"
                    src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                @endif
                {{-- end empty --}}
            </div>
        </div>

    </section>
</main>

<div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-none">
            <form action="{{route('leave')}}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title text-black">Pengajuan Cuti Baru</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="title" class="form-label">Judul</label>
                        @error('title')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span>{{$message}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <input autocomplete="off" type="text" name="title" id="title" class="form-control"
                            placeholder="Masukkan judul" value="{{old('title')}}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tyoe" class="form-label">Tipe Cuti</label>
                        @error('tyoe')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span>{{$message}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <select name="type" id="type" class="form-select" required>
                            <option disabled {{ old('type') ? '' : 'selected' }}>Pilih Tipe</option>
                            <option value="izin" {{ old('type') == 'izin' ? 'selected' : '' }}>
                                Izin
                            </option>
                            <option value="sakit" {{ old('type') == 'sakit' ? 'selected' : '' }}>
                                Sakit
                            </option>
                            <option value="liburan" {{ old('type') == 'liburan' ? 'selected' : '' }}>
                                Liburan
                            </option>
                            <option value="hamil" {{ old('type') == 'hamil' ? 'selected' : '' }}>
                                Hamil
                            </option>
                            <option value="kedukaan" {{ old('type') == 'kedukaan' ? 'selected' : '' }}>
                                Kedukaan
                            </option>
                            <option value="lain-lain" {{ old('type') == 'kedukaan' ? 'selected' : '' }}>
                                Lain-lain
                            </option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-6">
                            <label for="start_date" class="form-label">Tanggal Awal</label>
                            @error('start_date')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span>{{$message}}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @enderror
                            <input autocomplete="off" type="date" name="start_date" id="start_date" class="form-control"
                                value="{{old('start_date')}}" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            @error('end_date')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span>{{$message}}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @enderror
                            <input autocomplete="off" type="date" name="end_date" id="end_date" class="form-control"
                                value="{{old('end_date')}}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="form-label">Alasan Cuti</label>
                        @error('reason')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span>{{$message}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <textarea name="reason" id="reason" cols="30" rows="10" class="form-control"
                            placeholder="Masukkan alasan" required>{{old('reason')}}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Cuti</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.tab-pane').first().addClass('show active');

        $('.nav-link').click(function () {
            $('.tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
        });
    });

</script>

@endpush
