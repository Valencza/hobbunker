@extends('backoffice.layouts.auth')

@section('title', 'Login')

@section('content')
<main class="login-background">
    <section class="w-100 d-flex justify-content-center mt-0 pt-0">
        <img src="{{asset('assets/media/logos/whiteLogo.png')}}" alt="HOB Logo" class="m-auto">
    </section>

    <div class="p-3 w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <form class="form w-100" action="{{route('login')}}" method="POST">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3 text-2xl">Masuk</h1>
                <!--end::Title-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input required class="form-control form-control-lg " type="text" name="email"
                    value="{{old('email')}}" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-5">
                <!--begin::Wrapper-->
                <!--begin::Label-->
                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                <!--end::Label-->
                <!--begin::Input-->
                @error('password')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span>{{$message}}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @enderror
                <input required class="form-control form-control-lg " type="password" name="password" />
                <!--end::Input-->
                <!--end::Wrapper-->
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                @error('email')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span>Email/Password salah</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @enderror
                <button id="submit-btn" type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">Masuk</span>
                </button>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>

    <p class="text-light my-5 text-center">Version 1.1.0</p>
</main>
@endsection

@push('scripts')
<script>
    $('form').submit(function () {
        $('#submit-btn').attr('disabled', true);
        $('#submit-btn').attr('type', 'submit');
    });
</script>
@endpush
