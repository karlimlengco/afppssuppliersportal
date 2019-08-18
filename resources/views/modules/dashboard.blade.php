@section('title')
Overview
@stop

@section('contents')


<div class="p-modal">
  <div class="p-modal__dialog">
  	<form method="POST" id="my-awesome-dropzone" enctype="multipart/form-data"  action="{{ route('eligibilities.store') }}"   accept-charset="UTF-8" >

  		<input name="_token" type="hidden" value="{{ csrf_token() }}">
  		<input name="_method" type="hidden" value="POST">
	    <div class="p-modal__header">
	      <button class="c-button c-button--icon js-close-modal"><i class="nc-icon-mini ui-1_simple-remove"></i></button>
	      <h4>Eligibility</h4>
	    </div>
	    <div class="p-modal__body">
		      <div class="o-row">
		        <div class="o-col o-col--4">
		        {!! Form::textField('name', 'Name') !!}
			      </div>
		        <div class="o-col o-col--4">
		        {!! Form::textField('ref_number', 'Ref Number') !!}
			      </div>
		        <div class="o-col o-col--4">
		        {!! Form::textField('place', 'Place of Issuance') !!}
			      </div>
		      </div>
		      <div class="o-row"> 
		        <div class="o-col o-col--4">
			        <div class="c-form-group">
			        	<label for="id-field-issued_date" class="c-label">Type</label>
			        	<select class="c-select" name="type" id="type" readonly>
			        		<option value="dti">DTI</option>
			        		<option value="mayors_permit">Mayors Permit</option>
			        		<option value="tax_clearance">Tax Clearance</option>
			        		<option value="philgeps_registraion">Philgeps Registration </option>
			        	</select>
			        </div>
			      </div>
		        <div class="o-col o-col--4">
			        <div class="c-form-group">
			        	<label for="id-field-issued_date" class="c-label">Issued Date</label>
			        	<input class="c-input datepicker" name="issued_date" type="date">
			        </div>
			      </div>
		        <div class="o-col o-col--4">
			        <div class="c-form-group">
			        	<label for="id-field-validity_date" class="c-label">Validity Date</label>
			        	<input class="c-input datepicker" name="validity_date" type="date">
			        </div>
			      </div>
		      </div>
		      <div class="o-row">
		      	<div class="o-col o-col--4">
		      		{!! Form::fileField('file', 'File') !!}
		      	</div>
		      </div>
	    </div>
	    <div class="p-modal__footer">
	    	<div class="c-alert u-border-radius">
	    		Are you sure you are ready to save this to your record? You might not be able to undo this action later.
					Please review all your data before saving your record.
					<br>
					<br>
					<br>
					<div class="checkbox">
						<input id="agreement" type="checkbox" name="agreement" class="checkbox" aria-required="true" aria-invalid="true"> 
						<label for="agreement">I agree and wish to continue</label>
					</div>
	    	</div>
	      <div class="p-actions">
	        <div class="c-button-group u-pos-right">
	          <button class="c-button u-border-radius js-close-modal">Cancel</button>
	          <button class="c-button u-border-radius" id="save" style="visibility: hidden;">Okay</button>
	        </div>
	      </div>
	    </div>
	  </form>
  </div>
</div>

<div class="row">
	{{-- [
		'dti',
		'mayors_permit',
		'tax_clearance',
		'philgeps_registraion'
	] --}}
  <br>
  <h3 class="u-margin-l-top">Eligibilities</h3>
  <div class="twelve columns">
		<table class="c-table c-table--stripe c-table--hover">
			<thead>
				<tr>
					<th>Type</th>
					<th>Name</th>
					<th>Ref No.</th>
					<th>Place</th>
					<th>Issued Date</th>
					<th>Validity Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>DTI</td>
					@if(isset($resource['dti']))
					<td>{{$resource['dti']->name}}</td>
					<td>{{$resource['dti']->ref_number}}</td>
					<td>{{$resource['dti']->place}}</td>
					<td>{{$resource['dti']->issued_date}}</td>
					<td>{{$resource['dti']->validity_date}}</td>
					@else
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					@endif
          <td class="u-align-right">
            <div class="c-button-group">
              <button class="c-button c-button--icon js-open-modal" data-type="dti">
                <span class="nc-icon-mini ui-1_edit-72"></span>
              </button>
            </div>
          </td>
				</tr>
				<tr>
					<td>Mayors Permit</td>
					@if(isset($resource['mayors_permit']))
					<td>{{$resource['mayors_permit']->name}}</td>
					<td>{{$resource['mayors_permit']->ref_number}}</td>
					<td>{{$resource['mayors_permit']->place}}</td>
					<td>{{$resource['mayors_permit']->issued_date}}</td>
					<td>{{$resource['mayors_permit']->validity_date}}</td>
					@else
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					@endif
          <td class="u-align-right">
            <div class="c-button-group">
              <button class="c-button c-button--icon  js-open-modal" data-type="mayors_permit">
                <span class="nc-icon-mini ui-1_edit-72"></span>
              </button>
            </div>
          </td>
				</tr>
				<tr>
					<td>Tax Clearance</td>
					@if(isset($resource['tax_clearance']))
					<td>{{$resource['tax_clearance']->name}}</td>
					<td>{{$resource['tax_clearance']->ref_number}}</td>
					<td>{{$resource['tax_clearance']->place}}</td>
					<td>{{$resource['tax_clearance']->issued_date}}</td>
					<td>{{$resource['tax_clearance']->validity_date}}</td>
					@else
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					@endif
          <td class="u-align-right">
            <div class="c-button-group">
              <button class="c-button c-button--icon js-open-modal" data-type="tax_clearance">
                <span class="nc-icon-mini ui-1_edit-72"></span>
              </button>
            </div>
          </td>
				</tr>
				<tr>
					<td>Philgeps Registration</td>
					@if(isset($resource['philgeps_registraion']))
					<td>{{$resource['philgeps_registraion']->name}}</td>
					<td>{{$resource['philgeps_registraion']->ref_number}}</td>
					<td>{{$resource['philgeps_registraion']->place}}</td>
					<td>{{$resource['philgeps_registraion']->issued_date}}</td>
					<td>{{$resource['philgeps_registraion']->validity_date}}</td>
					@else
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					@endif
          <td class="u-align-right">
            <div class="c-button-group">
              <button class="c-button c-button--icon js-open-modal" data-type="philgeps_registraion">
                <span class="nc-icon-mini ui-1_edit-72"></span>
              </button>
            </div>
          </td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@stop

@section('scripts')
<script>
  $(window).on("load", function(){
	  var modal = $('.p-modal');
	  var openModal = $('.js-open-modal');
	  var closeModal = $('.js-close-modal');



	  openModal.on("click", function(){
	    modal.addClass('p-modal--active');
	    $("#type").val($(this).data('type'));
	  })

	  closeModal.on("click", function(){
	    modal.removeClass('p-modal--active');
  	})

	  $('#agreement').on("click", function(){
	  	if($(this).is(":checked")) {
		    $('#save').css('visibility', 'visible');
	  	}else{
		    $('#save').css('visibility', 'hidden');
	  	}
  	})
  })
	
</script>
@stop