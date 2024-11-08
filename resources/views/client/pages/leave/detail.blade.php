@extends('client.layouts.app')

@section('title', 'Detail Pengajuan Cuti')

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

    <h2 class="anchor mb-5 mt-3">Detail Pengajuan Cuti</h2>

    <section class="card shadow-sm rounded-2xl mb-3">
        <h3 class=" border-1 border-bottom p-4">Detail Pengajuan Cuti</h3>

        <div class="px-4 py-3">
            <h4 class="mb-1">Nama</h4>
            <h5 class="mb-4 pb-2 text-secondary mt-2 text-capitalize border-bottom">
                {{$leave->masterUser->name}}
            </h5>

            <h4 class="mb-1">Tipe Cuti</h4>
            <h5 class="mb-4 pb-2 text-secondary mt-2 text-capitalize border-bottom">
                {{ucwords($leave->type)}}
            </h5>

            <h4 class="mb-1">Tanggal Awal Cuti</h4>
            <h5 class="mb-4 pb-2 text-secondary mt-2 text-capitalize border-bottom">
                {{$leave->formatted_start_date}}
            </h5>

            <h4 class="mb-1">Tanggal Akhir Cuti</h4>
            <h5 class="mb-4 pb-2 text-secondary mt-2 text-capitalize border-bottom">
                {{$leave->formatted_end_date}}
            </h5>

            <h4 class="mb-1">Alasan Cuti</h4>
            <h5 class="mb-4 pb-2 text-secondary mt-2 text-capitalize border-bottom">
                {{$leave->reason}}
            </h5>

            <div class="mb-3">
                <label for="note" class="form-label">Keterangan</label>
                <textarea id="note" name="note" class="form-control form-control mb-8 border"
                    rows="5" @readonly($leave->status != 'pending') placeholder="Masukkan keterangan">{{$leave->note}}</textarea>
            </div>

            @if ($leave->status == 'pending')
            <div class="d-flex align-items-center justify-content-center gap-3">
                <form action="{{ route('leave.reject', $leave->uuid) }}" method="POST" class="d-inline w-100">
                    @csrf
                    <input id="reject-note" type="hidden" name="note" value="{{$leave->note}}">
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-x border border-light rounded-circle fs-4 p-0"></i>
                        Tolak
                    </button>
                </form>
                <form action="{{ route('leave.accept', $leave->uuid) }}" method="POST" class="d-inline w-100">
                    @csrf
                    <input id="approve-note" type="hidden" name="note" value="{{$leave->note}}">
                    <button type="submit" class="btn bg-primary text-white w-100">
                        <i class="bi bi-check border border-light rounded-circle fs-4 p-0 text-white"></i>
                        Setujui
                    </button>
                </form>
            </div>
            @endif
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const approveForm = $('#approve-note');
        const rejectForm = $('#reject-note');
        const note = $('#note');

        note.on('change', function () {
            approveForm.val(note.val());
            rejectForm.val(note.val());
        });
    });
</script>
@endpush
