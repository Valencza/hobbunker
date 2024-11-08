@extends('backoffice.layouts.app')

@section('title', 'Laporan Cuti')

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
        <li class="breadcrumb-item text-gray-500">Laporan</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Laporan Cuti Karyawan</h3>

    <div>
        <div class="row pb-2">
            <div class="col-12 col-md-3 p-4">
                <div class="card h-100 bg-light-secondary p-5 rounded-3 my-2" style="background: #F5F5F5;">
                    <i class="bi bi-hourglass text-blue fs-1"></i>
                    <h1 class="text-blue text-2xl mt-3">{{$countLeaveBalanceYear}}</h1>
                    <h4 style="color: #878787;">Saldo Cuti Pertahun</h4>
                </div>
            </div>
            <div class="col-12 col-md-3 p-4">
                <div class="card h-100 bg-body-secondary p-5 rounded-3 my-2" style="background: #F5F5F5;">
                    <i class="bi bi-file-earmark-check-fill text-yellow fs-1"></i>
                    <h1 class="text-yellow text-2xl mt-3">{{$countLeavesRequest}}</h1>
                    <h4 style="color: #878787;">Jumlah Pengajuan</h4>
                </div>
            </div>
            <div class="col-12 col-md-3 p-4">
                <div class="card h-100 bg-body-secondary p-5 rounded-3 my-2" style="background: #F5F5F5;">
                    <i class="bi bi-file-earmark-text-fill text-green fs-1"></i>
                    <h1 class="text-green text-2xl mt-3">{{$countLeavesAccepted}}</h1>
                    <h4 style="color: #878787;">Jumlah Disetujui</h4>
                </div>
            </div>
            <div class="col-12 col-md-3 p-4">
                <div class="card h-100 bg-body-secondary p-5 rounded-3 my-2" style="background: #F5F5F5;">
                    <i class="bi bi-file-earmark-excel-fill fs-1 text-danger"></i>
                    <h1 class="text-danger text-2xl mt-3">{{$countLeavesRejected}}</h1>
                    <h4 style="color: #878787;">Cuti Ditolak</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm p-6">
        <div class="p-2">
            <h3>Rekap Cuti Karyawan</h3>
            <h6 class="text-muted fs-7">Data Tahunan</h6>
        </div>
        <div id="chart"></div>
    </div>

    <div class="d-flex gap-3 align-items-center justify-content-between my-5 pt-5">
        <div class="d-flex gap-3 align-items-center mt-5">
            <h3 class="mt-2">Daftar Karyawan</h3>
            <!--begin::Form-->
            <form data-kt-search-element="form" class="position-relative" autocomplete="off">
                <!--begin::Hidden input(Added to disable form autocomplete)-->
                <input type="hidden" />
                <!--end::Hidden input-->
                <!--begin::Icon-->
                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                <span class="svg-icon svg-icon-2 svg-icon-gray-700 position-absolute top-50 translate-middle-y ms-4">
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
                <input type="text" class="form-control  h-40px bg-light border border-secondary ps-13 fs-7"
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
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
        <div class="d-flex gap-3">
            <a href="{{ route('leave-report.pdf', ['search' => $search, 'startDate' => $startDate ?? '', 'endDate' => $endDate ?? '']) }}"
                class="d-flex gap-3 align-items-center btn btn-light-success px-3 py-2">
                <i class="bi bi-upload"></i>
                <span>Export Report</span>
            </a>
        </div>
    </div>

    <div class="py-5 card p-4 rounded-3 shadow-sm ">
        <div class="overflow-auto">
            <table class="table table-row-dashed table-row-gray-300 gy-7">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Pengajuan/Saldo</th>
                        <th>Telepon</th>
                        <th>Umur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td class="d-flex align-items-center gap-5">
                            <div class="img-profile rounded-circle w-35px h-35px">
                                <x-acronym text="{{$user->name}}"></x-acronym>
                            </div>
                            <h6 class="text-dark fw-normal mt-2">{{$user->name}}</h6>
                        </td>
                        <td>{{$user->masterRole->name}}</td>
                        <td><span class="fw-bolder">{{$user->leave_request}}</span> / {{$countLeaveBalanceYear}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->age}}</td>
                        <td>
                            <a href="{{route('leave-report.detail', $user->uuid)}}"
                                class="btn btn-icon rounded-circle btn-light-primary">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$users->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($users) == 0)
        <img class="img-fluid mx-auto d-block" width="200" src="{{asset('assets/media/illustrations/empty.webp')}}"
            alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </div>

</div>
@endsection

@push('scripts')
<script>
    var options1 = {
        series: [{
                name: 'Pengajuan',
                data: [
                    "{{$countLeavesRequestYear['January']}}",
                    "{{$countLeavesRequestYear['February']}}",
                    "{{$countLeavesRequestYear['March']}}",
                    "{{$countLeavesRequestYear['April']}}",
                    "{{$countLeavesRequestYear['May']}}",
                    "{{$countLeavesRequestYear['June']}}",
                    "{{$countLeavesRequestYear['July']}}",
                    "{{$countLeavesRequestYear['August']}}",
                    "{{$countLeavesRequestYear['September']}}",
                    "{{$countLeavesRequestYear['October']}}",
                    "{{$countLeavesRequestYear['November']}}",
                    "{{$countLeavesRequestYear['December']}}"
                ]
            }, {
                name: 'Disetujui',
                data: [
                    "{{$countLeavesAcceptedYear['January']}}",
                    "{{$countLeavesAcceptedYear['February']}}",
                    "{{$countLeavesAcceptedYear['March']}}",
                    "{{$countLeavesAcceptedYear['April']}}",
                    "{{$countLeavesAcceptedYear['May']}}",
                    "{{$countLeavesAcceptedYear['June']}}",
                    "{{$countLeavesAcceptedYear['July']}}",
                    "{{$countLeavesAcceptedYear['August']}}",
                    "{{$countLeavesAcceptedYear['September']}}",
                    "{{$countLeavesAcceptedYear['October']}}",
                    "{{$countLeavesAcceptedYear['November']}}",
                    "{{$countLeavesAcceptedYear['December']}}"
                ]
            },
            {
                name: 'Ditolak',
                data: [
                    "{{$countLeavesRejectedYear['January']}}",
                    "{{$countLeavesRejectedYear['February']}}",
                    "{{$countLeavesRejectedYear['March']}}",
                    "{{$countLeavesRejectedYear['April']}}",
                    "{{$countLeavesRejectedYear['May']}}",
                    "{{$countLeavesRejectedYear['June']}}",
                    "{{$countLeavesRejectedYear['July']}}",
                    "{{$countLeavesRejectedYear['August']}}",
                    "{{$countLeavesRejectedYear['September']}}",
                    "{{$countLeavesRejectedYear['October']}}",
                    "{{$countLeavesRejectedYear['November']}}",
                    "{{$countLeavesRejectedYear['December']}}"
                ]
            }
        ],
        chart: {
            type: 'bar',
            height: 350
        },
        xaxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Mei',
                'Jun',
                'Jul',
                'Agu',
                'Sep',
                'Okt',
                'Nov',
                'Des'
            ]
        },
        dataLabels: {
            enabled: false
        }
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options1);
    chart.render();

</script>
@endpush
