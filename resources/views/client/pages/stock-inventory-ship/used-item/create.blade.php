@extends('client.layouts.app')

@section('title', 'Tambah Pemakaian Barang')

@push('styles')
<style>
    #reader {
        width: 500px;
        height: 500px;
    }

</style>
@endpush

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
        <h2 class="anchor mb-5 mt-5">Tambah Pemakaian</h2>
    </div>

    <section class="card shadow-sm rounded-2xl mb-4">
        <form action="{{route('stock-inventory-ship.used-item.store', $uuid)}}" method="POST" class="px-4 mt-3">
            @csrf

            <h3 class=" border-1 border-bottom p-4">Form Pemakaian Barang</h3>

            <div class="mb-10">
                <label for="title" class="required form-label">Judul</label>
                <input type="text" id="title" name="title" class="form-control border" placeholder="Masukkan judul" />
            </div>
            <div class="mb-10">
                <label for="date" class="required form-label">Tanggal Pemakaian</label>
                <input type="date" id="date" name="date" class="form-control border" />
            </div>
            <div>
                <label for="description" class="required form-label">Keterangan</label>
                <textarea name="description" id="description" cols="30" rows="10" class="form-control border"  placeholder="Masukkan keterangan" ></textarea>
            </div>

            <div class="d-flex">
                <div id="scan-btn" class="btn btn-light-success px-2 py-2 w-100 my-3 me-3">
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
                    <span>Scan Barcode</span>
                </div>

                <div class="btn btn-light-success px-2 py-2 w-100 my-3" data-bs-toggle="modal"
                    data-bs-target="#scanItemModal">
                    <i class="fa-solid fa-code"></i>
                    <span>Input Code</span>
                </div>

                <div class="modal fade" id="scanItemModal" tabindex="-1" aria-labelledby="scanItemModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="scanItemModalLabel">Input Kode</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="scan_code" class="form-label">Kode</label>
                                    <input autocomplete="off" type="text" name="scan_code" id="scan_code"
                                        class="form-control" placeholder="Masukkan kode barang"
                                        value="{{old('scan_code')}}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" id="add-btn" class="btn btn-primary"
                                    data-bs-dismiss="modal">Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- card --}}
            <div id="list-item" class="d-flex flex-column gap-3">
            </div>

            <div class="my-3">
                <div id="submit-btn" class="btn btn-primary w-100">Pakai Stok</div>
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
    $('#camera-main').hide();

    let items = [];

    let index = 0;

    let scanner;

    function checkItemCode(item) {
        if (item.code) {
                let check = items.find(i => i.code == item.code);

                if (check) {
                    Swal.fire({
                        title: 'Barang sudah ditambahkan',
                        icon: 'error',
                        showConfirmButton: false
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('api.stock-inventory-ship.check-item-code')}}",
                    type: 'GET',
                    data: {
                        code: item.code
                    },
                    success: function (response) {
                        if (response.status) {
                            item = {
                            name: response.data.name,
                            code: response.data.code,
                        }
                        
                            Swal.fire({
                                title: `${item.name}(${item.code})`,
                                icon: 'success',
                                showConfirmButton: false
                            });

                            items.push(item);
                            index += 1;

                            $('#list-item').append(`
                                <div class="item card rounded-2xl  px-4 py-3">
                                    <h5>${item.name}</h5>
                                    <h6 class="text-secondary">Code: <span class="text-info">${item.code}</span></h6>
                                </div>

                                <input type="hidden" name="code[${index}]" value="${item.code}" />
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
                            title: 'Barang tidak ditemukan',
                            icon: 'error',
                            showConfirmButton: false
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Barang tidak ditemukan',
                    icon: 'error',
                    showConfirmButton: false
                });
            }
    }

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

            checkItemCode(item);
        }

        function onScanFailure(error) {}
    });

    $('#add-btn').click(function () {
        let item = {
            code: $('#scan_code').val(),
        }

        checkItemCode(item);

        $('#scan_code').val('');
    });

    $('#submit-btn').click(function () {
        let title = $('#title').val();
        let date = $('#date').val();
        let description = $('#description').val();
        let itemsLength = $('#list-item').children().length;

        if (!title) {
            Swal.fire({
                title: 'Judul belum diisi',
                icon: 'info',
                showConfirmButton: false
            });

            return;
        }

        if (!date) {
            Swal.fire({
                title: 'Tanggal belum diisi',
                icon: 'info',
                showConfirmButton: false
            });

            return;
        }

        if (!description) {
            Swal.fire({
                title: 'Description belum diisi',
                icon: 'info',
                showConfirmButton: false
            });

            return;
        }

        if (!itemsLength) {
            Swal.fire({
                title: 'Barang belum dipilih',
                icon: 'info',
                showConfirmButton: false
            });

            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pemakaian Barang',
            text: "Pastikan data pemakaian barang telah sesuai",
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
