@section('title')
Users (Supplier)
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')

{!! Form::model($user, $modelConfig['update']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.user-suppliers.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="submit" class="button "  tooltip="Save">
        <i class="nc-icon-mini ui-2_disk"></i>
        </button>

        <a href="#" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
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
            <h3>{{$user->first_name}} - {{$user->surname}}</h3>

            <ul class="list-unstyled user_data">
                <li ></i> {{$user->address}}</li>
                <li ></i> {{$user->contact_number  }}</li>
                <li ></i> {{$user->username  }}</li>

                <li > {{$user->email}} </li>
                <li ></i> {{$user->last_login  }}</li>

            </ul>

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

                    <div class="row">
                        <div class="six columns">
                            {!! Form::textField('username', 'Username', null, ['readony']) !!}
                        </div>
                        <div class="six columns">
                            {!! Form::fileField('avatar', 'Avatar') !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="six columns">
                            {!! Form::textField('first_name', 'Company') !!}
                        </div>
                        <div class="six columns">
                            {!! Form::textField('surname', 'Owner') !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="six columns">
                            {!! Form::textField('email', 'Email') !!}
                        </div>
                        <div class="six columns">
                            {!! Form::textField('contact_number', 'Contact Number') !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="twelve columns">
                            {!! Form::textareaField('address', 'Address', null, ['rows'=>4]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="twelve columns">
                            {!! Form::tagField('suppliers', 'Sub Unit', $suppliers, json_decode($user->suppliers) ) !!}
                        </div>
                    </div>

                 {{--    <button type="submit" class="button">Update</button>
                    <button class="button topbar__utility__button--modal" >Delete</button> --}}
                {{-- {!! Form::close() !!} --}}
                    <input type="hidden" name="gender" value="male">
                    <input type="hidden" name="unit_id" value="x">
                    <input type="hidden" name="designation" value="x">
                <br>
                <br>
                <div class="row">
                    <h3>Change Password</h3>
                </div>
                <hr>
                <br>
                <div class="row">
                    <div class="twelve columns">{!! Form::passwordField('password', 'New Password') !!}</div>
                    <div class="twelve columns">{!! Form::passwordField('password_confirmation', 'Confirm Password') !!}</div>
                </div>
            </div>

                {!! Form::close() !!}
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