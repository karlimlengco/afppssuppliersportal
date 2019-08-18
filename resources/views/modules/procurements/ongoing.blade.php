@section('title')@section('breadcrumbs')
  <div class="c-breadcrumbs">
    <a href="" class="c-breadcrumbs__item">Procurements</a>
    <a href="" class="c-breadcrumbs__item c-breadcrumbs__item--active">Ongoing</a>
  </div> 
  <div class="c-button-group u-pos-right">
    <button class="c-button c-button--icon c-button--disabled js-help-button" data-tippy-content="Help Button" data-tippy-arrow="true">
      <i class="nc-icon-mini ui-e_round-e-help"></i>
    </button>
  </div>
@stop
@stop

@section('contents')

{!! Form::open(['route' => 'procurements.ongoing', 'method' => 'GET']) !!}
  <div class="c-searchbar u-pos-right">
    <div class="c-input-group">
      <input type="text" name="search" class="c-input u-border-radius" placeholder="Looking for something?">
      <span class="c-input-group__icon"><i class="nc-icon-mini ui-1_zoom"></i></span>
    </div>
  </div>
{!!Form::close()!!}
<div class="row">
    <h3 class="u-margin-l-top">List of Ongoing Unit Purchase Requests</h3>
    <div class="twelve columns">
      @if(count($resources )) 
      <table class="c-table c-table--stripe c-table--hover">
        <thead>
            <tr>
                <th>UPR No.</th>
                <th width="150px">Project Name/ Activity</th>
                <th>Unit</th>
                <th>ABC</th>
                <th>Bid Amount</th>
                <th width="150px">Status</th>
                <th width="120px"># Due Days</th>
                <th width="120px" >Remarks</th>
                <th width="80px" >Date Prepared</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($resources as $data) 
          @if($data->proponent_id == null || $data->rfq == $data->proponent_id)
     				<tr>
     					<td>{{$data->upr_number}}</td>
     					<td>{{$data->project_name}}</td>
              <td>{{($data->unit) ? $data->unit->short_code: ''}}</td>
     					<td>{{number_format($data->total_amount,2)}}</td>
     					<td>{{number_format($data->bid_amount,2)}}</td>
     					<td>{{$data->status}}</td>
              <td>
                @if($data->next_due != null && $data->next_due < $today)
                  {{\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::createFromFormat('Y-m-d',$data->next_due))}} days due for {{$data->next_step}}
                @else
                  0
                @endif
              </td>
            <td>{{$data->last_remarks}}</td>
            <td>{{$data->date_prepared->format('M-d-y')}}</td>
     				</tr>
            @endif
     			@endforeach()
        </tbody>
      </table>
      
      {{ $resources->links() }}
      @else
      @endif
    </div>
</div>

@stop

@section('scripts')
@stop