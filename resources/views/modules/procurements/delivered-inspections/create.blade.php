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

@section('contents')


{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <div class="row">
            <div class="twelve columns">
                {!! Form::selectField('dr_id', 'RFQ Number', $delivery_list) !!}
            </div>
        </div>

    </div>
</div>

{!!Form::close()!!}
@stop
@section('scripts')

<script type="text/javascript">
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-purchase_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop