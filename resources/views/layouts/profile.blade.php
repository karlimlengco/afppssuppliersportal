<div class="profile">
    <div class="profile_pic">
        <img src="{{route('user.avatar.show', $currentUser->id)}}" alt="..." class="img-circle profile_img">
    </div>
    <div class="profile_info">
        <span>Welcome,</span>
        <h2>{{$currentUser->first_name}}, {{$currentUser->surname}}</h2>
    </div>
</div>