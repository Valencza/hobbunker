@extends('backoffice.layouts.app')

@section('title', 'Laporan Stok')

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
        <li class="breadcrumb-item text-gray-500">Laporan</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Laporan Stok</h3>

    <section class="card mb-5 p-5">
        <form action="{{route('stock-report')}}" onchange="this.submit()">
            <div class="row">
                <div class="col-6 col-md-3">
                    <label for="master_ship_uuid" class="form-label">Kapal</label>
                    <select class="form-select border" id="master_ship_uuid" name="master_ship_uuid">
                        <option value="kantor" @selected($masterShipUuid == 'kantor')>Kantor</option>
                        <option value="" @selected($masterShipUuid == '')>Semua Kapal</option>
                        @foreach ($masterShips as $masterShip)
                        <option value="{{$masterShip->uuid}}" @selected($masterShipUuid==$masterShip->uuid)>{{$masterShip->name}}</option>
                        @endforeach
                    </select>
                </div>
                @if ($masterShipUuid != 'kantor')
                <div class="col-6 col-md-3">
                    <label for="position" class="form-label">Bagian</label>
                    <select class="form-select border" id="position" name="position">
                        <option value="" @selected($position=='' )>Semua</option>
                        <option value="dek" @selected($position=='dek' )>Dek</option>
                        <option value="mesin" @selected($position=='mesin' )>Mesin</option>
                    </select>
                </div>
                @endif
                <div class="col-6 col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                        value="{{ $startDate}}">
                </div>
                <div class="col-6 col-md-3">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{$endDate}}">
                </div>
            </div>
        </form>
    </section>

    <section class="py-5 card p-4 rounded-3 shadow-sm">
        <div class="d-flex align-items-center justify-content-between px-5">
            <h3>Daftar Stok</h3>
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
                    <th class="text-center">Nama</th>
                    <th class="text-center">Sisa Stok</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($masterItems as $masterItem)
                <tr>
                    <td class="text-center">{{$masterItem->name}}</td>
                    <td class="text-center">{{$masterItem->stockHistory->total}}</td>
                    <td class="text-center">{{$masterItem->masterUnit->name}}</td>
                    <td class="text-center">
                        <a href="{{route('stock-report.detail', [
                            'uuid' => $masterItem->uuid,
                            'master_ship_uuid' => $masterShipUuid,
                            'position' => $position,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                        ])}}"
                            class="btn btn-icon rounded-circle btn-light-primary">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{$masterItems->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($masterItems) == 0)
        <img class="img-fluid mx-auto d-block" width="200" src="{{asset('assets/media/illustrations/empty.webp')}}"
            alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>
</div>
@endsection