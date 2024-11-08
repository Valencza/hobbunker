<section class="d-flex justify-content-between px-4 py-4">
    <div id="sidebar-btn" class="text-primary">
            <i class="fa-solid fa-bars me-3"></i>
            <a>
                <img src="{{ asset('assets/media/logos/blueLogo.svg') }}" alt="HOB Logo">
            </a>
    </div>

    <div class="d-flex gap-3">
        {{-- <a href="{{route('notification')}}"
            class="btn btn-icon btn-color-gray-700 btn-active-color-primary btn-outline-secondary w-35px h-35px label-icon">
            <i class="bi bi-bell icon-size"></i>
        </a> --}}
        <a href="{{route('profile')}}" class="img-profile rounded w-35px h-35px text-decoration-none">
            <x-acronym text="{{Auth::user()->name}}"></x-acronym>
        </a>
        <button class="btn btn-light-danger btn-icon w-35px h-35px" data-bs-toggle="modal"
            data-bs-target="#logoutModal">
            <i class="fa fa-sign-out"></i>
        </button>
    </div>
</section>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{route('logout')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Keluar</button>
                </div>
            </form>
        </div>
    </div>
</div>