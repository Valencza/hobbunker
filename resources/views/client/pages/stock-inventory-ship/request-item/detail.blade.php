@extends('client.layouts.app')

@section('title', 'Detail Permintaan ' .ucwords($requestItem->type))

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
        <h2 class="anchor mb-5 mt-5">Permintaan {{ucwords($requestItem->type)}}</h2>
    </div>

    <section class="card shadow-sm rounded-2xl">
        <div class="d-flex justify-content-between align-items-center p-4 border-1 border-bottom">
            <h3>Detail Permintaan {{ucwords($requestItem->type)}}</h3>
            @switch($requestItem->status)
            @case('baru')
            <span class="btn btn-light-primary text-primary w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
            @break
            @case('terkonfirmasi')
            <span class="btn btn-light-success text-success w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
            @break
            @case('siap dikirim')
            <span class="btn btn-light-warning text-warning w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
            @break
            @case('selesai')
            <span class="btn btn-light-success text-success w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
            @break
            @case('ditolak')
            <span class="btn btn-light-danger text-danger w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
            @break
            @endswitch
        </div>
        <div class="px-4">
            <div class="mt-3 text-secondary fw-lighter">
                <h4 class="mb-1 text-dark">Nama PIC</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->masterUser->name}}</h5>

                <h4 class="mb-1 text-dark">Tanggal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->formatted_created_at}}</h5>

                <h4 class="mb-1 text-dark">Kapal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->masterShip->name}}</h5>

                <h4 class="mb-1 text-dark">Deskripsi</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->description}}</h5>

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

                <div class="pb-3">
                    <table class="table table-row-dashed table-row-gray-300 gy-7">
                        <thead>
                            <tr class="fw-bolder fs-6 text-gray-800">
                                <th>Item</th>
                                <th class="w-25">Qty</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requestItem->requestItemDetails as $requestItemDetail)
                            <tr>
                                @if (strpos($requestItemDetail->master_item_uuid, '~'))\
                                <td class="text-secondary">
                                    <span class=" fs-5 fw-medium text-secondary">{{explode('~', $requestItemDetail->master_item_uuid)[1]}}</span>
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
                                <td class="text-secondary">{{$requestItemDetail->qty}}</td>
                                <td class="text-secondary">{{ucwords(explode('~', $requestItemDetail->master_item_uuid)[2])}}</td>
                                @else
                                <td class="text-secondary">
                                    <span class=" fs-5 fw-medium text-secondary">{{$requestItemDetail->masterItem->name}}</span>
                                    <p class="fs-5 m-0">Bagian: {{$requestItemDetail->position}}</p>
                                    @if ($requestItemDetail->reject)
                                    <p class="fs-5 m-0 text-danger fw-bold mt-3">Ditolak:</p>
                                    <p>{{$requestItemDetail->reject_description}}</p>
                                    @endif
                                    @if ($requestItemDetail->image)
                                    <p class="fs-5 m-0">Foto:</p>
                                    <img src="{{asset($requestItemDetail->image)}}" width="200">
                                    @endif
                                    @if ($requestItemDetail->description)
                                    <p class="fs-5 m-0">Keterangan:</p>
                                    <p>{{$requestItemDetail->description}}</p>
                                    @endif
                                </td>
                                <td class="text-secondary">{{$requestItemDetail->qty}}</td>
                                <td class="text-secondary">{{ucwords($requestItemDetail->masterItem->masterUnit->name)}}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($requestItem->status == 'siap dikirim')
                <a href="{{route('stock-inventory-office.request-item.check', [$requestItem->uuid, $requestItem->type])}}" class="btn btn-primary w-100">
                    Selesaikan Pengiriman
                </a>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.nav-link').click(function () {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
        });
    });

</script>
@endpush
