@extends('client.layouts.app')

@section('title', 'Upload Foto')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
    integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<main class="p-4" style="height: 100vh">
    <form action="{{route('absent.upload.process')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="photo" id="photo" class="dropify" data-height="300" required />
        <input type="hidden" name="uuid" id="uuid">
        <input type="hidden" name="absent_status" id="absent_status">
        <div id="submit-btn" class="btn btn-primary mt-3 w-100">Submit</div>
    </form>
</main>
@endsection

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
    integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.dropify').dropify({
            tpl: {
                wrap: '<div class="dropify-wrapper"></div>',
                loader: '<div class="dropify-loader"></div>',
                message: '<div class="dropify-message"><span class="file-icon" /><br/><small style="font-size: 20px">Upload Foto</small></div>',
            }
        });
    
        $('#photo').on('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = new Image();
                    img.src = e.target.result;
    
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
    
                        // Set canvas dimensions to the image dimensions
                        var maxWidth = 800; // or any max width you prefer
                        var maxHeight = 1200; // or any max height you prefer
                        var width = img.width;
                        var height = img.height;
    
                            var ratio = Math.min(maxWidth / width, maxHeight / height);
                            canvas.width = width * ratio;
                            canvas.height = height * ratio;
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
                        canvas.toBlob(function (blob) {
                            var newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
    
                            // Replace the file input with the new file
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            $('#photo')[0].files = dataTransfer.files;
                            $('.dropify').dropify('refresh'); // Refresh Dropify
    
                        }, 'image/jpeg', 0.1); // 0.1 is the quality for 10%
                    };
                };
    
                reader.readAsDataURL(file);
            }
        });
    
        $('#submit-btn').click(function () {
            if (!$('#photo').val()) {
                Swal.fire({
                    title: 'Foto belum diupload',
                    icon: 'info',
                    showConfirmButton: false
                });
    
                return;
            }
    
            $('#submit-btn').attr('disabled', true);
            $('#submit-btn').text('Harap tunggu...');
            $('#loading-progress').show();
    
            $.ajax({
                type: "POST",
                url: "{{route('absent.process')}}",
                data: {
                    absent_status: localStorage.getItem('absent_status'),
                    absent_latitude: localStorage.getItem('absent_latitude'),
                    absent_longitude: localStorage.getItem('absent_longitude'),
                    absent_radius: localStorage.getItem('absent_radius'),
                    absent_status_radius: localStorage.getItem('absent_status_radius'),
                    absent_location: localStorage.getItem('absent_location'),
                    absent_detail_job: localStorage.getItem('absent_detail_job'),
                    absent_clock: localStorage.getItem('absent_clock'),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        $('#uuid').val(data.uuid);
                        $('#absent_status').val(localStorage.getItem('absent_status'));
                        $('form').submit();
                    } else {
                        $('#submit-btn').removeAttr('disabled');
                        $('#submit-btn').text('Submit');
                        $('#loading-progress').hide();
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred. Please try again.',
                            icon: 'error'
                        });
                    }
                },
                error: function (error) {
                    console.error("Error during check-in", error);
                    $('#submit-btn').removeAttr('disabled');
                    $('#submit-btn').text('Submit');
                    $('#loading-progress').hide();
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred. Please try again.',
                        icon: 'error'
                    });
                }
            });
        });
    </script>
    
@endpush
