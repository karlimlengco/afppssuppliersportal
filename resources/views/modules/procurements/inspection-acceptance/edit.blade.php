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
    @include('modules.partials.create_signatory')
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
                <div class="six columns">
                    {!! Form::textField('nature_of_delivery', 'Nature Of Delivery') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('findings', 'Finding') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('recommendation', 'Recommendation') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('inspection_signatory', 'Inspection Signatory', $signatory_list) !!} --}}

                    <label class="label">Inspection Signatory</label>
                    {!! Form::select('inspection_signatory',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-inspection_signatory']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('acceptance_signatory', 'Acceptance Signatory', $signatory_list) !!} --}}

                    <label class="label">SAO Acceptance Signatory <small>(signatory for IIAR)</small></label>
                    {!! Form::select('acceptance_signatory',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-acceptance_signatory']) !!}
                </div>
            </div>
            <div class="row">
                <div class="twelve columns">
                    {{-- {!! Form::selectField('sao_signatory', 'SAO Signatory', $signatory_list) !!} --}}

                    <label class="label">SAO Signatory <small>(Signatory for MFO)</small> </label>
                    {!! Form::select('sao_signatory',  $signatory_list,null, ['class' => 'selectize', 'id' => 'id-field-sao_signatory']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')

<script type="text/javascript">

    $signatory = $('#id-field-inspection_signatory').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $acceptance = $('#id-field-acceptance_signatory').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    $sao = $('#id-field-sao_signatory').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    signatory  = $signatory[0].selectize;
    acceptance  = $acceptance[0].selectize;
    sao  = $sao[0].selectize;

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
                acceptance.addOption({value:result.id, text: result.name});
                sao.addOption({value:result.id, text: result.name});

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