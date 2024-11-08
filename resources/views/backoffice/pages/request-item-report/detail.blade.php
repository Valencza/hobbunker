@extends('backoffice.layouts.app')

@section('title', 'Detail Permintaan Pengiriman')

@section('content')
<div class="content flex-row-fluid" id="kt_content">
    {{-- breadcrumb --}}
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">
            <div class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </div>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item ">Laporan</li>
        <li class="breadcrumb-item">
            <a href="{{route('request-item-report', [
                'uuid' => $requestItem->uuid,
                'master_ship_uuid' => $masterShipUuid,
                'type' => $type,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ])}}" class="text-decoration-none">Laporan Permintaan Pengiriman</a>
        </li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Detail Permintaan Pengiriman</h3>

    <section class="py-5 card p-4 rounded-3 shadow-sm">
        <div class="px-4 py-3">
            <h4 class="mb-1">Nama PIC</h4>
            <p class="pb-4 mt-2">{{$requestItem->masterUser->name}}</p>

            <h4 class="mb-1">Tanggal</h4>
            <p class="pb-4 mt-2">{{$requestItem->formatted_created_at}}</p>

            <h4 class="mb-1">Kapal</h4>
            <p class="pb-4 mt-2">{{$requestItem->masterShip->name}}</p>

            <h4 class="mb-1 text-dark">Deskripsi</h4>
            <p class="pb-4 mt-2">{{$requestItem->description ?? '-'}}</p>

            @if ($requestItem->status == 'selesai')
            <h4 class="mb-1 text-dark">Foto Barang</h4>
            <div class="row mb-2">
                @foreach ($requestItem->requestItemDocumentsItem as $requestItemDocument)
                <a href="{{asset($requestItemDocument->file)}}" download class="col-md-4 col-12">
                    <div class="file-preview">
                        @php
                        $ext = explode('.',$requestItemDocument->file);
                        $ext = end($ext);
                        @endphp
                        @if (in_array($ext, ['jpg', 'png', 'jpeg']))
                        <img src="{{asset($requestItemDocument->file)}}">
                        @else
                        <i class="fa fa-file"></i>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            
            <h4 class="mb-1 text-dark">Surat Jalan</h4>
            <div class="row mb-2">
                @if ($requestItem->requestItemDocumentsRoadLetter)
                <a href="{{asset($requestItem->requestItemDocumentsRoadLetter->file)}}" download class="col-md-4 col-12">
                @else
                <a href="{{route('road-letter.pdf', $requestItem->roadLetter->uuid)}}" download class="col-md-4 col-12">
                    @endif
                <div class="file-preview">
                        <i class="fa fa-file"></i>
                    </div>
                </a>
            </div>
            @endif

            <table id="table-item" class="table gs-7 gy-7 gx-7">
                <thead>
                    <tr class="fw-bold fs-4 text-gray-800 border-bottom border-gray-200">
                        <th class="px-0 py-2 text-left">Item</th>
                        <th class="px-2 py-2 text-center">Qty</th>
                        <th class="px-2 py-2 text-center">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requestItem->requestItemDetails as $requestItemDetail)
                    @if ($requestItemDetail->masterItem)
                    <tr>
                        @if (strpos($requestItemDetail->master_item_uuid, '~'))
                        <td class="text-left">
                            <span
                                class=" fs-5 fw-medium ">{{explode('~', $requestItemDetail->master_item_uuid)[1]}}</span>
                            <p class="fs-5 m-0">Bagian: {{$requestItemDetail->position}}</p>
                            @if ($requestItemDetail->reject)
                            <p class="fs-5 m-0 text-danger fw-bold mt-3">Ditolak:</p>
                            <p>{{$requestItemDetail->reject_description}}</p>
                            @endif
                            @if ($requestItemDetail->image)
                            <p class="fs-5 m-0">Foto:</p>
                            <img src="" width="200">
                            @endif
                            @if ($requestItemDetail->description)
                            <p class="fs-5 m-0">Keterangan:</p>
                            <p>{{$requestItemDetail->description}}</p>
                            @endif
                        </td>
                        <td class="text-center">{{$requestItemDetail->qty}}</td>
                        <td class="text-center">{{ucwords(explode('~', $requestItemDetail->master_item_uuid)[2])}}</td>
                        @else
                        <td class="">
                            <span class=" fs-5 fw-medium ">{{$requestItemDetail->masterItem->name}}</span>
                            <p class="fs-5 m-0">Bagian: {{$requestItemDetail->position}}</p>
                            @if ($requestItemDetail->image)
                            <p class="fs-5 m-0">Foto:</p>
                            <img src="{{asset($requestItemDetail->image)}}" width="200">
                            @endif
                            @if ($requestItemDetail->description)
                            <p class="fs-5 m-0">Keterangan:</p>
                            <p>{{$requestItemDetail->description}}</p>
                            @endif
                        </td>
                        <td class="text-center">{{$requestItemDetail->qty}}</td>
                        <td class="text-center">{{ucwords($requestItemDetail->masterItem->masterUnit->name)}}</td>
                        @endif
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
