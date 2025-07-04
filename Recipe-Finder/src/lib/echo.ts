import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: "pusher",
  key: "a25a2563d4ac650fb4f1",
  cluster: "eu",
  forceTLS: true,
});

