@extends('client.layouts.app')

@section('title', 'Laporan Kerusakan')

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
        <h2 class="anchor mb-5 mt-5">Laporan Kerusakan</h2>
        <div>
            <a href="{{route('stock-inventory-ship.crash-report.create', $uuid)}}" class="btn btn-primary d-flex gap-1 align-items-center">
                <i class="bi bi-plus-square"></i>
                <span>Kerusakan</span>
            </a>
        </div>
    </div>

    <section class="card shadow-sm p-4 rounded-2xl mt-4">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">List Perbaikan</h2>
        </div>

        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_0">Baru</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_1">Proses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Selesai</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="kt_tab_pane_0">
                <div class="d-flex flex-column gap-3 mb-5">
                    @foreach ($crashReportsNew as $crashReportNew)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div
                            class="card btn btn-light-danger px-2 py-3 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-shield-x fs-4 text-danger"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$crashReportNew->title}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Tanggal :
                                <span class="text-info ml-3">{{$crashReportNew->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.crash-report.detail', ['uuid' => $uuid, 'crashReportUuid' => $crashReportNew->uuid])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{-- empty --}}
                    @if (count($crashReportsNew) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
            </div>
            <div class="tab-pane fade show" id="kt_tab_pane_1">
                <div class="d-flex flex-column gap-3 mb-5">
                    @foreach ($crashReportsProcess as $crashReportProcess)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div
                            class="card btn btn-light-danger px-2 py-3 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-shield-x fs-4 text-danger"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$crashReportProcess->title}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Tanggal :
                                <span class="text-info ml-3">{{$crashReportProcess->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.crash-report.detail', ['uuid' => $uuid, 'crashReportUuid' => $crashReportProcess->uuid])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{-- empty --}}
                    @if (count($crashReportsProcess) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
            </div>
            <div class="tab-pane fade" id="kt_tab_pane_2">
                <div class="d-flex flex-column gap-3 mb-5">
                    @foreach ($crashReportsDone as $crashReportDone)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div
                            class="card btn btn-light-danger px-2 py-3 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-shield-x fs-4 text-danger"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$crashReportDone->title}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Tanggal :
                                <span class="text-info ml-3">{{$crashReportDone->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('stock-inventory-ship.crash-report.detail', ['uuid' => $uuid, 'crashReportUuid' => $crashReportDone->uuid])}}"
                                class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{-- empty --}}
                    @if (count($crashReportsDone) == 0)
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
