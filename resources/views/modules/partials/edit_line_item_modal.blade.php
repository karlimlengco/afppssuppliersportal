<div class="modal" id="edit-line-item-modal" style="z-index:999999999">
    <div class="modal__dialogue modal__dialogue--round-corner">

        <div class="moda__dialogue__head">
            <h1 class="modal__title">Edit Item (<span id="title_description"></span>) </h1>
        </div>

        <div class="row">
            <div class="six columns">
            </div>
        </div>

        <form id="update-line-item-form" accept-charset="UTF-8" method="post">
        <div class="modal__dialogue__body">

              <input name="_method" type="hidden" value="PUT">
              <input name="_token" type="hidden" value="{{ csrf_token() }}">

              <div class="row">
                  <div class="twelve columns">
                      {!! Form::textField('quantity', 'Quantity', null, ['required', 'class' => 'input id-field-quantity']) !!}
                  </div>
              </div>
              <div class="row">
                  <div class="twelve columns">
                      {!! Form::textField('unit_price', 'Unit Price', null, ['required', 'class' => 'input id-field-unit_price']) !!}
                  </div>
              </div>
              <div class="row">
                  <div class="twelve columns">
                      {!! Form::textField('unit_measurement', 'Unit of Measurement', null, ['required', 'class' => 'input id-field-unit_measurement']) !!}
                  </div>
              </div>
        </div>

        <div class="modal__dialogue__foot">
            <button type="button" class="button pull-left" id='close-line-item-button'>Cancel</button>
            <button type="submit" class="button">Proceed</button>
        </div>
        </form>

    </div>
</div>