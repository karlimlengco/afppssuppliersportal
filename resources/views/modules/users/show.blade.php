@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Users  <small>Profile</small></h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

                    <div class="profile_img">

                    <!-- end of image cropping -->
                        <div id="crop-avatar">
                                          <!-- Current avatar -->
                            <img class="img-responsive avatar-view" src="{{route('user.avatar.show', $user->id)}}" alt="Avatar" title="Change the avatar">
                        </div>
                        <h3>{{$user->first_name}} {{$user->middle_name}} {{$user->surname}}</h3>

                        <ul class="list-unstyled user_data">
                            <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Address"><i class="fa fa-map-marker user-profile-icon"></i> {{$user->address}}</li>
                            <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Contact Number"><i class="fa fa-phone user-profile-icon"></i> {{$user->contact_number  }}</li>
                            <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Username"><i class="fa fa-external-link user-profile-icon"></i> {{$user->username  }}</li>

                            <li class="m-top-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Email">
                                <i class="fa fa-envelope user-profile-icon"></i>
                                <a href="#" >{{$user->email}}</a>
                            </li>
                            <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Last Login"><i class="fa fa-clock-o user-profile-icon"></i> {{$user->last_login  }}</li>

                        </ul>

                        <a href="{{route('reminder.reset', 'email='.$user->email)}}" class="btn btn-xs btn-danger"><i class="fa fa-edit m-right-xs"></i>Reset Password</a>
                        @if($user->last_login == null)
                            <a href="{{route('reminder.reset', 'email='.$user->email)}}" class="btn btn-xs btn-primary"><i class="fa fa-paper-plane m-right-xs"></i>Resend Code</a>
                        @endif
                        <br>
                    </div>
                </div>


                <div class="col-md-9 col-sm-9 col-xs-12">

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="true">Profile</a></li>
                            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Role</a></li>
                        </ul>

                        <div id="myTabContent" class="tab-content">
                            {{-- Profile --}}
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content2" aria-labelledby="profile-tab">

                                {!! Form::model($user, $modelConfig['update']) !!}

                                            {!! Form::fileField('avatar', 'Avatar') !!}
                                            {!! Form::textField('username', 'Username') !!}
                                            {!! Form::textField('first_name', 'First Name') !!}
                                            {!! Form::textField('middle_name', 'Middle Name') !!}
                                            {!! Form::textField('surname', 'Surname') !!}
                                            {!! Form::textField('contact_number', 'Contact Number') !!}
                                            {!! Form::selectField('gender', 'Gender', $genders) !!}
                                            {!! Form::textField('email', 'Email Address') !!}
                                            {!! Form::textField('address', 'Address') !!}
                                    <div class="ln_solid"></div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-success">Update</button>

                                            <a href="#"  class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</a>
                                        </div>
                                    </div>
                                {!! Form::close() !!}

                            </div>
                            {{-- Profile --}}
                            {{-- Roles --}}
                            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                {!!Form::model($user, ['route'=>['user.groups.update',$user->id], 'method'=> 'PUT', 'id' => 'mainForm'])!!}
                                    <div class="row">
                                        <div class="col-xs-12">
                                        {!! Form::tagField('role', 'Roles', $roles, (count( $user->roles ) != 0 ) ? $user->roles->pluck('id')->toArray()   : "", ['data-max-items' => 10]) !!}
                                        </div>
                                    </div>

                                    <div class="modal-btn">
                                        <button type="submit"  class="btn btn-success" data-form-id="#mainForm">Continue</button>
                                    </div>
                                {!!Form::close()!!}
                            </div>
                            {{-- Roles --}}

                        </div>
                    </div>
                </div>

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