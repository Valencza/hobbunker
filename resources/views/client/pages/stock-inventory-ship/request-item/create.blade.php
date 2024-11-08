@extends('client.layouts.app')

@section('title', 'Buat Permintaan '. ucwords($type))

@push('styles')
<link href="
https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.44/dist/virtual-select.min.css
" rel="stylesheet">
<style>
     *,
    *:after,
    *:before {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        -ms-box-sizing: border-box;
        box-sizing: border-box;
    }

    .vscomp-toggle-button {
        background: none;
        border: none;
        margin: 0;
        padding: 0;
    }

    .vscomp-wrapper.focused .vscomp-toggle-button,
    .vscomp-wrapper:focus .vscomp-toggle-button {
        box-shadow: none;
    }

    .vscomp-arrow {
        display: none;
    }

    .form-select {
        min-width: 100%;
    }
    .form-select:hover,
    .form-select:focus {
        border: 1px solid #696cff;
    }
</style>
@endpush

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
        <li class="breadcrumb-item">
            <a href="{{route('home')}}" class="text-secondary text-decoration-none">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('stock-inventory-ship.detail', Auth::user()->master_ship_uuid)}}"
                class="text-secondary text-decoration-none">Stok & Inventaris
                Kapal</a>
        </li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Tambah Permintaan Pengiriman</h2>

    <section>
        <div class="card shadow-sm rounded-2xl">
            <h3 class=" border-1 border-bottom p-4">Form Permintaan Pengiriman {{ucwords($type)}}</h3>

            <div class="px-4 py-3 text-capitalize">
                <form action="{{route('stock-inventory-ship.request-item.store', [$masterShip->uuid, $type])}}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="date" class="form-label">Tanggal Permintaan</label>
                        @error('date')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span>{{$message}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <input type="date" name="date" id="date" class="form-control w-100" value="{{$date}}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="master_ship_uuid" class="form-label">Kapal</label>
                        <select name="master_ship_uuid" id="master_ship_uuid" class="master_ship_uuid form-select"
                            readonly>
                            <option value="{{$masterShip->uuid}}" selected>{{$masterShip->name}}</option>
                        </select>
                    </div>

                    <h3 class=" border-1 pb-10">Detail {{ucwords($type)}}</h3>

                    <div class="mb-10">
                        <label for="position" class="required form-label">Bagian</label>
                        <select id="position" class="form-select border">
                            <option value="" disabled selected>Pilih Bagian</option>
                            <option value="dek">Dek</option>
                            <option value="mesin">Mesin</option>
                        </select>
                    </div>

                    <div>
                        <label for="master_item" class="required form-label">Nama {{ucwords($type)}}</label>
                        <select id="master_item" class="form-select w-100">
                            <option value="" selected>Pilih {{ucwords($type)}}</option>
                            @foreach ($masterItems as $masterItem)
                            <option value="{{$masterItem}}">{{$masterItem->name}}</option>
                            @endforeach
                        </select>
                        <input type="text" id="check_master_item" class="form-control d-none"
                            placeholder="Masukkan nama {{$type}} lainnya">

                    </div>

                    <div class="mb-3 mt-3 d-none" id="merk-section">
                        <label for="merk" class="required form-label">Merk</label>
                        <input id="merk" type="text" class="form-control border" placeholder="Masukkan merk" />
                    </div>

                    <div class="mb-3 mt-3 d-none" id="unit-section">
                        <label for="unit" class="required form-label">Unit</label>
                        <input id="unit" type="text" class="form-control border" placeholder="Masukkan unit" />
                    </div>

                    <div class="align-items-center mt-3 mb-10">
                        <input type="checkbox" name="other_item" id="other_item" class="form-checkbox">
                        <label for="other_item">{{ucwords($type)}} Lainnya</label>
                    </div>

                    <div class="mb-10">
                        <label for="qty" class="required form-label">Jumlah</label>
                        <input id="qty" type="number" class="form-control border" placeholder="Masukkan jumlah" />
                    </div>

                    <div id="image-section">
                        <div class="mb-10" id="image-box-0">
                            <label for="image-0" class="form-label">Foto</label>
                            <input name="image[0]" id="image-0" type="file" class="form-control border" />
                        </div>
                    </div>

                    <div class="mb-10">
                        <label for="description" class="form-label">Keterangan</label>
                        <textarea name="description" id="description" cols="30" rows="10" class="form-control"
                            plac w-100eholder="Masukkan keterangan"></textarea>
                    </div>

                    <div id="add-btn" class="btn btn-light-success px-2 py-2 w-100 mb-10">
                        <i class="bi bi-plus text-primary ml-1 fs-4"></i>
                        <span>Tambah {{ucwords($type)}}</span>
                    </div>

                    <div id="list-item" class="px-2 pb-10 d-flex gap-3 flex-column">
                    </div>
                </form>

            </div>

            <div class="d-flex gap-3 w-100 px-4 mb-4">
                <a href="{{route('stock-inventory-ship.request-item', [$masterShip->uuid, $type])}}"
                    class="btn btn-secondary rounded-3 w-100">Batal</a>
                <div id="submit-btn" class="btn btn-primary rounded-3 text-white w-100">Kirim</div>
            </div>
        </div>
    </section>
</main>
@endsection


@push('scripts')
<script src="
https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.44/dist/virtual-select.min.js
"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuidv4.min.js"></script>
<script>
    let index = 0;
    let items = [];

    VirtualSelect.init({
        ele: '#master_item',
        search: true
    });

    function deleteItem(indexDelete) {
        $('#list-item').children().eq(indexDelete).remove();
        items.splice(indexDelete, 1);
    }

    $(document).ready(function () {
        $('#master_ship_uuid').change(function () {
            $('#list-item').children().remove();
        });

        $('#other_item').click(function () {
            if ($('#other_item').is(':checked')) {
                $('#master_item')[0].selectedIndex = 0;
                $('#master_item').addClass('d-none');
                $('#check_master_item').removeClass('d-none');
                $('#unit-section').removeClass('d-none');
                $('#merk-section').removeClass('d-none');
            } else {
                $('#check_master_item').addClass('d-none');
                $('#master_item').removeClass('d-none');
                $('#unit-section').addClass('d-none');
                $('#merk-section').addClass('d-none');
            }
        });

        $('#add-btn').click(function () {
            let position = $('#position').val();
            let description = $('#description').val();
            let item = JSON.parse($('#master_item').val()) ?? $('#check_master_item').val();
            let qty = $('#qty').val();
            let fileInput = $(`#image-${index}`).get(0);
            let file = fileInput.files.length > 0 ? fileInput.files[0] : null;

            if (item && qty > 0 && position) {
                if ($('#other_item').is(':checked')) {
                    item = {
                        uuid: $('#check_master_item').val(),
                        name: $('#check_master_item').val(),
                        merk: $('#merk').val(),
                        master_unit: {
                            name: $('#unit').val()
                        }
                    };
                }
                let reader = new FileReader();

                reader.onload = function (event) {
                    let imagePreview = file ?
                        `
                        <p class="text-secondary mb-1">Foto:</p>
                        <img id="preview-${index}" src="${event.target.result}" class="img-fluid mb-3" width="200"/>
                        ` :
                        '';
                    let descriptionSection = description ?
                        `  <p class="text-secondary mb-1">Keterangan:</p>
                            <p class="text-secondary">${description}</p>` :
                        '';
                    let inputUnit = $('#other_item').is(':checked') ?
                        ` <input type="hidden" value="${item.master_unit.name}" name="unit[${index}]" />` :
                        '';
                    let inputMerk = $('#other_item').is(':checked') ?
                        ` <input type="hidden" value="${item.merk ?? 'Non Merk'}" name="merk[${index}]" />` :
                        '';

                    $('#list-item').append(`
                    <div id="item-${item.uuid}" class="item px-4 py-3 border rounded-2xl">
                        <h4>${item.name}</h4>
                        <p class="text-secondary">Bagian: ${position}</p>
                        ${imagePreview}
                        ${descriptionSection}
                        <h5>${qty} ${item.master_unit.name}</h5>
                        <div onclick="deleteItem(${index})" class="btn btn-secondary py-2 rounded-3 mb-2">Hapus</div>

                        <input type="hidden" value="${item.uuid}" name="master_item[${index}]" />
                        <input type="hidden" value="${qty}" name="qty[${index}]" />
                        <input type="hidden" value="${position}" name="position[${index}]" />
                        <input type="hidden" value="${description}" name="description[${index}]" />
                        ${inputUnit}
                        ${inputMerk}
                    </div>
                `);

                    $('#master_item')[0].selectedIndex = 0;
                    $('#position')[0].selectedIndex = 0;
                    $('#qty').val('');
                    $('#description').val('');
                    item.qty = qty;
                    items.push(item);

                    $(`#image-box-${index}`).css('position', 'absolute');
                    $(`#image-box-${index}`).css('opacity', 0);

                    index += 1;

                    $('#image-section').append(`
                    <div class="mb-10" id="image-box-${index}">
                        <label for="image-${index}" class="form-label">Foto</label>
                        <input name="image[${index}]" id="image-${index}" type="file" class="form-control border" />
                    </div>
                `);
                };

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    reader.onload();
                }
            } else {
                Swal.fire({
                    title: 'Isi semua data {{ucwords($type)}}',
                    icon: 'info',
                    showConfirmButton: false
                });
            }
        });

        $('#submit-btn').click(function () {
            let ship = $('#master_ship_uuid').val();
            let itemsLength = $('#list-item').children().length;

            if (!ship) {
                Swal.fire({
                    title: 'Kapal belum dipilih',
                    icon: 'info',
                    showConfirmButton: false
                });

                return;
            }

            if (!itemsLength) {
                Swal.fire({
                    title: '{{ucwords($type)}} belum dipilih',
                    icon: 'info',
                    showConfirmButton: false
                });

                return;
            }

            Swal.fire({
                title: 'Konfirmasi Permintaan Pengiriman',
                text: "Pastikan data Permintaan Pengiriman telah sesuai",
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
