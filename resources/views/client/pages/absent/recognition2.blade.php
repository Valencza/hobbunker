@extends('client.layouts.app')

@section('title', 'Validasi Absensi')

@section('content')
<main>
    <iframe id="myIframe" class="w-100" style="height: 100vh" src="{{ env('APP_FLASK') }}/fr_page" frameborder="0"></iframe>
</main>

<script>
// Assuming your iframe has loaded
const iframe = document.getElementById('myIframe');

// Function to send message to iframe
function sendMessageToIframe(message) {
    iframe.contentWindow.postMessage(message, '{{ env('APP_FLASK') }}'); // Change to actual origin if necessary
}

// Example: Sending a message to the iframe
sendMessageToIframe({ action: 'getLocalStorage' });

// Listener to receive messages from iframe
window.addEventListener('message', (event) => {
    // Ensure message is from expected origin
    if (event.origin !== '{{ env('APP_FLASK') }}') {
        return;
    }

    // Handle message received from iframe
    const data = event.data;
    console.log('Received from iframe:', data);

    // Example: Accessing localStorage data from iframe
    if (data.action === 'localStorageData') {
        const localStorageData = data.localStorageData;
        console.log('LocalStorage data from iframe:', localStorageData);
        // Use localStorageData as needed
    }
});
</script>
@endsection
