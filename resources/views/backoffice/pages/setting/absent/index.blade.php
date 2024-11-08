@extends('backoffice.layouts.app')

@section('title', 'Pengaturan Absensi')

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

    <h3 class="pb-5">Absensi</h3>

    <section class="card px-3">
        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th class="text-center">Jam Masuk</th>
                    <th class="text-center">Batas Jam Masuk</th>
                    <th class="text-center">Jam Pulang</th>
                    <th class="text-center">Batas Jam Pulang</th>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{$settingAbsent->clock_in}}</td>
                    <td class="text-center">{{$settingAbsent->clock_in_limit}}</td>
                    <td class="text-center">{{$settingAbsent->clock_out}}</td>
                    <td class="text-center">{{$settingAbsent->clock_out_limit}}</td>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <td class="text-center">
                        <button class="btn btn-icon rounded-circle btn-light-warning" data-bs-toggle="modal"
                            data-bs-target="#editSettingAbsentModal-{{$settingAbsent->uuid}}">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                    @endif
                </tr>

                <div class="modal fade" id="editSettingAbsentModal-{{$settingAbsent->uuid}}" tabindex="-1"
                    aria-labelledby="editSettingAbsentModalLabel-{{$settingAbsent->uuid}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form class="modal-content" action="{{route('setting-absent.update')}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSettingAbsentModalLabel-{{$settingAbsent->uuid}}">
                                        Ubah Satuan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="form-group col-md-6">
                                            <label for="clock_in" class="form-label">Jam Masuk</label>
                                            @error('clock_in')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input type="time" name="clock_in" id="clock_in" class="form-control"
                                                placeholder="Masukkan jam masuk"
                                                value="{{old('clock_in', $settingAbsent->clock_in)}}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="clock_in_limit" class="form-label">Batas Jam Masuk</label>
                                            @error('clock_in_limit')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input type="time" name="clock_in_limit" id="clock_in_limit"
                                                class="form-control" placeholder="Masukkan batas jam masuk"
                                                value="{{old('clock_in_limit', $settingAbsent->clock_in_limit)}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="clock_out" class="form-label">Jam Pulang</label>
                                            @error('clock_out')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input type="time" name="clock_out" id="clock_out" class="form-control"
                                                placeholder="Masukkan jam pulang"
                                                value="{{old('clock_out', $settingAbsent->clock_out)}}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="clock_out_limit" class="form-label">Batas Jam Pulang</label>
                                            @error('clock_out_limit')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input type="time" name="clock_out_limit" id="clock_out_limit"
                                                class="form-control" placeholder="Masukkan batas jam pulang"
                                                value="{{old('clock_out_limit', $settingAbsent->clock_out)}}" required>
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
