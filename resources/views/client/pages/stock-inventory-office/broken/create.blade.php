@extends('client.layouts.app')

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

    <h2 class="anchor mb-5 mt-3">Tambah Laporan Kerusakan </h2>

    <section class="card shadow-sm rounded-2xl">
        <h3 class=" border-1 border-bottom p-4">Form Laporan Kerusakan</h3>

        <form class="p-4">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="required form-label">Kapal</label>
                <input type="text" class="form-control  border" placeholder="Example input"
                    disabled />
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="required form-label">Tanggal</label>
                <input type="date" class="form-control  border" placeholder="Example input"
                    disabled />
            </div>

            <h4 class="py-3">Kerusakan</h4>

            <div class="mb-3">
                <label for="exampleFormControlInput1" class="required form-label">Bagian</label>
                <select class="form-select border" aria-label="Select example">
                    <option value="1">Mesin</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="required form-label">Objek Kerusakan</label>
                <select class="form-select border" aria-label="Select example" disabled>
                    <option value="1">Boshpump</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="required form-label">Uraian Kerusakan</label>
                <input type="date" class="form-control  border" placeholder="Example input" />
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="required form-label">Penyebab Kerusakan</label>
                <input type="date" class="form-control  border" placeholder="Example input" />
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="required form-label">Upload Foto</label>
                <input type="file" class="form-control  border" placeholder="Example input" />
                <div
                    class="mt-3 d-flex justify-content-between px-3 align-items-center form-control  border">
                    <i class="bi bi-image text-secondary"></i>
                    <span class="text-secondary w-75">Img_234234.jpg</span>
                    <i class="bi bi-x-lg text-secondary"></i>
                </div>
            </div>
            <a href="#" class="btn btn-light-success px-3 py-2 m-0">
                <i class="bi bi-plus fs-3"></i>
                <span>
                    Tambah Kerusakan
                </span>
            </a>
        </form>

        <div class="px-2">
            <div class="px-4 mx-4 border rounded-2xl">
                <h4 class="py-3">Objek Kerusakan</h4>
                <div class="text-secondary fw-lighter">
                    <h5 class="border-1 border-bottom border-secondary pb-3 mb-3 fw-normal">Bagian : <span class="fw-light">Dek</span></h5>
                    <h5 class="fw-normal">Uraian Kerusakan:</h5>
                    <h5 class="fw-light border-bottom border-secondary pb-3 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione minus pariatur dolores dignissimos rerum suscipit ea nostrum officia porro commodi.</h5>
                    <h5 class="fw-normal">Penyebab Kerusakan:</h5>
                    <h5 class="fw-light">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione minus pariatur dolores dignissimos rerum suscipit ea nostrum officia porro commodi.</h5>

                    <a href="#" class="btn btn-bg-secondary px-4 py-2 my-3">Hapus</a>
                </div>
            </div>
        </div>

        <a href="#" class="btn btn-primary mx-4 my-4">Kirim Kerusakan</a>
    </section>
</main>
