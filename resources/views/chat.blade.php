@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row border-bottom py-3">
            <div class="col-12">
                <div class="d-flex justify-content-evenly">
                    <a href="/rooms"><h1>Laravel Chats</h1></a>
                    <form action="{{ route('logout')}}" id="logout_form" class="visually-hidden" method="POST">
                        @csrf
                        <button type="submit">logout</button>
                    </form>
                    <button onclick="setOnline('{{auth()->user()->id}}')" class="btn btn-danger">Logout</button>
                </div>
            </div>

            <script src="/js/manageOnline.js"></script>

        </div>
        <div class="row">
            <div class="col-12 border-bottom border-dark py-3">
                <div class="row">
                    <div class="col">
                        <div class="d-flex gap-3 align-items-center">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#update_profile_picture">
                                <img src="{{auth()->user()->profile_picture}}" alt="profile picture" id="profile_picture" width="75" class="img-fluid">
                            </button>
                            <h1 class="fw-bold" id="username">{{auth()->user()->name}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="row py-3 messages">
            <p class="mb-0 fw-bold" id="welcome-message">Welcome!  It looks like there currently aren't any messages.  Send one below!</p>
        </div>

        <div class="row py-3">
            <div class="col-12">
                <form action="" onsubmit="sendMessage(event)">
                    <div class="row">
                        <div class="col-10">
                            <input type="text" name="message" id="message" class="form-control" placeholder="Enter message...">
                            <label for="message" class="visually-hidden form-label"></label>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_profile_picture" tabindex="-1" aria-labelledby="update_profile_picture_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update_profile_picture_label">Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/user/profile/picture/upload" method="POST" id="profile_picture_form" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                            </div>
                            <div class="col-auto px-0 d-flex flex-column justify-content-end">
                                <input type="text" class="visually-hidden" name="user_id" id="user_id" value="{{auth()->user()->id}}">
                                <button class="btn p-0" type="submit"><i class="bi bi-check fs-1" style="color: green;"></i></button>
                            </div>
                        </div>
                    </form>

                    <form action="" onsubmit="submitDisplayName(event, this)">
                        <div class="row">
                            <div class="col">
                                <label for="username_input" class="form-label">Display Name</label>
                                <input type="text" value="{{auth()->user()->name}}" name="username_input" id="username_input" class="form-control">
                            </div>
                            <div class="col-auto px-0 d-flex flex-column justify-content-end">
                                <button class="btn p-0" type="submit"><i class="bi bi-check fs-1" style="color: green;"></i></button>
                            </div>
                        </div>
                    </form>

                    <script>
                        function submitDisplayName(e, form) {
                            e.preventDefault()

                            fetch('/user/profile/username/update', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    'user_id': user_id,
                                    'username': form.querySelector('input').value,
                                })
                            }).then(function(res) {
                                return res.json()
                            }).then(function(data) {
                                document.getElementById('username').innerText = data.username
                                document.getElementById('username_input').value = data.username
                            })
                        }
                    </script>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
          </div>
    </div>
</body>

<script>
    const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: 'us2'})
    const chat_channel = pusher.subscribe('private-{{ $room->name }}')
    const online_channel = pusher.subscribe('online')

    pusher.connection.bind('connected', () => {
        console.log('Pusher connection established.');
    });

    pusher.connection.bind('error', (err) => {
        console.error('Pusher connection error:', err);
    });

    online_channel.bind('online', function (data) {
        console.log(`${data.user_id} is online: ${data.online}`)

        console.log(`received-container-${data.user_id}`)

        const username_msg_containers = document.querySelectorAll(`.received-container-${data.user_id}`)
    
        console.log(username_msg_containers)

        username_msg_containers.forEach(container => {
            console.log('checking msg container')

            const online_icon = container.querySelector('.online-status')
            if(!data.online) {
                console.log('setting offline')
                online_icon.classList.remove('online')
                online_icon.classList.add('offline')
            } else {
                console.log('setting online')
                online_icon.classList.add('online')
                online_icon.classList.remove('offline')
            }
        })
    })

    chat_channel.bind('private-{{ $room->name }}', function (data) {
        console.log('binded to: private-{{ $room->name }}')

        fetch('/receive', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'profile_picture': data.profile_picture,
                'username': data.username,
                'user_id': data.user_id,
                'message': data.message,
            })
        }).then(function (res) {
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }

            if(document.getElementById('welcome-message')) {
                document.getElementById('welcome-message').remove()
            }

            return res.text();
        }).then(function (htmlContent) {
            console.log(`received: ${htmlContent}`);

            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlContent, 'text/html');
            const newMessage = doc.body.firstChild;

            document.querySelector('.messages').appendChild(newMessage);

            window.scrollTo(0, document.body.scrollHeight);
            document.getElementById('message').value = '';
        })
    })

    function sendMessage(e) {
        e.preventDefault()

        fetch('/broadcast', {
            method: 'POST',
            headers: {
                'X-Socket-Id': pusher.connection.socket_id,
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'profile_picture': document.getElementById('profile_picture').src,
                'username': document.getElementById('username').innerText,
                'user_id': '{{ auth()->user()->id }}',
                'message': document.getElementById('message').value,
                'room_name': 'private-{{ $room->name }}',
            })
        }).then(function (res) {
            if (!res.ok) {
                throw new Error(`HTTP error! Status: ${res.status}`);
            }

            if(document.getElementById('welcome-message')) {
                document.getElementById('welcome-message').remove()
            }

            return res.text();
        }).then(function (htmlContent) {
            console.log(`broadcasted: ${htmlContent}`);

            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlContent, 'text/html');
            const newMessage = doc.body.firstChild;

            document.querySelector('.messages').appendChild(newMessage);

            window.scrollTo(0, document.body.scrollHeight);
            document.getElementById('message').value = '';
        })
    }

</script>

@endsection