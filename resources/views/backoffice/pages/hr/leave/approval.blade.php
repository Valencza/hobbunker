@extends('backoffice.layouts.app')

@section('title', 'Detail Cuti')

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
        <li class="breadcrumb-item text-secondary">Pengajuan Cuti</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Approval Cuti</h3>

    <div class="d-flex gap-3 align-items-center justify-content-between my-5">
        <div class="d-flex gap-3 align-items-center">
            <h3 class="mt-2">Daftar Karyawan</h3>
        </div>
    </div>

    <div class="py-5 card p-4 rounded-3 shadow-sm">
        <div class="d-flex justify-content-between border-2 pb-3 border-bottom">
            <ul class="nav nav-line-tabs-2x fs-6">
                <li class="nav-item">
                    <a class="nav-link pb-5 {{$status == '' ? 'text-primary fw-bold' : ''}}" href="{{
                            route('leave-approval', [
                                'status' => ''
                            ])}}">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-5 {{$status == 'menunggu' ? 'text-primary fw-bold' : ''}}" href="{{
                            route('leave-approval', [
                                'status' => 'menunggu'
                            ])}}">Menunggu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-5 {{$status == 'disetujui' ? 'text-primary fw-bold' : ''}}" href="{{
                            route('leave-approval', [
                                'status' => 'disetujui'
                            ])}}">Disetujui</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-5 {{$status == 'ditolak' ? 'text-primary fw-bold' : ''}}" href="{{
                            route('leave-approval', [
                                'status' => 'ditolak'
                            ])}}">Ditolak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-5 {{$status == 'expired' ? 'text-primary fw-bold' : ''}}" href="{{
                            route('leave-approval', [
                                'status' => 'expired'
                            ])}}">Expired</a>
                </li>
            </ul>

            <div>
                <!--begin::Form-->
                <form data-kt-search-element="form" class="position-relative" autocomplete="off">
                    <!--begin::Hidden input(Added to disable form autocomplete)-->
                    <input type="hidden" />
                    <!--end::Hidden input-->
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span
                        class="svg-icon svg-icon-2 svg-icon-gray-700 position-absolute top-50 translate-middle-y ms-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Input-->
                    <input type="text"
                        class="form-control  h-40px bg-light border border-secondary ps-13 fs-7"
                        name="search" value="{{$search}}" placeholder="Cari..." data-kt-search-element="input" />
                    <!--end::Input-->
                    <!--begin::Spinner-->
                    <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                        data-kt-search-element="spinner">
                        <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                    </span>
                    <!--end::Spinner-->
                    <!--begin::Reset-->
                    <span
                        class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4"
                        data-kt-search-element="clear">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-2 me-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <!--end::Reset-->
                </form>
                <!--end::Form-->
            </div>
        </div>

        <div class="overflow-auto">
            <table class="table table-row-dashed table-row-gray-300 gy-7">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th class="text-left">Karyawan</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Mulai</th>
                        <th class="text-center">Selesai</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Pengajuan/Saldo</th>
                        @if ($status == '')
                        <th class="text-center">Status</th>
                        @endif
                        @if (Auth::user()->masterRole->slug == 'hrd' || Auth::user()->masterRole->slug == 'superadmin')
                        <th class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave)
                    <tr>
                        <td class="d-flex align-items-center gap-5">
                            <div class="img-profile rounded-circle w-35px h-35px">
                                <x-acronym text="{{$leave->masterUser->name}}"></x-acronym>
                            </div>
                            <h6 class="text-dark fw-normal mt-2">{{$leave->masterUser->name}}</h6>
                        </td>
                        <td class="text-center">{{ucwords($leave->type)}}</td>
                        <td class="text-center">{{$leave->formatted_start_date}}</td>
                        <td class="text-center">{{$leave->formatted_end_date}}</td>
                        <td class="text-center">{{$leave->masterUser->masterRole->name}}</td>
                        <td class="text-center">{{$leave->masterUser->leave_total}}/{{$countLeaveBalanceYear}}</td>
                        @if ($status == '')
                        <td class="text-center">
                            @switch($leave->status)
                            @case('pending')
                            <span class="badge badge-warning">{{ucwords($leave->status)}}</span>
                            @break
                            @case('disetujui')
                            <span class="badge badge-success">{{ucwords($leave->status)}}</span>
                            @break
                            @case('ditolak')
                            <span class="badge badge-danger">{{ucwords($leave->status)}}</span>
                            @break
                            @case('expired')
                            <span class="badge badge-dark">{{ucwords($leave->status)}}</span>
                            @break
                            @endswitch
                        </td>
                        @endif
                        @if (Auth::user()->masterRole->slug == 'hrd' || Auth::user()->masterRole->slug == 'superadmin')
                        <td>
                            <button class="btn btn-icon rounded-circle btn-light-primary" data-bs-toggle="modal"
                                data-bs-target="#detailLeaveModal-{{$leave->uuid}}">
                                <i class="fa fa-eye"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
    
                    <div class="modal fade" id="detailLeaveModal-{{$leave->uuid}}" tabindex="-1"
                        aria-labelledby="detailLeaveModalLabel-{{$leave->uuid}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailLeaveModalLabel-{{$leave->uuid}}">Detail
                                        Pengajuan
                                        Cuti</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="px-4 pt-3">
                                        <h4 class="mb-3">Nama</h4>
                                        <span>{{$leave->masterUser->name}}</span>
                                        <div class="border-t border-1 border mt-2 mb-5"></div>
    
                                        <h4 class="mb-3">Judul Cuti</h4>
                                        <span>{{$leave->title}}</span>
                                        <div class="border-t border-1 border mt-2 mb-5"></div>
    
                                        <h4 class="mb-3">Tanggal Awal Cuti</h4>
                                        <span>{{$leave->formatted_start_date}}</span>
                                        <div class="border-t border-1 border mt-2 mb-5"></div>
    
                                        <h4 class="mb-3">Tanggal Akhir Cuti</h4>
                                        <span>{{$leave->formatted_end_date}}</span>
                                        <div class="border-t border-1 border mt-2 mb-5"></div>
    
                                        <h4 class="mb-3">Alasan Cuti</h4>
                                        <span>{{$leave->reason}}</span>
                                        <div class="border-t border-1 border mt-2 mb-5"></div>
    
                                        <div class="mb-3">
                                            <label for="note" class="form-label">Keterangan</label>
                                            <textarea id="note" @readonly($leave->status != 'pending')
                                                class="form-control mb-8 border" rows="3"
                                                placeholder="Masukkan keterangan">{{$leave->note}}</textarea>
                                        </div>
    
                                        @if ($leave->status == 'pending')
                                        <div class="d-flex align-items-center justify-content-center gap-3">
                                            <form action="{{ route('leave-approval.reject', $leave->uuid) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <input id="reject-note" type="hidden" name="note" value="{{$leave->note}}">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-x border border-light rounded-circle fs-4 p-0"></i>
                                                    Tolak
                                                </button>
                                            </form>
                                            <form action="{{ route('leave-approval.accept', $leave->uuid) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <input id="approve-note" type="hidden" name="note" value="{{$leave->note}}">
                                                <button type="submit" class="btn bg-green text-white">
                                                    <i
                                                        class="bi bi-check border border-light rounded-circle fs-4 p-0 text-white"></i>
                                                    Setujui
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$leaves->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($leaves) == 0)
        <img class="img-fluid mx-auto d-block" width="200" src="{{asset('assets/media/illustrations/empty.webp')}}"
            alt="Empty Illustration">
        @endif
        {{-- end empty --}}

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const approveForm = $('#approve-note');
        const rejectForm = $('#reject-note');
        const note = $('#note');

        note.on('change', function () {
            approveForm.val(note.val());
            rejectForm.val(note.val());
        });
    });
</script>
@endpush
