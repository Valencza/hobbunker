@extends('client.layouts.app')

@section('title', 'Tambah Docking')

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

    <h2 class="anchor mb-5 mt-3">Tambah Docking</h2>

    <section class="card shadow-sm rounded-2xl">
        <h3 class=" border-1 border-bottom p-4">Form Docking</h3>

        <form class="p-4" action="{{route('stock-inventory-ship.docking.store', $uuid)}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title" class="required form-label">Judul</label>
                <input type="text" name="title" id="title" class="form-control border" placeholder="Masukkan judul" />
            </div>

            <div class="mb-3">
                <label for="type" class="required form-label">Tipe Docking</label>
                <select class="form-select border" name="type" id="type">
                    <option selected disabled>Pilih Tipe</option>
                    <option value="docking besar">Docking Besar</option>
                    <option value="docking kecil">Docking Kecil</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="required form-label">Keterangan</label>
                <textarea name="description" id="description" class="form-control" cols="30" rows="10"
                    placeholder="Masukkan keterangan"></textarea>
            </div>

            <div class="mb-3">
                <label for="cost" class="required form-label">Biaya</label>
                <input type="text" name="cost" id="cost" class="form-control border" placeholder="Masukkan Biaya" />
            </div>

            <div class="mb-3">
                <label for="administrasi" class="required form-label">File Administrasi</label>
                <input type="file" name="administrasi[]" id="administrasi" multiple class="form-control border" />
                <div id="file-list-administrasi" class="mt-3">
                </div>
            </div>

            <div class="mb-3">
                <label for="laporan" class="required form-label">Dokumen Laporan Docking</label>
                <input type="file" name="laporan[]" id="laporan" multiple class="form-control border" />
                <div id="file-list-laporan" class="mt-3">
                </div>
            </div>

            <div class="mb-3">
                <label for="sertifikat" class="required form-label">Sertifikat Docking</label>
                <input type="file" name="sertifikat[]" id="sertifikat" multiple class="form-control border" />
                <div id="file-list-sertifikat" class="mt-3">
                </div>
            </div>

            <div class="mb-3">
                <label for="penawaran_1" class="required form-label">Penawaran I</label>
                <input type="file" name="penawaran_1[]" id="penawaran_1" multiple class="form-control border" />
                <div id="file-list-penawaran_1" class="mt-3">
                </div>
            </div>

            <div class="mb-3">
                <label for="penawaran_2" class="required form-label">Penawaran II</label>
                <input type="file" name="penawaran_2[]" id="penawaran_2" multiple class="form-control border" />
                <div id="file-list-penawaran_2" class="mt-3">
                </div>
            </div>

            <div class="mb-3">
                <label for="penawaran_3" class="required form-label">Penawaran III</label>
                <input type="file" name="penawaran_3[]" id="penawaran_3" multiple class="form-control border" />
                <div id="file-list-penawaran_3" class="mt-3">
                </div>
            </div>

            <div class="mb-3">
                <label for="perbaikan" class="required form-label">Repair List</label>
                <input type="file" name="perbaikan[]" id="perbaikan" multiple class="form-control border" />
                <div id="file-list-perbaikan" class="mt-3">
                </div>
            </div>

        </form>
        <button id="submit-btn" class="btn btn-primary mx-4 my-4 w-100">Simpan Riwayat</button>
    </section>
</main>
@endsection


@push('scripts')
<script>
    function handleFileChange(fileEl, fileListEl) {
        let files = $(fileEl)[0].files;
        let fileList = $(fileListEl);
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
            $(fileEl)[0].files = dataTransfer.files;

            $(this).parent().remove();
        });

    }

    $('#administrasi').on('change', function () {
        handleFileChange('#administrasi', '#file-list-administrasi');
    });

    $('#laporan').on('change', function () {
        handleFileChange('#laporan', '#file-list-laporan');
    });

    $('#sertifikat').on('change', function () {
        handleFileChange('#sertifikat', '#file-list-sertifikat');
    });

    $('#penawaran_1').on('change', function () {
        handleFileChange('#penawaran_1', '#file-list-penawaran_1');
    });

    $('#penawaran_2').on('change', function () {
        handleFileChange('#penawaran_2', '#file-list-penawaran_2');
    });

    $('#penawaran_3').on('change', function () {
        handleFileChange('#penawaran_3', '#file-list-penawaran_3');
    });

    $('#perbaikan').on('change', function () {
        handleFileChange('#perbaikan', '#file-list-perbaikan');
    });

    $('#submit-btn').click(function () {
            if (!$('#title').val()) {
                Swal.fire({
                    title: 'Judul belum diisi',
                    icon: 'info',
                    showConfirmButton: true
                });
                return;
            }

            if (!$('#type').val()) {
                Swal.fire({
                    title: 'Tipe belum diisi',
                    icon: 'info',
                    showConfirmButton: true
                });
                return;
            }

            if (!$('#description').val()) {
                Swal.fire({
                    title: 'Deskripsi belum diisi',
                    icon: 'info',
                    showConfirmButton: true
                });
                return;
            }

            if (!$('#cost').val()) {
                Swal.fire({
                    title: 'Biaya belum diisi',
                    icon: 'info',
                    showConfirmButton: true
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Riwayat Docking',
                text: "Pastikan data riwayat docking telah sesuai",
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
