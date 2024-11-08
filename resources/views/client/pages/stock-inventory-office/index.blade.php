@extends('client.layouts.app')

@section('title', 'Stok & Inventaris Kantor')

@section('content')
<main class="p-4">
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">
            <a href="{{ route('home') }}" class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-500">Home</li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Stok & Inventaris Kantor</h2>

    <section class="rounded-2xl shadow-sm position-relative card-attendance">
        <img src="{{ asset('assets/media/illustrations/cuti_background.svg') }}" alt=""
            class="position-absolute rounded-4 w-100">

        <div class="position-absolute z-2 text-white p-4 w-100 border rounded-4 shadow-sm">
            <div class="d-flex justify-content-end">
                <a href="{{route('stock-inventory-office.request-item.create')}}" class="btn btn-success fw-bolder">
                    Kirim Barang
                </a>
            </div>

            <div class="text-white fw-bolder mt-5 mb-4">
                <h3 class="fw-bolder mb-4">Data Stok & Inventaris</h3>
            </div>

            <div class="row pb-2">
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-inbox text-primary fs-1"></i>
                        <h1 class="text-primary text-2xl mt-3">{{$totalInventory}}</h1>
                        <p class="text-secondary" style="height: 20px">Total Inventaris</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-inbox text-success fs-1"></i>
                        <h1 class="text-success text-2xl mt-3">{{$totalStock}}</h1>
                        <p class="text-secondary" style="height: 20px">Total Stok</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-inbox text-warning fs-1"></i>
                        <h1 class="text-warning text-2xl mt-3">{{$totalRequest}}</h1>
                        <p class="text-secondary" style="height: 20px">Permintaan</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-inbox fs-1 text-danger"></i>
                        <h1 class="text-2xl text-danger mt-3">{{$totalDown}}</h1>
                        <h4 class="text-secondary" style="height: 20px">Penurunan</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row mb-5">
        <div class="col-6">
            <a href="{{route('stock-inventory-office.stock')}}"
                class="card h-100 px-4 py-2 rounded-2xl shadow-sm d-flex flex-column gap-2 text-decoration-none">
                <img src="{{ asset('assets/media/illustrations/icon_stock_kantor.webp') }}" alt="">
                <span class="text-muted text-center fs-5 fw-bold">Stok Kantor</span>
            </a>
        </div>
        <div class="col-6">
            <a href="{{route('stock-inventory-office.inventory')}}" class="card h-100 px-4 py-2 rounded-2xl shadow-sm d-flex flex-column gap-2 text-decoration-none">
                <img src="{{ asset('assets/media/illustrations/icon_inventaris_kantor.webp') }}" alt="">
                <span class="text-muted text-center fs-5 fw-bold">Inventaris Kantor</span>
            </a>
        </div>
    </div>

    <div class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3">Daftar Permintaan</h2>
            <a href="{{route('stock-inventory-office.request-item')}}" class="text-primary fs-5 text-decoration-none">Lihat Semua</a>
        </div>

        <div class="d-flex flex-column gap-3 mb-5">
            @foreach ($requestItems as $requestItem)
            <a href="{{route('stock-inventory-office.request-item.detail', $requestItem->uuid)}}"
                class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center p-2 text-decoration-none">
                <div class="centering bg-light-warning rounded p-3">
                    <i class="bi bi-inbox text-warning fs-2"></i>
                </div>
                <div class="d-flex flex-column w-100">
                    <span class="fw-medium fs-5 p-0">{{$requestItem->number}}</span>
                    <span class="text-muted p-0">{{$requestItem->total_item}} Item</span>
                    <div class="anchor fw-normal d-flex text-muted gap-1 align-items-center">
                        Date: <span class="fs-6 fw-normal text-primary">{{$requestItem->formatted_created_at}}</span>
                    </div>
                </div>
                <div class="d-flex flex-column justify-content-end w-50">
                    <div>
                        @switch($requestItem->status)
                        @case('baru')
                        <span
                            class="btn btn-light-primary text-primary py-1 px-1 w-100">{{ucwords($requestItem->status)}}</span>
                        @break
                        @case('terkonfirmasi')
                        <span
                            class="btn btn-light-success text-success py-1 px-1 w-100">{{ucwords($requestItem->status)}}</span>
                        @break
                        @case('siap dikirim')
                        <span
                            class="btn btn-light-warning text-warning py-1 px-1 w-100">{{ucwords($requestItem->status)}}</span>
                        @break
                        @case('selesai')
                        <span
                            class="btn btn-light-success text-success py-1 px-1 w-100">{{ucwords($requestItem->status)}}</span>
                        @break
                        @case('ditolak')
                        <span
                            class="btn btn-light-danger text-danger py-1 px-1 w-100">{{ucwords($requestItem->status)}}</span>
                        @break
                        @endswitch
                    </div>
                </div>
            </a>
            @endforeach

            {{-- empty --}}
            @if (count($requestItems) == 0)
            <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                alt="Empty Illustration">
            @endif
            {{-- end empty --}}
        </div>
    </div>
</main>
@endsection
