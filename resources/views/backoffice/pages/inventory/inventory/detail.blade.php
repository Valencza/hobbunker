@extends('backoffice.layouts.app')

@section('title', 'Laporan Inventaris')

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
        <li class="breadcrumb-item text-secondary">Laporan</li>
        <li class="breadcrumb-item">
            <a href="{{route('stock-report', [
                'uuid' => $masterItem->uuid,
                'master_ship_uuid' => $masterShipUuid,
                'position' => $position,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ])}}" class="text-secondary text-decoration-none">Laporan Inventaris</a>
        </li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Detail Inventaris</h3>

    <section class="py-5 card p-4 rounded-3 shadow-sm">
        <div class="d-flex align-items-center justify-content-between px-5">
            <h3>{{$masterItem->name}}</h3>
        </div>
        <table class="table table-row-dashed table-row-gray-300 gy-7">
            <thead>
                <tr class="fw-bolder fs-6 text-gray-800">
                    <th class="text-center">Stok Masuk</th>
                    <th class="text-center">Tanggal Masuk</th>
                    <th class="text-center">Stok Keluar</th>
                    <th class="text-center">Tanggal Keluar</th>
                    <th class="text-center">Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stocks as $stock)
                <tr>
                    <td class="text-center">{{$stock->status == 'masuk' ? $stock->qty : '-'}}</td>
                    <td class="text-center">{{$stock->status == 'masuk' ? $stock->formatted_created_at : '-'}}</td>
                    <td class="text-center">{{$stock->status == 'keluar' ? $stock->qty : '-'}}</td>
                    <td class="text-center">{{$stock->status == 'keluar' ? $stock->formatted_created_at : '-'}}</td>
                    <td class="text-center">{{$stock->total}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{$stocks->links('vendor.pagination.bootstrap-5')}}

        {{-- empty --}}
        @if (count($stocks) == 0)
        <img class="img-fluid mx-auto d-block" width="200" src="{{asset('assets/media/illustrations/empty.webp')}}"
            alt="Empty Illustration">
        @endif
        {{-- end empty --}}
    </section>
</div>
@endsection
