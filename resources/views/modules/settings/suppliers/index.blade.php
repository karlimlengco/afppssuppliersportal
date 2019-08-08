@section('title')
Suppliers
@stop

@section('search')
{!! Form::open(['route'  => 'settings.suppliers.index', 'method'=>'get', 'style' =>'width:100%']) !!}
<input type="text"  name="search" class="sidebar__search__input" id="newForm"  placeholder="Looking for something?">
<button style="float:right" class="sidebar__search__button"><i class="nc-icon-mini ui-1_zoom"></i></button>
{!! Form::close() !!}
@stop


@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
    <div class="six columns utility utility--align-right" >

        <a target="_blank" href="{{route('settings.suppliers.print')}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>
        <span class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="{{route($draftRoute)}}" class="button__options__item">Drafts</a>
            </div>
        </span>

        <a class="button" href="{{route($createRoute)}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Owner</th>
                    <th>Address</th>
                    <th>Line of Business</th>
                    <th>DTI</th>
                    <th>Mayors Permit</th>
                    <th>Tax Clearance</th>
                    <th>Philgeps Posting</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resources as $data)
                    <tr>
                        <td>
                            <a href="{{route( 'settings.suppliers.show',[$data->id] )}}">
                            {{$data->name}}
                            </a>
                        </td>
                        <td>{{$data->owner}}</td>
                        <td>{{$data->address}}</td>
                        <td>{{$data->line_of_business}}</td>
                        <td>{{$data->dti_validity_date}}</td>
                        <td>{{$data->mayors_validity_date}}</td>
                        <td>{{$data->tax_validity_date}}</td>
                        <td>{{$data->philgeps_validity_date}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <?php echo $resources->render(); ?>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">

    // table = $('#datatable-responsive').DataTable({
    //     "bLengthChange": false,
    //     processing: true,
    //     serverSide: true,
    //     ordering: true,
    //     "order": [[ 0 , "asc" ]],
    //     ajax: {
    //             url: "{{route('datatables.settings.suppliers')}}",
    //         },
    //     columns: [
    //         {data: 'name', name: 'name'},
    //         {data: 'owner', name: 'owner'},
    //         {data: 'address', name: 'address'},
    //         {data: 'line_of_business', name: 'line_of_business'},
    //         {data: 'dti_validity_date', name: 'dti_validity_date'},
    //         {data: 'mayors_validity_date', name: 'mayors_validity_date'},
    //         {data: 'tax_validity_date', name: 'tax_validity_date'},
    //         {data: 'philgeps_validity_date', name: 'philgeps_validity_date'}
    //     ],
    //     "fnInitComplete": function (oSettings, json) {
    //         $("#datatable-responsive_previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $("#datatable-responsive_next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     }

    // });

    // overide datatable filter for custom css
    // $('#newForm').keyup(function(){
    //       table.search($(this).val()).draw() ;
    // })
</script>
@stop