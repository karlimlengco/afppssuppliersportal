@section('title')
Delivered Items Inspection
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

            <button type="button" class="button"  id="edit-button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

            <a href="{{route($showRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        </div>
    </div>
    <div class="row">
        <div class="twelve columns">

            <div class="row">
                @if($data->start_date !=null)
                <div class="six columns">
                    {!! Form::textField('start_date', 'Start Date') !!}
                </div>
                @endif
                @if($data->closed_date !=null)
                <div class="six columns">
                    {!! Form::textField('closed_date', 'Closed Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('requested_by', 'Requested By', $signatory_list) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('received_by', 'Witness By', $signatory_list) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('approved_by', 'Certify By', $signatory_list) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('issued_by', 'Note By', $signatory_list) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::selectField('inspected_by', 'Inspected By', $signatory_list) !!}
                </div>
            </div>


        </div>
    </div>


{!! Form::close() !!}

@stop

@section('scripts')

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })
    // datepicker

     var start_date = new Pikaday(
    {
        field: document.getElementById('id-field-start_date'),
        firstDay: 1,
    });

     var closed_date = new Pikaday(
    {
        field: document.getElementById('id-field-closed_date'),
        firstDay: 1,
    });

    // end datepicker
</script>
@stop