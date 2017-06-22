@section('title')
Users
@stop

@section('contents')

{!! Form::model($user, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.users.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="submit" class="button topbar__utility__button--modal"  tooltip="Save">
        <i class="nc-icon-mini ui-2_disk"></i>
        </button>

        <a href="" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="four columns">
        <div class="profile_img">

        <!-- end of image cropping -->
            <div id="crop-avatar">
                              <!-- Current avatar -->
                <img class="img-responsive avatar-view" src="{{route('user.avatar.show', $user->id)}}" alt="Avatar" title="Change the avatar">
            </div>
            <h3>{{$user->first_name}} {{$user->middle_name}} {{$user->surname}}</h3>

            <ul class="list-unstyled user_data">
                <li ></i> {{$user->address}}</li>
                <li ></i> {{$user->contact_number  }}</li>
                <li ></i> {{$user->username  }}</li>

                <li > {{$user->email}} </li>
                <li ></i> {{$user->last_login  }}</li>

            </ul>

                <a class="button" href="{{route('reminder.reset', 'email='.$user->email)}}"><i class="fa fa-edit m-right-xs"></i>Reset Password</a>
            @if($user->last_login == null)
                    <a class="button" href="{{route('reminder.reset', 'email='.$user->email)}}"><i class="fa fa-paper-plane m-right-xs"></i>Resend Code</a>
            @endif
            <br>
        </div>
    </div>

    <div class="one columns"></div>


    <div class="eight columns">

        <div class="row">
            <div class="twelve columns">

                <div class="row">
                    <h3>User Details</h3>
                </div>
                <hr>
                <br>


                    {!! Form::fileField('avatar', 'Avatar') !!}
                    {!! Form::textField('username', 'Username') !!}
                    {!! Form::textField('first_name', 'First Name') !!}
                    {!! Form::textField('middle_name', 'Middle Name') !!}
                    {!! Form::textField('surname', 'Surname') !!}
                    {!! Form::textField('contact_number', 'Contact Number') !!}
                    {!! Form::selectField('gender', 'Gender', $genders) !!}
                    {!! Form::textField('email', 'Email Address') !!}
                    {!! Form::textField('address', 'Address') !!}
                 {{--    <button type="submit" class="button">Update</button>
                    <button class="button topbar__utility__button--modal" >Delete</button> --}}
                {!! Form::close() !!}

                <br>
                <br>
                <div class="row">
                    <h3>User Roles</h3>
                </div>
                <hr>
                <br>

                {!!Form::model($user, ['route'=>['user.groups.update',$user->id], 'method'=> 'PUT', 'id' => 'mainForm'])!!}
                    {!! Form::tagField('role', 'Roles', $roles, (count( $user->roles ) != 0 ) ? $user->roles->pluck('id')->toArray()   : "", ['data-max-items' => 10]) !!}
                    <button type="submit" class="button">Continue</button>
                {!!Form::close()!!}</div>
            </div>

        </div>
    </div>

</div>

@include('modules.partials.modals.delete')
@stop

@section('scripts')

    <script type="text/javascript">
        $("#change-avatar").click(function(e){
            $('#id-field-avatar').click();
            e.preventDefault();
        })

        // Change Avatar

        $("#id-field-avatar").change(function() {
            // $("#uplaod-avatar").toggle();
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#avatar-image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop