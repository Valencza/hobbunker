@extends('backoffice.layouts.app')

@section('title', 'Pengumuman')

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
        <li class="breadcrumb-item text-gray-500">Pengumuman</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Pengumuman</h3>

    <section class="card mb-5">
        <div class="p-5 d-flex align-items-center justify-content-between border-bottom border-secondary">
            <h3 class="p-3">Kalender</h3>
            @if (Auth::user()->masterRole->slug == 'hrd' || Auth::user()->masterRole->slug == 'superadmin')
            <button data-bs-toggle="modal" data-bs-target="#addAnnouncementModal" class="btn btn-primary">
                <i class="bi bi-plus fs-3"></i>
                Tambah Pengumuman
            </button>
            @endif
        </div>

        <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{route('backoffice.announcement.store')}}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAnnouncementModalLabel">Tambah Pengumuman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="image" class="form-label">Gambar</label>
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
                                <label for="title" class="form-label">Judul</label>
                                @error('title')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Masukkan judul" value="{{old('title')}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="type" class="form-label">Tipe</label>
                                @error('type')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <select name="type" id="type" class="form-select" required>
                                    <option disabled {{ old('type') ? '' : 'selected' }}>Pilih Tipe</option>
                                    <option value="pengumuman" {{ old('type') == 'pengumuman' ? 'selected' : '' }}>
                                        Pengumuman</option>
                                    <option value="libur nasional"
                                        {{ old('type') == 'libur nasional' ? 'selected' : '' }}>
                                        Libur Nasional</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-12 col-md-6">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    @error('start_date')
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <span>{{$message}}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    @enderror
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{old('start_date')}}" required>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                                    @error('end_date')
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <span>{{$message}}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    @enderror
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{old('end_date')}}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">Deskripsi</label>
                                @error('description')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <span>{{$message}}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @enderror
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control"
                                    placeholder="Masukkan deskripsi" required>{{old('description')}}</textarea>
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

        <div id="calendar" class="p-4"></div>
    </section>

    <section class="card">
        <div class="p-3 d-flex align-items-center justify-content-between">
            <h3>Daftar Pengumuman</h3>

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
                    <input type="text" class="form-control form-control-lg  px-15" name="search"
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
        </div>

        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th class="text-center">Judul</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-center">Tanggal Mulai</th>
                    <th class="text-center">Tanggal Selesai</th>
                    @if (Auth::user()->masterRole->slug == 'hrd' || Auth::user()->masterRole->slug == 'superadmin')
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($masterAnnouncements as $masterAnnouncement)
                <tr>
                    <td class="text-center">{{$masterAnnouncement->title}}</td>
                    <td class="text-center">{{ucwords($masterAnnouncement->type)}}</td>
                    <td class="text-center">{{$masterAnnouncement->formatted_start_date}}</td>
                    <td class="text-center">{{$masterAnnouncement->formatted_end_date}}</td>
                    @if (Auth::user()->masterRole->slug == 'hrd' || Auth::user()->masterRole->slug == 'superadmin')
                    <td class="text-center">
                        <button class="btn btn-icon rounded-circle btn-light-warning" data-bs-toggle="modal"
                            data-bs-target="#editAnnouncementModal-{{$masterAnnouncement->uuid}}">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-icon rounded-circle btn-light-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteAnnouncementModal-{{$masterAnnouncement->uuid}}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                    @endif
                </tr>

                <div class="modal fade" id="editAnnouncementModal-{{$masterAnnouncement->uuid}}" tabindex="-1"
                    aria-labelledby="editAnnouncementModalLabel-{{$masterAnnouncement->uuid}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <form class="modal-content"
                                action="{{route('backoffice.announcement.update', $masterAnnouncement->uuid)}}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="editAnnouncementModalLabel-{{$masterAnnouncement->uuid}}">Ubah Pengumuman
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Image -->
                                    <div class="form-group mb-3">
                                        <label for="image" class="form-label">Gambar</label>
                                        @error('image')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="file" name="image" id="image" class="dropify"
                                            data-default-file="{{asset($masterAnnouncement->image)}}">
                                    </div>
                                    <!-- Title -->
                                    <div class="form-group mb-3">
                                        <label for="title" class="form-label">Judul</label>
                                        @error('title')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <input type="text" name="title" id="title" class="form-control"
                                            placeholder="Masukkan judul"
                                            value="{{old('title', $masterAnnouncement->title)}}" required>
                                    </div>
                                    <!-- Type -->
                                    <div class="form-group mb-3">
                                        <label for="type" class="form-label">Tipe</label>
                                        @error('type')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <select name="type" id="type" class="form-select" required>
                                            <option disabled {{ old('type') ? '' : 'selected' }}>Pilih Tipe</option>
                                            <option value="pengumuman"
                                                {{ (old('type') == 'pengumuman' || $masterAnnouncement->type == 'pengumuman') ? 'selected' : '' }}>
                                                Pengumuman</option>
                                            <option value="libur nasional"
                                                {{ (old('type') == 'libur nasional' || $masterAnnouncement->type == 'libur nasional') ? 'selected' : '' }}>
                                                Libur Nasional</option>
                                        </select>
                                    </div>
                                    <!-- Start Date -->
                                    <div class="row mb-3">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                                            @error('start_date')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                value="{{ old('start_date', date('Y-m-d', strtotime($masterAnnouncement->start_date))) }}"
                                                required>
                                        </div>
                                        <!-- End Date -->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                                            @error('end_date')
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <span>{{$message}}</span>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                            @enderror
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                value="{{ old('end_date', date('Y-m-d', strtotime($masterAnnouncement->end_date))) }}"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        @error('description')
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span>{{$message}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        @enderror
                                        <textarea name="description" id="description" cols="30" rows="10"
                                            class="form-control" placeholder="Masukkan deskripsi"
                                            required>{{old('description', $masterAnnouncement->description)}}</textarea>
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

                <div class="modal fade" id="deleteAnnouncementModal-{{$masterAnnouncement->uuid}}" tabindex="-1"
                    aria-labelledby="deleteAnnouncementModalLabel-{{$masterAnnouncement->uuid}}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form class="modal-content"
                                action="{{route('backoffice.announcement.delete', $masterAnnouncement->uuid)}}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="deleteAnnouncementModalLabel-{{$masterAnnouncement->uuid}}">Ubah Pengumuman
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="m-0 text-center">Pengumuman akan dihapus</p>
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

        {{$masterAnnouncements->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($masterAnnouncements) == 0)
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
    $(document).ready(function () {
        const calendarEl = $('#calendar')[0];
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: {!!$calendars!!},
            eventClick: function (info) {
                Swal.fire({
                    title: info.event.title,
                    icon: 'info',
                    showConfirmButton: false
                });
            }
        });
        calendar.render();
    });

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
