@extends('client.layouts.app')

@section('title', 'Detail Permintaan Barang')

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

    <h2 class="anchor mb-5 mt-3">Permintaan Barang</h2>

    <section>
        <div class="card shadow-sm rounded-2xl">
            <div class="d-flex justify-content-between align-items-center p-4 border-1 border-bottom">
                <h3>Detail Permintaan Barang</h3>
                @switch($requestItem->status)
                @case('baru')
                <span
                    class="btn btn-light-primary text-primary w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
                @break
                @case('terkonfirmasi')
                <span
                    class="btn btn-light-success text-success w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
                @break
                @case('siap dikirim')
                <span
                    class="btn btn-light-warning text-warning w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
                @break
                @case('selesai')
                <span
                    class="btn btn-light-success text-success w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
                @break
                @case('ditolak')
                <span
                    class="btn btn-light-danger text-danger w-25 py-1 text-sm">{{ucwords($requestItem->status)}}</span>
                @break
                @endswitch
            </div>

            <div class="px-4 py-3">
                <h4 class="mb-1">Nama PIC</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->masterUser->name}}</h5>

                <h4 class="mb-1">Tanggal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->formatted_created_at}}</h5>

                <h4 class="mb-1">Kapal</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->masterShip->name}}</h5>

                <h4 class="mb-1 text-dark">Deskripsi</h4>
                <h5 class="pb-4 text-secondary mt-2">{{$requestItem->description}}</h5>

                @if ($requestItem->status == 'selesai')
                <h4 class="mb-1 text-dark">Foto Barang</h4>
                <div class="row mb-2">
                    @foreach ($requestItem->requestItemDocumentsItem as $requestItemDocument)
                    <a href="{{asset($requestItemDocument->file)}}" download class="col-md-4 col-12">
                        <div class="file-preview">
                            @php
                            $ext = explode('.',$requestItemDocument->file);
                            $ext = end($ext);
                            @endphp
                            @if (in_array($ext, ['jpg', 'png', 'jpeg']))
                            <img src="{{asset($requestItemDocument->file)}}">
                            @else
                            <i class="fa fa-file"></i>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <h4 class="mb-1 text-dark">Surat Jalan</h4>
                <div class="row mb-2">
                    @if ($requestItem->requestItemDocumentsRoadLetter)
                    <a href="{{asset($requestItem->requestItemDocumentsRoadLetter->file)}}" download class="col-md-4 col-12">
                    @else
                    <a href="{{route('road-letter.pdf', $requestItem->roadLetter->uuid)}}" download class="col-md-4 col-12">
                        @endif
                    <div class="file-preview">
                            <i class="fa fa-file"></i>
                        </div>
                    </a>
                </div>
                @endif

                <table id="table-item" class="table gs-7 gy-7 gx-7">
                    <thead>
                        <tr class="fw-bold fs-4 text-gray-800 border-bottom border-gray-200">
                            <th class="px-0 py-2 text-left">Item</th>
                            <th class="px-2 py-2 text-center">Qty</th>
                            <th class="px-2 py-2 text-center">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>


                @switch($requestItem->status)
                @case('baru')
                <div class="row mt-5">
                    <div class="col-6">
                        <div class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#kt_modal_1">
                            Sesuaikan
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="submit-btn" class="btn bg-success text-white w-100">
                            Konfirmasi
                        </div>
                    </div>
                </div>
                @break
                @case('terkonfirmasi')
                <a href="{{route('stock-inventory-office.request-item.scan', $requestItem->uuid)}}"
                    class="btn bg-success text-white w-100">
                    Siapkan Barang
                </a>
                @break
                @endswitch
            </div>
        </div>
    </section>

    <div class="modal fade" tabindex="-1" id="kt_modal_1">
        <div class="modal-dialog modal-dialog-centered m-3">
            <div class="modal-content">
                <form action="{{route('stock-inventory-office.request-item.confirm')}}" method="POST">
                    @csrf
                    <input type="hidden" name="uuid" value="{{$requestItem->uuid}}">

                    <div class="modal-header">
                        <h5 class="modal-title">Seusaikan Item</h5>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="bi bi-x fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body">
                        <div id="list-item" class="d-flex flex-column gap-3">
                        </div>
                    </div>

                    <div class="d-flex w-100 px-3 gap-3 pb-3">
                        <div class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</div>
                        <div id="save-btn" class="btn btn-primary w-100" data-bs-dismiss="modal">Simpan Perubahan</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuidv4.min.js"></script>
