@extends('client.layouts.app')

@section('title', 'Foto Absensi')

@push('styles')
<style>
    #html5-qrcode-button-camera-stop {
        --bs-btn-color: #fff;
        --bs-btn-bg: #0d6efd;
        --bs-btn-border-color: #0d6efd;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #0b5ed7;
        --bs-btn-hover-border-color: #0a58ca;
        --bs-btn-focus-shadow-rgb: 49, 132, 253;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: #0a58ca;
        --bs-btn-active-border-color: #0a53be;
        --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        --bs-btn-disabled-color: #fff;
        --bs-btn-disabled-bg: #0d6efd;
        --bs-btn-disabled-border-color: #0d6efd;
    }

    #reader {
        width: 500px;
        height: 500px;
    }

    .border-animation {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 5px solid transparent;
        border-radius: 10px;
        animation: animateBorder 5s linear infinite;
    }

    @keyframes animateBorder {
        0% {
            border-color: #ff80ab;
        }

        100% {
            border-color: #80d8ff;
        }
    }

    .video-container {
        margin: auto;
        display: inline-block;
        position: relative;
        width: 100vw;
        height: 200px;
        border-radius: 10px;
        overflow: hidden;
        border: 5px solid transparent;
    }

    .video-frame {
        width: 100vw;
        height: 200px;
        border-radius: 10px;
        object-fit: cover;
        /* Maintain aspect ratio and cover the container */
    }

    section {
        display: grid;
        place-items: center;
    }

</style>
@endpush

@section('content')
<main class="p-4" style="height: 100vh">
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1 p-4">
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
            <a href="{{route('profile')}}" class="text-secondary text-decoration-none">Profil</a>
        </li>
        <!--end::Item-->
    </ul>

    <div id="reader" style="width: 90vw; height: 10vh; margin: auto; padding: 0;"></div>

    <button id="startButton" style="position: absolute; bottom: 5vh; width: 90vw; margin: auto" class="btn btn-primary">
        <span>Mulai</span>
    </button>
</main>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let video = document.getElementsByTagName('video')[0];
    const startButton = $('#startButton');
    const uploadForm = $('#uploadForm');
    const borderAnimation = $('.border-animation');
    let canvas, context, imageBlob;
    let images = [];
    let scanner;

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

    startButton.click(() => {
        video = document.getElementsByTagName('video')[0];

        let timer = 20
        startButton.attr('disabled', 'true');
        startButton.html(`<span>${timer}</spa>`);

        borderAnimation.css('animation', 'animateBorder 1s linear infinite');

        let interval = setInterval(() => {
            if (timer <= 1) {
                startButton.html(`
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    <span>Penyesuaian</span>
                `);
            } else {
                startButton.html(`<span>${timer}</spa>`);
            }

            if (images.length >= 20) {
                clearInterval(interval);
                uploadImages();
                return;
            }
            captureImage();

            timer -= 1;
        }, 1000);
    });

    function captureImage() {
        video = document.getElementsByTagName('video')[0];

        canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(blob => {
            images.push(blob);
        }, 'image/jpeg');
    }

    async function uploadImages() {
        try {
            await Promise.all(images.map(async (image, index) => {
                let formData = new FormData();
                formData.append('images[]', image, `photo${index}.jpg`);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('number', index + 1);

                const response = await fetch("{{route('profile.upload')}}", {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Failed to upload image ' + index);
                }

                return response.json();
            }));

            Swal.fire({
                title: 'Berhasil',
                icon: 'success',
                showConfirmButton: false
            });

            window.location.href = "{{route('profile')}}";
        } catch (error) {
            console.error('Error uploading images:', error);
            // Handle error, show appropriate message to the user
            Swal.fire({
                title: 'Error',
                text: 'Failed to upload images',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }

</script>
@endpush
