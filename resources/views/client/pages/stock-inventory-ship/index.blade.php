@extends('client.layouts.app')

@section('title', 'Daftar Kapal')

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

    <h2 class="anchor mb-5 mt-3">Daftar Kapal</h2>

    {{-- search form --}}
    <form data-kt-search-element="form" class="w-100 position-relative mb-5" autocomplete="off">
        <!--begin::Hidden input(Added to disable form autocomplete)-->
        <input type="hidden" />
        <!--end::Hidden input-->
        <!--begin::Icon-->
        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
        <span class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-3 translate-middle-y">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
            </svg>
        </span>
        <!--end::Svg Icon-->
        <!--end::Icon-->
        <!--begin::Input-->
        <input type="text" class="form-control form-control-lg  px-15" name="search" value="" placeholder="Cari..." data-kt-search-element="input" />
        <!--end::Input-->
        <!--begin::Spinner-->
        <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5" data-kt-search-element="spinner">
            <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
        </span>
        <!--end::Spinner-->
        <!--begin::Reset-->
        <span class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none" data-kt-search-element="clear">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
            <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </span>
        <!--end::Reset-->
    </form>
    {{-- end search form --}}


    <section class="d-flex flex-column gap-4">
        @foreach ($masterShips as $masterShip)
        <a href="{{route('stock-inventory-ship.detail', $masterShip->uuid)}}" class="card rounded-2xl shadow-sm p-3 text-decoration-none mb-3">
            <img src="{{ asset($masterShip->image) }}" class="mb-3">
            <div class="text-center">
                <h3 class="fw-bolder">{{$masterShip->name}}</h3>
                <h3 class="text-secondary">{{$masterShip->type}} - Capacity of {{$masterShip->capacity}}</h3>
            </div>
        </a>
        @endforeach

        {{$masterShips->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($masterShips) == 0)
        <img class="img-fluid mx-auto d-block" width="200" src="{{asset('assets/media/illustrations/empty.webp')}}"
            alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>


</main>
@endsection
