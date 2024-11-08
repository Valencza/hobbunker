@extends('backoffice.layouts.app')

@section('title', 'Laporan Absensi')

@push('styles')
    <style>
        .chart {
            height: 80vh !important;
        }
        @media only screen and (max-width: 600px) {
            .chart {
            height: 70vh !important;
            margin-bottom: 30px;
        }
        }
    </style>
@endpush

@section('content')
<div class="content flex-row-fluid" id="kt_content">
    {{-- breadcrumb --}}
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <li class="breadcrumb-item text-gray-600">
            <a href="{{ route('home') }}" class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </a>
        </li>
        <li class="breadcrumb-item text-gray-500">Laporan</li>
    </ul>
    {{-- end breadcrumb --}}

    <div class="row rounded-3 my-3 mb-10">
        <div class="col-md-6 col-12">
            <div class="chart card shadow-sm rounded-3 p-5">
                <div class="row p-2">
                    <div class="col-6">
                        <h3>Performance</h3>
                        <h6 class="text-muted fs-7">Rekap Absensi Hari ini</h6>
                    </div>
                </div>
                <div class="row align-items-center">
                    <canvas id="myChart" style="height: 100%"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 ">
            <div class="chart card shadow-sm p-6">
                <div class="p-2">
                    <h3>Grafik Kehadiran</h3>
                    <h6 class="text-muted fs-7">Total Kehadiran Mingguan</h6>
                </div>
                <div id="chart" style="height: 100%"></div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills mb-5 d-flex" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home"
                type="button" role="tab" aria-controls="pills-home" aria-selected="true">Daftar Absensi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Belum Absensi</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="py-5 card p-4 rounded-3 shadow-sm mb-5">
                <h3>Filter Absensi</h3>
                <form action="" class="row" onchange="this.submit()">
                    <div class="form-group col-12 col-md-4">
                        <label for="search">Nama Karyawan</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                            placeholder="Masukkan nama karyawan">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ $startDate }}">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="end_date">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                </form>
            </div>

            <div class="py-5 card p-4 rounded-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3>Daftar Absensi</h3>
                    <a href="{{ route('absent-report.pdf', ['search' => $search, 'startDate' => $startDate, 'endDate' => $endDate]) }}"
                        class="d-flex gap-3 align-items-center btn btn-light-success px-3 py-2">
                        <i class="bi bi-upload"></i>
                        <span>Export Report</span>
                    </a>
                </div>

                <div style="width: 100%; overflow: auto">
                    <table class="table table-row-dashed table-row-gray-300 gy-7 mx-5">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th>Karyawan</th>
                                <th class="text-center">Masuk</th>
                                <th class="text-center">Pekerjaan</th>
                                <th class="text-center">Status</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-center">Jarak</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Pulang</th>
                                <th class="text-center">Pekerjaan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Jarak</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absents as $absent)
                            <tr>
                                <td class="d-flex align-items-center gap-5">
                                    <h6 class="text-dark fw-normal mt-2">{{ $absent->masterUser->name }}</h6>
                                </td>
                                <td class="text-center">{{ $absent->checkin_clock }}</td>
                                <td class="text-center">{{ $absent->checkin_detail_job }}</td>
                                <td class="text-center" colspan="4">
                                    <form action="{{ route('absent-report.update', $absent->uuid) }}" method="POST"
                                        onchange="this.submit()">
                                        @csrf
                                        @method('PUT')

                                        <select name="status" class="form-select text-center px-3 py-3">
                                            <option value="hadir" @selected($absent->status == 'hadir')>Hadir</option>
                                            <option value="tidak hadir" @selected($absent->status == 'tidak
                                                hadir')>Tidak Hadir
                                            </option>
                                            <option value="cuti" @selected($absent->status == 'cuti')>Cuti</option>
                                            <option value="terlambat" @selected($absent->status ==
                                                'terlambat')>Terlambat</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    @if ($absent->checkin_status_radius == 'dalam radius')
                                    <span class="badge badge-success">
                                        @else
                                        <span class="badge badge-danger">
                                            @endif
                                            {{ ucwords($absent->checkin_status_radius) }}
                                        </span>
                                </td>
                                <td class="text-center">
                                    @if ($absent->checkin_photo)
                                    <img src="{{ asset($absent->checkin_photo) }}" width="100" height="80"
                                        style="object-fit: contain" alt="Checkin Photo">
                                    @else
                                    <span class="badge badge-success">Scan Wajah</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $absent->checkout_clock ?? '--:--:--' }}</td>
                                <td class="text-center">{{ $absent->checkout_detail_job ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($absent->checkout_clock)
                                    @if ($absent->checkout_status == 'checkout')
                                    <span class="badge badge-success">
                                        @else
                                        <span class="badge badge-danger">
                                            @endif
                                            {{ ucwords($absent->checkout_status) }}
                                        </span>
                                        @else
                                        -
                                        @endif
                                </td>
                                <td class="text-center">
                                    @if ($absent->checkout_clock)
                                    @if ($absent->checkout_status_radius == 'dalam radius')
                                    <span class="badge badge-success">
                                        @else
                                        <span class="badge badge-danger">
                                            @endif
                                            {{ ucwords($absent->checkout_status_radius) }}
                                        </span>
                                        @else
                                        -
                                        @endif
                                </td>
                                <td class="text-center">
                                    @if ($absent->checkout_photo)
                                    <img src="{{ asset($absent->checkout_photo) }}" width="100" height="80"
                                        style="object-fit: contain" alt="Checkout Photo">
                                    @elseif($absent->checkout_clock && !$absent->checkout_photo)
                                    <span class="badge badge-success">Scan Wajah</span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('absent-report.detail', $absent->masterUser->uuid) }}">
                                        <button class="btn btn-primary btn-sm">Detail</button>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5">
                        {{ $absents->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="py-5 card p-4 rounded-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3>Belum Absen</h3>
                </div>
                <div style="width: 100%; overflow: auto">
                    <table class="table table-row-dashed table-row-gray-300 gy-7 mx-5">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th>No</th>
                                <th>Karyawan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notAbsents as $notAbsent)
                            <tr>
                                <td class="text-dark fw-normal mt-2">{{ $loop->iteration }}</td>
                                <td class="text-dark fw-normal mt-2">{{ $notAbsent->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5">
                        {{ $notAbsents->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'doughnut',
        height: '100%',
        data: {
            labels: ['Hadir', 'Belum Absen', 'Terlambat', 'Cuti'],
            datasets: [{
                label: 'Laporan Absensi',
                data: ["{{$countAbsentsOnTime}}", "{{$countAbsentsNotYet}}", "{{$countAbsentsLate}}",
                    "{{$countAbsentsLeave}}"
                ],
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

    var options1 = {
        series: [{
                name: 'Terlambat',
                data: [
                    "{{$countAbsentsLateWeek['Monday']}}",
                    "{{$countAbsentsLateWeek['Tuesday']}}",
                    "{{$countAbsentsLateWeek['Wednesday']}}",
                    "{{$countAbsentsLateWeek['Thursday']}}",
                    "{{$countAbsentsLateWeek['Friday']}}",
                    "{{$countAbsentsLateWeek['Saturday']}}"
                ]
            },
            {
                name: 'Hadir',
                data: [
                    "{{$countAbsentsOnTimeWeek['Monday']}}",
                    "{{$countAbsentsOnTimeWeek['Tuesday']}}",
                    "{{$countAbsentsOnTimeWeek['Wednesday']}}",
                    "{{$countAbsentsOnTimeWeek['Thursday']}}",
                    "{{$countAbsentsOnTimeWeek['Friday']}}",
                    "{{$countAbsentsOnTimeWeek['Saturday']}}"
                ]
            }
        ],
        chart: {
            type: 'bar',
            height: '90%'
        },
        xaxis: {
            categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu']
        },
        dataLabels: {
            enabled: false
        }
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options1);
    chart.render();

</script>
@endpush
