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
    <h3 class="u-margin-l-top">List of Failed Unit Purchase Requests</h3>
    <div class="twelve columns">
      <table class="c-table c-table--stripe c-table--hover">
        <thead>
            <tr>
                <th>UPR No.</th>
                <th>Project Name/ Activity</th>
                <th>Unit</th>
                <th>ABC</th>
                <th>Date Prepared</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($resources as $data)
          @if($data->proponent_id !== null && $data->proponent_id != $data->rfq )
   				<tr>
   					<td>{{$data->upr_number}}</td>
   					<td>{{$data->project_name}}</td>
              <td>{{($data->unit) ? $data->unit->short_code: ''}}</td>
   					<td>{{number_format($data->total_amount,2)}}</td>
            <td>{{$data->date_prepared->format('F d Y')}}</td>
   				</tr>
          @endif
   			@endforeach()
        </tbody>
      </table>
      {{ $resources->links() }}
    </div>
</div>

@stop

@section('scripts')
@stop