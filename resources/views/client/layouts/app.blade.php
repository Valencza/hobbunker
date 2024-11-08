@include('client.layouts.head')

<style>
    .download-overlay {
        background-color: rgba(0, 0, 0, .5);
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100vh;
        z-index: 99999;
        overflow: hidden;
    }

    .download-card {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: white;
        border-radius: 20px 20px 0 0;
        display: grid;
        place-items: center;
        padding: 20px;
    }
</style>

<section class="d-flex justify-content-between px-4 py-4">
    <a href="{{route('home')}}">
        <img src="{{ asset('assets/media/logos/blueLogo.svg') }}" alt="HOB Logo">
    </a>

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
    <div class="modal-dialog modal-sm modal-dialog-centered">
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

@yield('content')

<section id="installOverlay" class="download-overlay" style="display: none">
    <div class="download-card">
        <p>Unduh aplikasi ke perangkat anda!</p>

        <button id="installButton" class="btn btn-primary w-100">Unduh Aplikasi</button>
    </div>
</section>

@include('client.layouts.tail')

<script>
    let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    // Stash the event so it can be triggered later.
    deferredPrompt = e;
    // Update UI to notify the user they can add to home screen
    const installOverlay = document.getElementById('installOverlay');
    const installButton = document.getElementById('installButton');
    installOverlay.style.display = 'block';

    installButton.addEventListener('click', () => {
        // Hide the app provided install promotion
        installOverlay.style.display = 'none';
        // Show the install prompt
        deferredPrompt.prompt();
        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the A2HS prompt');
            } else {
                console.log('User dismissed the A2HS prompt');
            }
            deferredPrompt = null;
        });
    });
});

// Hide the install button if the app is already installed
window.addEventListener('appinstalled', () => {
    const installOverlay = document.getElementById('installOverlay');
    installOverlay.style.display = 'none';
    console.log('PWA was installed');
});

</script>

@yield('script')
