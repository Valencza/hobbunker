@extends('client.layouts.app')

@section('title', 'Cetak Barcode')

@push('styles')
    <style>
        .download-areas {
            position: absolute;
            top: -100000vh;
        }
    </style>
@endpush

@section('content')
<main class="p-4" style="overflow-x: hidden">
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

    <h2 class="anchor mb-5 mt-3">Barcode</h2>

    <form action="{{route('stock-inventory-office.inventory.store')}}" method="POST">
        @csrf

        <section id="list-item" class="card shadow-sm rounded-2xl">
        </section>

        <div id="submit-btn" class="btn btn-primary mt-4  w-100">Konfirmasi Tambah Inventaris</div>
    </form>
</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uuid/8.3.2/uuidv4.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    let date = JSON.parse(localStorage.getItem('office-inventory-date'));
    let items = JSON.parse(localStorage.getItem('office-inventories'));

    items.forEach((item, index) => {
        $('#list-item').append(`
            <div class="fs-4 mb-3 border-1 border-bottom py-4 px-4 d-flex align-items-center justify-content-between">
                <h2>${item.name}(${item.qty})</h2>
                <span class="badge badge-danger w-fit">Berlaku hingga: ${moment(item.expired_at).format('D MMM YYYY')}</span>
            </div>
            <input type="hidden" value="${item.expired_at}" name="expired_at[${index}]" />

            <div id="list-code-${item.uuid}" class="d-flex flex-column gap-3 mb-5 px-4">
            </div>
        `);

        for (let i = 1; i <= item.qty; i++) {
            var phrase = item.name;
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

            $(`#list-code-${item.uuid}`).append(`
                <section class="download-areas rounded-2xl shadow-sm card" id="download-area-${index}" style="width: 1000px; height: 1100px; margin: auto;">
                    <div class="d-flex flex-column align-items-center">
                        <img class="mb-3 mt-5" width="300" src="{{ asset('assets/media/logos/blueLogo.svg') }}" alt="HOB Logo">
                        <img src="${base64Image}" class="px-4" alt="">
                    </div>
                    <div class="text-center mt-5">
                        <h5 style="font-size: 50px;">${item.name}</h5>
                        <h6 style="font-size: 50px;" class="text-secondary">${code}</h6>
                    </div>
                </section>
                <div
                    class="card border-1 border-secondary border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center p-4">
                    <div class="d-flex flex-column w-100">
                        <span class="fw-medium fs-5 p-0">${code}</span>
                        <h4 class="text-muted p-0 text-secondary fs-6">
                            Tanggal: ${moment(date).format('D MMMM YYYY')}
                        </h4>
                    </div>
                    <div onclick="exportBarcode(${index}, '${code}')">
                        <i class="bi bi-upc-scan fs-2 px-3 py-2 rounded-4 bg-info text-white"></i>
                    </div>
                </div>

                <input type="hidden" value="${item.uuid}" name="uuid[${index}][${i}]" />
                <input type="hidden" value="${item.name}" name="name[${index}][${i}]" />
                <input type="hidden" value="${item.merk}" name="merk[${index}][${i}]" />
                <input type="hidden" value="${item.master_unit.name}" name="unit[${index}][${i}]" />
                <input type="hidden" value="${code}" name="code[${index}][${i}]" />
                <input type="hidden" value="${base64Image}" name="barcode[${index}][${i}]" />
            `);
        }
    });

    $('#submit-btn').click(function () {
        Swal.fire({
            title: 'Konfirmasi Penambahan Inventaris Barang',
            text: "Pastikan data penambahan inventaris barang telah sesuai",
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

    function exportBarcode(index, code) {
        html2canvas($(`#download-area-${index}`)[0]).then(function (canvas) {
            var imgData = canvas.toDataURL('image/jpeg');
            var link = $('<a></a>').attr({
                href: imgData,
                download: `${code}.jpg`
            });
            $('body').append(link);
            link[0].click();
            link.remove();
        });
    }

</script>
@endpush
