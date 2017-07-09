@section('title')
Minutes of the Meeting
@stop

@section('modal')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <a href="#" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>

        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3></h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Date Start :</strong> {{$data->date_opened}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Time Start :</strong> {{$data->time_opened}} </li>


        </ul>
    </div>

    <div class="six columns pull-right">
        <h3></h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Time Closed :</strong> {{$data->time_closed}} </li>
        </ul>
    </div>

    <div class="six columns pull-right">
        <h3></h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Venue :</strong> {{$data->venue}} </li>
        </ul>
    </div>
</div>
<br>
<div class="row">
    <div class="six columns ">
        <h3>Members</h3>
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th style="text-align:left">Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->members as $member)
                    <tr>
                        <td style="text-align:left">{{$member->signatory->name}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="six columns ">
        <h3>Canvass</h3>
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th style="text-align:left">RFQ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->canvass as $canvass_dat)
                    <tr>
                        <td style="text-align:left">{{$canvass_dat->canvass->rfq_number}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">


</script>
@stop