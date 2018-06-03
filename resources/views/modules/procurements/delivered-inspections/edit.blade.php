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
    @include('modules.partials.create_signatory')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
    @include('modules.partials.modals.delete')
@stop

@section('contents')

    <div class="row">
        <div class="twelve columns align-right utility utility--align-right">

            <button type="button" class="button"  id="edit-button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

            <a href="{{route($showRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

            @if(\Sentinel::getUser()->hasRole('Admin'))
            <a href="#" id="delete-button" class="button" ><i class="nc-icon-mini ui-1_trash"></i></a>
            @endif
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
                    {{-- {!! Form::selectField('requested_by', 'Requested By', $signatory_list) !!} --}}
                    <label class="label">Requested By</label>
                    {!! Form::select('requested_by',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-requested_by']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('received_by', 'Witness By', $signatory_list) !!} --}}
                    <label class="label">Received By</label>
                    {!! Form::select('received_by',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-received_by']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('approved_by', 'Certify By', $signatory_list) !!} --}}
                    <label class="label">Approved By</label>
                    {!! Form::select('approved_by',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-approved_by']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('issued_by', 'Note By', $signatory_list) !!} --}}
                    <label class="label">Issued By</label>
                    {!! Form::select('issued_by',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-issued_by']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('inspected_by', 'Inspected By', $signatory_list) !!} --}}
                    <label class="label">Inspected By</label>
                    {!! Form::select('inspected_by',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-inspected_by']) !!}
                </div>
            </div>


            <h3>COI Signatories</h3>


            <div class="row">
                <div class="twelve columns">
                    <label class="label">Chairman</label>
                    {!! Form::select('chairman_signatory',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-chairman_signatory']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    <label class="label">Signatory One</label>
                    {!! Form::select('signatory_one',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-signatory_one']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    <label class="label">Signatory Two</label>
                    {!! Form::select('signatory_two',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-signatory_two']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    <label class="label">Signatory Three</label>
                    {!! Form::select('signatory_three',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-signatory_three']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    <label class="label">Signatory Four</label>
                    {!! Form::select('signatory_four',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-signatory_four']) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    <label class="label">Signatory Five</label>
                    {!! Form::select('signatory_five',  ['' => 'Select One']+$signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-signatory_five']) !!}
                </div>
            </div>


        </div>
    </div>


{!! Form::close() !!}

@stop

@section('scripts')

<script type="text/javascript">

    $chairman_signatory = $('#id-field-chairman_signatory').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $signatory_one = $('#id-field-signatory_one').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $signatory_two = $('#id-field-signatory_two').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $signatory_three = $('#id-field-signatory_three').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $signatory_four = $('#id-field-signatory_four').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $signatory_five = $('#id-field-signatory_five').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $requested_by = $('#id-field-requested_by').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $received_by = $('#id-field-received_by').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $approved_by = $('#id-field-approved_by').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $issued_by = $('#id-field-issued_by').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $inspected_by = $('#id-field-inspected_by').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    requested_by        = $requested_by[0].selectize;
    received_by         = $received_by[0].selectize;
    approved_by         = $approved_by[0].selectize;
    issued_by           = $issued_by[0].selectize;
    inspected_by        = $inspected_by[0].selectize;
    chairman_signatory  = $chairman_signatory[0].selectize;
    signatory_one       = $signatory_one[0].selectize;
    signatory_two       = $signatory_two[0].selectize;
    signatory_three     = $signatory_three[0].selectize;
    signatory_four      = $signatory_four[0].selectize;
    signatory_five      = $signatory_five[0].selectize;

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
                requested_by.addOption({value:result.id, text: result.name});
                received_by.addOption({value:result.id, text: result.name});
                approved_by.addOption({value:result.id, text: result.name});
                issued_by.addOption({value:result.id, text: result.name});
                inspected_by.addOption({value:result.id, text: result.name});
                chairman_signatory.addOption({value:result.id, text: result.name});
                signatory_one.addOption({value:result.id, text: result.name});
                signatory_two.addOption({value:result.id, text: result.name});
                signatory_three.addOption({value:result.id, text: result.name});
                signatory_four.addOption({value:result.id, text: result.name});
                signatory_five.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });

    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })
    $('#delete-button').click(function(e){
        e.preventDefault();
        $('#delete-modal').addClass('is-visible');
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