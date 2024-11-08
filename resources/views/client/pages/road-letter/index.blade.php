@extends('client.layouts.app')

@section('title', 'Surat Jalan')

@section('content')
<main class="p-4">
    <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">
            <a href="{{route('home')}}" class="text-gray-600 text-hover-primary">
                <i class="bi bi-house-door"></i>
            </a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-500">Home</li>
        <!--end::Item-->
    </ul>

    <h2 class="anchor mb-5 mt-3">Surat Jalan</h2>

    <section class="card shadow-sm p-4 rounded-2xl">
        <div class="d-flex justify-content-between">
            <h2 class="fs-4 mb-3 ">Surat Jalan</h2>
        </div>

        <form action="" method="GET" onchange="this.submit()" class="row mb-3">
            <div class="form-group col-12 col-md-6">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{$startDate}}">
            </div>

            <div class="form-group col-12 col-md-6">
                <label for="end_date">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{$endDate}}">
            </div>
        </form>

        <div class="d-flex flex-column gap-3 mb-5">
            <ul class="nav nav-tabs nav-line-tabs mb-2 fs-6">
                <li class="nav-item">
                    <a class="nav-link mx-2 active" data-bs-toggle="tab" href="#kt_tab_pane_1">Baru</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_2">Terkonfirmasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2" data-bs-toggle="tab" href="#kt_tab_pane_3">Selesai</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_tab_pane_1">
                    @foreach ($roadLettersNew as $roadLetterNew)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="card btn btn-light-warning p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-inbox text-warning fs-4 my-2 ml-3"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$roadLetterNew->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal :
                                <span>{{$roadLetterNew->requestItem->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date :
                                <span class="text-info ml-3">{{$roadLetterNew->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('road-letter.detail', $roadLetterNew->uuid)}}" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$roadLettersNew->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($roadLettersNew) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_2">
                    @foreach ($roadLettersConfirmed as $roadLetterConfirmed)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="card btn btn-light-warning p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-inbox text-warning fs-4 my-2 ml-3"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$roadLetterConfirmed->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal :
                                <span>{{$roadLetterConfirmed->requestItem->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date :
                                <span class="text-info ml-3">{{$roadLetterConfirmed->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('road-letter.detail', $roadLetterConfirmed->uuid)}}" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$roadLettersConfirmed->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($roadLettersConfirmed) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
                <div class="tab-pane fade show" id="kt_tab_pane_3">
                    @foreach ($roadLettersDone as $roadLetterDone)
                    <div
                        class="card border-1 border-dashed rounded-2xl d-flex gap-3 flex-row justify-content-between align-items-center py-2 px-3 mb-3">
                        <div class="card btn btn-light-warning p-2 border-0 rounded-2xl w-25 d-flex align-items-center">
                            <i class="bi bi-inbox text-warning fs-4 my-2 ml-3"></i>
                        </div>
                        <div class="d-flex flex-column w-100">
                            <span class="fw-medium fs-5 p-0">{{$roadLetterDone->number}}</span>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Kapal :
                                <span>{{$roadLetterDone->requestItem->masterShip->name}}</span>
                            </h4>
                            <h4 class="text-muted p-0 text-secondary fs-6">
                                Date :
                                <span class="text-info ml-3">{{$roadLetterDone->formatted_created_at}}</span>
                            </h4>
                        </div>
                        <div class="d-flex flex-column justify-content-end w-50 text-end">
                            <a href="{{route('road-letter.detail', $roadLetterDone->uuid)}}" class="btn btn-secondary px-3 py-1 fs-6">Detail</a>
                        </div>
                    </div>
                    @endforeach

                    {{$roadLettersDone->links('vendor.pagination.bootstrap-5')}}

                    {{-- empty --}}
                    @if (count($roadLettersDone) == 0)
                    <img class="img-fluid mx-auto d-block" height="50"
                        src="{{asset('assets/media/illustrations/empty.png')}}" alt="Empty Illustration">
                    @endif
                    {{-- end empty --}}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.nav-link').click(function () {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('show active');
            $($(this).attr('href')).addClass('show active');
        });
    });

</script>
@endpush
