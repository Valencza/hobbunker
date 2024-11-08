@extends('client.layouts.app')

@section('title', 'Selesaikan Permintaan ' .ucwords($requestItem->type))

@section('content')
<main id="form-main" class="p-4">
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
        <h2 class="anchor mb-5 mt-5">Selesaikan Permintaan {{ucwords($requestItem->type)}}</h2>
    </div>

    <section class="card shadow-sm rounded-2xl">
        <h3 class="p-4">Bukti Pengiriman {{ucwords($requestItem->type)}}</h3>
        <form class="px-4" action="{{route('stock-inventory-office.request-item.update', [$requestItem->uuid, $requestItem->type])}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mt-3 text-secondary fw-lighter">
                <div class="form-group">
                    <label for="image" class="required form-label">Upload Foto Barang</label>
                    <input type="file" id="image" name="image[]" class="form-control border" multiple />
                    <div id="fileList" class="mt-3"></div>
                </div>

                <div class="form-group">
                    <label for="road_letter" class="required form-label">Upload Surat Jalan</label>
                    <input type="file" id="road_letter" name="road_letter" class="form-control border" />
                </div>

                <div id="list-item" class="mt-3"></div>

                <div id="scan-btn" class="btn btn-light-success px-2 py-2 w-100 my-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                        <path
                            d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z" />
                        <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z" />
                        <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z" />
                        <path
                            d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z" />
                        <path d="M12 9h2V8h-2z" />
                    </svg>
                    <span>Scan Barcode Surat Jalan</span>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Keterangan</label>
                    <textarea name="description" id="description" class="form-control" cols="30" rows="10"></textarea>
                </div>
            </div>

            <div id="submit-btn" class="btn btn-primary mt-3 w-100">
                Kirim Bukti Pengiriman
            </div>
        </form>
    </section>
</main>

<main id="camera-main" style="background-color: #182239; height: 100%;">
    <div id="reader" style="width: 100vw; height: 100%"></div>
</main>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    $(document).ready(function () {
        let index = 0;
        let files = null;
        let fileList = null;
        let requestItem = @json($requestItem);
        $('#camera-main').hide();

        let items = [];

        let scanner;

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

        $('#scan-btn').click(function () {
            $('#reader').show();
            $('#form-main').hide();
            $('#camera-main').show();

            if (scanner) {
                scanner.clear();
                scanner = null;
            }

            scanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: 250
                },
                false
            );

            scanner.render(onScanSuccess, onScanFailure);

            function onScanSuccess(decodedText, decodedResult) {
                Swal.showLoading();
                scanner.clear();
                $('#reader').hide();
                $('#form-main').show();
                $('#camera-main').hide();

                let item = JSON.parse(decodedText);

                if (item.number) {
                    let check = items.find(i => i.code == item.code);

                    if (check) {
                        Swal.fire({
                            title: 'Surat jalan sudah ditambahkan',
                            icon: 'error',
                            showConfirmButton: false
                        });
                        return;
                    }

                    $.ajax({
                        url: "{{ route('api.road-letter.check-code')}}",
                        type: 'GET',
                        data: {
                            uuid: requestItem.uuid,
                            code: item.code
                        },
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: `Surat jalan berhasil dideteksi`,
                                    icon: 'success',
                                    showConfirmButton: false
                                });

                                items.push(item);
                                index += 1;

                                $('#list-item').append(`
                                <a  href="{{route('road-letter.pdf', $requestItem->roadLetter->uuid)}}" class="col-1 text-decoration-none">
                                    <div class="btn btn-success d-flex w-100 align-items-center">
                                        <i class="bi bi-file-earmark fs-1 text-light me-3"></i>
                                        <h3>{{$requestItem->roadLetter->number}}</h3>
                                    </div>
                                </a>
                            `);
                            } else {
                                Swal.fire({
                                    title: response.message,
                                    icon: 'error',
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Surat jalan tidak ditemukan',
                                icon: 'error',
                                showConfirmButton: false
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Surat jalan tidak ditemukan',
                        icon: 'error',
                        showConfirmButton: false
                    });
                }
            }

            function onScanFailure(error) {}
        });

         $('#submit-btn').click(function () {
            if (!fileList) {
                Swal.fire({
                    title: 'Foto barang belum diisi',
                    icon: 'info',
                    showConfirmButton: false
                });

                return;
            }

            if (!items) {
                Swal.fire({
                    title: 'Foto surat jalan belum diisi',
                    icon: 'info',
                    showConfirmButton: false
                });

                return;
            }

            Swal.fire({
                title: 'Konfirmasi Penyelesaian Pengiriman',
                text: "Pastikan data pengiriman telah sesuai",
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
