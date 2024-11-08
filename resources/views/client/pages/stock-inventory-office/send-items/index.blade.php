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

    <h2 class="anchor mb-5 mt-3">Kirim Barang</h2>

    <section class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">List Permintaan Barang</h2>
        </div>

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
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_5">Selesai</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="fade show active d-flex flex-column gap-3" id="kt_tab_pane_1">
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="card btn btn-light-warning p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-inbox text-warning fs-4 my-2 ml-3"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">Barang Masuk</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal :
                                <span>SPOB Petro Ocean III</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date :
                                <span class="text-info ml-3">12 Feb 2023</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="#" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="card btn btn-light-warning p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-inbox text-warning fs-4 my-2 ml-3"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">Barang Masuk</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal :
                                <span>SPOB Petro Ocean III</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date :
                                <span class="text-info ml-3">12 Feb 2023</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="#" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="card btn btn-light-warning p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-inbox text-warning fs-4 my-2 ml-3"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">Barang Masuk</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal :
                                <span>SPOB Petro Ocean III</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date :
                                <span class="text-info ml-3">12 Feb 2023</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="#" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_2">
                    <p>Content for Terkonfirmasi tab</p>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_3">
                    <p>Content for Siap Dikirim tab</p>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_4">
                    <p>Content for Telah Dikirim tab</p>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_5">
                    <p>Content for Selesai tab</p>
                </div>
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
