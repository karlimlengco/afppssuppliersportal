@section('title')
My Profile
@stop

@section('contents')

{!! Form::model($user, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.users.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="submit" class="button topbar__utility__button--modal"  tooltip="Save">
        <i class="nc-icon-mini ui-2_disk"></i>
        </button>

        {{-- <a href="" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a> --}}
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

            <ul>
                <li></i> {{$user->address}}</li>
                <li></i> {{$user->contact_number  }}</li>
                <li></i> {{$user->username  }}</li>

                <li><a href="#" >{{$user->email}}</a></li>
                <li></i> {{$user->last_login  }}</li>
            </ul>
{{--
                <a class="button" href="{{route('reminder.reset', 'email='.$user->email)}}" >Reset Password</a>
            @if($user->last_login == null)
                <a class="button" href="{{route('reminder.reset', 'email='.$user->email)}}">Resend Code</a>
            @endif --}}
            <br>
        </div>
    </div>

    <div class="one columns"></div>


    <div class="eight columns">

        <div class="row">

                <div class="row">
                    <div class="six columns">
                        {!! Form::fileField('avatar', 'Avatar') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('username', 'Username') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="four columns">
                        {!! Form::textField('first_name', 'First Name') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('middle_name', 'Middle Name') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('surname', 'Surname') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="four columns">
                        {!! Form::textField('contact_number', 'Contact Number') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::selectField('gender', 'Gender', $genders) !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('email', 'Email Address') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('address', 'Address', null, ['rows'=> 3 ]) !!}
                    </div>
                </div>

            </div>
            Change Password
            <hr>
            <div class="row">
                <div class="twelve columns">{!! Form::passwordField('password', 'New Password') !!}</div>
                <div class="twelve columns">{!! Form::passwordField('password_confirmation', 'Confirm Password') !!}</div>
            </div>
        </div>

    </div>

</div>
{!! Form::close() !!}

@stop

@section('scripts')

    <script type="text/javascript">

        $("#change-avatar").click(function(e){
            $('#id-field-avatar').click();
            e.preventDefault();
        })


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