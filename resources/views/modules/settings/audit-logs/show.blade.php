@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Audit Logs  <small>Details</small></h2>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <div class="row">
                    <div class="col-md-12">
                        <p>
                            <strong>Responsible User: </strong>
                            {{($data->user) ? $data->user->first_name ." ". $data->user->surname :  "Not Available"}}
                        </p>
                        <p>
                            <strong>Event: </strong>
                            {{$data->event}}
                        </p>
                        <p>
                            <strong>APP: </strong>
                            {{$data->auditable_type}}
                        </p>
                        <p>
                            <strong>APP ID: </strong>
                            {{$data->auditable_id}}
                        </p>
                        <p>
                            <strong>IP Address: </strong>
                            {{$data->ip_address}}
                        </p>
                        <p>
                            <strong>Created: </strong>
                            {{$data->created_at}}
                        </p>

                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="ln_solid"></div>

                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <h4>Old Values</h4>
                        </p>

                        <dl class="dl-horizontal">
                            @foreach( json_decode($data->old_values) as $oldKey => $oldValue )
                              <dt>{{$oldKey}}</dt>
                              <dd>{{$oldValue}}</dd>
                            @endforeach
                        </dl>

                    </div>

                    <div class="col-md-6">
                        <p>
                            <h4>New Values</h4>
                        </p>

                        <dl class="dl-horizontal">
                            @foreach( json_decode($data->new_values) as $newKey => $newValue )
                              <dt>{{$newKey}}</dt>
                              <dd>{{$newValue}}</dd>
                            @endforeach
                        </dl>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
@stop