@extends('client.layouts.app')

@section('title', 'Detail Laporan Kerusakan')

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

    <h2 class="anchor mb-5 mt-3">Detail Laporan</h2>

    <section class="card shadow-sm rounded-2xl mb-3">
        <h3 class=" border-1 border-bottom p-4">Detail Laporan</h3>

        <div class="px-4 py-3">
            <h4 class="mb-1">PIC</h4>
            <h5 class="pb-4 text-secondary mt-2 text-capitalize">
                {{$crashReport->masterUser->name}}
            </h5>

            <h4 class="mb-1">Tanggal</h4>
            <h5 class="pb-4 text-secondary mt-2 text-capitalize">
                {{$crashReport->formatted_created_at}}
            </h5>

            <h4 class="mb-1 text-capitalize">Kapal</h4>
            <h5 class="pb-4 text-secondary mt-2">
                {{$crashReport->masterShip->name}}
            </h5>

            <h4 class="mb-1 text-capitalize">Nama dan Jabatan</h4>
            <h5 class="pb-4 text-secondary mt-2">
                {{$crashReport->name_position}}
            </h5>

        </div>
    </section>

    <section class="card shadow-sm rounded-2xl mb-3 p-3">
        <h3 class=" border-1 border-bottom p-4">Kerusakan</h3>
        @foreach ($crashReport->crashReportDetails as $crashReportDetail)
        <div class="card rounded-2xl m-4">
            <div class="px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-1">Objek Kerusakan</h4>
                    @if ($crashReport->status != 'baru')
                    @if ($crashReportDetail->status == 'proses' && $crashReport->status != 'baru')
                    <span class="badge badge-warning">
                        @else
                        <span class="badge badge-success">
                            @endif
                            {{ucwords($crashReportDetail->status)}}</span>
                        @endif
                </div>
                <h5 class="pb-4 text-secondary mt-2 text-capitalize">
                    {{$crashReportDetail->object}}
                </h5>

                <h4 class="mb-1 text-capitalize">Bagian Kapal</h4>

                <h5 class="pb-4 text-secondary mt-2">
                    {{$crashReportDetail->position}}
                </h5>

                <h4 class="mb-1">Uraian Kerusakan</h4>

                <h5 class="pb-4 text-secondary mt-2x">
                    {{$crashReportDetail->description}}
                </h5>

                @if ($crashReportDetail->reason)
                <h4 class="mb-1 text-capitalize">Penyebab Kerusakan</h4>

                <h5 class="pb-4 text-secondary mt-2">
                    {{$crashReportDetail->reason}}
                </h5>
                @endif

                <h4 class="mb-1 text-capitalize">Lampiran Kerusakan</h4>
                <div class="mb-3 row">
                    @foreach ($crashReportDetail->crashReportDetailPhotos as $crashReportDetailPhoto)
                    <a href="{{asset($crashReportDetailPhoto->image)}}" style="width: 150px; height: 150px;" class="col-6 m-1">
                        <img class="file-preview" style="width: 150px; height: 150px;object-fit: cover"
                            src="{{asset($crashReportDetailPhoto->image)}}">
                    </a>
                    @endforeach
                </div>

                @if ($crashReportDetail->status == 'selesai')
                <h4 class="mb-1 text-capitalize">Upaya Perbaikan</h4>
                <p class="mb-3">{{$crashReportDetail->fixReport->description}}</p>
                <div class="mb-3 row">
                    @foreach ($crashReportDetail->fixReport->fixReportPhotos as $fixReportPhoto)
                    <a href="{{asset($fixReportPhoto->image)}}" class="col-6 mb-2">
                        <img class="file-preview" src="{{asset($fixReportPhoto->image)}}">
                    </a>
                    @endforeach
                </div>
                @endif

                @if ($crashReportDetail->status == 'proses' && $crashReport->status != 'baru')
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                    data-bs-target="#fix-modal-{{$crashReportDetail->uuid}}">
                    Laporan Perbaikan
                </button>

                <div class="modal fade" tabindex="-1" id="fix-modal-{{$crashReportDetail->uuid}}">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form
                                action="{{route('stock-inventory-ship.crash-report.update', $crashReportDetail->uuid)}}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Perbaikan</h5>
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                        data-bs-dismiss="modal" aria-label="Close">
                                        <i class="bi bi-x fs-1"></i>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <div class="px-4 py-3">
                                        <h4 class="mb-1">Objek Kerusakan</h4>
                                        <h5 class="pb-4 text-secondary mt-2 text-capitalize">
                                            {{$crashReportDetail->object}}
                                        </h5>

                                        <div class="mb-3">
                                            <label for="photo-{{$crashReportDetail->uuid}}"
                                                class="required form-label">Upload Foto</label>
                                            <input type="file" id="photo-{{$crashReportDetail->uuid}}" name="photo[]"
                                                class="form-control border" multiple
                                                onchange="handleFileUpload('{{$crashReportDetail->uuid}}')" />
                                            <div class="my-3" id="file-list-{{$crashReportDetail->uuid}}"></div>
                                            <div class="mb-3 mt-10">
                                                <label for="description-{{$crashReportDetail->uuid}}"
                                                    class="form-label">Keterangan</label>
                                                <textarea id="description-{{$crashReportDetail->uuid}}"
                                                    name="description" class="form-control mb-8 border" rows="5"
                                                    placeholder="Masukkan keterangan perbaikan"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer w-100">
                                    <button type="submit" class="btn btn-primary w-100">Konfirmasi Selesai</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <form id="form-done" action="{{route('stock-inventory-ship.crash-report.done', $crashReport->uuid)}}"
            method="POST">
            @csrf
            @method('PUT')
        </form>

        @if ($crashReport->status == 'proses')
        <button id="submit-btn" class="btn bg-success text-light w-100">
            Perbaikan Selesai
        </button>
        @endif
    </section>
</main>
@endsection

@push('scripts')
<script>
    function handleFileUpload(uuid) {
        let index = 0;
        let files = null;
        let fileList = null;

        files = $(`#photo-${uuid}`)[0].files;
        fileList = $(`#file-list-${uuid}`);
        fileList.empty();
        let fileTooLarge = false;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.size > 3145728) {
                fileTooLarge = true;
                Swal.fire({
                    title: 'File Terlalu Besar',
                    text: `File ${file.name} lebih dari 3MB. Silakan pilih file yang lebih kecil.`,
                    icon: 'warning',
                    showConfirmButton: true
                });
                continue;
            }

            const fileDiv = $(`
                    <div class="d-flex justify-content-between px-3 align-items-center form-control border mb-2">
                        <i class="bi bi-image text-secondary"></i>
                        <span class="text-secondary w-75">${file.name}</span>
                        <i class="bi bi-x-lg text-secondary remove-file" data-index="${i}"></i>
                    </div>
                `);
            fileList.append(fileDiv);
        }

        if (fileTooLarge) {
            $(this).val('');
            fileList.empty();
        }

        $('.remove-file').on('click', function () {
            const indexFile = $(this).data('index');
            const newFileList = Array.from(files).filter((file, i) => i !== indexFile);
            const dataTransfer = new DataTransfer();
            newFileList.forEach(file => dataTransfer.items.add(file));
            $(`#photo-${uuid}`)[0].files = dataTransfer.files;

            $(this).parent().remove();
        });
    }

    $('#submit-btn').click(function () {
        Swal.fire({
            title: 'Konfirmasi Selesai Perbaikan',
            text: "Pastikan data perbaikan telah sesuai",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-done').submit();
            }
        });
    });

</script>
@endpush
