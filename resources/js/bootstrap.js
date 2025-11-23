import axios from 'axios';
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
});


// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
//     wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
//     wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
//     forceTLS: false,
//     enabledTransports: ['ws', 'wss'],
// });





