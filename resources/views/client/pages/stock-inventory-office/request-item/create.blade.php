@extends('client.layouts.app')

@section('title', 'Buat Pengiriman Barang')

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
            <a href="{{route('stock-inventory-office')}}" class="text-secondary text-decoration-none">Stok & Inventaris
                kantor</a>
        </li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Tambah Pengiriman</h2>

    <section>
        <div class="card shadow-sm rounded-2xl">
            <h3 class=" border-1 border-bottom p-4">Form Pengiriman Barang</h3>

            <div class="px-4 py-3 text-capitalize">


                <form onchange="this.submit()">
                    <div class="form-group">
                        <label for="type" class="form-label">Tipe</label>
                        <select class="form-select" name="type" id="type">
                            <option value="stok" @selected($type=='stok' )>Stok</option>
                            <option value="inventaris" @selected($type=='inventaris' )>Inventaris</option>
                        </select>
                    </div>
                </form>

                <form action="{{route('stock-inventory-office.request-item.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="type_select" value="{{$type}}">

                    <div class="form-group mb-3">
                        <label for="date" class="form-label">Tanggal Pengiriman</label>
                        @error('date')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span>{{$message}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <input autocomplete="off" type="date" name="date" id="date" class="form-control"
                            value="{{$date}}" required readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="master_ship_uuid" class="form-label">Kapal</label>
                        @error('master_ship_uuid')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span>{{$message}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @enderror
                        <select name="master_ship_uuid" id="master_ship_uuid" class="master_ship_uuid form-select mb-10"
                            required>
                            <option disabled {{ old('master_ship_uuid') ? '' : 'selected' }}>
                                Pilih Kapal</option>
                            @foreach ($masterShips as $masterShip)
                            <option value="{{ $masterShip->uuid }}"
                                {{ old('master_ship_uuid') == $masterShip->uuid ? 'selected' : '' }}>
                                {{ $masterShip->name }}
                            </option>
                            @endforeach
                        </select>

                    </div>

                    <h3 class=" border-1 pb-10">Detail Barang</h3>

                    <div class="mb-10">
                        <label for="position" class="required form-label">Bagian</label>
                        <select id="position" class="form-select border">
                            <option value="" disabled selected>Pilih Bagian</option>
                            <option value="dek">Dek</option>
                            <option value="mesin">Mesin</option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="master_item" class="required form-label">Nama Barang</label>
                        <select id="master_item" class="form-select border">
                            <option value="" selected disabled>Pilih Barang</option>
                            @foreach ($masterItems as $masterItem)
                            <option value="{{$masterItem}}">{{$masterItem->name}} - {{$masterItem->merk ?? 'No Merk'}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="qty" class="form-label">Jumlah</label>
                        <input id="qty" type="number" class="form-control  border" placeholder="Masukkan jumlah" />
                    </div>

                    <div id="add-btn" class="btn btn-light-success px-2 py-2 w-100 mb-10">
                        <i class="bi bi-plus text-primary ml-1 fs-4"></i>
                        <span>Tambah Barang</span>
                    </div>

                    <div id="list-item" class="px-2 pb-10 d-flex gap-3 flex-column">
                    </div>
                </form>

            </div>

            <div class="d-flex gap-3 w-100 px-4 mb-4">
                <a href="{{route('stock-inventory-office.request-item')}}"
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

    function deleteItem(indexDelete) {
        $('#list-item').children().eq(indexDelete).remove();
        items.splice(indexDelete, 1);
    }

    VirtualSelect.init({
        ele: '#master_item',
        search: true
    });

    $(document).ready(function () {
        $('#master_ship_uuid').change(function () {
            $('#list-item').children().remove();
        });

        $('#add-btn').click(function () {
            let position = $('#position').val();
            let item = JSON.parse($('#master_item').val());
            let qty = $('#qty').val();

            if (item && qty > 0 && position) {
                index += 1;

                $('#list-item').append(`
                        <div id="item-${item.uuid}" class="item px-4 py-3 border rounded-2xl">
                            <h4>${item.name}</h4>
                            <p class="text-secondary">Bagian: ${position}</p>
                            <h5>${qty} ${item.master_unit.name}</h5>
                            <div onclick="deleteItem(${index})" class="btn btn-secondary py-2 rounded-3 mb-2">Hapus</div>

                            <input type="hidden" value="${item.uuid}" name="master_item[${index}]" />
                            <input type="hidden" value="${qty}" name="qty[${index}]" />
                            <input type="hidden" value="${position}" name="position[${index}]" />
                        </div>
                    `);

                $('#master_item')[0].selectedIndex = 0;
                $('#position')[0].selectedIndex = 0;
                $('#qty').val('');
                item.qty = qty;
                items.push(item);
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
                    title: 'Barang belum dipilih',
                    icon: 'info',
                    showConfirmButton: false
                });

                return;
            }

            Swal.fire({
                title: 'Konfirmasi Pengiriman',
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
