@section('title')
Canvassing
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
    @include('modules.partials.modals.delete')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

            <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
            <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>
            <a href="#" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('canvass_date', 'Canvassing Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('canvass_time', 'Canvassing Time') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('adjourned_time', 'Adjourned Time') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('presiding_officer', 'Presiding Officer', $signatory_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::selectField('chief', 'Chief', $signatory_list) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('other_attendees', 'Other Attendees') !!}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })

    var timepicker = new TimePicker(['id-field-canvass_time', 'id-field-adjourned_time' ], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });


    // datepicker

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-canvass_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop