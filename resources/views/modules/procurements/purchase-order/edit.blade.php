@section('title')
Purchase Order
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
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="#" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">


            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('payment_term', 'Payment Terms', $term_lists) !!}
                </div>
                <div class="six columns">
                    {!! Form::numberField('delivery_terms', 'Delivery Terms') !!}
                </div>
            </div>


            <div class="row">
                <div class="four columns">
                    {!! Form::textField('purchase_date', 'Purchase Date') !!}
                </div>
                @if($data->funding_released_date != null)
                <div class="four columns">
                    {!! Form::textField('funding_released_date', 'Funding Released Date') !!}
                </div>
                @endif
                @if($data->funding_received_date != null)
                <div class="four columns">
                    {!! Form::textField('funding_received_date', 'Fundig Received Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                @if($data->mfo_released_date != null)
                <div class="four columns">
                    {!! Form::textField('mfo_released_date', 'MFO Released Date') !!}
                </div>
                @endif
                @if($data->mfo_received_date != null)
                <div class="four columns">
                    {!! Form::textField('mfo_received_date', 'MFO Received Date') !!}
                </div>
                @endif
                @if($data->coa_approved_date != null)
                <div class="four columns">
                    {!! Form::textField('coa_approved_date', 'COA Approved Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                <div class="six columns">

                {!! Form::selectField('requestor_id', 'Requestor', $signatory_list) !!}

                </div>
                <div class="six columns">
                    {!! Form::selectField('accounting_id', 'Accounting', $signatory_list) !!}

                </div>
            </div>
            <div class="row">
                <div class="six columns">
                   {!! Form::selectField('approver_id', 'Approver', $signatory_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::selectField('coa_signatory', 'COA Signatory', $signatory_list) !!}
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