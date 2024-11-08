@extends('client.layouts.app')

@section('title', 'Profil')

@section('content')
<main class="p-4">
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
        <!--end::Item-->
    </ul>

    <div class="card rounded mt-5">
        <div class="card-body">
            <h3 class="mb-5">Profil</h3>
            <div class="d-flex gap-3 align-items-center">
                <div class="img-profile rounded-circle" style="width: 50px; height: 50px">
                    <x-acronym text="{{$user->name}}"></x-acronym>
                </div>
                <div>
                    <p class="mb-2 fw-bold fs-4">{{$user->name}}</p>
                    <p class="mb-2 text-secondary">{{$user->email}}</p>
                </div>
            </div>

            <hr>

            <div class="mt-3 mb-5 list-group">
                <a href="{{route('profile.photo')}}" class="card mb-2 text-decoration-none p-3">
                    <div class=" d-flex align-items-center gap-3 fw-bold text-dark ">
                        <i class="fa fa-camera" aria-hidden="true"></i>
                        <span>Foto Absensi</span>
                    </div>
                </a>
            </div>

            <button class="mt-5 d-flex gap-x align-items-center btn btn-danger w-100">
                <i class="fa fa-sign-out"></i>
                <span>Keluar</span>
            </button>
        </div>
    </div>
</main>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{route('logout')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Keluar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection