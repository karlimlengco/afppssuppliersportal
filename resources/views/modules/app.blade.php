@section('breadcrumbs')
  <div class="c-breadcrumbs">
    <a href="" class="c-breadcrumbs__item">APP</a>
    <a href="" class="c-breadcrumbs__item c-breadcrumbs__item--active">All</a>
  </div>
  <div class="c-button-group u-pos-right">

    <a id="attachment-button"  href="#" class="c-button c-button--icon js-help-button" data-tippy-content="Help Button" data-tippy-arrow="true">
      <i class="nc-icon-mini ui-1_circle-add"></i>
    </a>
  </div>
@stop

@section('modal')
<div class="p-modal" id="unit-attachment-modal">
  <div class="p-modal__dialog">

    <form method="POST" id="my-awesome-dropzone" enctype="multipart/form-data"  action="{{ route('app.upload', $unit_id) }}"   accept-charset="UTF-8" >
  	<div class="p-modal__header">
  		Add APP with attachment
  	</div>
  	<div class="p-modal__body">

  		<div class="row">
  		    <div class="four columns">
  		        {!! Form::textField('name', 'Name') !!}
  		    </div>
  		    <div class="four columns">
  		        {!! Form::numberField('amount', 'Amount') !!}
  		    </div>
  		    <div class="four columns">
  		      <div class="form-group">
  		        <label for="id-field-validity_date" class="label">Validity Year</label>
  		        <select name="validity_date" class="c-select" id="id-field-validity_date">
  		          <option value="">Select Year</option>
  		          @for($i = 2015 ; $i <= date('Y'); $i++){
  		              <option value="{{$i}}">{{$i}}</option>;
  		          @endfor
  		        </select>
  		      </div>
  		    </div>
  		</div>
  		<div class="row">
  		    <div class="twelve columns dropzone">
  		        <input  type="file" name="file"/>
  		    </div>
  		</div>

  		<input name="_token" type="hidden" value="{{ csrf_token() }}">
  		<input name="_method" type="hidden" value="POST">
  	</div>
    <div class="p-modal__footer">
      <div class="p-actions">
        <div class="c-button-group u-pos-right">
          <button id="attachment-button-close" class="c-button u-border-radius">Cancel</button>
          <button type="submit" class="c-button u-border-radius">Save</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>
@stop

@section('contents')

<div class="row">
    <br>
    <h3 class="u-margin-l-top">List of Aproved Procurement Budget</h3>
    <div class="twelve columns">
      <table class="c-table c-table--stripe c-table--hover">
        <thead>
            <tr>
                <th>NAME</th>
                <th>AMOUNT</th>
                <th>YEAR</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        	@foreach($resources as $data)
  				<tr>
            <td>{{$data->name}}</td>
            <td>{{number_format($data->amount,2)}}</td>
            <td>{{$data->validity_date}}</td>
            <td><a target="_blank" href="{{route('app.download', $data->id)}}">download</a></td>
  				</tr>
	  			@endforeach()
        </tbody>
      </table>
      {{ $resources->links() }}

    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#unit-attachment-modal').addClass('p-modal--active');
    })


    $('#attachment-button-close').on("click", function(){
      $('#unit-attachment-modal').removeClass('p-modal--active');
    })
</script>
@stop