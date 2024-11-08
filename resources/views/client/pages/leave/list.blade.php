@extends('client.layouts.app')

@section('title', 'Persetujuan Cuti')

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

    <div class="d-flex justify-content-between align-items-center">
        <h2 class="anchor mb-5 mt-5">Persetujuan Cuti</h2>
    </div>

    <section class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between">
            <div class="w-75">
                <h2 class="fs-4 mb-3 ">Daftar Cuti Karyawan</h2>
            </div>
            {{-- search form --}}
            <div class="w-50">
                <form action="" method="GET" data-kt-search-element="form" class="position-relative" autocomplete="off">
                    <!--begin::Hidden input(Added to disable form autocomplete)-->
                    <input type="hidden" />
                    <!--end::Hidden input-->
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span
                        class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-3 translate-middle-y">
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
                    <input type="text" class="form-control form-control-lg  ps-5 fs-6" name="search"
                        value="{{$search}}" placeholder="Cari..." data-kt-search-element="input" />
                    <!--end::Input-->
                    <!--begin::Spinner-->
                    <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                        data-kt-search-element="spinner">
                        <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                    </span>
                    <!--end::Spinner-->
                    <!--begin::Reset-->
                    <span
                        class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
                        data-kt-search-element="clear">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
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
            </div>
            {{-- end search form --}}
        </div>


        <div class="d-flex flex-column gap-3 mb-5">
            <ul class="nav nav-tabs nav-line-tabs mb-2 fs-6">
                <li class="nav-item">
                    <a class="nav-link mx-2 active" data-bs-toggle="tab" href="#kt_tab_pane_1">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_2">Menunggu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_3">Disetujui</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_4">Ditolak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_5">Expired</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_tab_pane_1">
                    <table class="w-100">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th class="fs-6">NAMA</th>
                                <th class="fs-6 text-center">TIPE CUTI</th>
                                <th class="fs-6 text-center">TANGGAL MULAI</th>
                                <th class="fs-6 text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $leave)
                            <tr>
                                <td class="fs-6 py-2">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->masterUser->name}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->masterUser->name}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->formatted_start_date}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        @switch($leave->status)
                                        @case('pending')
                                        <span class="badge badge-warning">
                                            @break
                                            @case('ditolak')
                                            <span class="badge badge-danger">
                                                @break
                                                @default
                                                <span class="badge badge-success">
                                                    @endswitch
                                                    {{ucwords($leave->status)}}
                                                </span>
                                    </a>
                                </td>
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
                <div class="tab-pane fade show" id="kt_tab_pane_2">
                    <table class="w-100">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th class="fs-6">NAMA</th>
                                <th class="fs-6 text-center">TIPE CUTI</th>
                                <th class="fs-6 text-center">TANGGAL MULAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leavesRequest as $leave)
                            <tr>
                                <td class="fs-6 py-2">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->masterUser->name}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{ucwords($leave->type)}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->formatted_start_date}}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$leavesRequest->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($leavesRequest) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_3">
                    <table class="w-100">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th class="fs-6">NAMA</th>
                                <th class="fs-6 text-center">TIPE CUTI</th>
                                <th class="fs-6 text-center">TANGGAL MULAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leavesAccepted as $leave)
                            <tr>
                                <td class="fs-6 py-2">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->masterUser->name}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{ucwords($leave->type)}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->formatted_start_date}}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$leavesAccepted->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($leavesAccepted) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_4">
                    <table class="w-100">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th class="fs-6">NAMA</th>
                                <th class="fs-6 text-center">TIPE CUTI</th>
                                <th class="fs-6 text-center">TANGGAL MULAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leavesRejected as $leave)
                            <tr>
                                <td class="fs-6 py-2">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->masterUser->name}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{ucwords($leave->type)}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->formatted_start_date}}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$leavesRejected->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($leavesRejected) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_5">
                    <table class="w-100">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th class="fs-6">NAMA</th>
                                <th class="fs-6 text-center">TIPE CUTI</th>
                                <th class="fs-6 text-center">TANGGAL MULAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leavesExpired as $leave)
                            <tr>
                                <td class="fs-6 py-2">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->masterUser->name}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{ucwords($leave->type)}}
                                    </a>
                                </td>
                                <td class="fs-6 py-2 text-center">
                                    <a class="text-decoration-none text-dark" href="{{route('leave.detail', $leave->uuid)}}">
                                        {{$leave->formatted_start_date}}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$leavesExpired->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($leavesExpired) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.nav-link').click(function () {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
        });
    });

</script>
@endpush
