<script>
    var hostUrl = "assets/";

    function asset(path) {
        const url = new URL(window.location.origin);
        url.pathname = path.startsWith('/') ? path : `/${path}`;
        return url.href;
    }

</script>
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
{{-- <script src="{{asset('assets/js/scripts.bundle.js')}}"></script> --}}
<!--end::Global Javascript Bundle-->
<!--begin::Page Custom Javascript(used by this page)-->
{{-- <script src="{{asset('assets/js/custom/widgets.js')}}"></script> --}}
{{-- <script src="{{asset('assets/js/custom/apps/chat/chat.js')}}"></script> --}}
{{-- <script src="{{asset('assets/js/custom/modals/create-app.js')}}"></script> --}}
{{-- <script src="{{asset('assets/js/custom/modals/upgrade-plan.js')}}"></script> --}}
<!--end::Page Custom Javascript-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{--dropify--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
@if(Session::has('success'))
<script>
    Swal.fire({
        title: '{{session('success')}}',
        icon: 'success',
        showConfirmButton: false
    });

</script>
@endif
@if(Session::has('error'))
<script>
    Swal.fire({
        title: '{{session('error')}}',
        icon: 'error',
        showConfirmButton: false
    });

</script>
@endif
<script src="{{ asset('/sw.js') }}"></script>
<script>
    if ("serviceWorker" in navigator) {
        // Register a service worker hosted at the root of the
        // site using the default scope.
        navigator.serviceWorker.register("/sw.js").then(
            (registration) => {
                console.log("Service worker registration succeeded:", registration);
            },
            (error) => {
                console.error(`Service worker registration failed: ${error}`);
            },
        );
    } else {
        console.error("Service workers are not supported.");
    }

    $(document).ready(function () {
        $('#global-loading').addClass('hidden');
    });

    let open = false;

    $('#sidebar-btn').click(function(){
        if ($(window).width() <= 768) {

        open = !open;

        if(open){
            $('#kt_aside').css('display', 'block');
            $('#shadow-aside').css('display', 'block');
        } else {
            $('#kt_aside').css('display', 'none');
            $('#shadow-aside').css('display', 'none');

        }
    }   
    });

    $('#shadow-aside > div').click(function(){
        open =false;
        $('#shadow-aside').css('display', 'none');
        $('#kt_aside').css('display', 'none');
    });

    function checkWidth() {
        if ($(window).width() <= 768) {
            open = false;
            $('#kt_aside').css('position', 'absolute');
            $('#kt_aside').css('display', 'none');
            $('#shadow-aside').css('display', 'none');
        } else {
            $('#kt_aside').css('position', 'static');
            $('#kt_aside').css('display', 'block');
            $('#shadow-aside').css('display', 'none');
        }
    }

    checkWidth();

    $(window).resize(checkWidth);
</script>
@include('firebase')
@stack('scripts')
<!--end::Javascript-->
</body>
<!--end::Body-->

</html>
