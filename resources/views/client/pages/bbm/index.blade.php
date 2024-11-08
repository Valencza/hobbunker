@extends('client.layouts.app')

@section('title', 'Pemakian BBM')

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

    <h2 class="anchor mb-5 mt-3">Pemakaian BBM</h2>

    <section class="rounded-2xl shadow-sm position-relative card-attendance-s">
        <img src="{{ asset('assets/media/illustrations/cuti_background.svg') }}" alt=""
            class="position-absolute rounded-4 w-100">

        <div class="position-absolute z-2 text-white p-4 w-100 border rounded-4 shadow-sm">
            <div class="d-flex justify-content-end">
                <a href="{{route('bbm.create')}}" class="btn btn-success fw-bolder">
                    <i class="bi bi-plus-square"></i>
                    Buat Laporan
                </a>
            </div>

            <div class="text-white fw-bolder mt-5 mb-4">
                <h3 class="fw-bolder mb-4">BBM</h3>
            </div>

            <div class="row pb-2">
                <div class="col-6">
                    <div class="card h-100 bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-inbox text-primary fs-1"></i>
                        <h1 class="text-primary text-2xl mt-3">{{$totalBBMStock}} <span
                                class="fs-4 fw-normal">{{ucwords($BBMUnit)}}</span></h1>
                        <h4 class="text-secondary">Sisa Stok</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card h-100 bg-body-secondary p-3 rounded-3 my-2">
                        <img width="30" src="{{asset('assets/media/illustrations/icon_solar.webp')}}">
                        <h1 class="text-danger fs-1 mt-3">{{$latestBBM->total_hour ?? '-'}} Jam</h1>
                        <h4 class="text-secondary">Jumlah Jam</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">Riwayat Pemakaian BBM</h2>
        </div>

        {{-- search form --}}
        <form data-kt-search-element="form" class="w-100 position-relative my-4" autocomplete="off">
            <!--begin::Hidden input(Added to disable form autocomplete)-->
            <input type="hidden" />
            <!--end::Hidden input-->
            <!--begin::Icon-->
            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
            <span
                class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-3 translate-middle-y">
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
            <input type="text" class="form-control form-control-lg  px-15" name="search"
                value="{{$search}}" placeholder="Cari..." data-kt-search-element="input" />
            <!--end::Input-->
            <!--begin::Spinner-->
            <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                data-kt-search-element="spinner">
                <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
            </span>
            <!--end::Spinner-->
            <!--begin::Reset-->
            <span
                class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
                data-kt-search-element="clear">
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
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
        {{-- end search form --}}

        <div class="d-flex flex-column gap-3 mb-5">
            @foreach ($BBMChanges as $BBMChange)
            <div
                class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                <div class="card btn btn-light-info p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                    <img width="30" src="{{asset('assets/media/illustrations/icon_solar.webp')}}">
                </div>
                <div class="d-flex flex-column w-100">
                    <span class="fw-medium fs-5 p-0">{{ucwords($BBMChange->type)}}</span>
                    <h4 class="text-muted p-0 text-secondary fs-6">
                        Tanggal :
                        <span class="text-info ml-3">{{$BBMChange->formatted_created_at}}</span>
                    </h4>
                </div>
            </div>
            @endforeach

            {{$BBMChanges->links('vendor.pagination.bootstrap-5')}}

            {{-- empty --}}
            @if (count($BBMChanges) == 0)
            <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                alt="Empty Illustration">
            @endif
            {{-- end empty --}}
        </div>
    </section>
</main>
@endsection
