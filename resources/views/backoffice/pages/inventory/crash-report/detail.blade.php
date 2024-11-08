@extends('backoffice.layouts.app')

@section('title', 'Laporan Kerusakan')

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
        <li class="breadcrumb-item text-gray-500">Laporan Kerusakan</li>
        <!--end::Item-->
    </ul>
    {{--        end breaddcrumb --}}

    <h3 class="pb-5">Detail Kerusakan</h3>

    <section class="py-5 card p-5 rounded-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <h3>{{$crashReport->title}}</h3>
                <p>{{$crashReport->masterShip->name}}</p>
                <p>PIC: {{$crashReport->masterUser->name}}</p>
                <p>{{$crashReport->formatted_created_at}}</p>
            </div>

            @switch($crashReport->status)
            @case('baru')
            <span class="badge badge-primary text-light">{{ucwords($crashReport->status)}}</span>
            @break
            @case('proses')
            <span class="badge badge-warning text-light">{{ucwords($crashReport->status)}}</span>
            @break
            @case('selesai')
            <span class="badge badge-success text-light">{{ucwords($crashReport->status)}}</span>
            @break
            @endswitch
        </div>

        <hr>

        <h3 class="my-3">Bagian Kerusakan</h3>
        @foreach ($crashReport->crashReportDetails as $crashReportDetail)
        <div class="d-flex my-3">
            <p class="m-0 fw-bold me-5">Bagian: <span class="fw-normal">{{ucwords($crashReportDetail->position)}}</span>
            </p>
            <p class="m-0 fw-bold ms-5">Objek: <span class="fw-normal">{{ucwords($crashReportDetail->object)}}</span>
            </p>
        </div>
        <p class="m-0 fw-bold">Uraian:</p>
        <p>
            {{$crashReportDetail->description}}
        </p>
        <p class="m-0 fw-bold">Penyebab:</p>
        <p>
            {{$crashReportDetail->reason}}
        </p>

        <h4>Lampiran</h4>
        <div class="row">
            @foreach ($crashReportDetail->crashReportDetailPhotos as $crashReportDetailPhoto)
            <a href="{{asset($crashReportDetailPhoto->image)}}" download class="col-md-4 col-12">
                <div class="file-preview">
                    @php
                    $ext = explode('.',$crashReportDetailPhoto->image);
                    $ext = end($ext);
                    @endphp
                    @if (in_array($ext, ['jpg', 'png', 'jpeg']))
                    <img src="{{asset($crashReportDetailPhoto->image)}}">
                    @else
                    <i class="fa fa-file"></i>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        @if ($crashReportDetail->status == 'selesai')
        <h4 class="mt-3">Upaya Perbaikan</h4>
        <div class="row">
            @foreach ($crashReportDetail->fixReport->fixReportPhotos as $fixReportPhoto)
            <a href="{{asset($fixReportPhoto->image)}}" class="col-md-4 col-12">
                <img class="file-preview" src="{{asset($fixReportPhoto->image)}}">
            </a>
            @endforeach
        </div>
        @endif
        @endforeach

        @if ($crashReport->status == 'baru' && Auth::user()->masterRole->slug != 'teknis')
        <button id="submit-btn" class="btn btn-primary mt-5">Konfirmasi</button>

        <form action="{{route('crash-report.update', $crashReport->uuid)}}" method="POST">
            @csrf
            @method('PUT')
        </form>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    $('#submit-btn').click(function () {
        Swal.fire({
            title: 'Konfirmasi Laporan Kerusakan',
            text: "Laporan kerusakan akan diproses",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                $('form').submit();
            }
        });
    });

</script>
@endpush
