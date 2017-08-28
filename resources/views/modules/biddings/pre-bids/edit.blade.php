@section('title')
Pre Bid Conference
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
    {{-- @include('modules.partials.modals.delete') --}}
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

            <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
            <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>
            {{-- <a href="#" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a> --}}
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <div class="row">
            <div class="four columns">
                {!! Form::textField('transaction_date', 'Transaction Date') !!}
            </div>
            <div class="four columns">
                {!! Form::textField('sbb_date', 'SBB Date') !!}
            </div>
            <div class="four columns">
                {!! Form::booleanField('is_resched', 'Need for another pre-bid conference') !!}
            </div>
        </div>
        <div class="row">
            <div class="six columns">
                {!! Form::textField('bid_opening_date', 'SOBE Date') !!}
            </div>
            <div class="six columns">
                {!! Form::textField('resched_date', 'Re-Sched Date') !!}
            </div>
        </div>
        {!! Form::textareaField('resched_remarks', 'Re-Sched Remarks', null,['rows'=>3]) !!}
        {!! Form::textareaField('remarks', 'Remarks', null,['rows'=>3]) !!}
        {!! Form::textareaField('action', 'Action Taken', null,['rows'=>3]) !!}

        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })
    var transaction_date = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var bid_opening_date = new Pikaday(
    {
        field: document.getElementById('id-field-bid_opening_date'),
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
    var sbb_date = new Pikaday(
    {
        field: document.getElementById('id-field-sbb_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

</script>
@stop