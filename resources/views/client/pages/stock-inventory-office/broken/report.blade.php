@extends('client.layouts.app')

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

    <h2 class="anchor mb-5 mt-3">Laporan Kerusakan</h2>

    <section class="card shadow-sm p-4 rounded-2xl mt-4">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">Daftar Kerusakan</h2>
        </div>

        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Link 1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Link 2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Link 3</a>
            </li>
        </ul>


        <div class="tab-content">
            <div class="tab-pane fade show active" id="kt_tab_pane_1">
                <div class="d-flex flex-column gap-3 mb-5">
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div
                            class="card btn btn-light-danger px-2 py-3 border-0 rounded-2xl w-25 d-flex align-items-center">
                           <i class="bi bi-shield-x fs-4 text-danger"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">Barang Keluar</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Barang keluar untuk ke..
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Input Stok :
                                <span class="text-info ml-3">12 Feb 2023</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="#" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div
                            class="card btn btn-light-danger px-2 py-3 border-0 rounded-2xl w-25 d-flex align-items-center">
                           <i class="bi bi-shield-x fs-4 text-danger"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">Barang Keluar</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Barang keluar untuk ke..
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Input Stok :
                                <span class="text-info ml-3">12 Feb 2023</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="#" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="kt_tab_pane_2">
                tab2
            </div>
            <div class="tab-pane fade" id="kt_tab_pane_3">
                tab3
            </div>
        </div>

    </section>
</main>

<script>
    $(document).ready(function() {
        $('.nav-link').click(function() {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
        });
    });
</script>
@endpush
