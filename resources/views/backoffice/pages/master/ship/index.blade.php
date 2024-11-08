@extends('backoffice.layouts.app')

@section('title', 'Kapal')

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

    <h3 class="pb-5">Kapal</h3>

    <section class="card">
        <h3 class="p-5 border-bottom border-secondary">Daftar Kapal</h3>

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

            @if (Auth::user()->masterRole->slug == 'superadmin')
            <button data-bs-toggle="modal" data-bs-target="#addKapalModal" class="btn btn-primary ms-auto">
                <i class="bi bi-plus fs-3"></i>
                Tambah Kapal
            </button>
            @endif
        </div>

        <div class="modal fade" id="addKapalModal" tabindex="-1" aria-labelledby="addKapalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{route('master-ship.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addKapalModalLabel">Tambah Kapal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="image" class="form-label" style="text-align: left">Gambar</label>
                                @error('image')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input type="file" name="image" id="image" class="dropify" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="name" class="form-label" style="text-align: left">Nama</label>
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
                                <label for="type" class="form-label" style="text-align: left">Tipe</label>
                                @error('type')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input type="text" name="type" id="type" class="form-control"
                                    placeholder="Masukkan tipe" value="{{old('type')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity" class="form-label" style="text-align: left">Kapasitas</label>
                                @error('capacity')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input type="number" name="capacity" id="capacity" class="form-control"
                                    value="{{old('capacity')}}" placeholder="Masukkan kapasitas" required>
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
                    <th class="text-center">Gambar</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-center">Kapasitas</th>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($masterShips as $masterShip)
                <tr>
                    <td class="text-center">
                        <img src="{{asset($masterShip->image)}}" alt="Ship Image" width="200" height="80">
                    </td>
                    <td class="text-center">{{$masterShip->name}}</td>
                    <td class="text-center">{{$masterShip->type}}</td>
                    <td class="text-center">{{$masterShip->capacity}}</td>
                    @if (Auth::user()->masterRole->slug == 'superadmin')
                    <td class="text-center">
                        <button class="btn btn-icon rounded-circle btn-light-warning" data-bs-toggle="modal"
                            data-bs-target="#editShipModal-{{$masterShip->uuid}}">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-icon rounded-circle btn-light-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteShipModal-{{$masterShip->uuid}}">
                            <i class="fa fa-trash"></i>
                        </button>
                        <div class="modal fade" id="editShipModal-{{$masterShip->uuid}}" tabindex="-1"
                            aria-labelledby="editShipModalLabel-{{$masterShip->uuid}}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{route('master-ship.update', $masterShip->uuid)}}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editShipModalLabel-{{$masterShip->uuid}}">Edit
                                                Kapal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group mb-3">
                                                <label for="image-{{$masterShip->uuid}}"
                                                    class="form-label" style="text-align: left">Gambar</label>
                                                @error('image')
                                                <div class="alert alert-danger alert-dismissible fade show"
                                                    role="alert">
                                                    <span>{{$message}}</span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                                @enderror
                                                <input type="file" name="image" id="image-{{$masterShip->uuid}}"
                                                    class="dropify" data-default-file="{{asset($masterShip->image)}}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="name-{{$masterShip->uuid}}" class="form-label" style="text-align: left">Nama</label>
                                                @error('name')
                                                <div class="alert alert-danger alert-dismissible fade show"
                                                    role="alert">
                                                    <span>{{$message}}</span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                                @enderror
                                                <input type="text" name="name" id="name-{{$masterShip->uuid}}"
                                                    class="form-control" placeholder="Masukkan nama"
                                                    value="{{$masterShip->name}}" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="type-{{$masterShip->uuid}}" class="form-label" style="text-align: left">Tipe</label>
                                                @error('type')
                                                <div class="alert alert-danger alert-dismissible fade show"
                                                    role="alert">
                                                    <span>{{$message}}</span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                                @enderror
                                                <input type="text" name="type" id="type-{{$masterShip->uuid}}"
                                                    class="form-control" placeholder="Masukkan tipe"
                                                    value="{{$masterShip->type}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="capacity-{{$masterShip->uuid}}"
                                                    class="form-label" style="text-align: left">Kapasitas</label>
                                                @error('capacity')
                                                <div class="alert alert-danger alert-dismissible fade show"
                                                    role="alert">
                                                    <span>{{$message}}</span>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="Close"></button>
                                                </div>
                                                @enderror
                                                <input type="number" name="capacity" id="capacity-{{$masterShip->uuid}}"
                                                    class="form-control" value="{{$masterShip->capacity}}"
                                                    placeholder="Masukkan kapasitas" required>
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

                        <div class="modal fade" id="deleteShipModal-{{$masterShip->uuid}}" tabindex="-1"
                            aria-labelledby="deleteShipModalLabel-{{$masterShip->uuid}}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form class="modal-content"
                                        action="{{route('master-ship.delete', $masterShip->uuid)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteShipModalLabel-{{$masterShip->uuid}}">
                                                Hapus Kapal
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="m-0 text-center">Kapal akan dihapus</p>
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
                    </td>@endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>

@push('scripts')
<script>
    $('.dropify').dropify({
        tpl: {
            message: `
            <div class="dropify-message">
                <span class="file-icon" />
            </div>
            `
        }
    });

</script>
@endpush
@endsection
