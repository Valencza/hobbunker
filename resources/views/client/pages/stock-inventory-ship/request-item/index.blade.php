@extends('client.layouts.app')

@section('title', 'Permintaan ' . ucwords($type))

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
        <h2 class="anchor mb-5 mt-5">Permintaan {{ucwords($type)}}</h2>
        <div>
            <a href="{{route('stock-inventory-ship.request-item.create', [ $uuid, $type])}}"
                class="btn btn-primary d-flex gap-1 align-items-center">
                <i class="bi bi-plus-square"></i>
                <span>Permintaan</span>
            </a>
        </div>
    </div>

    <section class="card shadow-sm p-4 rounded-2xl">
        <h2 class="fs-4 mb-3 ">Daftar Permintaan {{ucwords($type)}}</h2>

        <form action="" method="GET" onchange="this.submit()" class="row mb-3">
            <div class="col-6">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{$startDate}}">
            </div>
            <div class="col-6">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{$endDate}}">
            </div>
        </form>

        <div class="d-flex flex-column gap-3 mb-5">
            <ul class="nav nav-tabs nav-line-tabs mb-2 fs-6">
                <li class="nav-item">
                    <a class="nav-link mx-2 active" data-bs-toggle="tab" href="#kt_tab_pane_1">Baru</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_2">Terkonfirmasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_3">Siap Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_4">Telah Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_5">Ditolak</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_tab_pane_1">
                    @foreach ($requestItemsNew as $requestItemNew)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="centering bg-light-warning rounded p-3">
                            <i class="bi bi-inbox text-warning fs-2"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$requestItemNew->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal:
                                <span>{{$requestItemNew->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date:
                                <span class="text-info ml-3">{{$requestItemNew->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.request-item.detail', [$requestItemNew->uuid, $type])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$requestItemsNew->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($requestItemsNew) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_2">
                    @foreach ($requestItemsConfirmed as $requestItemConfirmed)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="centering bg-light-warning rounded p-3">
                            <i class="bi bi-inbox text-warning fs-2"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$requestItemConfirmed->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal:
                                <span>{{$requestItemConfirmed->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date:
                                <span class="text-info ml-3">{{$requestItemConfirmed->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.request-item.detail', [$requestItemConfirmed->uuid, $type])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$requestItemsConfirmed->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($requestItemsConfirmed) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_3">
                    @foreach ($requestItemsReady as $requestItemReady)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="centering bg-light-warning rounded p-3">
                            <i class="bi bi-inbox text-warning fs-2"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$requestItemReady->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal:
                                <span>{{$requestItemReady->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date:
                                <span class="text-info ml-3">{{$requestItemReady->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.request-item.detail', [$requestItemReady->uuid, $type])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$requestItemsReady->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($requestItemsReady) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_4">
                    @foreach ($requestItemsDelivered as $requestItemDelivered)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="centering bg-light-warning rounded p-3">
                            <i class="bi bi-inbox text-warning fs-2"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$requestItemDelivered->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal:
                                <span>{{$requestItemDelivered->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date:
                                <span class="text-info ml-3">{{$requestItemDelivered->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.request-item.detail', [$requestItemDelivered->uuid, $type])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$requestItemsDelivered->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($requestItemsDelivered) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_5">
                    @foreach ($requestItemsRejected as $requestItemRejected)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="centering bg-light-warning rounded p-3">
                            <i class="bi bi-inbox text-warning fs-2"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$requestItemRejected->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal:
                                <span>{{$requestItemRejected->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date:
                                <span class="text-info ml-3">{{$requestItemRejected->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.request-item.detail', [$requestItemRejected->uuid, $type])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$requestItemsRejected->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($requestItemsRejected) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
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
