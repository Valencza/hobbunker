@extends('backoffice.layouts.app')

@section('title', 'Laporan Fluktuasi Gaji')

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

    <h3 class="pb-5">Laporan Fluktuasi Gaji Karyawan</h3>

    <div class="card shadow-sm p-6">
        <div class="p-2 d-flex justify-content-between">
            <div>
                <h3 class="fs-1 d-flex align-items-start gap-3">
                    <span class="fs-6" style="color: #A39A9A">Rp</span>
                    {{number_format($totalSalary, 0, '.', '.')}}
                </h3>
                <h6 class="text-muted fs-7">Total Keseluruhan Gaji Karyawan</h6>
            </div>

            <div>
                <h3>Rata - Rata Tingkat Abseinteeism Rate</h3>
                <h6 class="text-muted fs-7">Data Tahunan Persentase Nilai Karyawan</h6>
            </div>
        </div>

        <div id="chart"></div>
    </div>

    <div class="d-flex gap-3 align-items-center justify-content-between my-5">
        <div class="d-flex gap-3 align-items-center">
            <h3 class="mt-2">List Karyawan</h3>
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
    </div>

    <div class="py-5 card p-4 rounded-3 shadow-sm">
        <div class="overflow-auto">
            <table class="table table-row-dashed table-row-gray-300 gy-7">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Telepon</th>
                        <th>Umur</th>
                        <th>Gaji Saat ini</th>
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
                        <td>Rp
                            {{ $user->salaryFluctuation ? number_format($user->salaryFluctuation->salary, 0, '.', '.') : 0}}
                        </td>
                        <td>
                            <button class="btn btn-icon rounded-circle btn-light-primary" data-bs-toggle="modal"
                                data-bs-target="#detailSalaryModal-{{$user->uuid}}">
                                <i class="fa fa-eye"></i>
                            </button>
                        </td>
                    </tr>
    
                    <div class="modal fade" id="detailSalaryModal-{{$user->uuid}}" tabindex="-1"
                        aria-labelledby="detailSalaryModalLabel-{{$user->uuid}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailSalaryModalLabel-{{$user->uuid}}">
                                        Detail Gaji</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
    
                                <div class="modal-body">
                                    <h5 class="fw-normal">Nama</h5>
                                    <h5 class="text-gray-400 fw-normal mb-5 border-bottom pb-4">{{$user->name}}</h5>
    
                                    <h5 class="fw-normal">Gaji</h5>
                                    <h5 class="text-gray-400 fw-normal mb-5 border-bottom pb-4">Rp
                                        {{ $user->salaryFluctuation ? number_format($user->salaryFluctuation->salary, 0, '.', '.') : 0}}
                                    </h5>
    
                                    <div class="card p-3">
                                        <h3 class="my-5">Riwayat Fluktasi Gaji</h3>
                                        <div class="mb-3 d-flex justify-content-between">
                                            <p class="fw-bold">Tanggal</p>
                                            <p class="fw-bold">Besaran Gaji</p>
                                        </div>
                                        @foreach ($user->salaryFluctuations as $salary)
                                        <div class="mb-3 d-flex justify-content-between">
                                            <p>{{$salary->formatted_date}}</p>
                                            <p>Rp {{number_format($salary->salary, 0, '.', '.')}}</p>
                                        </div>
                                        @endforeach
                                        {{-- empty --}}
                                        @if (count($user->salaryFluctuations) == 0)
                                        <img class="img-fluid mx-auto d-block" height="50"
                                            src="{{asset('assets/media/illustrations/empty.png')}}"
                                            alt="Empty Illustration">
                                        @endif
                                        {{-- end empty --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
    
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
    var options = {
        chart: {
            type: 'line',
            height: 350
        },
        series: [{
            name: 'Total Gaji Karyawan',
            data: [
                "{{$monthlySalaries['January']}}",
                "{{$monthlySalaries['February']}}",
                "{{$monthlySalaries['March']}}",
                "{{$monthlySalaries['April']}}",
                "{{$monthlySalaries['May']}}",
                "{{$monthlySalaries['June']}}",
                "{{$monthlySalaries['July']}}",
                "{{$monthlySalaries['August']}}",
                "{{$monthlySalaries['September']}}",
                "{{$monthlySalaries['October']}}",
                "{{$monthlySalaries['November']}}",
                "{{$monthlySalaries['December']}}"
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
