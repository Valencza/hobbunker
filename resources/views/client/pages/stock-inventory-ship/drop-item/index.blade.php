@extends('client.layouts.app')

@section('title', 'Penurunan Barang')

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
        <h2 class="anchor mb-5 mt-5">Daftar Penurunan Barang</h2>
        <div>
            <a href="{{route('stock-inventory-ship.drop-item.create', $uuid)}}"
                class="btn btn-primary d-flex gap-1 align-items-center">
                <i class="bi bi-plus-square"></i>
                <span>Penurunan</span>
            </a>
        </div>
    </div>

    <section class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">Daftar Penurunan Barang</h2>
        </div>

        <div class="d-flex flex-column gap-3 mb-5">
            @foreach ($dropItems as $dropItem)
            <div
                class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                <div class="card btn btn-light-danger p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                    <i class="bi bi-inbox text-danger fs-4 my-2 ml-3"></i>
                </div>
                <div class="d-flex flex-column w-100">
                    <span class="fw-medium fs-5 p-0">{{$dropItem->name}}</span>
                    <h4 class="text-muted p-0 text-secondary fs-6">
                        Tanggal :
                        <span class="text-info">{{$dropItem->formatted_date}}</span>
                    </h4>
                    <h4 class="text-muted p-0 text-secondary fs-6">
                        Kapal :
                        <span class="text-info">{{$dropItem->masterShip->name}}</span>
                    </h4>
                </div>
                <div class="d-flex flex-column justify-content-end w-50 text-end">
                    <a href="{{ route('stock-inventory-ship.drop-item.detail', ['uuid' => $uuid, 'dropItemUuid' => $dropItem->uuid]) }}"
                        class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                </div>
            </div>
            @endforeach

            {{-- empty --}}
            @if (count($dropItems) == 0)
            <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                alt="Empty Illustration">
            @endif
            {{-- end empty --}}
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
