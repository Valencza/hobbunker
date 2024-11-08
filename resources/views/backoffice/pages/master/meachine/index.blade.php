@extends('backoffice.layouts.app')

@section('title', 'Mesin')

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

    <h3 class="pb-5">Mesin</h3>

    <section class="card">
        <h3 class="p-5 border-bottom border-secondary">Daftar Mesin</h3>

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
                    <input autocomplete="off" type="text" class="form-control form-control-lg  px-15"
                        name="search" value="{{$search}}" placeholder="Cari..." data-kt-search-element="input" />
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

            @if (Auth::user()->masterRole->slug == 'superadmin' || Auth::user()->masterRole->slug == 'karyawan-inventaris-kantor')
            <button data-bs-toggle="modal" data-bs-target="#addMesinModal" class="btn btn-primary ms-auto">
                <i class="bi bi-plus fs-3"></i>
                Tambah Mesin
            </button>
            @endif
        </div>

        <div class="modal fade" id="addMesinModal" tabindex="-1" aria-labelledby="addMesinModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{route('master-meachine.store')}}" method="POST"
                    >
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addMesinModalLabel">Tambah Mesin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    placeholder="Masukkan nama" value="{{old('name')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="hours" class="form-label">Jam Kerja</label>
                                @error('hours')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input type="number" name="hours" id="hours" class="form-control"
                                    placeholder="Masukkan jam kerja" value="{{old('hours')}}" required>
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

        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th class="text-center">Nama</th>
                    <th class="text-center">Jam Kerja</th>
                    @if (Auth::user()->masterRole->slug == 'superadmin' || Auth::user()->masterRole->slug == 'karyawan-inventaris-kantor')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($masterMeachines as $masterMeachine)
                <tr>
                    <td class="text-center">{{$masterMeachine->name}}</td>
                    <td class="text-center">{{$masterMeachine->hours}}</td>
                    @if (Auth::user()->masterRole->slug == 'superadmin' || Auth::user()->masterRole->slug == 'karyawan-inventaris-kantor')
                    <td class="text-center">
                        <button class="btn btn-icon rounded-circle btn-light-warning" data-bs-toggle="modal"
                            data-bs-target="#editUnitModal-{{$masterMeachine->uuid}}">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-icon rounded-circle btn-light-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteUnitModal-{{$masterMeachine->uuid}}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                    @endif
                </tr>

                <div class="modal fade" id="editUnitModal-{{$masterMeachine->uuid}}" tabindex="-1"
                    aria-labelledby="editUnitModalLabel-{{$masterMeachine->uuid}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form class="modal-content" action="{{route('master-meachine.update', $masterMeachine->uuid)}}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUnitModalLabel-{{$masterMeachine->uuid}}">Ubah Mesin
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
                                            placeholder="Masukkan nama" value="{{old('name', $masterMeachine->name)}}"
                                            required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="hours" class="form-label">Jam Kerja</label>
                                        @error('hours')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="number" name="hours" id="hours" class="form-control"
                                            placeholder="Masukkan jam kerja" value="{{old('hours', $masterMeachine->hours)}}"
                                            required>
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

                <div class="modal fade" id="deleteUnitModal-{{$masterMeachine->uuid}}" tabindex="-1"
                    aria-labelledby="deleteUnitModalLabel-{{$masterMeachine->uuid}}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form class="modal-content" action="{{route('master-meachine.delete', $masterMeachine->uuid)}}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUnitModalLabel-{{$masterMeachine->uuid}}">Hapus Mesin
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="m-0 text-center">Mesin akan dihapus</p>
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
                @endforeach
            </tbody>
        </table>

        {{$masterMeachines->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($masterMeachines) == 0)
        <img class="img-fluid mx-auto d-block" width="200" src="{{asset('assets/media/illustrations/empty.webp')}}"
            alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>
</div>
@endsection