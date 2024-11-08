importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyCcdm4stqrqdCWyh7hOWjIg49PU-8KXFLY",
    authDomain: "hobbunker-b4824.firebaseapp.com",
    projectId: "hobbunker-b4824",
    storageBucket: "hobbunker-b4824.appspot.com",
    messagingSenderId: "281759530988",
    appId: "1:281759530988:web:27d1a2f004ab2638ec0ac9",
    measurementId: "G-4ZVZ3SKN5B"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});