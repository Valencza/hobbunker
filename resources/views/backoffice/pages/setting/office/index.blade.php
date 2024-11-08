@extends('backoffice.layouts.app')

@section('title', 'Pengaturan Kantor')

@section('content')
<div class="content flex-row-fluid" id="kt_content">
    {{-- breadcrumb --}}
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">
            <div class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </div>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-500">Pengaturan</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Kantor</h3>

    <section class="card px-3">
        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th class="text-center">Nama</th>
                    <th class="text-center">Singkatan</th>
                    <th class="text-center">Alamat</th>
                    <th class="text-center">Latidue</th>
                    <th class="text-center">Longitude</th>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{$settingOffice->name}}</td>
                    <td class="text-center">{{$settingOffice->shortname}}</td>
                    <td class="text-center">{{$settingOffice->address}}</td>
                    <td class="text-center">{{$settingOffice->latitude}}</td>
                    <td class="text-center">{{$settingOffice->longitude}}</td>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <td class="text-center">
                        <button class="btn btn-icon rounded-circle btn-light-warning" data-bs-toggle="modal"
                            data-bs-target="#editSettingOfficeModal-{{$settingOffice->uuid}}">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                    @endif
                </tr>

                <div class="modal fade" id="editSettingOfficeModal-{{$settingOffice->uuid}}" tabindex="-1"
                    aria-labelledby="editSettingOfficeModalLabel-{{$settingOffice->uuid}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form class="modal-content" action="{{route('setting-office.update')}}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSettingOfficeModalLabel-{{$settingOffice->uuid}}">Ubah Satuan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        @error('name')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Masukkan nama" value="{{old('name', $settingOffice->name)}}"
                                            required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="shortname" class="form-label">Singkatan</label>
                                        @error('shortname')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="text" name="shortname" id="shortname" class="form-control"
                                            placeholder="Masukkan singkatan"
                                            value="{{old('shortname', $settingOffice->shortname)}}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="address" class="form-label">Alamat</label>
                                        @error('address')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="text" name="address" id="address" class="form-control"
                                            placeholder="Masukkan alamat"
                                            value="{{old('address', $settingOffice->address)}}" required>
                                    </div>
                                  <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        @error('latitude')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="number" name="latitude" id="latitude" class="form-control"
                                            placeholder="Masukkan latitude"
                                            value="{{old('latitude', $settingOffice->latitude)}}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="latitude" class="form-label">Longitude</label>
                                        @error('longitude')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="number" name="longitude" id="longitude" class="form-control"
                                            placeholder="Masukkan longitude"
                                            value="{{old('longitude', $settingOffice->longitude)}}" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </tbody>
        </table>
    </section>
</div>
@endsection
