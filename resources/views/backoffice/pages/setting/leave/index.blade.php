@extends('backoffice.layouts.app')

@section('title', 'Pengaturan Cuti')

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

    <h3 class="pb-5">Cuti</h3>

    <section class="card px-3">
        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th class="text-center">Saldo Cuti Pertahun</th>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{$settingLeave->balance_year}}</td>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <td class="text-center">
                        <button class="btn btn-icon rounded-circle btn-light-warning" data-bs-toggle="modal"
                            data-bs-target="#editSettingLeaveModal-{{$settingLeave->uuid}}">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                    @endif
                </tr>

                <div class="modal fade" id="editSettingLeaveModal-{{$settingLeave->uuid}}" tabindex="-1"
                    aria-labelledby="editSettingLeaveModalLabel-{{$settingLeave->uuid}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form class="modal-content" action="{{route('setting-leave.update')}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSettingLeaveModalLabel-{{$settingLeave->uuid}}">
                                        Ubah Satuan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="balance_year" class="form-label">Saldo Cuti Pertahun</label>
                                        @error('balance_year')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="number" name="balance_year" id="balance_year" class="form-control"
                                            placeholder="Masukkan saldo cuti"
                                            value="{{old('balance_year', $settingLeave->balance_year)}}" required>
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
