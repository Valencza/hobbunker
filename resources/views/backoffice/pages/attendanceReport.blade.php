@extends('backoffice.layouts.app')

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
            <li class="breadcrumb-item text-gray-500">Home</li>
            <!--end::Item-->
        </ul>
        {{--        end breaddcrumb--}}

        <div class="row gap-10 rounded-3 my-3">
            <div class="col card shadow-sm rounded-3 p-5">
                <div class="row p-5">
                    <div class="col-6">
                        <h3>Performance</h3>
                        <h6 class="text-muted fs-7">Rekap Absensi Hari ini</h6>
                    </div>
                    <div class="col-6 text-end">
                        <button href="#" class="btn btn-bg-secondary fs-7 px-5 py-2">View</button>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6 p-4">
                        <table class="table table-row-dashed table-row-secondary gy-5">
                            <tr class="border-bottom border-dashed border-secondary">
                                <td class="d-flex gap-2 align-items-center">
                                    <div class="w-10px h-10px rounded-1 bg-primary"></div>
                                    <span>Hadir</span>
                                </td>
                                <td class="">
                                    30 <span class="fw-bolder">Pegawai</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-dashed border-secondary">
                                <td class="d-flex gap-2 align-items-center">
                                    <div class="w-10px h-10px rounded-1 bg-danger"></div>
                                    <span>Belum Absen</span>
                                </td>
                                <td class="">
                                    30 <span class="fw-bolder">Pegawai</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-dashed border-secondary">
                                <td class="d-flex gap-2 align-items-center">
                                    <div class="w-10px h-10px rounded-1 bg-warning"></div>
                                    <span>Terlambat</span>
                                </td>
                                <td class="">
                                    30 <span class="fw-bolder">Pegawai</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-dashed border-secondary">
                                <td class="d-flex gap-2 align-items-center">
                                    <div class="w-10px h-10px rounded-1 bg-blue-600"></div>
                                    <span>Lembur</span>
                                </td>
                                <td class="">
                                    30 <span class="fw-bolder">Pegawai</span>
                                </td>
                            </tr>
                            <tr class="border-bottom border-dashed border-secondary">
                                <td class="d-flex gap-2 align-items-center">
                                    <div class="w-10px h-10px rounded-1 bg-secondary"></div>
                                    <span>Cuti</span>
                                </td>
                                <td class="">
                                    30 <span class="fw-bolder">Pegawai</span>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-6 p-4">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col card shadow-sm p-6">
                <div class="p-3">
                    <h3>Grafik Kehadiran</h3>
                    <h6 class="text-muted fs-7">Total Kehadiran Mingguan</h6>
                </div>
                {{--                <canvas id="chart1" class="mh-400px"></canvas>--}}
                <div id="chart"></div>
            </div>
        </div>

        <div class="d-flex gap-3 align-items-center justify-content-between my-5">
            <div class="d-flex gap-3 align-items-center">
                <h3 class="mt-2">List Karyawan</h3>
                <!--begin::Form-->
                <form data-kt-search-element="form" class="position-relative" autocomplete="off">
                    <!--begin::Hidden input(Added to disable form autocomplete)-->
                    <input type="hidden"/>
                    <!--end::Hidden input-->
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span
                        class="svg-icon svg-icon-2 svg-icon-gray-700 position-absolute top-50 translate-middle-y ms-4">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                              height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                              fill="black"/>
														<path
                                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                            fill="black"/>
													</svg>
												</span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Input-->
                    <input type="text"
                           class="form-control  h-40px bg-light border border-secondary ps-13 fs-7"
                           name="search"
                           value="" placeholder="Search ..." data-kt-search-element="input"/>
                    <!--end::Input-->
                    <!--begin::Spinner-->
                    <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                          data-kt-search-element="spinner">
													<span
                                                        class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
												</span>
                    <!--end::Spinner-->
                    <!--begin::Reset-->
                    <span
                        class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4"
                        data-kt-search-element="clear">
													<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
													<span class="svg-icon svg-icon-2 me-0">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                             viewBox="0 0 24 24" fill="none">
															<rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                                  rx="1" transform="rotate(-45 6 17.3137)"
                                                                  fill="black"/>
															<rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                  transform="rotate(45 7.41422 6)" fill="black"/>
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
            <table class="table table-row-dashed table-row-gray-300 gy-7">
                <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th>Nama Karyawan</th>
                    <th>Jabawan</th>
                    <th>Telepon</th>
                    <th>Umur</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr>Position
                    <td class="d-flex align-items-center gap-5">
                        <img src="{{ asset('assets/media/misc/bg-2.jpg') }}" alt="HOB Logo" class="w-30px h-30px rounded-pill">
                        <h6 class="text-dark fw-normal mt-2">Arya Rizky</h6>
                    </td>
                    <td>Karyawan</td>
                    <td>+6298712397</td>
                    <td>61</td>
                    <td>
                        <a href="#" class="btn btn-light border border-secondary px-4 py-2">Detail</a>
                    </td>
                </tr>
                <tr>
                    <td>Garrett Winters</td>
                    <td>Accountant</td>
                    <td>Tokyo</td>
                    <td>63</td>
                    <td>
                            <a href="#" class="btn btn-light border border-secondary px-4 py-2">Detail</a>
                    </td>
                </tr>
                </tbody>
            </table>

            <ul class="pagination">
                <li class="page-item previous disabled"><a href="#" class="page-link"><i class="previous"></i></a></li>
                <li class="page-item "><a href="#" class="page-link">1</a></li>
                <li class="page-item active"><a href="#" class="page-link">2</a></li>
                <li class="page-item "><a href="#" class="page-link">3</a></li>
                <li class="page-item "><a href="#" class="page-link">4</a></li>
                <li class="page-item "><a href="#" class="page-link">5</a></li>
                <li class="page-item "><a href="#" class="page-link">6</a></li>
                <li class="page-item next"><a href="#"  class="page-link"><i class="next"></i></a></li>
            </ul>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // var ctx1 = document.getElementById('chart1').getContext('2d');
        // var data = {
        //     labels: ['January', 'February', 'March', 'April', 'May'],
        //     datasets: [{
        //         label: 'Sales',
        //         backgroundColor: 'rgba(54, 162, 235, 0.2)',
        //         borderColor: 'rgba(54, 162, 235, 1)',
        //         borderWidth: 1,
        //         data: [10, 20, 15, 25, 30]
        //     }]
        // };
        // var options = {
        //     scales: {
        //         yAxes: [{
        //             ticks: {
        //                 beginAtZero: true
        //             }
        //         }]
        //     }
        // };
        // var myChart = new Chart(ctx1, {
        //     type: 'bar',
        //     data: data,
        //     options: options
        // });

        var options1 = {
            series: [{
                name: 'Series A',
                data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
            }, {
                name: 'Series B',
                data: [25, 20, 35, 40, 50, 55, 60, 70, 80]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                },
            },
            legend: {
                show: true,
                labels: {
                    colors: '#333'
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
            },
            yaxis: {
                title: {
                    text: 'Values'
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart"), options1);
        chart.render();
    </script>
@endpush
