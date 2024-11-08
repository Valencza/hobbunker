@extends('client.layouts.app')

@section('title', 'Detail Penurunan Inventaris')

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
        <h2 class="anchor mb-5 mt-5">Penurunan Inventaris</h2>
    </div>

    <section class="card shadow-sm rounded-2xl">
        <div class="d-flex justify-content-between align-items-center p-4 border-1 border-bottom">
            <h3>Detail Penurunan Inventaris</h3>
            @switch($dropItem->status)
            @case('proses')
            <span class="btn btn-light-primary text-primary w-25 py-1 text-sm">{{ucwords($dropItem->status)}}</span>
            @break
            @case('selesai')
            <span class="btn btn-light-success text-success w-25 py-1 text-sm">{{ucwords($dropItem->status)}}</span>
            @break
            @endswitch
        </div>
        <div class="px-4">
            <div class="mt-3 text-secondary fw-lighter">
                <h4 class="mb-1 text-dark">Nama PIC</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$dropItem->masterUser->name}}</h5>

                <h4 class="mb-1 text-dark">Tanggal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$dropItem->formatted_created_at}}</h5>

                <h4 class="mb-1 text-dark">Kapal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$dropItem->masterShip->name}}</h5>

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
                            @foreach ($dropItem->dropItemDetails as $dropItemDetail)
                            <tr>
                                <td class="text-secondary">
                                    <span class=" fs-5 fw-medium text-secondary">{{$dropItemDetail->requestItemDetailStock->requestItemDetail->masterItem->name}}</span>
                                    <p class="fs-5 m-0">Bagian: {{$dropItemDetail->requestItemDetailStock->requestItemDetail->position}}</p>
                                </td>
                                <th class="text-secondary">1</th>
                                <td class="text-secondary">{{ucwords($dropItemDetail->requestItemDetailStock->requestItemDetail->masterItem->masterUnit->name)}}</td>
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
