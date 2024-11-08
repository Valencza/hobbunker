@extends('client.layouts.app')

@section('title', 'Detail Inventaris')

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
            <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Detail Inventaris</h2>

    <section class="rounded-2xl shadow-sm position-relative card-attendance-s">
        <img src="{{ asset('assets/media/illustrations/cuti_background.svg') }}" alt=""
            class="position-absolute rounded-4 w-100">

        <div class="position-absolute z-2 text-white p-4 w-100 border rounded-4 shadow-sm">
            <div class="row">
                <div class="col-6">
                    <button class="w-100 btn btn-success fw-bolder" id="modal" data-bs-toggle="modal"
                        data-bs-target="#useStockModal">
                        <i class="fa-solid fa-hands-holding-circle"></i>
                        Pakai Stok
                    </button>
                </div>
                <div class="col-6">
                    <button class="w-100 btn btn-success fw-bolder" id="modal" data-bs-toggle="modal"
                        data-bs-target="#deleteStockModal">
                        <i class="bi bi-trash fs-4"></i>
                        Hapus Stok
                    </button>
                </div>
            </div>

            <div class="text-white fw-bolder mt-5 mb-4">
                <h3 class="fw-bolder mb-4">{{$masterItem->name}}</h3>
                <h5>{{$masterItem->merk ?? 'Non Merk'}}</h5>
            </div>

            <div class="row pb-2">
                <div class="col-12">
                    <div class="card h-100 bg-body-secondary p-3 rounded-3 my-2">
                        <i class="bi bi-inbox text-primary fs-1"></i>
                        <h1 class="text-primary text-2xl mt-3">{{$masterItem->balance_office_stock}}</h1>
                        <h4 class="text-secondary">Sisa Stok</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">Daftar Inventaris</h2>
        </div>

        {{-- search form --}}
        <form data-kt-search-element="form" class="w-100 position-relative my-4" autocomplete="off">
            <!--begin::Hidden input(Added to disable form autocomplete)-->
            <input type="hidden" />
            <!--end::Hidden input-->
            <!--begin::Icon-->
            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
            <span
                class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-3 translate-middle-y">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                        transform="rotate(45 17.0365 15.1223)" fill="black" />
                    <path
                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                        fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
            <!--end::Icon-->
            <!--begin::Input-->
            <input type="text" class="form-control form-control-lg  px-15" name="search" value="{{$search}}"
                placeholder="Cari..." data-kt-search-element="input" />
            <!--end::Input-->
            <!--begin::Spinner-->
            <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                data-kt-search-element="spinner">
                <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
            </span>
            <!--end::Spinner-->
            <!--begin::Reset-->
            <span
                class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
                data-kt-search-element="clear">
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                            transform="rotate(-45 6 17.3137)" fill="black" />
                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                            fill="black" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </span>
            <!--end::Reset-->
        </form>
        {{-- end search form --}}

        <div class="d-flex flex-column gap-1 mb-5">
            @foreach ($officeStocks as $officeStock)
            <div
                class="card border-1 border-dashed rounded-2xl d-flex gap-2 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                <div class="centering bg-light-primary rounded p-3">
                    <span class="text-primary fw-bolder">
                        <x-acronym text="{{$masterItem->name}}"></x-acronym>
                    </span>
                </div>
                <div class="d-flex flex-column w-100">
                    <span class="fw-medium fs-5 p-0">{{$officeStock->code}}</span>
                    <h4 class="text-muted p-0 text-secondary fs-6">
                        Tanggal :
                        <span class="text-info ml-3">{{$officeStock->formatted_created_at}}</span>
                    </h4>
                    <span class="badge badge-danger w-fit">
                        Berlaku hingga: {{$officeStock->formatted_expired_at}}
                    </span>
                </div>
                <a class="m-0" href="{{route('stock-inventory-office.inventory.scan', $officeStock->uuid)}}">
                    <i class="bi bi-upc-scan fs-2 px-2 py-2 rounded-4 bg-info text-white"></i>
                </a>
                <button class="btn btn-warning btn-icon rounded-4 text-white" data-bs-toggle="modal"
                    data-bs-target="#editInventory-{{$officeStock->uuid}}">
                    <i class="fa fa-edit px-3"></i>
                </button>
            </div>

            <div class="modal fade" id="editInventory-{{$officeStock->uuid}}" tabindex="-1"
                aria-labelledby="editInventoryLabel-{{$officeStock->uuid}}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <form class="modal-content"
                        action="{{route('stock-inventory-office.inventory.update.expired_at', $officeStock->uuid)}}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editInventoryLabel-{{$officeStock->uuid}}">Ubah
                                Inventaris
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="expired_at_{{$officeStock->uuid}}" class="form-label">Berlaku hingga</label>
                                @error('expired_at')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="date" name="expired_at"
                                    id="expired_at_{{$officeStock->uuid}}" class="form-control"
                                    value="{{ old('expired_at', date('Y-m-d', strtotime($officeStock->expired_at))) }}"
                                    required>
                            </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach

            {{$officeStocks->links('vendor.pagination.bootstrap-5')}}

            {{-- empty --}}
            @if (count($officeStocks) == 0)
            <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                alt="Empty Illustration">
            @endif
            {{-- end empty --}}
        </div>
    </section>

    <section class="card shadow-sm p-4 rounded-2xl mt-4">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">Riwayat Inventaris</h2>
        </div>

        <div class="d-flex flex-column gap-3 mb-5">
            @foreach ($stockHistories as $stockHistory)
            <div
                class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                @switch($stockHistory->status)
                @case('masuk')
                <div class="card btn btn-light-info p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                    <i class="bi bi-download text-info fs-4 ml-3"></i>
                </div>
                @break
                @case('keluar')
                <div class="card btn btn-light-danger p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                    <i class="bi bi-upload text-danger fs-4 ml-3"></i>
                </div>
                @break
                @case('edit')
                <div class="card btn btn-light-primary p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                    <i class="bi bi-pencil-square text-primary fs-4 ml-3"></i>
                </div>
                @break
                @endswitch
                <div class="d-flex flex-column w-100">
                    <span class="fw-medium fs-5 p-0">
                        {{ucwords($stockHistory->status)}}
                    </span>
                    <h4 class="text-muted p-0 text-secondary fs-6">
                        Tanggal :
                        <span class="text-info ml-3">{{$stockHistory->formatted_created_at}}</span>
                    </h4>
                </div>
            </div>
            @endforeach

            {{$stockHistories->links('vendor.pagination.bootstrap-5')}}

            {{-- empty --}}
            @if (count($stockHistories) == 0)
            <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                alt="Empty Illustration">
            @endif
            {{-- end empty --}}
        </div>
    </section>

    <section class="card shadow-sm p-4 rounded-2xl mt-4">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">Pemakaian Inventaris</h2>
        </div>

        <div class="d-flex flex-column gap-3 mb-5">
            @foreach ($usedItems as $usedItem)
            <div
                class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                <div class="d-flex flex-column w-100">
                    <span class="fw-medium fs-5 p-0">
                        {{$usedItem->title}}
                    </span>
                    <span class="text-muted">
                        {{$usedItem->description}}
                    </span>
                    <h4 class="text-muted p-0 text-secondary fs-6">
                        Tanggal :
                        <span class="text-info ml-3">{{$stockHistory->formatted_created_at}}</span>
                    </h4>

                    <div class="row">
                        @foreach ($usedItem->usedItemDetails as $usedItemDetail)
                        <div class="col-3">
                            <div class="alert alert-primary">
                                {{$usedItemDetail->code}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            {{$usedItems->links('vendor.pagination.bootstrap-5')}}

            {{-- empty --}}
            @if (count($usedItems) == 0)
            <img class="img-fluid mx-auto d-block" height="50" src="{{asset('assets/media/illustrations/empty.png')}}"
                alt="Empty Illustration">
            @endif
            {{-- end empty --}}
        </div>
    </section>

    {{-- use modal --}}
    <div class="modal fade" id="useStockModal" tabindex="-1" aria-labelledby="useStockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5 class="modal-title" id="useStockModalLabel">Pakai Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="height: 300px; overflow-x: auto;">
                    <form id="use-form" action="{{route('stock-inventory-office.inventory.use', $masterItem->uuid)}}" method="POST">
                        @csrf
                        <input type="hidden" name="codes" id="use-codes">

                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="title" id="title"
                                placeholder="Masukan judul pemakaian">
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" name="description" id="description"
                                placeholder="Masukan deksripsi pemakain" cols="30" rows="10"></textarea>
                        </div>

                        <label class="form-label mb-3">Stok</label>

                        @foreach ($officeStocksAll as $officeStock)
                        <div id="item-use-{{$officeStock->code}}" onclick="toggleDelete('{{$officeStock->code}}', 'use')"
                            class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3 cursor-pointer">
                            <div class="centering bg-light-primary rounded p-3">
                                <span class="text-primary fw-bolder">
                                    <x-acronym text="{{$masterItem->name}}"></x-acronym>
                                </span>
                            </div>
                            <div class="d-flex flex-column w-100">
                                <span class="fw-medium fs-5 p-0">{{$officeStock->code}}</span>
                            </div>
                        </div>
                        @endforeach

                    </form>
                </div>

                <div class="modal-footer">
                    <div id="submit-use-btn" class="btn btn-primary w-100 rounded-3">Simpan Perubahan</div>
                </div>
            </div>
        </div>
    </div>

    {{-- delete modal --}}
    <div class="modal fade" id="deleteStockModal" tabindex="-1" aria-labelledby="deleteStockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h5 class="modal-title" id="deleteStockModalLabel">Hapus Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="height: 300px; overflow-x: auto;">
                    <form id="delete-form" action="{{route('stock-inventory-office.inventory.update', $masterItem->uuid)}}" method="POST">
                        @csrf
                        <input type="hidden" name="codes" id="codes">

                        @foreach ($officeStocksAll as $officeStock)
                        <div id="item-delete-{{$officeStock->code}}" onclick="toggleDelete('{{$officeStock->code}}', 'delete')"
                            class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3 cursor-pointer">
                            <div class="centering bg-light-primary rounded p-3">
                                <span class="text-primary fw-bolder">
                                    <x-acronym text="{{$masterItem->name}}"></x-acronym>
                                </span>
                            </div>
                            <div class="d-flex flex-column w-100">
                                <span class="fw-medium fs-5 p-0">{{$officeStock->code}}</span>
                            </div>
                        </div>
                        @endforeach

                    </form>
                </div>

                <div class="modal-footer">
                    <div id="submit-delete-btn" class="btn btn-primary w-100 rounded-3">Simpan Perubahan</div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    let codes = [];

    const toggleDelete = (code, action) => {
        let check = codes.find(item => item == code);
        if (check) {
            codes = codes.filter(item => item != code);
            $(`#item-${action}-${code}`).removeClass('border-danger text-danger');
        } else {
            codes.push(code);
            $(`#item-${action}-${code}`).addClass('border-danger text-danger');
        }
    }

    $('#submit-use-btn').click(function () {
        if (!$('#title').val()) {
            Swal.fire({
                title: 'Judul pemakaian stok belum diisi',
                icon: 'info',
                showConfirmButton: false
            });
            return;
        }

        if (!$('#description').val()) {
            Swal.fire({
                title: 'Deskripsi pemakaian stok belum diisi',
                icon: 'info',
                showConfirmButton: false
            });
            return;
        }

        if (codes.length <= 0) {
            Swal.fire({
                title: 'Barang pemakaian stok belum dipilih',
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
                $('#use-codes').val(codes);
                $('#use-form').submit();
            }
        });
    });

    $('#submit-delete-btn').click(function () {
        if (codes.length <= 0) {
            Swal.fire({
                title: 'Barang penghapusan stok belum dipilih',
                icon: 'info',
                showConfirmButton: false
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Penghapusan Barang',
            text: "Pastikan data penghapusan barang telah sesuai",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-codes').val(codes);
                $('#delete-form').submit();
            }
        });
    });

</script>
@endpush
