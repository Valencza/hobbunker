@extends('client.layouts.app')

@section('title', 'Pemakian barang')

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
        <h2 class="anchor mb-5 mt-5">Pemakaian Barang</h2>
        <div>
            <a href="{{route('stock-inventory-ship.used-item.create', $uuid)}}" class="btn btn-primary d-flex gap-1 align-items-center">
                <i class="bi bi-plus-square"></i>
                <span>Pemakaian</span>
            </a>
        </div>
    </div>

    <section class="card shadow-sm rounded-2xl">
        <div>
            <h2 class="fs-4 mb-3 p-4 border-1 border-bottom border-light-secondary">Daftar Pemakaian Barang</h2>
        </div>

        <div class="d-flex flex-column gap-3 mb-5 px-4">
            @foreach ($usedItems as $usedItem)
            <div
                class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                <div class="card btn btn-light-primary p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                    <i class="bi bi-inbox text-success fs-4 my-2 ml-3"></i>
                </div>
                <div class="d-flex flex-column w-100 text-capitalize">
                    <span class="fw-medium fs-5 p-0">{{$usedItem->title}}</span>
                    <h6 class="text-muted">Tanggal : <span class="text-primary">{{$usedItem->formatted_date}}</span>
                    </h6>
                    <h4 class="text-muted p-0 text-secondary fs-6">
                        {{$usedItem->description}}
                    </h4>
                </div>
                <div class="d-flex flex-column justify-content-end w-50 text-end">
                    <a href="{{route('stock-inventory-ship.used-item.detail', $usedItem->uuid)}}"
                        class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                </div>
            </div>
            @endforeach
            {{$usedItems->links('vendor.pagination.bootstrap-5')}}

            {{-- empty --}}
            @if (count($usedItems) == 0)
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
