@extends('client.layouts.app')

@section('title', 'Buat Laporan Kerusakan')

@section('content')
<main class="p-4 overflow-x-hidden">
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

    <h2 class="anchor mb-5 mt-3">Tambah Laporan Kerusakan </h2>

    <section class="card shadow-sm rounded-2xl">
        <h3 class=" border-1 border-bottom p-4">Form Laporan Kerusakan</h3>

        <form action="{{route('stock-inventory-ship.crash-report.store', $uuid)}}" method="POST" class="p-4"
            enctype="multipart/form-data">
            @csrf
            <div class="mb-10">
                <label for="master_user_uuid" class="required form-label">PIC</label>
                <select class="form-select form-control border" id="master_user_uuid" name="master_user_uuid" readonly>
                    <option value="{{Auth::user()->uuid}}" selected>{{Auth::user()->name}}</option>
                </select>
            </div>
            <div class="mb-10">
                <label for="master_ship_uuid" class="required form-label">Kapal</label>
                <select class="form-select form-control border" id="master_ship_uuid" name="master_ship_uuid" readonly>
                    <option value="{{$masterShip->uuid}}" selected>{{$masterShip->name}}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="date" class="required form-label">Tanggal</label>
                <input type="date" id="date" name="date" class="form-control form-control border" value="{{$date}}" />
            </div>
            <div class="mb-10">
                <label for="title" class="required form-label">Judul</label>
                <input type="text" id="title" name="title" class="required form-control form-control border"
                    placeholder="Masukkan judul" />
            </div>
            <div class="mb-3">
                <label for="name_jabatan" class="required form-label">Nama dan Jabatan</label>
                <input id="name_jabatan" class="required form-control form-control border" name="name_position"
                    placeholder="Masukkan nama dan jabatan" />
            </div>

            <h4 class="py-3">Kerusakan</h4>

            <div class="mb-3">
                <label for="position" class="required form-label">Bagian</label>
                <select class="form-select border" id="position" name="position">
                    <option selected disabled>Pilih Bagian</option>
                    <option value="mesin">Mesin</option>
                    <option value="dek">Dek</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="object" class="required form-label">Objek Kerusakan</label>
                <select class="form-select border" id="object">
                    <option selected disabled>Pilih Objek Kerusakan</option>
                    <option value="boshpump">Boshpump</option>
                    <option value="propeller">Propeller</option>
                    <option value="rudder">Rudder</option>
                    <option value="hull">Hull</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="required form-label">Uraian Kerusakan</label>
                <textarea id="description" class="form-control" cols="30" rows="10"
                    placeholder="Masukkan uraian kerusakan"></textarea>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Estimasi Penyebab Kerusakan</label>
                <input type="text" id="reason" class="form-control form-control border"
                    placeholder="Masukkan Estimasi penyebab kerusakan" />
            </div>
            <div class="mb-3" id="list-image">
                
            </div>
            <div id="add-btn" class="btn btn-light-success px-3 py-2 m-0 w-100">
                <i class="bi bi-plus fs-3"></i>
                <span>
                    Tambah Kerusakan
                </span>
            </div>

            <div id="list-item" class="my-3">
            </div>
        </form>


        <div id="submit-btn" class="btn btn-primary mx-4 my-4">Kirim Kerusakan</div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let index = 0;
        let files = null;
        let fileList = null;

        $('#list-image').append(`
            <div id="image-${index}" class="image-item">
                <label for="image" class="required form-label">Upload Foto</label>
                <input type="file" id="image" name="image[${index}][]" class="form-control border" multiple />
                <div id="fileList" class="mt-3"></div>
            </div>
        `);

        $('#image').on('change', function () {
            files = $(this)[0].files;
            fileList = $('#fileList');
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
                $('#image')[0].files = dataTransfer.files;

                $(this).parent().remove();
            });
        });

        window.removeItem = (index) => {
            $(`#item-${index}`).remove();
            index -= 1;
        }

        $('#add-btn').click(function () {
            let position = $('#position').val();
            let object = $('#object').val();
            let reason = $('#reason').val();
            let description = $('#description').val();

            if (position && object && description) {
                $('#list-item').append(`
                    <div id="item-${index}" class="px-4 mx-4 border rounded-2xl mb-2">
                        <h4 class="py-3">${object}</h4>
                        <div class="text-secondary fw-lighter">
                            <h5 class="border-1 border-bottom border-secondary pb-3 mb-3 fw-normal">Bagian : <span class="fw-light">${position}</span></h5>
                            <h5 class="fw-normal">Uraian Kerusakan:</h5>
                            <h5 class="fw-light border-bottom border-secondary pb-3 mb-3">${description}</h5>
                            <h5 class="fw-normal">Penyebab Kerusakan:</h5>
                            <h5 class="fw-light">${reason ?? '-'}</h5>
                            <div onclick="removeItem(${index})" class="btn btn-bg-secondary px-4 py-2 my-3">Hapus</div>
                        </div>
                    </div>

                    <input type="hidden" name="position[${index}]" value="${position}" />
                    <input type="hidden" name="object[${index}]" value="${object}" />
                    <input type="hidden" name="reason[${index}]" value="${reason}" />
                    <input type="hidden" name="description[${index}]" value="${description}" />
                `);


                $('#list-image').append(`
                    <div id="image-${index+1}" class="image-item">
                        <label for="image" class="required form-label">Upload Foto</label>
                        <input type="file" id="image" name="image[${index+1}][]" class="form-control border" multiple />
                        <div id="fileList" class="mt-3"></div>
                    </div>
                `);

                $('#position').prop('selectedIndex', 0);
                $('#object').prop('selectedIndex', 0);
                $('#reason').val('');
                $('#description').val('');

                fileList.empty();

                index += 1;
                
                $(`#image-${index-1}`).css('position', 'absolute');
                $(`#image-${index-1}`).css('opacity', 0);
                $(`#image-${index-1}`).css('right', '-100vw');
            } else {
                Swal.fire({
                    title: 'Kesalahan',
                    text: 'Pastikan semua kolom kerusakan telah diisi.',
                    icon: 'info',
                    showConfirmButton: true
                });
            }
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

            if (index == 0) {
                Swal.fire({
                    title: 'Kerusakan belum diisi',
                    icon: 'info',
                    showConfirmButton: true
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Laporan Kerusakan',
                text: "Pastikan data laporan kerusakan telah sesuai",
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
    });
</script>

@endpush
