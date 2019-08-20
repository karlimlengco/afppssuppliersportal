@section('breadcrumbs')
  <div class="c-breadcrumbs">
    <a href="" class="c-breadcrumbs__item">Procurements</a>
    <a href="" class="c-breadcrumbs__item c-breadcrumbs__item--active">Awarded</a>
  </div> 
  <div class="c-button-group u-pos-right">
    <button class="c-button c-button--icon c-button--disabled js-help-button" data-tippy-content="Help Button" data-tippy-arrow="true">
      <i class="nc-icon-mini ui-e_round-e-help"></i>
    </button>
  </div>
@stop

@section('contents')

<div class="row">
    <br>
    <h3 class="u-margin-l-top">List of Awarded Unit Purchase Requests</h3>
    <div class="twelve columns">
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
                <th width="80px">Concerned Office</th>
                <th width="120px">Remarks</th>
                <th width="80px">Date Prepared</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($resources as $data)
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
            <td>
              @if(in_array($data->status, $accounting))
                Accounting
              @else
                PCCO
              @endif
            </td>
            <td>{{$data->last_remarks}}</td>
            <td>{{$data->date_prepared->format('M-d-y')}}</td>
   				</tr>
   			@endforeach()
        </tbody>
      </table>
      {{ $resources->links() }}
    </div>
</div>

@stop

@section('scripts')
@stop