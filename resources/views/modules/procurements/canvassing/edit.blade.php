@section('title')
Canvassing
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
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
    @include('modules.partials.modals.delete')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

            <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
            <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>
            <a href="#" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('canvass_date', 'Canvassing Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('canvass_time', 'Canvassing Time') !!}
                </div>
                @if($data->adjourned_time != null)
                <div class="four columns">
                    {!! Form::textField('adjourned_time', 'Adjourned Time') !!}
                </div>
                @endif
            </div>

            <div class="row">
                <div class="six columns">
                        <label class="label">Presiding Officer</label>
                        {!! Form::select('presiding_officer',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-presiding_officer']) !!}
                </div>
                <div class="six columns">
                        <label class="label">Chief</label>
                        {!! Form::select('chief',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-chief']) !!}
                </div>
            </div>


            <div class="row">
                <div class="six columns">
                        <label class="label">Unit Head</label>
                        {!! Form::select('unit_head',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-unit_head']) !!}
                </div>
                <div class="six columns">
                        <label class="label">MFO</label>
                        {!! Form::select('mfo',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-mfo']) !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                        <label class="label">Legal</label>
                        {!! Form::select('legal',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-legal']) !!}
                </div>
                <div class="six columns">
                    <label class="label">Secretary</label>
                    {!! Form::select('secretary',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-secretary']) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('other_attendees', 'Other Attendees') !!}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    $presiding_officer = $('#id-field-presiding_officer').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $chief = $('#id-field-chief').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $unit_head = $('#id-field-unit_head').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $mfo = $('#id-field-mfo').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $secretary = $('#id-field-secretary').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    $legal = $('#id-field-legal').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    approver        = $approver[0].selectize;
    presiding_officer= $presiding_officer[0].selectize;
    chief           = $chief[0].selectize;
    unit_head       = $unit_head[0].selectize;
    mfo             = $mfo[0].selectize;
    secretary       = $secretary[0].selectize;
    legal           = $legal[0].selectize;

    $(document).on('submit', '#create-signatory-form', function(e){
        e.preventDefault();
        var inputs =  $("#create-signatory-form").serialize();

        $.ajax({
            type: "POST",
            url: '/api/signatories/store',
            data: inputs,
            success: function(result) {
                presiding_officer.addOption({value:result.id, text: result.name});
                chief.addOption({value:result.id, text: result.name});
                unit_head.addOption({value:result.id, text: result.name});
                mfo.addOption({value:result.id, text: result.name});
                secretary.addOption({value:result.id, text: result.name});
                legal.addOption({value:result.id, text: result.name});

                $('#create-signatory-modal').removeClass('is-visible');
                $('#create-signatory-form')[0].reset();
            }
        });

    });


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })

    var timepicker = new TimePicker(['id-field-canvass_time', 'id-field-adjourned_time' ], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });


    // datepicker

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-canvass_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop