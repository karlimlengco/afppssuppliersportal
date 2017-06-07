@section('contents')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <div class="row">
                    <div class="col-xs-6">
                        <h3 class="box-title">List of Archive Users</h3>
                    </div>
                    <div class="col-xs-6">
                        <a href="{{route('users.index')}}" class="btn btn-primary btn-flat pull-right">List of Users</a>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th class="text-right">Full name</th>
                            <th class="text-right">Position</th>
                            <th class="text-right">Date Employed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td><a href="{{route('users.archives.show', $user->id)}}"> {{$user->username}}</a></td>
                            <td class="text-right">{{$user->first_name}} {{$user->surname}}</td>
                            <td class="text-right">{{$user->position}}</td>
                            <td class="text-right">{{$user->date_of_assumption}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>
@stop