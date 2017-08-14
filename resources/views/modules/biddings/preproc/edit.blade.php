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
            <div class="six columns">
                {!! Form::textField('pre_proc_date', 'Pre Proc Date') !!}
            </div>
            <div class="six columns">
                {!! Form::textField('resched_date', 'Re-Sched Date') !!}
            </div>
        </div>

        <div class="row">
            {!! Form::textareaField('resched_remarks', 'Re-Sched Remarks', null, ['rows'=>3])!!}
        </div>

        <div class="row">
            {!! Form::textareaField('remarks', 'Remarks', null, ['rows'=>3])!!}
        </div>

        <div class="row">
            {!! Form::textareaField('action', 'Action', null, ['rows'=>3])!!}
        </div>

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
    var pre_proc_date = new Pikaday(
    {
        field: document.getElementById('id-field-pre_proc_date'),
        firstDay: 1,
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