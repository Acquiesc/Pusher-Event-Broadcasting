<div class="my-3 broadcasted-container-{{$user_id}}">
    <div class="col-12 d-flex flex-column justify-content-start text-start">
        <div class="d-flex align-items-center mb-3">
            <img src="{{$profile_picture}}" class="" alt="Message profile picture icon" width="50">
            <p class="mb-0 mx-2">{{$username}}</p>
            <div class="online"></div>
        </div>
    </div>
    <div class="col-12 d-flex flex-column justify-content-start text-start border rounded p-3">
        <p>{{$message}}</p>
    </div>
</div>