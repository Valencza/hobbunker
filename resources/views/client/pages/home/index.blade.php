@extends('client.layouts.app')

@section('title', 'Home')

@php
$role = $user->masterRole->slug;
$subRole = $user->sub_role;
@endphp

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

    <h2 class="anchor mb-5 mt-3">Dashboard</h2>

    @if ($role == 'karyawan-inventaris-kapal')
    <div class="card border-1 mt-5 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-4 px-3 mb-3"
    style="background-color: #FFF8DD;border-color: #E8B70C; margin-top: -30px">
    <div class="d-flex">
        <svg width="39" height="39" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M34.189 15.8742C33.5948 15.2542 32.9809 14.6143 32.7341 14.0187C32.5056 13.4657 32.4934 12.6476 32.4797 11.7823C32.4584 10.3533 32.434 8.73539 31.3493 7.6507C30.2646 6.56602 28.6406 6.54164 27.2177 6.52031C26.3524 6.5066 25.542 6.49441 24.9813 6.2659C24.3857 6.0191 23.7458 5.40516 23.1258 4.81102C22.1142 3.84211 20.9686 2.74219 19.5 2.74219C18.0314 2.74219 16.8858 3.84211 15.8742 4.81102C15.2542 5.40516 14.6143 6.0191 14.0187 6.2659C13.4657 6.49441 12.6476 6.5066 11.7823 6.52031C10.3533 6.54164 8.73539 6.56602 7.6507 7.6507C6.56602 8.73539 6.54164 10.3594 6.52031 11.7823C6.5066 12.6476 6.49441 13.458 6.2659 14.0187C6.0191 14.6143 5.40516 15.2542 4.81102 15.8742C3.84211 16.8858 2.74219 18.0314 2.74219 19.5C2.74219 20.9686 3.84211 22.1142 4.81102 23.1258C5.40516 23.7458 6.0191 24.3857 6.2659 24.9813C6.49441 25.5343 6.5066 26.3524 6.52031 27.2177C6.54164 28.6467 6.56602 30.2646 7.6507 31.3493C8.73539 32.434 10.3594 32.4584 11.7823 32.4797C12.6476 32.4934 13.458 32.5056 14.0187 32.7341C14.6143 32.9809 15.2542 33.5948 15.8742 34.189C16.8858 35.1579 18.0314 36.2578 19.5 36.2578C20.9686 36.2578 22.1142 35.1579 23.1258 34.189C23.7458 33.5948 24.3857 32.9809 24.9813 32.7341C25.5343 32.5056 26.3524 32.4934 27.2177 32.4797C28.6467 32.4584 30.2646 32.434 31.3493 31.3493C32.434 30.2646 32.4584 28.6467 32.4797 27.2177C32.4934 26.3524 32.5056 25.542 32.7341 24.9813C32.9809 24.3857 33.5948 23.7458 34.189 23.1258C35.1579 22.1142 36.2578 20.9686 36.2578 19.5C36.2578 18.0314 35.1579 16.8858 34.189 15.8742ZM32.8697 21.8613C32.1582 22.6017 31.4224 23.3695 31.0416 24.2836C30.679 25.158 30.6637 26.1909 30.6485 27.1903C30.6318 28.3237 30.6135 29.4968 30.0544 30.0574C29.4953 30.618 28.3207 30.6348 27.1873 30.6516C26.1879 30.6668 25.155 30.682 24.2805 31.0446C23.3665 31.4224 22.6048 32.1582 21.8568 32.8727C21.057 33.6345 20.2297 34.4327 19.497 34.4327C18.7642 34.4327 17.937 33.639 17.1356 32.8727C16.3952 32.1613 15.6274 31.4255 14.7134 31.0446C13.8389 30.682 12.806 30.6668 11.8066 30.6516C10.6732 30.6348 9.50016 30.6165 8.93953 30.0574C8.37891 29.4983 8.36215 28.3237 8.34539 27.1903C8.33016 26.1909 8.31492 25.158 7.95234 24.2836C7.57453 23.3695 6.83871 22.6078 6.12422 21.8598C5.36402 21.06 4.57031 20.2328 4.57031 19.5C4.57031 18.7672 5.36402 17.94 6.13031 17.1387C6.84176 16.3983 7.57758 15.6305 7.95844 14.7164C8.32102 13.842 8.33625 12.8091 8.35148 11.8097C8.36824 10.6762 8.38652 9.5032 8.94563 8.94258C9.50473 8.38195 10.6793 8.3652 11.8127 8.34844C12.8121 8.3332 13.845 8.31797 14.7195 7.95539C15.6335 7.57758 16.3952 6.84176 17.1432 6.12727C17.94 5.36402 18.7672 4.57031 19.5 4.57031C20.2328 4.57031 21.06 5.36402 21.8613 6.13031C22.6017 6.84176 23.3695 7.57758 24.2836 7.95844C25.158 8.32102 26.1909 8.33625 27.1903 8.35148C28.3237 8.36824 29.4968 8.38652 30.0574 8.94563C30.618 9.50473 30.6348 10.6793 30.6516 11.8127C30.6668 12.8121 30.682 13.845 31.0446 14.7195C31.4224 15.6335 32.1582 16.3952 32.8727 17.1432C33.6345 17.943 34.4327 18.7703 34.4327 19.503C34.4327 20.2358 33.636 21.06 32.8697 21.8613ZM18.5859 20.7188V12.1875C18.5859 11.9451 18.6822 11.7126 18.8537 11.5412C19.0251 11.3697 19.2576 11.2734 19.5 11.2734C19.7424 11.2734 19.9749 11.3697 20.1463 11.5412C20.3178 11.7126 20.4141 11.9451 20.4141 12.1875V20.7188C20.4141 20.9612 20.3178 21.1937 20.1463 21.3651C19.9749 21.5365 19.7424 21.6328 19.5 21.6328C19.2576 21.6328 19.0251 21.5365 18.8537 21.3651C18.6822 21.1937 18.5859 20.9612 18.5859 20.7188ZM21.0234 26.2031C21.0234 26.5044 20.9341 26.799 20.7667 27.0495C20.5993 27.3 20.3614 27.4953 20.083 27.6106C19.8046 27.7259 19.4983 27.7561 19.2028 27.6973C18.9073 27.6385 18.6358 27.4934 18.4228 27.2804C18.2097 27.0673 18.0646 26.7958 18.0058 26.5003C17.9471 26.2048 17.9772 25.8985 18.0925 25.6201C18.2078 25.3418 18.4031 25.1038 18.6536 24.9364C18.9042 24.769 19.1987 24.6797 19.5 24.6797C19.904 24.6797 20.2915 24.8402 20.5772 25.1259C20.8629 25.4116 21.0234 25.7991 21.0234 26.2031Z"
                fill="#E8B70C" />
        </svg>
        <div class="d-flex flex-column mx-3 w-50">
            <span class="fw-bold p-0">
                Input Pemakaian BBM
            </span>
            <p class="text-muted m-0">Laporkan Jam Mesin
                Pemakaian BBM Harian</p>
        </div>
    </div>
    
    <a class="btn btn-dark"  href="{{route('bbm')}}">Buat Laporan</a>
