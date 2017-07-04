@section('title')
Purchase Order
@stop

@section('modal')
    @include('modules.partials.modals.delete')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')


<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="#" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">


            <div class="row">
                <div class="four columns">
                    {!! Form::textField('purchase_date', 'Purchase Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('funding_released_date', 'Funding Released Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('funding_received_date', 'Fundig Received Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('mfo_released_date', 'MFO Released Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('mfo_received_date', 'MFO Received Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('coa_approved_date', 'COA Approved Date') !!}
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

    var coa_approved_date = new Pikaday(
    {
        field: document.getElementById('id-field-coa_approved_date'),
        firstDay: 1,
    });


    var mfo_received_date = new Pikaday(
    {
        field: document.getElementById('id-field-mfo_received_date'),
        firstDay: 1,
    });

    var mfo_released_date = new Pikaday(
    {
        field: document.getElementById('id-field-mfo_released_date'),
        firstDay: 1,
    });

    var purchase_date = new Pikaday(
    {
        field: document.getElementById('id-field-purchase_date'),
        firstDay: 1,
    });

    var funding_released_date = new Pikaday(
    {
        field: document.getElementById('id-field-funding_released_date'),
        firstDay: 1,
    });

    var funding_received_date = new Pikaday(
    {
        field: document.getElementById('id-field-funding_received_date'),
        firstDay: 1,
    });
    // end datepicker
</script>
@stop