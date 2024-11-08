@extends('client.layouts.app')

@section('title', 'Detail Pemakaian Barang')

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

    <div class="d-flex justify-content-between align-items-center">
        <h2 class="anchor mb-5 mt-5">Detail Pemakaian Barang</h2>
    </div>

    <section class="card shadow-sm rounded-2xl mb-4">
        <h3 class=" border-1 border-bottom p-4">Info Pemakaian Barang</h3>

        <div class="px-4 py-3">
            <h4 class="mb-1">Judul</h4>
            <h5 class="pb-4 text-secondary mt-2 text-capitalize">{{$usedItem->title}}</h5>

            <h4 class="mb-1 text-capitalize">Tanggal pemakaian</h4>
            <h5 class="pb-4 text-secondary mt-2">{{$usedItem->formatted_date}}</h5>

            <h4 class="mb-1">keterangan</h4>
            <h5 class="pb-4 text-secondary mt-2">{{$usedItem->description}}</h5>
        </div>
    </section>

    <section class="card shadow-sm rounded-2xl">
        <h3 class=" border-1 border-bottom p-4">Item Dipakai</h3>

        <div class="px-4 py-3 d-flex flex-column gap-3">
            @foreach ($usedItem->usedItemDetails as $usedItemDetail)
            <div class="card border-1 border-dashed rounded-2xl p-4">
                <span class="fs-5 m-0">{{$usedItemDetail->requestItemDetailStock->requestItemDetail->masterItem->name}}</span>
                <span class="">Code: {{$usedItemDetail->requestItemDetailStock->code}}</span>
            </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
