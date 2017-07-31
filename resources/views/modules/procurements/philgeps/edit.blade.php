@section('title')
PhilGeps Posting
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

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
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
        <button type="button" id="edit-button"  class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="#" class="button topbar__utility__button--modal" ><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>


<div class="row">
    <div class="twelve columns">


            {!! Form::hidden('rfq_id') !!}
            <div class="row">
                {{-- <div class="four columns"> --}}
                {{-- </div> --}}
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
                <div class="six columns">
                    {!! Form::selectField('status', 'Status', ['1' => 'Approved', '0' => 'Need Repost']) !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('philgeps_number', 'PhilGeps number') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('philgeps_posting', 'PhilGeps From Posting') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('deadline_rfq', 'Deadline To Submit RFQ') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('opening_time', 'Opening Time') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('remarks', 'Remarks', null, ['rows' => 3]) !!}
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

    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-deadline_rfq'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-philgeps_posting'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop