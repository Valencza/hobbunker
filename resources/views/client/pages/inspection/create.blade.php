@extends('client.layouts.app')

@section('title', 'Laporan Pergantian Oli')

@push('styles')
<style>
    #loading-screen {
        background-color: rgba(50, 65, 168, .8);
        z-index: 9999;
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        display: grid;
        place-items: center;
    }

    .loader {
        width: 15px;
        aspect-ratio: 1;
        border-radius: 50%;
        animation: l5 1s infinite linear alternate;
    }

    @keyframes l5 {
        0% {
            box-shadow: 20px 0 #fff, -20px 0 #fff2;
            background: #fff
        }

        33% {
            box-shadow: 20px 0 #fff, -20px 0 #fff2;
            background: #fff2
        }

        66% {
            box-shadow: 20px 0 #fff2, -20px 0 #fff;
            background: #fff2
        }

        100% {
            box-shadow: 20px 0 #fff2, -20px 0 #fff;
            background: #fff
        }
    }

</style>
@endpush

@section('content')

<div id="loading-screen">
    <div class="text-center text-light">
        <div class="loader mx-auto mb-3"></div>
        <p class="m-0">Memproses data</p>
        <p>Harap jangan keluar dari halaman ini</p>
    </div>
</div>

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
        <li class="breadcrumb-item text-gray-500">Inskepsi Kapal</li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Laporan Laporan Inspeksi Kapal</h2>


    <form action="{{route('inspection.store')}}" method="POST" enctype="multipart/form-data">
        @csrf

        <div id="step-1" class="step mb-5 position-relative pt-5">
            <img class="mb-5 position-absolute" style="right: 0; top: 0" width="300"
                src="{{asset('assets/media/illustrations/inspect-1.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Informasi Kapal</h3>
                <p>Masukkan Data Kru dan Jabatan</p>
            </div>

            <div class="form-group mb-3 mt-5 pt-5">
                <label for="master_ship_uuid" class=" form-label">Kapal</label>
                <select name="master_ship_uuid" id="master_ship_uuid" class="master_ship_uuid form-select" required>
                    <option disabled selected>Pilih Kapal</option>
                    @foreach ($masterShips as $masterShip)
                    <option value="{{$masterShip->uuid}}">{{$masterShip->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3 ">
                <label for="date" class="form-label">Tanggal</label>
                <input type="date" value="{{$date}}" id="date" name="date" class="form-control border" readonly />
            </div>
            <div class="form-group mb-3 ">
                <label for="crew" class="form-label">Nama Kru</label>
                <input type="text" id="crew" name="crew" class="form-control border" placeholder="Masukan nama kru" />
            </div>
            <div class="form-group mb-3 ">
                <label for="position" class="form-label">Jabatan</label>
                <input type="text" id="position" name="position" class="form-control border"
                    placeholder="Masukan jabatan" />
            </div>

            <div id="add-btn" class="btn btn-light-success px-3 py-2 m-0 w-100 mb-3">
                <i class="bi bi-plus fs-3"></i>
                <span>
                    Tambah Kru Jabatan
                </span>
            </div>

            <div id="list-crew">

            </div>
        </div>

        <div id="step-2" class="step mb-5 position-relative pt-5">
            <img class="mb-5 position-absolute" style="right: 0; top: 0" width="275"
                src="{{asset('assets/media/illustrations/inspect-2.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Kondisi Dek</h3>
                <p>Masukkan Foto Inspeksi</p>
            </div>

            <div id="deck-photo" class="form-group mb-3 pt-5">
                <label for="deck_photo" class=" form-label">Upload Foto</label>
                <input type="file" id="deck_photo" name="deck_photo[]" class="form-control border" multiple />
            </div>

            <div id="deck-description" class="form-group">
                <label for="deck_description" class=" form-label">Keterangan</label>
                <textarea class="form-control" name="deck_description" id="deck_description" cols="30"
                    rows="10"></textarea>
            </div>
        </div>

        <div id="step-3" class="step mb-5 position-relative pt-5">
            <img class="mb-5 position-absolute" style="right: 0; top: 0" width="350"
                src="{{asset('assets/media/illustrations/inspect-3.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Kondisi Anjungan</h3>
                <p>Masukkan Foto Inspeksi</p>
            </div>

            <div id="platform-photo" class="form-group mb-3 pt-5">
                <label for="platform_photo" class=" form-label">Upload Foto</label>
                <input type="file" id="platform_photo" name="platform_photo[]" class="form-control border" multiple />
            </div>

            <div id="platform-description" class="form-group">
                <label for="platform_description" class=" form-label">Keterangan</label>
                <textarea class="form-control" name="platform_description" id="platform_description" cols="30"
                    rows="10"></textarea>
            </div>
        </div>

        <div id="step-4" class="step mb-5 position-relative pt-5">
            <img class="mb-5 position-absolute w-100" style="right: 0; top: 0" width="350"
                src="{{asset('assets/media/illustrations/inspect-4.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Kondisi Dapur</h3>
                <p>Masukkan Foto Inspeksi</p>
            </div>

            <div id="kitchen-photo" class="form-group mb-3 pt-5">
                <label for="kitchen_photo" class=" form-label">Upload Foto</label>
                <input type="file" id="kitchen_photo" name="kitchen_photo[]" class="form-control border" multiple />
            </div>

            <div id="kitchen-description" class="form-group">
                <label for="kitchen_description" class=" form-label">Keterangan</label>
                <textarea class="form-control" name="kitchen_description" id="kitchen_description" cols="30"
                    rows="10"></textarea>
            </div>
        </div>

        <div id="step-5" class="step mb-5 position-relative pt-5">
            <img class="mb-5 position-absolute w-100" style="right: 0; left: 0; top: 0" width="350"
                src="{{asset('assets/media/illustrations/inspect-5.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Kondisi Kamar Mesin</h3>
                <p>Masukkan Foto Inspeksi</p>
            </div>

            <div id="meachine-photo" class="form-group mb-3 pt-5">
                <label for="meachine_photo" class=" form-label">Upload Foto</label>
                <input type="file" id="meachine_photo" name="meachine_photo[]" class="form-control border" multiple />
            </div>

            <div id="meachine-description" class="form-group">
                <label for="meachine_description" class=" form-label">Keterangan</label>
                <textarea class="form-control" name="meachine_description" id="meachine_description" cols="30"
                    rows="10"></textarea>
            </div>
        </div>

        <div id="step-6" class="step mb-5 position-relative pt-5">
            <img class="mb-5 position-absolute w-100" style="right: 0; top: 0" width="350"
                src="{{asset('assets/media/illustrations/inspect-6.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Kondisi Alat Keselamatan</h3>
                <p>Masukkan Foto Inspeksi</p>
            </div>

            <div id="safety-photo" class="form-group mb-3 pt-5">
                <label for="safety_photo" class=" form-label">Upload Foto</label>
                <input type="file" id="safety_photo" name="safety_photo[]" class="form-control border" multiple />
            </div>

            <div id="safety-description" class="form-group">
                <label for="safety_description" class=" form-label">Keterangan</label>
                <textarea class="form-control" name="safety_description" id="safety_description" cols="30"
                    rows="10"></textarea>
            </div>
        </div>

        <div id="step-7" class="step mb-5  position-relative pt-5">
            <img class="mb-5 position-absolute w-100" style="right: 0; top: 0" width="275"
                src="{{asset('assets/media/illustrations/inspect-7.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Kondisi Alat Navigasi</h3>
                <p>Masukkan Foto Inspeksi</p>
            </div>

            <div id="navigation-photo" class="form-group mb-3 pt-5">
                <label for="navigation_photo" class=" form-label">Upload Foto</label>
                <input type="file" id="navigation_photo" name="navigation_photo[]" class="form-control border"
                    multiple />
            </div>

            <div id="navigation-description" class="form-group">
                <label for="navigation_description" class=" form-label">Keterangan</label>
                <textarea class="form-control" name="navigation_description" id="navigation_description" cols="30"
                    rows="10"></textarea>
            </div>
        </div>

        <div id="step-8" class="step mb-5 position-relative pt-5">
            <img class="mb-5 " style="left: 0; top: 0" width="300"
                src="{{asset('assets/media/illustrations/inspect-8.webp')}}">

            <div class="text-center mt-5 pt-5">
                <h3>Kondisi Obat - Obatan</h3>
                <p>Masukkan Foto Inspeksi</p>
            </div>

            <div id="medicine-photo" class="form-group mb-3 pt-5">
                <label for="medicine_photo" class=" form-label">Upload Foto</label>
                <input type="file" id="medicine_photo" name="medicine_photo[]" class="form-control border" multiple />
            </div>

            <div id="medicine-description" class="form-group">
                <label for="medicine_description" class=" form-label">Keterangan</label>
                <textarea class="form-control" name="medicine_description" id="medicine_description" cols="30"
                    rows="10"></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div id="prev-btn" class="btn btn-light-success text-center px-5">
                <i class="bi bi-arrow-left  fs-3"></i>
                <span class="ms-2">Sebelumnya</span>
            </div>

            <div id="next-btn" class="btn btn-primary text-center px-5">
                <span class="me-2">Lanjut</span>
                <i class="bi bi-arrow-right fs-3"></i>
            </div>

            <div id="submit-btn" class="btn btn-primary text-center px-5">
                Upload
            </div>
        </div>
    </form>
</main>

@endsection

@push('scripts')
<script>
    let step = 1;
    let index = 0;
    $('#loading-screen').hide();

    $('.step').hide();
    $('#prev-btn').hide();
    $('#submit-btn').hide();
    $('#step-1').show();

    $('#prev-btn').click(function () {
        $('#submit-btn').hide();
        $('#next-btn').show();
        $('.step').hide();
        step--;
        $(`#step-${step}`).show();

        if (step == 1) {
            $('#prev-btn').hide();
        }
    });

    $('#next-btn').click(function () {
        if (step == 1) {
            if (!$('#master_ship_uuid').val()) {
                Swal.fire({
                    text: 'Kapal belum dipilih',
                    icon: 'warning',
                    showConfirmButton: true
                });

                return;
            }
            if (index <= 0) {
                Swal.fire({
                    text: 'Kru belum diisi',
                    icon: 'warning',
                    showConfirmButton: true
                });

                return;
            }
        } else {
            if (!$(`#step-${step} input`).get(0).files.length) {
                Swal.fire({
                    text: 'Foto belum diisi',
                    icon: 'warning',
                    showConfirmButton: true
                });

                return;
            }

            if (!$(`#step-${step} textarea`).val()) {
                Swal.fire({
                    text: 'Keterangan belum diisi',
                    icon: 'warning',
                    showConfirmButton: true
                });

                return;
            }
        }

        $('.step').hide();
        step++;
        $(`#step-${step}`).show();

        if (step > 1) {
            $('#prev-btn').show();
        }

        if (step == 8) {
            $('#next-btn').hide();
            $('#submit-btn').show();
        } else {
            $('#next-btn').show();
            $('#submit-btn').hide();
        }
    });

    function removeCrew(deleteIndex) {
        $('#list-crew').children().eq(deleteIndex).remove();
        index--;
    }

    $('#add-btn').click(function () {
        if ($('#crew').val() && $('#position').val()) {
            $('#list-crew').prepend(`
                    <div class="d-flex justify-content-between align-items-center bg-light rounded py-3 px-4 mb-3 border">
                        <div>
                            <h3>${$('#crew').val()}</h3>
                            <p class="m-0">${$('#position').val()}</p>
                        </div>
                        <i onclick="removeCrew(${index})" class="fa fa-times"></i>
                    </div>

                    <input type="hidden" name="crew[${index}]" value="${$('#crew').val()}"/>
                    <input type="hidden" name="position[${index}]" value="${$('#position').val()}"/>
                `);

            $('#crew').val('');
            $('#position').val('');
            index++;
        }
    });

    $('#submit-btn').click(function () {
        Swal.fire({
            title: 'Konfirmasi Inspeksi Kapal',
            text: "Pastikan data inspeksi kapal telah sesuai",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loading-screen').show();
                $('form').submit();
            }
        });
    });

    $('#deck_photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        var maxWidth = 800;
                        var maxHeight = 800;
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#deck_photo')[0].files = dataTransfer.files;
    
                        }, 'image/jpeg', 0.1);
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });

        $('#platform_photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        var maxWidth = 800;
                        var maxHeight = 800;
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#platform_photo')[0].files = dataTransfer.files;
    
                        }, 'image/jpeg', 0.1);
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });


        $('#kitchen_photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        var maxWidth = 800;
                        var maxHeight = 800;
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#kitchen_photo')[0].files = dataTransfer.files;
    
                        }, 'image/jpeg', 0.1);
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });

        $('#meachine_photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        var maxWidth = 800;
                        var maxHeight = 800;
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#meachine_photo')[0].files = dataTransfer.files;
    
                        }, 'image/jpeg', 0.1);
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });

        $('#safety_photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        var maxWidth = 800;
                        var maxHeight = 800;
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#safety_photo')[0].files = dataTransfer.files;
    
                        }, 'image/jpeg', 0.1);
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });

        $('#navigation_photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        var maxWidth = 800;
                        var maxHeight = 800;
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#navigation_photo')[0].files = dataTransfer.files;
    
                        }, 'image/jpeg', 0.1);
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });

        $('#medicine_photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        var maxWidth = 800;
                        var maxHeight = 800;
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#medicine_photo')[0].files = dataTransfer.files;
    
                        }, 'image/jpeg', 0.1);
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });
</script>
@endpush
