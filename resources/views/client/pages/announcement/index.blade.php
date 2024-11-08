@extends('client.layouts.app')

@section('title', 'Pengumuman')

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
        <li class="breadcrumb-item">
            <a href="{{route('home')}}" class="text-secondary text-decoration-none">Home</a>
        </li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Pengumuman</h2>

    <section class="w-100">
        @if (count($today) > 0)
        {{-- today --}}
        <div class="anchor mb-3 mt-3 fw-normal d-flex gap-3 align-items-center">
            <span class="fs-4 fw-bold">Hari Ini</span>
        </div>
        @foreach ($today as $item)
        {{-- card --}}
        <div class="d-flex flex-column gap-3 mb-3">
            <a href="{{route('announcement.detail', $item->uuid)}}" class="text-decoration-none">
                <div class="card shadow-sm d-flex gap-3 flex-row justify-content-between align-items-center p-2">
                    <div class="card bg-blue-secondary border-0 rounded-2xl w-25 d-flex align-items-center">
                        <i class="bi bi-calendar-week fs-1 text-primary"></i>
                    </div>
                    <div class="d-flex flex-column w-100">
                        <span class="fw-bolder fs-5">{{$item->title}}</span>
                        <div class="anchor mt-2 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week mt-1 fw-bolder"></i>
                            <span class="fs-6 fw-normal">{{$item->formatted_start_date}}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-end w-50">
                        @switch($item->type)
                        @case('libur nasional')
                        <div class="btn btn-light-danger text-sm w-100">{{ucwords($item->type)}}</div>
                        @break
                        @case('pengumuman')
                        <div class="btn btn-light-success text-sm w-100">{{ucwords($item->type)}}</div>
                        @break
                        @endswitch
                    </div>
                </div>
            </a>
        </div>
        {{-- end card --}}
        @endforeach
        {{-- end today --}}
        @endif

        @if (count($yesterday) > 0)
        {{-- yesterday --}}
        <div class="anchor mb-3 mt-3 fw-normal d-flex gap-3 align-items-center">
            <span class="fs-4 fw-bold">Kemarin</span>
        </div>
        @foreach ($yesterday as $item)
        {{-- card --}}
        <div class="d-flex flex-column gap-3 mb-3">
            <a href="{{route('announcement.detail', $item->uuid)}}"  class="text-decoration-none">
                <div class="card shadow-sm d-flex gap-3 flex-row justify-content-between align-items-center p-2">
                    <div class="card bg-blue-secondary border-0 rounded-2xl w-25 d-flex align-items-center">
                        <i class="bi bi-calendar-week fs-1 text-primary"></i>
                    </div>
                    <div class="d-flex flex-column w-100">
                        <span class="fw-bolder fs-5">{{$item->title}}</span>
                        <div class="anchor mt-2 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week mt-1 fw-bolder"></i>
                            <span class="fs-6 fw-normal">{{$item->formatted_start_date}}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-end w-50">
                        @switch($item->type)
                        @case('libur nasional')
                        <div class="btn btn-light-danger text-sm w-100">{{ucwords($item->type)}}</div>
                        @break
                        @case('pengumuman')
                        <div class="btn btn-light-success text-sm w-100">{{ucwords($item->type)}}</div>
                        @break
                        @endswitch
                    </div>
                </div>
            </a>
        </div>
        {{-- end card --}}
        @endforeach
        {{-- end yesterday --}}
        @endif

        {{-- other --}}
        @if (count($other) > 0)
        <div class="anchor mb-3 mt-3 fw-normal d-flex gap-3 align-items-center">
            <span class="fs-4 fw-bold">Lainnya</span>
        </div>
        @endif
        @foreach ($other as $item)
        {{-- card --}}
        <div class="d-flex flex-column gap-3 mb-3">
            <a href="{{route('announcement.detail', $item->uuid)}}"  class="text-decoration-none">
                <div class="card shadow-sm d-flex gap-3 flex-row justify-content-between align-items-center p-2">
                    <div class="card bg-blue-secondary border-0 rounded-2xl w-25 d-flex align-items-center">
                        <i class="bi bi-calendar-week fs-1 text-primary"></i>
                    </div>
                    <div class="d-flex flex-column w-100">
                        <span class="fw-bolder fs-5">{{$item->title}}</span>
                        <div class="anchor mt-2 fw-normal d-flex gap-3 align-items-center">
                            <i class="bi bi-calendar2-week mt-1 fw-bolder"></i>
                            <span class="fs-6 fw-normal">{{$item->formatted_start_date}}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-end w-50">
                        @switch($item->type)
                        @case('libur nasional')
                        <div class="btn btn-light-danger text-sm w-100">{{ucwords($item->type)}}</div>
                        @break
                        @case('pengumuman')
                        <div class="btn btn-light-success text-sm w-100">{{ucwords($item->type)}}</div>
                        @break
                        @endswitch
                    </div>
                </div>
            </a>
        </div>
        {{-- end card --}}
        @endforeach
        {{-- end other --}}

        {{-- empty --}}
        @if (count($today) == 0 && count($yesterday) == 0 && count($other) == 0)
        <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                    alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>
</main>
@endsection
