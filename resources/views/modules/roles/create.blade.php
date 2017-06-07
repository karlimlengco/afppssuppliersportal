@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Roles  <small>Add New</small></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                {!! Form::open(['route' => 'settings.roles.store', 'id' => 'mainForm',  'class' => 'form-horizontal form-label-left' ]) !!}

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Name <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::textField('name', '') !!}
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Slug <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::textField('slug', '') !!}
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Permissions <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::tagField('permissions', '', $permissions) !!}
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>

                {!!Form::close()!!}

            </div>
        </div>
    </div>
</div>

@stop
