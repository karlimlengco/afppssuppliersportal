<div class="modal" id="unit-attachment-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="my-awesome-dropzone" enctype="multipart/form-data"  action="{{ route($modelConfig['add_attachment']['route'][0], $modelConfig['add_attachment']['route'][1]) }}"   accept-charset="UTF-8" >
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Add Attachment</h1>
            </div>

            <div class="modal__dialogue__body">
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
                        <select name="validity_date" class="select" id="id-field-validity_date">
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

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>