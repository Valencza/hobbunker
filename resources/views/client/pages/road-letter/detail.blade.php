@extends('client.layouts.app')

@section('title', 'Detail Surat Jalan')

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

    <h2 class="anchor mb-5 mt-3">Surat Jalan</h2>

    <section>
        <div class="card shadow-sm rounded-2xl">
            <div class="d-flex justify-content-between align-items-center border-1 border-bottom p-4">
                <h3 class="mt-2">Detail Surat Jalan</h3>
                <div>
                    <a href="{{route('road-letter.pdf', $roadLetter->uuid)}}" class="btn btn-light-success px-3 py-2">
                        <i class="bi bi-printer"></i>
                        <span>Cetak Surat Jalan</span>
                    </a>
                </div>
            </div>

            <div class="px-4 py-3">
                <h4 class="mb-1">Nomor Dokumen</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$roadLetter->number}}</h5>

                <h4 class="mb-1">Tanggal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$roadLetter->formatted_created_at}}</h5>

                <h4 class="mb-1">Kapal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$roadLetter->requestItem->masterShip->name}}</h5>

                <table class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                            <th>Item</th>
                            <th class="w-25">Qty</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roadLetter->requestItem->requestItemDetails as $requestItemDetail)
                        <tr>
                            @if (strpos($requestItemDetail->master_item_uuid, '~'))
                            <td class="text-secondary">
                                <span
                                    class=" fs-5 fw-medium text-secondary">{{explode('~', $requestItemDetail->master_item_uuid)[1]}}</span>
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
                            <td class="text-secondary">
                                {{ucwords(explode('~', $requestItemDetail->master_item_uuid)[2])}}</td>
                            @else
                            <td class="text-secondary">
                                <span
                                    class=" fs-5 fw-medium text-secondary">{{$requestItemDetail->masterItem->name}}</span>
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
                            <td class="text-secondary">{{$requestItemDetail->qty}}</td>
                            <td class="text-secondary">{{ucwords($requestItemDetail->masterItem->masterUnit->name)}}
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <form action="{{route('road-letter.update',$roadLetter->uuid)}}" method="POST">
                    @csrf
                    @method('PUT')
                </form>

                @if ($roadLetter->status == 'baru')
                <button id="submit-btn"
                class="btn bg-success text-white w-100 d-flex gap-3 align-items-center w-100 justify-content-center fs-5">
                <i class="bi bi-check-circle text-white fs-4"></i>
                <span>Konfirmasi</span>
            </button>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $('#submit-btn').click(function () {
        Swal.fire({
            title: 'Konfirmasi Surat Jalan',
            text: "Pastikan data surat jalan telah sesuai",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                $('form').submit();
            }
        });
    });

</script>
@endpush