<script>
    const submitAddMasterItem = (uuid, index) => {
        Swal.fire({
            title: 'Item akan ditambahkan ke master data',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#add-master-item-${index}`).submit();
            }
        });
    }

    $(document).ready(function () {
        let items = @json($requestItem->requestItemDetails);
        let requestItem = @json($requestItem);

        window.reject = (index) => {
            $(`#input-reject-${index}`).val(1);
            $(`#span-reject-${index}`).show();
            $(`#reject-description-${index}`).removeClass('d-none');
            $(`#item-qty-${index}`).removeClass('d-flex');
            $(`#item-qty-${index}`).addClass('d-none');
            $(`#accept-btn-${index}`).show();
            $(`#reject-btn-${index}`).hide();
        }

        window.accept = (index) => {
            $(`#input-reject-${index}`).val(0);
            $(`#span-reject-${index}`).hide();
            $(`#reject-description-${index}`).addClass('d-none');
            $(`#item-qty-${index}`).removeClass('d-none');
            $(`#item-qty-${index}`).addClass('d-flex');
            $(`#accept-btn-${index}`).hide();
            $(`#reject-btn-${index}`).show();
        }

        window.minQty = (index) => {
            let span = $(`#span-qty-${index}`);
            let input = $(`#input-qty-${index}`);
            let qty = parseInt(input.val());

            if (qty == 1) return;

            qty -= 1;

            span.text(qty);
            input.val(qty);
        }

        window.plusQty = (index) => {
            let span = $(`#span-qty-${index}`);
            let input = $(`#input-qty-${index}`);
            let qty = parseInt(input.val());

            qty += 1;

            span.text(qty);
            input.val(qty);
        }

        const loadTable = () => {
            $('#table-item tbody tr').remove();

            items.forEach((item, index) => {
                if (item.master_item_uuid.includes('~')) {
                    item = {
                        uuid: item.uuid,
                        master_item_uuid: item.master_item_uuid,
                        image: item.image,
                        description: item.description,
                        qty: item.qty,
                        position: item.position,
                        reject: item.reject,
                        reject_description: item.reject_description,
                        master_item: {
                            name: item.master_item_uuid.split('~')[1],
                            master_unit: {
                                name: item.master_item_uuid.split('~')[2]
                            }
                        }
                    }
                }

                let imgPreview = item.image ? `
                    <p class="fs-5 m-0">Foto:</p>
                    <img src="${item.image}" width="200">
                ` : '';

                let descriptionPreview = item.description ? `
                    <p class="fs-5 m-0">Keterangan:</p>
                    <p>${item.description}</p>
                ` : '';

                if (item.master_item_uuid.includes('~') && requestItem.status == 'baru') {
                    $('#table-item tbody').append(`
                        <tr>
                            <td colspan="3" class="text-gray-400 text-left px-0 py-2">
                                <div class="badge badge-warning w-100 mb-3">
                                    ${item.master_item.name} tidak tersedia di master data!
                                </div>
                                <form id="add-master-item-${index}" action="{{route('stock-inventory-office.request-item.master-item')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="uuid" value="${item.uuid}" />
                                    <div onclick="submitAddMasterItem('${item.uuid}', ${index})" class="btn btn-primary w-100">Tambahkan</div>
                                </form>
                            </td>
                        </tr>
                    `);
                }


                let rejectPreview = item.reject == true ? `
                    <p class="fs-5 m-0 text-danger mt-3 fw-bold">Ditolak:</p>
                    <p>${item.reject_description}</p>
                ` : '';

                $('#table-item tbody').append(`
                    <tr>
                        <td class="text-gray-400 text-left px-0 py-2">
                            <span class="fw-medium text-secondary">${item.master_item.name}</span>
                            <p class="fs-5 m-0">Bagian: ${item.position}</p>
                            ${imgPreview}
                            ${descriptionPreview}
                            ${rejectPreview}
                        </td>
                        <td class="p-2 text-gray-400 text-center">${item.qty}</td>
                        <td class="p-2 text-gray-400 text-center">${item.master_item.master_unit.name}</td>
                    </tr>
                `);
            });
        }

        const loadListItem = () => {
            $('#list-item div').remove();

            items.forEach((item, index) => {
                if (item.master_item_uuid.includes('~')) {
                    item = {
                        uuid: item.uuid,
                        master_item_uuid: item.master_item_uuid,
                        image: item.image,
                        description: item.description,
                        qty: item.qty,
                        position: item.position,
                        reject: item.reject,
                        reject_description: item.reject_description,
                        master_item: {
                            name: item.master_item_uuid.split('~')[1],
                            master_unit: {
                                name: item.master_item_uuid.split('~')[2]
                            }
                        }
                    }
                }

                $('#list-item').append(`
                    <div id="card-item-${index}" class="p-4 card rounded-2xl">

                        <h4 class="fw-bolder">${item.master_item.name}</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <div id="item-qty-${index}" class="d-flex gap-2 align-items-center">
                                <h6>Qty:</h6>
                                <div class="d-flex border border-bg-secondary rounded-3 d-flex align-items-center gap-2 ">
                                    <span class="btn btn-bg-secondary px-3 py-1 rounded-3" onclick="minQty(${index})">-</span>
                                    <span id="span-qty-${index}">${item.qty}</span>
                                    <input type="hidden" id="input-qty-${index}" name="qty[${index}]" value="${item.qty}"/>
                                    <input type="hidden" id="input-reject-${index}" name="reject[${index}]" value="${item.reject}"/>
                                    <input type="hidden" id="input-item-uuid-${index}" name="item-uuid[${index}]" value="${item.master_item.uuid}"/>
                                    <input type="hidden" id="input-request-item-detail-uuid-${index}" name="request-item-detail-uuid[${index}]" value="${item.uuid}"/>
                                    <span class="btn btn-bg-secondary px-3 py-1 rounded-3" onclick="plusQty(${index})">+</span>
                                </div>
                                <h6>Kg</h6>
                            </div>
                            <span id="span-reject-${index}" class="text-danger fw-bold">Ditolak</span>
                            <div id="reject-btn-${index}" onclick="reject(${index})" class="btn btn-light-danger">Tolak</div>
                            <div id="accept-btn-${index}" onclick="accept(${index})" class="btn btn-light-success">Setujui</div>
                        </div>
                        <textarea class="form-control mt-3 d-none" name="reject_description[${index}]" id="reject-description-${index}" placeholder="Masukkan alasan ditolak" cols="20" rows="10">${item.reject_description ?? ''}</textarea>
                    </div>
                `);

                if (!item.reject) {
                    $(`#span-reject-${index}`).hide();
                    $(`#reject-description-${index}`).addClass('d-none');
                    $(`#accept-btn-${index}`).hide();
                    $(`#reject-btn-${index}`).show();
                    $(`#item-qty-${index}`).removeClass('d-none');
                    $(`#item-qty-${index}`).addClass('d-flex');
                } else {
                    $(`#span-reject-${index}`).show();
                    $(`#reject-description-${index}`).removeClass('d-none');
                    $(`#accept-btn-${index}`).show();
                    $(`#reject-btn-${index}`).hide();
                    $(`#item-qty-${index}`).removeClass('d-flex');
                    $(`#item-qty-${index}`).addClass('d-none');
                }
            });
        }

        loadTable();
        loadListItem();

        $('.tab-pane').first().addClass('show active');
        $('.nav-link').click(function () {
            $($(this).attr('href')).addClass('show active');
        });

        $('#modal').click(function () {
            $('div').removeClass('modal-backdrop fade show');
        });

        $('#save-btn').click(function () {
            items = items.map((item, index) => {
                let qty = parseInt($(`#input-qty-${index}`).val());
                let reject = parseInt($(`#input-reject-${index}`).val());
                let reject_description = $(`#reject-description-${index}`).val();

                item.qty = qty;
                item.reject = reject;
                item.reject_description = reject_description;

                return item;
            });

            loadTable();
            loadListItem();
        });

        $('#submit-btn').click(function () {
            let check = items.find(item => item.master_item_uuid.includes('~') && !item.reject);

            if (check) {
                return Swal.fire({
                    title: 'Terdapat item yang tidak tersedia',
                    text: "Harap tambahkan atau tolak terlebih dahulu",
                    icon: 'info',
                    showConfirmButton: false
                });
            }

            items.forEach((item, index) => {
                for (let i = 0; i < item.qty; i++) {
                    if (item.master_item_uuid.includes('~')) {
                        item = {
                            uuid: item.uuid,
                            master_item_uuid: item.master_item_uuid,
                            image: item.image,
                            description: item.description,
                            qty: item.qty,
                            position: item.position,
                            reject: item.reject,
                            reject_description: item.reject_description,
                            master_item: {
                                name: item.master_item_uuid.split('~')[1],
                                master_unit: {
                                    name: item.master_item_uuid.split('~')[2]
                                }
                            }
                        }
                    }
                    var phrase = item.master_item.name;
                    var words = phrase.split(" ");
                    var acronym = "";
                    $.each(words, function (index, value) {
                        acronym += value.charAt(0).toUpperCase();
                    });

                    var code = `${acronym}-${uuidv4().substr(0, 5).toUpperCase()}`;

                    var jsonData = {
                        "name": item.name,
                        "code": code,
                    };

                    var jsonString = JSON.stringify(jsonData);

                    var qr = qrcode(10, 'H');
                    qr.addData(jsonString);
                    qr.make();

                    var base64Image = qr.createDataURL(10);

                    $(`#list-item`).append(`
                                <input type="hidden" value="${code}" name="code[${index}][${i}]" />
                                <input type="hidden" value="${base64Image}" name="barcode[${index}][${i}]" />
                            `);
                }
            })

            Swal.fire({
                title: 'Konfirmasi Permintaan Barang',
                text: "Pastikan data permintaan barang telah sesuai",
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
