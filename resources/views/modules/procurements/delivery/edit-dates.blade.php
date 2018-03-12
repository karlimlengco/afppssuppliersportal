@section('title')
Notice Of Delivery
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
    @include('modules.partials.create_signatory')
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
                <div class="six columns">
                    {!! Form::textField('delivery_number', 'Delivery Receipt') !!}
                </div>

            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('expected_date', 'Expexted Date') !!}
                </div>
                @if($data->delivery_date != null)
                <div class="six columns">
                    {!! Form::textField('delivery_date', 'Delivery Date') !!}
                </div>
                @endif
            </div>

            <div class="row">

                <div class="six columns">
                        {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>

                @if($data->date_delivered_to_coa != null)
                <div class="six columns">
                    {!! Form::textField('date_delivered_to_coa', 'COA Delivery Date') !!}
                </div>
                @endif
            </div>

            <div class="row">
                <div class="six columns">
                    {{-- {!! Form::selectField('signatory_id', 'Signatory', $signatory_list, $data->signatory_id) !!} --}}

                    <label class="label">Signatory</label>
                    {!! Form::select('signatory_id',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-signatory_id']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('notes', 'Notes', null, ['rows'=>3]) !!}
                </div>
            </div>


        </div>
    </div>


{!! Form::close() !!}

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    $signatory = $('#id-field-signatory_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    signatory  = $signatory[0].selectize;

    $(document).on('submit', '#create-signatory-form', function(e){
        e.preventDefault();
        var inputs =  $("#create-signatory-form").serialize();

        console.log(inputs);
        $.ajax({
            type: "POST",
            url: '/api/signatories/store',
            data: inputs,
            success: function(result) {
                console.log(result);
                signatory.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });

    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })
    // datepicker

     var expected_date = new Pikaday(
    {
        field: document.getElementById('id-field-expected_date'),
        firstDay: 1,
    });

     var delivery_date = new Pikaday(
    {
        field: document.getElementById('id-field-delivery_date'),
        firstDay: 1,
    });

     var transaction_date = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
    });

     var date_delivered_to_coa = new Pikaday(
    {
        field: document.getElementById('id-field-date_delivered_to_coa'),
        firstDay: 1,
    });

    // end datepicker
</script>
@stop