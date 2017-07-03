@section('title')
Notifications
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
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
                   <th>Expected Date</th>
                   <th>Working Days</th>
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
                        <td>{{$data['transaction_date']}}</td>
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