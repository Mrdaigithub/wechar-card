import Echo from 'laravel-echo';
import io from 'socket.io-client';

export default ({app, store, redirect}) => {
  window.io = io;

  window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
  });
}
