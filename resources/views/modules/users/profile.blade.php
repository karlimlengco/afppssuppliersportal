@section('breadcrumbs')
  <div class="c-breadcrumbs">
    <a href="#" class="c-breadcrumbs__item c-breadcrumb__item--active">Profile</a>
  </div> 
  <div class="c-button-group u-pos-right">
    <button class="c-button c-button--icon c-button--disabled js-help-button" data-tippy-content="Help Button" data-tippy-arrow="true">
      <i class="nc-icon-mini ui-e_round-e-help"></i>
    </button>
  </div>
@stop


@section('contents')

{!! Form::model($user, $modelConfig['update']) !!}

<div class="p-bottombar">
  <div class="p-bottombar__wrapper">
    <div class="c-button-group">
      {{-- <button class="c-button c-button--small u-border-radius">Btn A</button> --}}
      {{-- <button class="c-button c-button--small c-button--disabled u-border-radius">Btn B</button> --}}
    </div>
    <button class="c-button c-button--small u-border-radius u-pos-right">
      <span class="c-button__icon"><i class="nc-icon-mini ui-2_disk"></i></span>
      <span class="c-button__text">Update</span>
    </button>
  </div>
</div> 



<div class="p-profile-header u-margin-m-bottom u-valign-center">
  <div class="c-avatar c-avatar--circle c-avatar--x-large u-margin-s-right">
    @if($user->avatar)
    <img src="{{route('user.avatar.show', $user->id)}}" alt="">
    @else
    {{$user->first_name[0]}}
    {{$user->first_name[1]}}
    @endif
  </div>
  <div class="p-profile__preview">
    <div class="c-data-group">
      <h4>{{$user->first_name}} </h4>
      <span class="c-data">{{$user->surname}}</span>
      <span class="c-data">{{$user->address}}</span>
      <span class="c-data">{{$user->contact_number}}</span>
      <span class="c-data">{{$user->email}}</span>
      <span class="c-data"></span>
    </div>
  </div>
  <div class="p-profile-header__misc">
    <div class="p-profile-header__misc__panel u-valign-center">
      <div class="c-data-group">
        <span class="c-data c-data--muted">Last login {{$user->last_login}}</span>
      </div> 
    </div>
  </div>
</div>


<div class="o-row-group">
  <div class="o-division">
    <div class="o-division__cell o-division__cell--lock u-width-250 u-padding-m-right">
      <div class="c-data-group">
        <span class="c-data c-data--highlight u-margin-xs-bottom">Basic Information</span>
        <span class="c-data c-data--muted"></span>
      </div>
    </div>
    <div class="o-division__cell">
      <div class="o-row">
        <div class="o-col o-col--6">
            {!! Form::fileField('avatar', 'Avatar') !!}
        </div>
        <div class="o-col o-col--6">
            {!! Form::textField('username', 'Username', null, ['readonly']) !!}
        </div>
      </div>
      <div class="o-row">
        <div class="o-col o-col--6">
            {!! Form::textField('first_name', 'Company Name', null, ['readonly']) !!}
        </div>
        <div class="o-col o-col--6">
            {!! Form::textField('surname', 'Owner', null, ['readonly']) !!}
        </div>
      </div>
      <div class="o-row">
        <div class="o-col o-col--6">
            {!! Form::textField('contact_number', 'Contact Number') !!}
        </div>
        <div class="o-col o-col--6">
            {!! Form::textField('email', 'Email Address') !!}
        </div>
      </div>
      <div class="o-row">
        <div class="o-col o-col--12">
            {!! Form::textareaField('address', 'Address', null, ['rows'=> 3 ]) !!}
        </div>
      </div>
    <div class="o-row-group">
      <h5>Change Password</h5>
      <div class="o-row">
        <div class="o-col o-col--6">
            {!! Form::passwordField('password', 'New Password') !!}
        </div>
        <div class="o-col o-col--6">
            {!! Form::passwordField('password_confirmation', 'Confirm Password') !!} 
        </div>
      </div> 
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