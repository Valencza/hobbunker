@extends('client.layouts.app')

@section('title', 'Absensi')

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

    <h2 class="anchor mb-5 mt-3">Absensi</h2>

    <section class="rounded-2xl shadow-sm position-relative card-attendance">
        <img src="{{ asset('assets/media/illustrations/absensi_background.svg') }}" alt=""
            class="position-absolute rounded-4 w-100">

        <div class="position-absolute z-2 text-white p-4 w-100 border rounded-4 shadow-sm">
            <div class="d-flex justify-content-between border-bottom-dashed border-1 pb-3">
                <div class="d-flex gap-3 align-items-center">
                    <i class="bi bi-calendar-week-fill fs-4 text-white"></i>
                    <span class="fs-4">{{$today}}</span>
                </div>

                <div class="d-flex gap-2 align-items-center">
                    <i class="bi bi-alarm-fill fs-4 text-white"></i>
                    <span class="fs-4" id="currenct_clock">--:--:--</span>
                </div>
            </div>

            <div class="my-4 d-flex justify-content-between">
                <div>
                    <h2>Jam Kerja</h2>
                    <p class="fs-6">{{substr($settingAbsent->clock_in, 0, 5)}} WIB - {{substr($settingAbsent->clock_out, 0, 5)}} WIB</p>
                </div>
                <div>
                    {{-- @if ($clock >= $settingAbsent->clock_in && $clock <= $settingAbsent->clock_out)
                        <button class="btn btn-primary d-flex gap-2 align-items-center rounded-3">
                            <i class="bi bi-box-arrow-in-right fs-4 fw-bolder"></i>
                            <span class="fw-normal fs-4 ml-3">Masuk</span>
                        </button>
                        @endif
                        @if ($clock >= $settingAbsent->clock_out && $clock <= $settingAbsent->clock_out_limit)
                            <button class="btn btn-danger d-flex gap-2 align-items-center rounded-3">
                                <i class="bi bi-box-arrow-in-right fs-4 fw-bolder"></i>
                                <span class="fw-normal fs-4 ml-3">Pulang</span>
                            </button>
                            @endif --}}

                            @if ($absentToday)
                            <a href="{{route('absent.checkinout')}}" class="btn btn-danger d-flex gap-2 align-items-center rounded-3">
                                <i class="bi bi-box-arrow-in-left fs-4 fw-bolder"></i>
                                <span class="fw-normal fs-4 ml-3">Pulang</span>
                            </a>
                            @else
                            <a href="{{route('absent.checkinout')}}" class="btn btn-primary d-flex gap-2 align-items-center rounded-3">
                                <i class="bi bi-box-arrow-in-right fs-4 fw-bolder"></i>
                                <span class="fw-normal fs-4 ml-3">Masuk</span>
                            </a>    
                            @endif
                </div>
            </div>

            <div class="row pb-2">
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-alarm-fill text-success fs-3"></i>
                        <h1 class="text-success text-2xl mt-3">{{$countAbsentsOnTime}}</h1>
                        <h4 class="text-secondary">Tepat Waktu</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-alarm-fill text-primary fs-3"></i>
                        <h1 class="text-primary text-2xl mt-3">{{$countAbsentsLate}}</h1>
                        <h4 class="text-secondary">Terlambat</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-alarm-fill text-danger fs-3"></i>
                        <h1 class="text-danger text-2xl mt-3">{{$countAbsentsNotPresent}}</h1>
                        <h4 class="text-secondary">Tidak Hadir</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-alarm-fill fs-3 text-dark"></i>
                        <h1 class="text-2xl mt-3">{{$countAbsentsLeave}}</h1>
                        <h4 class="text-secondary">Cuti</h4>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between align-items-center mb-5 mt-3">
            <h2 class="anchor">Riwayat Absensi</h2>
            @if ($countAbsents > 5)
            <a href="{{route('absent.history')}}" class="text-primary">Lihat Semua</a>
            @endif
        </div>

        {{-- card --}}
        <div class="d-flex flex-column gap-3">
            @foreach ($absents as $item)
            <div class="card rounded-2xl border-dashed border-secondary px-4 py-3">
                <div class="d-flex justify-content-between my-3 align-items-center">
                    <h4>{{$item->formatted_created_at}}</h4>
                    @switch($item->type)
                    @case('hadir')
                    <div class="btn btn-light-success text-success px-3 py-1">{{ucwords($item->type)}}</div>
                    @break
                    @case('terlambat')
                    <div class="btn btn-light-primary text-success px-3 py-1">{{ucwords($item->type)}}</div>
                    @break
                    @case('tidak hadir')
                    <div class="btn btn-light-danger text-success px-3 py-1">{{ucwords($item->type)}}</div>
                    @break
                    @case('cuti')
                    <div class="btn btn-light-dark text-success px-3 py-1">{{ucwords($item->type)}}</div>
                    @break
                    @endswitch
                </div>
                <div class="d-flex justify-content-between ">
                    <div class="d-flex">
                        <div class="bg-blue-secondary text-primary p-3 rounded-3xl">
                            <i class="bi bi-box-arrow-in-right fs-3 fw-bolder text-primary"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-3">{{$item->checkin_clock}} WIB</span>
                            <span class="text-secondary">Absen Masuk </span>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="badge badge-light-danger p-3 rounded-3xl">
                            <i class="bi bi-box-arrow-in-right fs-3 fw-bolder text-danger"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fs-3">{{$item->checkout_clock ?? '--:--'}}WIB</span>
                            <span class="text-secondary">Absen Keluar </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{-- end card --}}

        {{-- empty --}}
        @if ($countAbsents == 0)
        <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                    alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>


</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        realtimeClock();
    });

</script>
@endpush
