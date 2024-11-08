@extends('client.layouts.app')

@section('title', 'Riwayat Absensi')

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
        <li class="breadcrumb-item fw-normal">Home</li>
        <li class="breadcrumb-item fw-normal">Absensi</li>
        <li class="breadcrumb-item text-gray-500">Riwayat Absensi</li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Riwayat Absensi</h2>

    <section>
        @foreach ($absents as $absent)
            {{-- card --}}
        <div class="d-flex flex-column gap-2 mb-3">
            <div>
                <div class="card rounded-2xl gap-2 justify-content-between align-items-start px-4 py-3">
                    <div class="anchor mb-3 fw-normal d-flex gap-3 align-items-center">
                        <i class="bi bi-calendar2-week-fill fw-bolder fs-5 text-black text-medium"></i>
                        <span class="fs-5 text-medium">{{$absent->formatted_created_at}}</span>
                    </div>

                    <div class="d-flex justify-between w-100">
                        <div class="d-flex flex-column gap-2 w-100">
                            <div class="d-flex gap-3">
                                <div>
                                    <div class="bg-blue-600 rounded-circle p-3 d-flex align-items-center">
                                        <i class="bi bi-box-arrow-in-right fs-4 fw-bolder text-white"></i>
                                    </div>
                                </div>

                                <div>
                                    <h6 class="text-secondary">Absen Masuk</h6>
                                    <h6 class="text-medium">{{$absent->checkin_clock}},
                                        <span class="text-success ml-1 fs-6">{{$absent->status}}</span>
                                    </h6>
                                </div>
                            </div>

                            <div class="d-flex gap-3">
                                <div>
                                    <div class="bg-red-600 rounded-circle p-3 d-flex align-items-center">
                                        <i class="bi bi-box-arrow-in-left fs-4 fw-bolder text-white"></i>
                                    </div>
                                </div>

                                <div>
                                    <h6 class="text-secondary">Absen Pulang</h6>
                                    <h6 class="text-medium">{{$absent->checkout_clock ?? '--:--'}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
        
        {{-- empty --}}
        @if (count($absents) == 0)
        <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                    alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>

</main>
@endsection
