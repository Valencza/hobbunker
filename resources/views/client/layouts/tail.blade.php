<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
    <span class="svg-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
            <path
                d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                fill="black" />
        </svg>
    </span>
    <!--end::Svg Icon-->
</div>

<script>
    var hostUrl = "assets/";

</script>
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
{{-- <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script> --}}
<!--end::Global Javascript Bundle-->
<!--begin::Page Vendors Javascript(used by this page)-->
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<!--end::Page Vendors Javascript-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--begin::Page Custom Javascript(used by this page)-->
{{-- <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('assets/js/custom/modals/create-app.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/custom/modals/upgrade-plan.js') }}"></script> --}}
<script>
    function updateOnlineStatus() {
        if (!navigator.onLine) {
            window.location.href = "{{ route('no-internet') }}";
        }
    }

    window.addEventListener('load', updateOnlineStatus);
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);

    function realtimeClock() {
        var time = $('#currenct_clock').text().split(':'); // Split the time into hours, minutes, and seconds
        var hours = parseInt(time[0]);
        var minutes = parseInt(time[1]);
        var seconds = parseInt(time[2]);
        // Get current date and time
        var now = new Date();

        // Format the time (hours, minutes, seconds)
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        // Ensure two digits for hours, minutes, and seconds
        hours = (hours < 10 ? "0" : "") + hours;
        minutes = (minutes < 10 ? "0" : "") + minutes;
        seconds = (seconds < 10 ? "0" : "") + seconds;

        // Concatenate hours, minutes, and seconds with a colon separator
        var clock = hours + ":" + minutes + ":" + seconds;
        $('#current_clock').text(clock);

        setInterval(function () {
            seconds++; // Increment seconds
            if (seconds >= 60) {
                seconds = 0;
                minutes++; // Increment minutes if seconds reach 60
                if (minutes >= 60) {
                    minutes = 0;
                    hours++; // Increment hours if minutes reach 60
                    if (hours >= 24) {
                        hours = 0; // Reset hours if it reaches 24 (assuming 24-hour format)
                    }
                }
            }

            // Format the time with leading zeros
            var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':' + ('0' +
                seconds).slice(-2);

            // Update the displayed time
            $('#currenct_clock').text(formattedTime);
        }, 1000); // Update every second (1000 milliseconds)
    }

</script>
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

</script>
@include('firebase')
@stack('scripts')
<!--end::Page Custom Javascript-->
<!--end::Javascript-->
</body>
<!--end::Body-->

</html>
