@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Roles  <small>Update Details</small></h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                {!! Form::model($role, ['route' => ['settings.roles.update', $role->id], 'id' => 'mainForm', 'method' => 'PUT',  'class' => 'form-horizontal form-label-left' ]) !!}

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
                            {!! Form::tagField('permissions', '', $permissions, ($role->permissions != "") ? array_keys($role->permissions) : "", ['data-max-items' => 10]) !!}
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <a href="{{route('settings.roles.index')}}" class="btn btn-default">Back</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

    @include('modules.partials.modals.delete')
@stop
