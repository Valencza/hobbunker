@extends('backoffice.layouts.app')

@section('title', 'Karyawan')

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
        <li class="breadcrumb-item text-gray-500">Master</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Karyawan</h3>

    <section class="card">
        <div class="p-5 border-bottom border-secondary d-flex justify-content-between align-items-center">
            <h3>Daftar Karyawan</h3>
            <a href="{{ route('master-user.pdf', ['search' => $search, 'startDate' => $startDate ?? '', 'endDate' => $endDate ?? '']) }}" class="btn btn-light-success fw-bolder">
            <i class="bi bi-upload text-primary fs-4 ml-3 fw-bolder"></i>
                Export Karyawan 
            </a>
        </div>

        <div class="p-3 d-flex align-items-center justify-content-between">
            <div class="w-25">
                {{-- search form --}}
                <form data-kt-search-element="form" class="w-100 position-relative my-4" autocomplete="off">
                    <!--begin::Hidden input(Added to disable form autocomplete)-->
                    <input type="hidden" />
                    <!--end::Hidden input-->
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span
                        class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 ms-3 translate-middle-y">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Input-->
                    <input autocomplete="off" type="text" class="form-control form-control-lg  px-15" name="search"
                        value="{{$search}}" placeholder="Cari..." data-kt-search-element="input" />
                    <!--end::Input-->
                    <!--begin::Spinner-->
                    <span class="position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-5"
                        data-kt-search-element="spinner">
                        <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
                    </span>
                    <!--end::Spinner-->
                    <!--begin::Reset-->
                    <span
                        class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 me-5 d-none"
                        data-kt-search-element="clear">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                    fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </span>
                    <!--end::Reset-->
                </form>
                {{-- end search form --}}
            </div>

            @if (Auth::user()->masterRole->slug == 'superadmin')
            <button data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-primary ms-auto">
                <i class="bi bi-plus fs-3"></i>
                Tambah Karyawan
            </button>
            @endif
        </div>



        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{route('master-user.store')}}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Tambah Karyawan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="master_role_uuid" class="form-label">Jabatan</label>
                                @error('master_role_uuid')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <select name="master_role_uuid" id="master_role_uuid"
                                    class="master_role_uuid form-select" required>
                                    <option disabled {{ old('master_role_uuid') ? '' : 'selected' }}>Pilih Jabatan
                                    </option>
                                    @foreach ($masterRoles as $masterRole)
                                    <option value="{{ $masterRole->uuid }}"
                                        {{ old('master_role_uuid') == $masterRole->uuid ? 'selected' : '' }}>
                                        {{ $masterRole->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sub_role_form form-group mb-3">
                                <label for="sub_role" class="form-label">Bagian</label>
                                @error('sub_role')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <select name="sub_role" id="sub_role"
                                    class="sub_role form-select" required>
                                    <option disabled {{ old('sub_role') ? '' : 'selected' }}>Pilih Bagian
                                    </option>
                                    <option value="hr" {{ old('sub_role') == 'hr' ? 'selected' : '' }}>
                                        HR
                                    </option>
                                    <option value="armada" {{ old('sub_role') == 'armada' ? 'selected' : '' }}>
                                        Armada
                                    </option>
                                    <option value="keuangan" {{ old('sub_role') == 'keuangan' ? 'selected' : '' }}>
                                        Keuangan
                                    </option>
                                    <option value="security" {{ old('sub_role') == 'security' ? 'selected' : '' }}>
                                        Security
                                    </option>
                                </select>
                            </div>
                            <div class="master_user_uuid_form form-group mb-3">
                                <label for="master_user_uuid" class="form-label">Kepala Divisi</label>
                                <select name="master_user_uuid" id="master_user_uuid" class="form-select" required>
                                    <option disabled {{ old('master_user_uuid') ? '' : 'selected' }}>Pilih Kepala Divisi
                                    </option>
                                    @foreach ($masterUserDivisions as $masterUserDivision)
                                    <option value="{{ $masterUserDivision->uuid }}"
                                        {{ old('master_user_uuid') == $masterUserDivision->uuid ? 'selected' : '' }}>
                                        {{ $masterUserDivision->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="position" class="form-label">Posisi</label>
                                @error('position')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <select name="position" id="position" class="position form-select" required>
                                    <option disabled {{ old('position') ? '' : 'selected' }}>Pilih Posisi</option>
                                    <option value="kantor" {{ old('position') == 'kantor' ? 'selected' : '' }}>
                                        Kantor
                                    </option>
                                    <option value="kapal" {{ old('position') == 'kapal' ? 'selected' : '' }}>
                                        Kapal
                                    </option>
                                </select>
                            </div>
                            <div class="master_ship_uuid_form form-group mb-3">
                                <label for="master_ship_uuid" class="form-label">Kapal</label>
                                <select name="master_ship_uuid" id="master_ship_uuid" class="form-select" required>
                                    <option disabled {{ old('master_ship_uuid') ? '' : 'selected' }}>Pilih Kapal
                                    </option>
                                    @foreach ($masterShips as $masterShip)
                                    <option value="{{ $masterShip->uuid }}"
                                        {{ old('master_ship_uuid') == $masterShip->uuid ? 'selected' : '' }}>
                                        {{ $masterShip->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama</label>
                                @error('name')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="text" name="name" id="name" class="form-control"
                                    placeholder="Masukkan nama" value="{{old('name')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nik" class="form-label">Nomor Pegawai</label>
                                @error('nik')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="number" name="nik" id="nik" class="form-control"
                                    placeholder="Masukkan nomor pegawai" value="{{old('nik')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">No. HP</label>
                                @error('phone')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="number" name="phone" id="phone" class="form-control"
                                    placeholder="Masukkan No. HP" value="{{old('phone')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="emergency_phone" class="form-label">No. HP Darurat</label>
                                @error('emergency_phone')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="number" name="emergency_phone" id="emergency_phone"
                                    class="form-control" placeholder="Masukkan No. HP darurat"
                                    value="{{old('emergency_phone')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="dependent" class="form-label">Jumlah Tanggungan</label>
                                @error('dependent')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="number" name="dependent" id="dependent"
                                    class="form-control" placeholder="Masukkan jumlah tanggungan"
                                    value="{{old('dependent')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="salary" class="form-label">Gaji</label>
                                @error('salary')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="number" name="salary" id="salary" class="form-control"
                                    placeholder="Masukkan gaji" value="{{old('salary')}}" required>
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
                                <input autocomplete="off" type="text" name="address" id="address" class="form-control"
                                    placeholder="Masukkan alamat" value="{{old('address')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="birth" class="form-label">Tanggal Lahir</label>
                                @error('birth')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="date" name="birth" id="birth" class="form-control"
                                    value="{{old('birth')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                @error('email')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="email" name="email" id="email" class="form-control"
                                    placeholder="Masukkan email" value="{{old('email')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                @error('password')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input autocomplete="off" type="password" name="password" id="password"
                                    class="form-control" placeholder="Masukkan password" value="{{old('password')}}"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input autocomplete="off" type="password" name="password_confirmation"
                                    id="password_confirmation" class="form-control"
                                    placeholder="Masukkan konfirmasi password" value="{{old('password_confirmation')}}"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="table table-row-dashed table-row-gray-300 gy-7" style="width: 100rem;">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th class="text-center">Nama</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">No. HP</th>
                        <th class="text-center">Gaji</th>
                        <th class="text-center">Email</th>
                        @if (in_array(Auth::user()->masterRole->slug, ['admin', 'hrd']))
                        <th class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($masterUsers as $masterUser)
                    <tr>
                        <td class="text-center">{{$masterUser->name}}</td>
                        <td class="text-center">{{$masterUser->masterRole->name}}</td>
                        <td class="text-center">{{$masterUser->phone}}</td>
                        <td class="text-center">Rp.
                            {{$masterUser->salaryFluctuation ? number_format($masterUser->salaryFluctuation->salary, 0, '.', '.') : 0}}
                        </td>
                        <td class="text-center">{{$masterUser->email}}</td>
                        <td class="text-center">
                            @if (Auth::user()->masterRole->slug == 'superadmin')
                            <button
                                onclick="handleEditChange('{{$masterUser->master_role_uuid}}', '{{$masterUser->position}}')"
                                class="btn btn-icon rounded-circle btn-light-warning" data-bs-toggle="modal"
                                data-bs-target="#editUserModal-{{$masterUser->uuid}}">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button class="btn btn-icon rounded-circle btn-light-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteUserModal-{{$masterUser->uuid}}">
                                <i class="fa fa-trash"></i>
                            </button>
                            @endif
                            @if (in_array(Auth::user()->masterRole->slug, ['superadmin', 'hrd']))
                            <button class="btn btn-icon rounded-circle btn-light-success" data-bs-toggle="modal"
                                data-bs-target="#detailUserModal-{{$masterUser->uuid}}">
                                <i class="fa fa-eye"></i>
                            </button>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="editUserModal-{{$masterUser->uuid}}" tabindex="-1"
                        aria-labelledby="editUserModalLabel-{{$masterUser->uuid}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <form class="modal-content" action="{{route('master-user.update', $masterUser->uuid)}}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel-{{$masterUser->uuid}}">Ubah
                                            Karyawan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="master_role_uuid_{{$masterUser->uuid}}"
                                                class="form-label">Jabatan</label>
                                            @error('master_role_uuid')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <select name="master_role_uuid" id="master_role_uuid_{{$masterUser->uuid}}"
                                                class="master_role_uuid form-select" required>
                                                <option disabled
                                                    {{ old('master_role_uuid', $masterUser->master_role_uuid) ? '' : 'selected' }}>
                                                    Pilih Jabatan</option>
                                                @foreach ($masterRoles as $masterRole)
                                                <option value="{{ $masterRole->uuid }}"
                                                    {{ old('master_role_uuid', $masterUser->master_role_uuid) == $masterRole->uuid ? 'selected' : '' }}>
                                                    {{ $masterRole->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="sub_role_{{$masterUser->uuid}}"
                                                class="form-label">Bagian</label>
                                            @error('sub_role')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <select name="sub_role" id="sub_role_{{$masterUser->uuid}}"
                                                class="sub_role form-select" required>
                                                <option disabled
                                                    {{ old('sub_role', $masterUser->sub_role) ? '' : 'selected' }}>Pilih
                                                    Bagian</option>
                                                <option value="hr"
                                                    {{ old('sub_role', $masterUser->sub_role) == 'hr' ? 'selected' : '' }}>
                                                    HR
                                                </option>
                                                <option value="armada"
                                                    {{ old('sub_role', $masterUser->sub_role) == 'armada' ? 'selected' : '' }}>
                                                    Armada
                                                </option>
                                                <option value="keuangan"
                                                {{ old('sub_role', $masterUser->sub_role) == 'keuangan' ? 'selected' : '' }}>
                                                    Keuangan
                                                </option>
                                                <option value="security"
                                                {{ old('sub_role', $masterUser->sub_role) == 'security' ? 'selected' : '' }}>
                                                    Security
                                                </option>
                                            </select>
                                        </div>
                                        <div class="master_user_uuid_form form-group mb-3">
                                            <label for="master_user_uuid_{{$masterUser->uuid}}"
                                                class="form-label">Kepala Divisi</label>
                                            <select name="master_user_uuid" id="master_user_uuid_{{$masterUser->uuid}}"
                                                class="form-select" required>
                                                <option disabled
                                                    {{ old('master_user_uuid', $masterUser->master_user_uuid) ? '' : 'selected' }}>
                                                    Pilih Kepala Divisi</option>
                                                @foreach ($masterUserDivisions as $masterUserDivision)
                                                <option value="{{ $masterUserDivision->uuid }}"
                                                    {{ old('master_user_uuid', $masterUser->master_user_uuid) == $masterUserDivision->uuid ? 'selected' : '' }}>
                                                    {{ $masterUserDivision->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="position_{{$masterUser->uuid}}"
                                                class="form-label">Posisi</label>
                                            @error('position')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <select name="position" id="position_{{$masterUser->uuid}}"
                                                class="position form-select" required>
                                                <option disabled
                                                    {{ old('position', $masterUser->position) ? '' : 'selected' }}>Pilih
                                                    Posisi</option>
                                                <option value="kantor"
                                                    {{ old('position', $masterUser->position) == 'kantor' ? 'selected' : '' }}>
                                                    Kantor
                                                </option>
                                                <option value="kapal"
                                                    {{ old('position', $masterUser->position) == 'kapal' ? 'selected' : '' }}>
                                                    Kapal
                                                </option>
                                            </select>
                                        </div>
                                        <div class="master_ship_uuid_form form-group mb-3">
                                            <label for="master_ship_uuid_{{$masterUser->uuid}}"
                                                class="form-label">Kapal</label>
                                            <select name="master_ship_uuid" id="master_ship_uuid_{{$masterUser->uuid}}"
                                                class="form-select" required>
                                                <option disabled
                                                    {{ old('master_ship_uuid', $masterUser->master_ship_uuid) ? '' : 'selected' }}>
                                                    Pilih Kapal</option>
                                                @foreach ($masterShips as $masterShip)
                                                <option value="{{ $masterShip->uuid }}"
                                                    {{ old('master_ship_uuid', $masterUser->master_ship_uuid) == $masterShip->uuid ? 'selected' : '' }}>
                                                    {{ $masterShip->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="name_{{$masterUser->uuid}}" class="form-label">Nama</label>
                                            @error('name')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="text" name="name"
                                                id="name_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan nama" value="{{ old('name', $masterUser->name) }}"
                                                required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="nik_{{$masterUser->uuid}}" class="form-label">Nomor
                                                Pegawai</label>
                                            @error('nik')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="number" name="nik"
                                                id="nik_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan nomor pegawai"
                                                value="{{ old('nik', $masterUser->nik) }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="phone_{{$masterUser->uuid}}" class="form-label">No. HP</label>
                                            @error('phone')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="number" name="phone"
                                                id="phone_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan No. HP"
                                                value="{{ old('phone', $masterUser->phone) }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="emergency_phone_{{$masterUser->uuid}}" class="form-label">No. HP
                                                Darurat</label>
                                            @error('emergency_phone')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="number" name="emergency_phone"
                                                id="emergency_phone_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan No. HP darurat"
                                                value="{{ old('emergency_phone', $masterUser->emergency_phone) }}"
                                                required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="dependent_{{$masterUser->uuid}}" class="form-label">Jumlah
                                                Tanggungan</label>
                                            @error('dependent')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="number" name="dependent"
                                                id="dependent_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan jumlah tanggungan"
                                                value="{{ old('dependent', $masterUser->dependent) }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="salary_{{$masterUser->uuid}}" class="form-label">Gaji</label>
                                            @error('salary')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="number" name="salary"
                                                id="salary_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan gaji"
                                                value="{{ old('salary', $masterUser->salaryFluctuation->salary ?? 0) }}"
                                                required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="address_{{$masterUser->uuid}}" class="form-label">Alamat</label>
                                            @error('address')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="text" name="address"
                                                id="address_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan alamat"
                                                value="{{ old('address', $masterUser->address) }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="birth_{{$masterUser->uuid}}" class="form-label">Tanggal
                                                Lahir</label>
                                            @error('birth')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input type="date" name="birth" id="birth_{{$masterUser->uuid}}"
                                                class="form-control"
                                                value="{{ old('birth', date('Y-m-d', strtotime($masterUser->birth))) }}"
                                                required autocomplete="off">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email_{{$masterUser->uuid}}" class="form-label">Email</label>
                                            @error('email')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="email" name="email"
                                                id="email_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan email"
                                                value="{{ old('email', $masterUser->email) }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="password_{{$masterUser->uuid}}"
                                                class="form-label">Password</label>
                                            @error('password')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input autocomplete="off" type="password" name="password"
                                                id="password_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan password">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="password_confirmation_{{$masterUser->uuid}}"
                                                class="form-label">Konfirmasi
                                                Password</label>
                                            <input autocomplete="off" type="password" name="password_confirmation"
                                                id="password_confirmation_{{$masterUser->uuid}}" class="form-control"
                                                placeholder="Masukkan konfirmasi password">
                                        </div>
                                    </div>
                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteUserModal-{{$masterUser->uuid}}" tabindex="-1"
                        aria-labelledby="deleteUserModalLabel-{{$masterUser->uuid}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form class="modal-content" action="{{route('master-user.delete', $masterUser->uuid)}}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteUserModalLabel-{{$masterUser->uuid}}">Hapus
                                            User
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="m-0 text-center">Karyawan akan dihapus</p>
                                    </div>
                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detailUserModal-{{$masterUser->uuid}}" tabindex="-1"
                        aria-labelledby="detailUserModalLabel-{{$masterUser->uuid}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content p-5 ">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <div class=" fs-4 fw-bold">Nomor Pegawai</div>
                                        <div>{{$masterUser->nik}}</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class=" fs-4 fw-bold">Kontak Darurat</div>
                                        <div>{{$masterUser->emergency_phone}}</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class=" fs-4 fw-bold">Alamat</div>
                                        <div>{{$masterUser->address}}</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class=" fs-4 fw-bold">Tanggal Lahir</div>
                                        <div>{{$masterUser->birth}}</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class=" fs-4 fw-bold">Jumlah Tanggungan</div>
                                        <div>{{$masterUser->dependent}}</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class=" fs-4 fw-bold">Posisi</div>
                                        <div>{{$masterUser->position}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{$masterUsers->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($masterUsers) == 0)
        <img class="img-fluid mx-auto d-block" width="200" src="{{asset('assets/media/illustrations/empty.webp')}}"
            alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
<script>
    function handleEditChange(masterRoleUuid, position) {
        handleRoleChange(masterRoleUuid);
        handleShipChange(position);
    }

    function handleRoleChange(masterRoleUuid) {
        switch (masterRoleUuid) {
            case 'a2a07a5a-123d-4d87-834b-fa82f03a4fa9':
            case '0a3f2a3e-7e53-44e7-9a3c-b408e5ab68bc':
            case '61a8325c-5283-4b7b-a92d-9449d72a69c7':
                $('.master_user_uuid_form').css('display', 'block');
                break;
            case '6b20e26e-30e7-4c44-8c72-2e24bcf8d5e6': 
                $('.sub_role_form').css('display', 'block');
                break;
            default:
                $('.sub_role_form').css('display', 'none');
                $('.master_user_uuid_form').css('display', 'none');
                break;
        }
    }

    function handleShipChange(position) {
        if (position == 'kapal') {
            $('.master_ship_uuid_form').css('display', 'block');
        } else {
            $('.master_ship_uuid_form').css('display', 'none');
        }
    }

    $(document).ready(function () {
        $('.master_user_uuid_form').css('display', 'none');
        $('.sub_role_form').css('display', 'none');
        $('.master_ship_uuid_form').css('display', 'none');


        $('.master_role_uuid').change(function () {
            let masterRoleUuid = $(this).val();

            handleRoleChange(masterRoleUuid);
        });

        $('.position').change(function () {
            let position = $(this).val();
            handleShipChange(position);
        });
    });

</script>
@endpush
