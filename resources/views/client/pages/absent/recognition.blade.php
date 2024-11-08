@extends('client.layouts.app')

@section('title', 'Scan Foto')

@push('styles')
<style>
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
        width: 300px;
        height: 300px;
        border-radius: 10px;
        overflow: hidden;
        border: 5px solid transparent;
    }

    .video-frame {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        object-fit: cover;
    }

    section {
        display: grid;
        place-items: center;
    }

</style>
@endpush

@section('content')
<main class="p-4" style="height: 100vh">
    <div id="reader" style="width: 90vw; height: 10vh; margin: auto; padding: 0;"></div>

    <div id="message" style="position: absolute; bottom: 5vh; width: 90vw; margin: auto"
        class="alert alert-warning text-center">
        <p class="m-0">Harap tunggu!</p>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{asset('face-api.min.js')}}"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    let video = false;
    let scanner = null;
    let request = false;

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

    async function recognizeFace() {
        setTimeout( async () => {
            $('#message > p').text('Hadap kamera!');
            console.log("Video playing");
            await loadLabeledImages();
        }, 5000);
    }

    function convertImageToBase64(img) {
        return new Promise((resolve, reject) => {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            resolve(canvas.toDataURL('image/jpeg'));
        });
    }

    async function loadLabeledImages() {
        const labels = ['{{$user->email}}']; // Assuming you want to use the user's email as the label
        let img1 = await captureCurrentFrame(); // Function to capture the current frame from the video

        return Promise.all(
            labels.map(async label => {
                const descriptions = [];
                for (let i = 1; i <= 20; i++) {
                    let img2 = `/labeled_images/${label}/${i}.jpg`;
                    let img2Loaded = await loadImage(img2);
                    let base64Img2 = await convertImageToBase64(img2Loaded);
                    compareImages(img1, base64Img2);
                }
            })
        );
    }

    function captureCurrentFrame() {
        return new Promise((resolve, reject) => {
            let video = document.querySelector('video');
            if (!video || video.readyState !== 4) {
                reject('Video not ready');
                return;
            }
            let canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            let context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            resolve(canvas.toDataURL('image/jpeg'));
        });
    }

    async function loadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = src;
        });
    }

    function compareImages(image1Base64, image2Base64) {
        let similarityPercentage = compareBase64Images(image1Base64, image2Base64);
        console.log("Similarity Percentage: " + similarityPercentage + "%");

        if (similarityPercentage > 1.3) {

            if (!request) {
                request = true;

                $.ajax({
                    type: "POST",
                    url: "{{route('absent.process')}}",
                    data: {
                        absent_status: localStorage.getItem('absent_status'),
                        absent_latitude: localStorage.getItem('absent_latitude'),
                        absent_longitude: localStorage.getItem('absent_longitude'),
                        absent_radius: localStorage.getItem('absent_radius'),
                        absent_status_radius: localStorage.getItem('absent_status_radius'),
                        absent_location: localStorage.getItem('absent_location'),
                        absent_detail_job: localStorage.getItem('absent_detail_job'),
                        absent_clock: localStorage.getItem('absent_clock'),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success) {
                            $('#message > p').text('Absensi berhasil!')
                            Swal.fire({
                                title: 'Berhasil',
                                icon: 'success',
                                showConfirmButton: false
                            });

                            window.location.href = "{{route('absent.history')}}";
                        }
                        request = false; // Reset request status after success
                    },
                    error: function (error) {
                        console.error("Error during check-in", error);
                        request = false; // Reset request status after error
                    }
                });
            }

        }
    }

    function compareBase64Images(base64Image1, base64Image2) {
        let binaryImage1 = atob(base64Image1.split(',')[1]);
        let binaryImage2 = atob(base64Image2.split(',')[1]);

        let arrayBuffer1 = new ArrayBuffer(binaryImage1.length);
        let arrayBuffer2 = new ArrayBuffer(binaryImage2.length);
        let view1 = new Uint8Array(arrayBuffer1);
        let view2 = new Uint8Array(arrayBuffer2);

        for (let i = 0; i < binaryImage1.length; i++) {
            view1[i] = binaryImage1.charCodeAt(i);
        }

        for (let j = 0; j < binaryImage2.length; j++) {
            view2[j] = binaryImage2.charCodeAt(j);
        }

        let similarity = calculateSimilarityPercentage(view1, view2);
        return similarity;
    }

    function calculateSimilarityPercentage(array1, array2) {
        let similarityCount = 0;

        for (let i = 0; i < array1.length; i++) {
            if (array1[i] === array2[i]) {
                similarityCount++;
            }
        }

        let similarityPercentage = (similarityCount / array1.length) * 100;
        return similarityPercentage.toFixed(2); // Return percentage rounded to 2 decimal places
    }

    let intervalId = setInterval(() => {
        video = document.getElementsByTagName('video')[0];

        if (video) {
            recognizeFace();
            clearInterval(intervalId);
        }
    }, 1000);

</script>

@endpush
