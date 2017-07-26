@section('title')
Vouchers
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

        <button type="button" class="button"  id="edit-button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="{{route($indexRoute,$data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <a href="#" class="button topbar__utility__button--modal" ><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                @if($data->payment_release_date)
                <div class="four columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('payment_release_date', 'Payment Release Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('payment_received_date', 'Payment Received Date') !!}
                </div>
                @else
                    <div class="six columns">
                        {!! Form::textField('transaction_date', 'Transaction Date') !!}
                    </div>
                @endif
            </div>

            <div class="row">
                @if($data->preaudit_date)
                <div class="six columns">
                    {!! Form::textField('preaudit_date', 'Pre Audit Date') !!}
                </div>
                @endif
                @if($data->certify_date)
                <div class="six columns">
                    {!! Form::textField('certify_date', 'Certify Date') !!}
                </div>
                @endif
            </div>
            <div class="row">
                @if($data->journal_entry_date)
                <div class="six columns">
                    {!! Form::textField('journal_entry_date', 'Journal Entry Date') !!}
                </div>
                @endif
                @if($data->approval_date)
                <div class="six columns">
                    {!! Form::textField('approval_date', 'Approval Date') !!}
                </div>
                @endif
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

    var transaction_date = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
    });

    var payment_release_date = new Pikaday(
    {
        field: document.getElementById('id-field-payment_release_date'),
        firstDay: 1,
    });

    var payment_received_date = new Pikaday(
    {
        field: document.getElementById('id-field-payment_received_date'),
        firstDay: 1,
    });

    var preaudit_date = new Pikaday(
    {
        field: document.getElementById('id-field-preaudit_date'),
        firstDay: 1,
    });

    var certify_date = new Pikaday(
    {
        field: document.getElementById('id-field-certify_date'),
        firstDay: 1,
    });

    var journal_entry_date = new Pikaday(
    {
        field: document.getElementById('id-field-journal_entry_date'),
        firstDay: 1,
    });

    var approval_date = new Pikaday(
    {
        field: document.getElementById('id-field-approval_date'),
        firstDay: 1,
    });
</script>
@stop