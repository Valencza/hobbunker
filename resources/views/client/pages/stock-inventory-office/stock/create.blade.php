@extends('client.layouts.app')

@section('title', 'Tambah Stok Kantor')


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
        <li class="breadcrumb-item text-gray-500">Home</li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-5">Stok Kantor</h2>

    <section class="card shadow-sm rounded-2xl">
        <h2 class="fs-4 mb-3 p-4 border-1 border-bottom">Tambah Stok</h2>

        <form method="POST" class="px-4">
            <div class="mb-10">
                <label for="date" class="required form-label">Tanggal Input</label>
                <input type="date" id="date" name="date" value="{{$date}}"
                    class="form-control  border" />
            </div>
            <div class="mb-10">
                <label for="item" class="required form-label">Nama Barang</label>
                <select name="item" id="item" class="form-select  border">
                    <option disabled selected>Pilih Barang</option>
                    @foreach ($masterItems as $masterItem)
                    <option value="{{$masterItem}}">{{$masterItem->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="">
                <label for="qty" class="required form-label">Jumlah</label>
                <input type="number" min="0" id="qty" name="qty" class="form-control  border"
                    placeholder="Masukkan jumlah" />
            </div>
        </form>
        <div class="px-4 mb-10 w-100">
            <div id="add-btn" href="#" class="btn btn-light-success px-2 py-2 w-100">
                <i class="bi bi-plus text-primary ml-1 fs-4"></i>
                <span>Tambah Barang</span>
            </div>
        </div>

        <div class="px-4 mb-10 w-100">
            <div id="list-item" class="d-flex flex-column gap-3">
            </div>
        </div>

        <div class="px-4 mb-10 w-100">
            <button id="submit-btn" class="w-100 btn btn-primary">Buat Barcode</button>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script src="
https://cdn.jsdelivr.net/npm/virtual-select-plugin@1.0.44/dist/virtual-select.min.js
"></script>
<script>
    let items = [];
    let index = 0;

    VirtualSelect.init({
        ele: '#item',
        search: true
    });

    function deleteItem(indexDelete) {
        $('#list-item').children().eq(indexDelete).remove();
        items.splice(indexDelete, 1);
    }

    $('#add-btn').click(function () {
        let qty = $('#qty').val();
        let item = JSON.parse($('#item').val());

        item.qty = qty;
        items.push(item);

        $('#list-item').append(`
            <div id="item-${item.uuid}" class="item px-4 py-3 border rounded-2xl">
                <h4>${item.name}</h4>
                <h5>${qty} ${item.master_unit.name}</h5>
                <div onclick="deleteItem(${index})" class="btn btn-secondary py-2 rounded-3 mb-2">Hapus</div>
            </div>
        `);
    });

    $('#submit-btn').click(function () {
        let date = $('#date').val();
        let itemsLength = $('#list-item').children().length;

        if (!date) {
            Swal.fire({
                title: 'Tanggal belum diisi',
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
            title: 'Konfirmasi Cetak Barcode',
            text: "Pastikan data penambahan stok barang telah sesuai",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.setItem('office-stock-date', JSON.stringify(date));
                localStorage.setItem('office-stocks', JSON.stringify(items));
                document.location.href = "{{route('stock-inventory-office.stock.barcode')}}"
            }
        });
    });

</script>
@endpush
