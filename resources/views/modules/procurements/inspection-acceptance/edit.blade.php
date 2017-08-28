@section('title')
Inspection And Acceptance Report
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
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop


@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button type="button" class="button"  id="edit-button"  tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                @if($data->accepted_date !=null)
                <div class="six columns">
                    {!! Form::textField('accepted_date', 'Accepted Date') !!}
                </div>
                @endif
                <div class="six columns">
                    {!! Form::textField('inspection_date', 'Inspection Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('inspection_signatory', 'Inspection Signatory', $signatory_list) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('acceptance_signatory', 'Acceptance Signatory', $signatory_list) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('sao_signatory', 'SAO Signatory', $signatory_list) !!}
                </div>
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
    // datepicker

    var accepted_date = new Pikaday(
    {
        field: document.getElementById('id-field-accepted_date'),
        firstDay: 1,
    });

    var inspection_date = new Pikaday(
    {
        field: document.getElementById('id-field-inspection_date'),
        firstDay: 1,
    });
    // end datepicker
</script>
@stop