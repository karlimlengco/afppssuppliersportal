@section('title')
Notifications
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
    <div class="six columns utility utility--align-right" >
        <a class="button" href="{{route('settings.permissions.create')}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div>


<div class="row">
   <div class="twelve columns">
        <table id="datatable-responsive" class="table" >
           <thead>
               <tr>
                   <th>UPR Number</th>
                   <th>Ref Number</th>
                   <th>Name</th>
                   <th>ABC</th>
                   <th>Status</th>
                   <th>Days</th>
               </tr>
           </thead>
           <tbody>
                @foreach($resources as $data)
                    <tr>
                        <td>{{$data['upr_number']}}</td>
                        <td>{{$data['ref_number']}}</td>
                        <td>{{$data['project_name']}}</td>
                        <td>{{formatPrice($data['total_amount'])}}</td>
                        <td>{{$data['event']}}</td>
                        <td>{{$data['days']}}</td>
                    </tr>
                @endforeach
           </tbody>
       </table>
   </div>
</div>

@stop

@section('scripts')
@stop