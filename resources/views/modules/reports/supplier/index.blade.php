@section('title')
Supplier Eligibility Report
@stop

@section('styles')
@stop

@section('contents')


<div class="table-scroll alternative">
    <table id="datatable-responsive" class="table table--with-border">
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Owners</th>
                <th>DTI</th>
                <th>Mayors Permit</th>
                <th>Tax Clearance</th>
                <th>Philgeps</th>
            </tr>
        </thead>
        <tbody>
          @foreach($suppliers as $supplier)
            <tr>
              <td>{{$supplier->name}}</td>
              <td>{{$supplier->owner}}</td>
              <td>{{$supplier->dti_validity_date}}</td>
              <td>{{$supplier->mayors_validity_date}}</td>
              <td>{{$supplier->tax_validity_date}}</td>
              <td>{{$supplier->philgeps_validity_date}}</td>
            </tr>
          @endforeach
        </tbody>
    </table>
    <?php echo $suppliers->render(); ?>
</div>

@stop

@section('scripts')
<script type="text/javascript">
</script>
@stop