</div>   
    @endif
  

    @if ($countMasterAnnouncements > 0)
    <section class="card rounded-2xl shadow-sm mt-5">
        <div class="d-flex w-100 justify-content-between px-4 py-3 align-items-center">
            <h5 class="card-title">Pengumuman</h5>
            @if ($countMasterAnnouncements > 5)
            <div class="card-toolbar">
                <!--begin::Link-->
                <a href="{{route('announcement.index')}}"
                    class="mt-3 link-primary fs-6 fw-bolder text-decoration-none">Lihat semua</a>
                <!--end::Link-->
            </div>
            @endif
        </div>


        <div class="card-body card-scroll pt-0">
            <div id="kt_carousel_1_carousel"
                class="card shadow-sm rounded-2xl border-gray-300 carousel carousel-custom slide border-2 border-gray-200 position-relative"
                data-bs-ride="carousel" data-bs-interval="8000">
                <!--begin::Heading-->
                <div
                    class="d-flex align-items-center justify-content-end flex-wrap position-absolute top-0 end-0 pt-2 pe-2 z-index-3">
                    <ol class="p-0 m-0 carousel-indicators carousel-indicators-dots float-end">
                        <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="0" class="ms-1 active"></li>
                        <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="1" class="ms-1"></li>
                        <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="2" class="ms-1"></li>
                    </ol>
                </div>
                <!--end::Heading-->

                <!--begin::Carousel-->
                <div class="carousel-inner p-0 position-relative">
                    @foreach ($masterAnnouncements as $index => $item)
                    <!--begin::Item-->
                    <div class="carousel-item {{$index == 0 ? 'active' : ''}} ps-4 pt-3">
                        <h1 class="w-75 text-sm">
                            {{strlen($item->title)>20?substr($item->title, 0, 20).'...':$item->title}}</h1>
                        <div class="d-flex gap-2 class fw-bold mt-3">
                            <i class="bi bi-calendar2-week-fill mt-1"></i>
                            <p>
                                {{$item->formatted_start_date}}
                                @if ($item->formatted_start_date != $item->formatted_end_date)
                                -
                                {{$item->formatted_end_date}}
                                @endif
                            </p>
                        </div>
                        <a href="{{route('announcement.detail', $item->uuid)}}"
                            class="btn bg-blue-400 text-white my-7">Detail</a>
                        <img src="{{asset('assets/media/illustrations/jadwalLiburan.png')}}"
                            class="position-absolute bottom-0 end-0" alt="">
                    </div>
                    <!--end::Item-->
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif


    <section class="card rounded-2xl p-3 mt-10 card-progress">
        <div class="d-flex align-items-center w-full px-3 py-4  flex-column mt-3">
            <div class="d-flex justify-content-between w-100 mt-auto">
                <span class="fw-bold fs-5 text-gray-400">Persentase Absensi Anda</span>
            </div>
            <div class="d-flex justify-content-end w-100 mb-4">
                <span class="fw-bolder fs-6 text-white">{{$percentage}}%</span>
            </div>
            <div class="h-10px mx-3 w-100 bg-light rounded mb-3">
                <div class="bg-primary rounded h-10px" role="progressbar" style="width: {{$percentage}}%;"
                    aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </section>

    <section class="mt-10 row px-2 justify-content-center">
    @if (in_array($role, [
            'superadmin',
            'direksi',
            'hrd',
            'teknis',
            'kepala-divisi'
        ]))
        @php
        $route = match($role) {
        'superadmin' => route('master-user'),
        'direksi' => route('master-user'),
        'hrd' => route('absent-report'),
        'kepala-divisi' => route('absent-report'),
        default => route('stock-report')
        };
        @endphp
        <a href="{{route('absent')}}" class="col-6 col-md-3 text-decoration-none">
        <div class="card my-2 text-center p-3">
            <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_absen.webp')}}"
                class="mx-auto mb-2">
            <h5 class="text-sm" style="height: 50px">Absensi</h5>
        </div>
    </a>
    <a href="{{route('leave')}}" class="col-6 col-md-3 text-decoration-none">
        <div class="card my-2 text-center text-decoration-none p-3">
            <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_cuti.webp')}}"
                class="mx-auto mb-2">
            <h5 class="text-sm" style="height: 50px">Pengajuan Cuti</h5>
        </div>
    </a>
    @endif
  
        @if ($role == 'kepala-divisi')
        <a href="{{route('leave.list')}}" class="col-6 col-md-3 text-decoration-none">
            <div class="card my-2 text-center text-decoration-none p-3">
                <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_approval_cuti.webp')}}"
                    class="mx-auto mb-2">
                <h5 class="text-sm" style="height: 50px">Persetujuan Cuti</h5>
            </div>
        </a>
        @endif
        @if (in_array($role, [
            'superadmin',
            'direksi',
            'hrd',
            'karyawan-inventaris-kantor',
            'teknis',
            'kepala-divisi'
        ]))
        @php
        $route = match($role) {
        'superadmin' => route('master-user'),
        'direksi' => route('master-user'),
        'hrd' => route('absent-report'),
        'kepala-divisi' => route('absent-report'),
        'karyawan-inventaris-kantor' => route('stock-report'),
        default => route('stock-report')
        };
        @endphp
        <a id="dashboard-button" href="{{ $route }}" class="col-6 col-md-3 text-decoration-none">
            <div class="card my-2 text-center p-3">
                <img width="100" height="100" src="{{ asset('assets/media/illustrations/icon_dashboard.webp') }}"
                    class="mx-auto mb-2">
                <h5 class="text-sm" style="height: 50px">Dashboard</h5>
            </div>
        </a>
        @endif

        @if ($role == 'karyawan-inventaris-kantor')
        <a href="{{route('stock-inventory-office')}}" class="col-6 col-md-3 text-decoration-none">
            <div class="card my-2 text-center text-decoration-none p-3">
                <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_kantor.webp')}}"
                    class="mx-auto mb-2">
                <h5 class="text-sm" style="height: 50px">Stok & Inventaris Kantor</h5>
            </div>
        </a>
        <a href="{{route('inspection')}}" class="col-6 col-md-3 text-decoration-none">
            <div class="card my-2 text-center text-decoration-none p-3">
                <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_inspeksi_kapal.webp')}}"
                    class="mx-auto mb-2">
                <h5 class="text-sm" style="height: 50px">Inspeksi Kapal</h5>
            </div>
        </a>
        @endif
        @if ($role == 'karyawan-inventaris-kapal')
        @if ($role == 'karyawan-inventaris-kapal')
        <a href="{{route('stock-inventory-ship.detail', Auth::user()->master_ship_uuid)}}"
            class="col-6 col-md-3 text-decoration-none">
            @else
            <a href="{{route('stock-inventory-ship')}}" class="col-6 col-md-3 text-decoration-none">
                @endif
                <div class="card my-2 text-center text-decoration-none p-3">
                    <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_kapal.webp')}}"
                        class="mx-auto mb-2">
                    <h5 class="text-sm" style="height: 50px">Stok & Inventaris Kapal</h5>
                </div>
            </a>

            <a href="{{route('oil-change')}}" class="col-6 col-md-3 text-decoration-none">
                <div class="card my-2 text-center text-decoration-none p-3">
                    <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_ganti_oli.webp')}}"
                        class="mx-auto mb-2">
                    <h5 class="text-sm" style="height: 50px">Jadwal Ganti Oli</h5>
                </div>
            </a>

            <a href="{{route('bbm')}}" class="col-6 col-md-3 text-decoration-none">
                <div class="card my-2 text-center text-decoration-none p-3">
                    <img width="100" height="100" src="{{asset('assets/media/illustrations/icon_bbm.webp')}}"
                        class="mx-auto mb-2">
                    <h5 class="text-sm" style="height: 50px">Pemakaian BBM</h5>
                </div>
            </a>
            @endif

            @if (in_array($role, [
                'security',
                'kepala-divisi'
            ]))
            @if (($role == 'kepala-divisi' && $subRole == 'security') || $role == 'security')
            <a href="{{route('road-letter')}}" class="col-6 col-md-3 text-decoration-none">
                <div class="card my-2 text-center text-decoration-none p-3">
                    <img width="100" height="100" src="{{asset('assets/media/illustrations/road_letter.svg')}}"
                        class="mx-auto mb-2">
                    <h5 class="text-sm" style="height: 50px">Surat Jalan</h5>
                </div>
            </a>
            @endif
            @endif
    </section>

</main>
@endsection


@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyCcdm4stqrqdCWyh7hOWjIg49PU-8KXFLY",
        authDomain: "hobbunker-b4824.firebaseapp.com",
        projectId: "hobbunker-b4824",
        storageBucket: "hobbunker-b4824.appspot.com",
        messagingSenderId: "281759530988",
        appId: "1:281759530988:web:27d1a2f004ab2638ec0ac9",
        measurementId: "G-4ZVZ3SKN5B"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
        messaging.requestPermission().then(function () {
            return messaging.getToken()
        }).then(function (token) {

            $.ajax({
                url: "{{ route('fcmToken') }}",
                method: "PATCH",
                data: {
                    _method: "PATCH",
                    token: token
                },
                success: function (data) {
                    console.log('FCM Token', data);
                },
                error: function (jqXHR) {
                    console.error('FCM Token', jqXHR.responseJSON);
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });

        }).catch(function (err) {
            console.log(`Token Error :: ${err}`);
        });
    }

    initFirebaseMessagingRegistration();

    messaging.onMessage(function ({
        data: {
            body,
            title
        }
    }) {
        new Notification(title, {
            body
        });
    });

</script>
@endpush
