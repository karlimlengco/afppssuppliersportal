@section('title')
Document Acceptance
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

@section('contents')

{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show',$id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="six columns">
        {!! Form::selectField('bac_id', 'BacSec', $bac_lists) !!}
    </div>
    <div class="six columns">
        {!! Form::textField('transaction_date', 'Transaction Date') !!}
    </div>
</div>
<div class="row">
    <div class="six columns">
        {!! Form::textField('approved_date', 'Approved Date') !!}
    </div>
    <div class="six columns">
        {!! Form::textField('resched_date', 'Re-Sched Date') !!}
    </div>
</div>
        {!! Form::textareaField('resched_remarks', 'Re-Sched Remarks', null,['rows'=>3]) !!}
        {!! Form::textareaField('remarks', 'Remarks', null,['rows'=>3]) !!}
        {!! Form::textareaField('action', 'Action Taken', null,['rows'=>3]) !!}
        <input type="hidden" name="upr_id" value="{{$id}}">

{!!Form::close()!!}

@stop

@section('scripts')
<script type="text/javascript">
    var transaction_date = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var approved_date = new Pikaday(
    {
        field: document.getElementById('id-field-approved_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var resched_date = new Pikaday(
    {
        field: document.getElementById('id-field-resched_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop