@section('title')
Minutes of the Meeting
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop


@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop

@section('modal')
@stop

@section('contents')
{!! Form::model($data, $modelConfig['update']) !!}


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button type="submit" id="edit-button" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

    </div>
</div>
<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="six columns">
                    {!! Form::dateField('date_opened', 'Meeting Date') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('time_opened', 'Meeting Time') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('time_closed', 'Adjourned Time') !!}
                </div>
                <div class="six columns">
                    {!! Form::selectField('officer_id', 'Presiding Officer', $signatory_lists) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::tagField('members', 'Members', $signatory_lists,  $members ) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::tagField('canvass', 'Canvass', $canvass_lists, $canvass )!!}
                </div>
            </div>


            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('venue', 'Venue', null, ['rows'=>3]) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>



@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    var timepicker1 = new TimePicker(['id-field-time_opened', 'id-field-time_closed'], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker1.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    // end datepicker
</script>
@stop