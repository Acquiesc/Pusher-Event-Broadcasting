<div class="my-3 p-3 rounded received-container-{{$user_id}}" style="background-color: rgba(7,7,7,.1);">
    <div class="col-12 d-flex flex-column justify-content-start text-start">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <div class="online-status online"></div>
            <p class="mb-0 mx-2">{{$username}}</p>
            <img src="{{$profile_picture}}" class="" alt="Message profile picture icon" width="50">
        </div>
    </div>
    <div class="col-12 d-flex flex-column justify-content-start text-start border border-dark rounded p-3">
        <p>{{$message}}</p>
    </div>
</div>