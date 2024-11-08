@extends('backoffice.layouts.app')

@section('title', 'Laporan Rangking Karyawan')

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

    <h3 class="pb-5">Laporan Rangking Karyawan</h3>

    <div class="row  rounded-3 my-3">
        <div class="col-12 col-md-6 mb-5 mb-md-0" style="height: 450px">
            <div class=" card shadow-sm">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">5 Top Rangking</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Karyawan Terbaik Tahun ini</span>
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-3">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($topUsers as $topUser)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="img-profile rounded-circle w-35px h-35px me-2">
                                            <x-acronym text="{{$topUser->name}}"></x-acronym>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <span
                                                class="text-muted fw-bold text-muted d-block fs-7">{{$topUser->name}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end w-25">
                                    <div class="d-flex flex-column w-100 me-2">
                                        <div class="d-flex flex-stack mb-2">
                                            <span
                                                class="text-muted me-2 fs-7 fw-bold">{{round($topUser->absent_percentage, 2)}}%</span>
                                        </div>
                                        <div class="progress h-6px w-100">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{$topUser->absent_percentage}}%"
                                                aria-valuenow="{{$topUser->absent_percentage}}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->

                    {{-- empty --}}
                    @if (count($topUsers) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <!--end::Table container-->
            </div>
            <!--begin::Body-->
            </div>
        </div>
        <div class="col-12 col-md-6 ">
            <div class="card shadow-sm p-6" style="height: 413px">
                <div class="p-2">
                    <h3>Rata - Rata Tingkat Abseinteeism Rate</h3>
                    <h6 class="text-muted fs-7">Data Tahunan Persentase Nilai Karyawan</h6>
                </div>
                <div id="chart"></div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3 align-items-center justify-content-between my-5">
        <div class="d-flex gap-3 align-items-center">
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
    </div>

    <div class="py-5 card p-4 rounded-3 shadow-sm"style="width: 100%; overflow: auto">
        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th>Umur</th>
                    <th>Persentase Kehadiran</th>
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
                    <td>{{$user->phone}}</td>
                    <td>{{$user->age}}</td>
                    <td>
                        <div class="d-flex flex-column w-100 me-2">
                            <div class="d-flex flex-stack mb-2">
                                <span class="text-muted me-2 fs-7 fw-bold">{{round($user->absent_percentage, 2)}}%</span>
                            </div>
                            <div class="progress h-6px w-100">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: {{round($user->absent_percentage, 2)}}%"
                                    aria-valuenow="{{round($user->absent_percentage, 2)}}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="{{route('absent-report.detail', $user->uuid)}}"
                            class="btn btn-icon rounded-circle btn-light-primary">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

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
    var options = {
        chart: {
            type: 'line',
            height: '90%'
        },
        series: [{
            name: 'Kehadiran',
            data: [
                "{{$countAbsentsOnTimeYear['January']}}",
                "{{$countAbsentsOnTimeYear['February']}}",
                "{{$countAbsentsOnTimeYear['March']}}",
                "{{$countAbsentsOnTimeYear['April']}}",
                "{{$countAbsentsOnTimeYear['May']}}",
                "{{$countAbsentsOnTimeYear['June']}}",
                "{{$countAbsentsOnTimeYear['July']}}",
                "{{$countAbsentsOnTimeYear['August']}}",
                "{{$countAbsentsOnTimeYear['September']}}",
                "{{$countAbsentsOnTimeYear['October']}}",
                "{{$countAbsentsOnTimeYear['November']}}",
                "{{$countAbsentsOnTimeYear['December']}}"
            ]
        }],
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
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

</script>
@endpush
