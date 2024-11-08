@extends('client.layouts.app')

@section('title', 'Scan Barang')

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
        <h2 class="anchor mb-5 mt-5">Scan Barang</h2>
    </div>

    <section class="card shadow-sm rounded-2xl mb-4">
        <form action="{{route('stock-inventory-office.request-item.send', $uuid)}}" method="POST" class="px-4 mt-3">
            @csrf
            <input type="hidden" name="uuid" value="{{$requestItem->uuid}}">

            <h3 class=" border-1 border-bottom p-4">Info Permintaan Barang</h3>

            <div class="px-4 py-3">
                <h4 class="mb-1">Nama PIC</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->masterUser->name}}</h5>

                <h4 class="mb-1">Tanggal Permintaan</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->formatted_created_at}}</h5>

                <h4 class="mb-1">Kapal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->masterShip->name}}</h5>

                <table class="table table-row-dashed table-row-gray-300 gy-7">
                    <thead>
                        <tr class="fw-bolder fs-6 text-gray-800">
                            <th>Item</th>
                            <th class="w-25">Qty</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requestItem->requestItemDetails as $requestItemDetail)
                        <tr>
                            @if (strpos($requestItemDetail->master_item_uuid, '~'))
                            <td class="text-secondary">
                                <span
                                    class=" fs-5 fw-medium text-secondary">{{explode('~', $requestItemDetail->master_item_uuid)[1]}}</span>
                                <p class="fs-5 m-0">Bagian: {{$requestItemDetail->position}}</p>
                                @if ($requestItemDetail->reject)
                                <p class="fs-5 m-0 text-danger fw-bold mt-3">Ditolak:</p>
                                <p>{{$requestItemDetail->reject_description}}</p>
                                @endif
                                @if ($requestItemDetail->image)
                                <p class="fs-5 m-0">Foto:</p>
                                <img src="" width="200">
                                @endif
                                @if ($requestItemDetail->description)
                                <p class="fs-5 m-0">Keterangan:</p>
                                <p>{{$requestItemDetail->description}}</p>
                                @endif
                            </td>
                            <td class="text-secondary">{{$requestItemDetail->qty}}</td>
                            <td class="text-secondary">
                                {{ucwords(explode('~', $requestItemDetail->master_item_uuid)[2])}}</td>
                            @else
                            <td class="text-secondary">
                                <span
                                    class=" fs-5 fw-medium text-secondary">{{$requestItemDetail->masterItem->name}}</span>
                                <p class="fs-5 m-0">Bagian: {{$requestItemDetail->position}}</p>
                                @if ($requestItemDetail->image)
                                <p class="fs-5 m-0">Foto:</p>
                                <img src="{{asset($requestItemDetail->image)}}" width="200">
                                @endif
                                @if ($requestItemDetail->description)
                                <p class="fs-5 m-0">Keterangan:</p>
                                <p>{{$requestItemDetail->description}}</p>
                                @endif
                            </td>
                            <td class="text-secondary">{{$requestItemDetail->qty}}</td>
                            <td class="text-secondary">{{ucwords($requestItemDetail->masterItem->masterUnit->name)}}
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <div id="submit-btn" class="btn btn-primary w-100">Siap Dikirim</div>
            </div>
        </form>
    </section>
</main>

<main id="camera-main" style="background-color: #182239; height: 100%;">
    <div id="reader" style="width: 100vw; height: 100%"></div>
</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuidv4.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    $('#camera-main').hide();

    let items = [];

    let index = 0;

    let scanner;

    function checkItemCode(item) {
        if (item) {
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
                url: "{{ route('api.stock-inventory-office.check-item-code')}}",
                type: 'GET',
                data: {
                    uuid: @json($requestItem).uuid,
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


        var number = `L-${uuidv4().substr(0, 5).toUpperCase()}`;

        var jsonData = {
            "number": number,
        };

        var jsonString = JSON.stringify(jsonData);

        var qr = qrcode(10, 'H');
        qr.addData(jsonString);
        qr.make();

        var base64Image = qr.createDataURL(10);

        $('form').append(`
            <input type="hidden" value="${number}" name="number" />
            <input type="hidden" value="${base64Image}" name="barcode" />
        `);

        Swal.fire({
            title: 'Konfirmasi Pengiriman Barang',
            text: "Pastikan data pengiriman barang telah sesuai",
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
