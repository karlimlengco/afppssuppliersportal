<div class="modal" id="edit-price-modal" style="z-index:999999999">
    <div class="modal__dialogue modal__dialogue--round-corner">

        <div class="moda__dialogue__head">
            <h1 class="modal__title">Edit Item (<span id="title_description"></span>) </h1>
        </div>

        <div class="row">
            <div class="six columns">
            </div>
        </div>

        <form id="update-item-form" accept-charset="UTF-8">
        <div class="modal__dialogue__body">

              <input name="_method" type="hidden" value="PUT">
              <input name="_token" type="hidden" value="{{ csrf_token() }}">

              <div class="row">
                  <div class="twelve columns">
                      {!! Form::textField('price_unit', 'Price Unit', null, ['required']) !!}
                  </div>
              </div>
        </div>

        <div class="modal__dialogue__foot">
            <button type="button" class="button pull-left" id='close-supplier-button'>Cancel</button>
            <button type="submit" class="button">Proceed</button>
        </div>
        </form>

    </div>
</div>