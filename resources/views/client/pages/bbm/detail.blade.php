@extends('client.layouts.app')

@section('title', 'Detail Pergantian Oli')

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
        <li class="breadcrumb-item text-gray-500">Jadwal Ganti Oli</li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Riwayat Pergantian Oli</h2>

    <section>
        <div class="card shadow-sm rounded-2xl py-3">
            <h2 class="anchor border-bottom px-4 pb-3">Detail Laporan Pergantian Oli</h2>
            <div class="px-4 pt-3">
                <h4 class="mb-1">Nama Kapal</h4>
                <span>{{$oilChange->masterShip->name}}</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Pesawat</h4>
                <span>{{$oilChange->section}}</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Tanggal Laporan</h4>
                <span>{{$oilChange->formatted_created_at}}</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Tanggal Pergantian Oli</h4>
                <span>{{$oilChange->formatted_date}}</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Objek</h4>
                <span>{{$oilChange->object}}</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <h4 class="mb-1">Jam Kerja Oli</h4>
                <span>{{$oilChange->hour}}</span>
                <div class="border-t border-1 border-dashed my-2"></div>

                <div class="pt-3">
                    <table class="table table-row-dashed table-row-gray-300 gy-7">
                        <thead>
                            <tr class="fs-5 text-gray-800">
                                <th class="px-0 py-2">Merk Oli</th>
                                <th class="px-0 py-2">Qty</th>
                                <th class="px-0 py-2">Bagian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($oilChange->oilChangeDetails as $oilChangeDetail)
                            <tr>
                                <td class="px-0 py-3 text-gray-400 fs-5">{{$oilChangeDetail->merk ?? 'Non Merk'}}</td>
                                <td class="px-0 py-3 text-gray-400 fs-5">{{$oilChangeDetail->qty}}</td>
                                <td class="px-0 py-3 text-gray-400 fs-5">{{$oilChangeDetail->position}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection
