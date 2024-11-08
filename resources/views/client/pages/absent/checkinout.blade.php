@extends('client.layouts.app')

@section('title', 'Absen ' . ($absentToday ? 'Pulang' : 'Masuk'))

@section('content')
<main>
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
            <a href="{{route('absent')}}" class="text-secondary text-decoration-none">Absen</a>
        </li>
        <!--end::Item-->
    </ul>

    <div class="mb-3 mt-3 px-4">
        <h2 class="anchor mb-2">Lokasi</h2>
        <span id="status" class="badge badge-danger"></span>
    </div>

    <div class="position-relative map">
        <div id="map" class="position-absolute top-0 start-0 z-0"></div>
        <form action="" method="POST"
            class="position-absolute start-50 translate-middle-x card bg-white map-form rounded-2xl shadow-sm px-5 py-4">
            @csrf

            <input type="hidden" name="checkin_latitude">
            <input type="hidden" name="checkin_longitude">
            <input type="hidden" name="checkin_radius">
            <input type="hidden" name="checkin_status_radius" value="dalam radius">

            <div class="d-flex justify-content-between border px-3 py-2 rounded-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-calendar-week-fill fs-5 text-black"></i>
                    <span class="fs-6 text-black">{{$today}}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-alarm fs-5 text-black"></i>
                    <span class="fs-5 text-black fw-bold" id="currenct_clock">--:--:--</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mt-4">
                <i class="bi bi-geo-alt-fill fs-5 text-black"></i>
                <span class="fs-5 text-black mt-1">Lokasi Terkini</span>
            </div>
            <textarea type="text" class="form-control  border mt-2" rows="2" name="checkin_location"
                readonly>Kantor</textarea>

            <div class="d-flex align-items-center gap-2 mt-4">
                <i class="bi bi-list fs-5 text-black"></i>
                <div class="fs-5 text-black mt-1">
                    Detail Pekerjaan<span class="text-danger">*</span>
                </div>
            </div>
            <textarea type="text" name="checkin_detail_job" class="form-control  border mt-2 mb-2" rows="4"
                placeholder="Masukkan Detail Pekerjaan"></textarea>

            <p class="mt-4">Ambil Foto</p>
            <div class="d-flex justify-content-between mt-2">
                <div id="upload-photo" class="btn btn-primary me-2 w-100">Upload Foto</div>
                <div id="btn-photo" class="btn btn-primary w-100">Scan Foto</div>
            </div>
        </form>
    </div>

</main>
@endsection

@push('scripts')
<script>
    realtimeClock();

    $('#upload-photo').click(function () {
        if ($('textarea[name="checkin_detail_job"]').val() != '') {
            localStorage.setItem('absent_status', '{{$status}}');
            localStorage.setItem('absent_latitude', $('input[name="checkin_latitude"]').val());
            localStorage.setItem('absent_longitude', $('input[name="checkin_longitude"]').val());
            localStorage.setItem('absent_radius', $('input[name="checkin_radius"]').val());
            localStorage.setItem('absent_status_radius', $('input[name="checkin_status_radius"]').val());
            localStorage.setItem('absent_location', $('textarea[name="checkin_location"]').val());
            localStorage.setItem('absent_detail_job', $('textarea[name="checkin_detail_job"]').val());
            localStorage.setItem('absent_clock', $('#currenct_clock').text());

            window.location.href = "{{ route('absent.upload') }}";
        }
    });

    $('#btn-photo').click(function () {
        if ($('textarea[name="checkin_detail_job"]').val() != '') {
            localStorage.setItem('absent_status', '{{$status}}');
            localStorage.setItem('absent_latitude', $('input[name="checkin_latitude"]').val());
            localStorage.setItem('absent_longitude', $('input[name="checkin_longitude"]').val());
            localStorage.setItem('absent_radius', $('input[name="checkin_radius"]').val());
            localStorage.setItem('absent_status_radius', $('input[name="checkin_status_radius"]').val());
            localStorage.setItem('absent_location', $('textarea[name="checkin_location"]').val());
            localStorage.setItem('absent_detail_job', $('textarea[name="checkin_detail_job"]').val());
            localStorage.setItem('absent_clock', $('#currenct_clock').text());

            window.location.href = "{{ route('absent.recognition') }}";
        }
    });

    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the earth in km
        const dLat = deg2rad(lat2 - lat1); // deg2rad below
        const dLon = deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distanceInKm = R * c; // Distance in km
        return distanceInKm;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }


    if (navigator.geolocation) {
        $('#status').hide();

        navigator.geolocation.getCurrentPosition(
            function (position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                $('input[name="checkin_latitude"]').val(latitude);
                $('input[name="checkin_longitude"]').val(longitude);
                $('input[name="checkin_detail_job"]').val(longitude);

                const distance = Math.round(getDistanceFromLatLonInKm(
                    latitude,
                    longitude,
                    "{{$settingAbsent->latitude}}",
                    "{{$settingAbsent->longitude}}",
                ));

                $('input[name="checkin_radius"]').val(distance);

                if (distance <= 10000) {
                    $('textarea[name="checkin_location"]').val('Kantor');
                    $('input[name="checkin_status_radius"]').val('dalam radius');
                } else {
                    $('textarea[name="checkin_location"]').val('Diluar kantor');
                    $('input[name="checkin_status_radius"]').val('diluar radius');
                }

                $.get('https://nominatim.openstreetmap.org/reverse', {
                    format: 'json',
                    lat: latitude,
                    lon: longitude,
                    zoom: 18 // Zoom level
                }, function (data) {
                    // Parse the response and extract the street name
                    $('textarea[name="checkin_location"]').val(data.display_name);

                    if (
                        data.display_name.includes("Jalan Ikan Mungsing") ||
                        data.display_name.includes("Johor") || 
                        $('textarea[name="checkin_location"]').val() == 'Kantor'
                    ) {
                        $('input[name="checkin_status_radius"]').val('dalam radius');
                    } else {
                        $('input[name="checkin_status_radius"]').val('diluar radius');
                    }
                });

                var map = L.map('map').setView([latitude, longitude], 80);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([latitude, longitude]).addTo(map);
            },
            function (error) {
                $('#status').show();

                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        $('#status').text(
                            "Permission denied. Please enable location services in your browser settings."
                        );
                        break;
                    case error.POSITION_UNAVAILABLE:
                        $('#status').text("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        $('#status').text("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        $('#status').text("An unknown error occurred.");
                        break;
                }
            }
        );
    } else {
        $('#status').text("Geolocation is not supported by this browser.");
    }

</script>
@endpush
