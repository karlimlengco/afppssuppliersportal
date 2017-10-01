@section('title')
Notice Of Award
@stop

@section('modal')
    @include('modules.partials.create_signatory')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
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

@section('contents')


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

            <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
            <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('awarded_date', 'Award Date') !!}
                </div>
                @if($data->award_accepted_date != null)
                <div class="six columns">
                    {!! Form::textField('award_accepted_date', 'Received Date') !!}
                </div>
                @endif
            </div>
            <div class="row">
                @if($data->accepted_date != null)
                <div class="six columns">
                    {!! Form::textField('accepted_date', 'Approved Date') !!}
                </div>
                @endif
                <div class="six columns">
                    {{-- {!! Form::selectField('signatory_id', 'Signatory', $signatory_lists) !!} --}}
                        <label class="label">Signatory</label>
                        {!! Form::select('signatory_id',  $signatory_lists, null, ['class' => 'selectize', 'id' => 'id-field-signatory_id']) !!}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')

<script type="text/javascript">


    $signatory_id = $('#id-field-signatory_id').selectize({
        create: true,
        create:function (input){
            $('#create-signatory-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });
    signatory_id        = $signatory_id[0].selectize;
    $(document).on('submit', '#create-signatory-form', function(e){
        e.preventDefault();
        var inputs =  $("#create-signatory-form").serialize();

        $.ajax({
            type: "POST",
            url: '/api/signatories/store',
            data: inputs,
            success: function(result) {
                signatory_id.addOption({value:result.id, text: result.name});

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

    var awarded_date = new Pikaday(
    {
        field: document.getElementById('id-field-awarded_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var award_accepted_date = new Pikaday(
    {
        field: document.getElementById('id-field-award_accepted_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var accepted_date = new Pikaday(
    {
        field: document.getElementById('id-field-accepted_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop