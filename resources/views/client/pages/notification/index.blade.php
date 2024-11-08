@extends('client.layouts.app')

@section('title', 'Notifikasi')

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

    <h2 class="anchor mb-5 mt-3">Notifikasi</h2>

    <section class="w-100">
        @if (count($today) > 0)
        {{-- today --}}
        <div class="anchor mb-3 mt-3 fw-normal d-flex gap-3 align-items-center">
            <span class="fs-4 fw-bold">Hari Ini</span>
        </div>
        @foreach ($today as $item)
        {{-- card --}}
        <div class="d-flex flex-column gap-3 mb-3">
            <div>
                <div class="card d-flex gap-4 flex-row justify-content-between align-items-start p-4">
                    <span class="badge badge-light-dark rounded-circle p-2">
                        @switch($item->type)
                        @case('absen')
                        <i class="bi bi-bell fs-6 text-black"></i>
                        @break
                        @case('stock')
                        <i class="bi bi-box-seam fs-6 text-black"></i>
                        @break
                        @case('request')
                        <i class="bi bi-card-text fs-6 text-black"></i>
                        @break
                        @endswitch
                    </span>
                    <div class="d-flex flex-column w-100">
                        <span class="fw-bold fs-5">{{$item->title}}</span>
                        <div class="anchor mt-2 fw-normal d-flex gap-3 align-items-center">
                            <span class="fs-6 fw-normal">{{$item->description}}</span>
                        </div>
                    </div>
                </div>
            </div>
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
            <div>
                <div class="card d-flex gap-4 flex-row justify-content-between align-items-start p-4">
                    <span class="badge badge-light-dark rounded-circle p-2">
                        @switch($item->type)
                        @case('absen')
                        <i class="bi bi-bell fs-6 text-black"></i>
                        @break
                        @case('stock')
                        <i class="bi bi-box-seam fs-6 text-black"></i>
                        @break
                        @case('request')
                        <i class="bi bi-card-text fs-6 text-black"></i>
                        @break
                        @endswitch
                    </span>
                    <div class="d-flex flex-column w-100">
                        <span class="fw-bold fs-5">{{$item->title}}</span>
                        <div class="anchor mt-2 fw-normal d-flex gap-3 align-items-center">
                            <span class="fs-6 fw-normal">{{$item->description}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end card --}}
        @endforeach
        {{-- end yesterday --}}
        @endif

        {{-- other --}}
        @if (count($today) > 0 && count($yesterday) > 0)
        <div class="anchor mb-3 mt-3 fw-normal d-flex gap-3 align-items-center">
            <span class="fs-4 fw-bold">Lainnya</span>
        </div>
        @endif
        @foreach ($other as $item)
        {{-- card --}}
        <div class="d-flex flex-column gap-3 mb-3">
            <div>
                <div class="card d-flex gap-4 flex-row justify-content-between align-items-start p-4">
                    <span class="badge badge-light-dark rounded-circle p-2">
                        @switch($item->type)
                        @case('absen')
                        <i class="bi bi-bell fs-6 text-black"></i>
                        @break
                        @case('stock')
                        <i class="bi bi-box-seam fs-6 text-black"></i>
                        @break
                        @case('request')
                        <i class="bi bi-card-text fs-6 text-black"></i>
                        @break
                        @endswitch
                    </span>
                    <div class="d-flex flex-column w-100">
                        <span class="fw-bold fs-5">{{$item->title}}</span>
                        <div class="anchor mt-2 fw-normal d-flex gap-3 align-items-center">
                            <span class="fs-6 fw-normal">{{$item->description}}</span>
                        </div>

                        <span
                            class="fs-6 fw-normal text-body-tertiary text-medium">{{$item->formatted_created_at }}</span>
                    </div>
                </div>
            </div>
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
