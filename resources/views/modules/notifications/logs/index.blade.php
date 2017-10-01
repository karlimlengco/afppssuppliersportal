@section('title')
Change Logs
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
                   <th></th>
                   <th>Module</th>
                   <th>User</th>
               </tr>
           </thead>
           <tbody>
                @foreach($resources as $data)
                <?php $url = $data['url'];?>
                <?php $id = $data['auditable_id'];?>
                    <tr>
                        <td><a target="_blank" href="{{ preg_replace("/$id$/", '', $url ). "logs/".$id }}">View here</a></td>
                        <td>{{$data['auditable_type']}}</td>
                        <td>{{$data['full_name']}}</td>
                    </tr>
                @endforeach
           </tbody>
       </table>
   </div>
</div>

@stop

@section('scripts')
@stop