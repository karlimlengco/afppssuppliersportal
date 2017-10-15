<!-- letterhead -->
<div class="printable-form__letterhead">
    <span class="printable-form__letterhead__details">
        {{$data["unitHeader"]}}
    </span>
</div>

<!-- title -->
<span class="printable-form__body__title">Certificate of Inspection</span>
<!-- content -->
<p>THIS IS TO CERTIFY that we, the undersigned had conducted actual physical inventory of delivered goods under the following information.</p>
<table class="printable-form__body__table no-border">
    <tr>
        <td width="30%">1. SUPPLIER:</td>
        <td class="align-left" width="70%"><strong>{{$data["supplier_name"]}}</strong></td>
    </tr>
    <tr>
        <td>2. ADDRESS:</td>
        <td class="align-left"><strong>{{$data["supplier_address"]}}</strong></td>
    </tr>
    <tr>
        <td>3. PURCHASE ORDER NO:</td>
        <td class="align-left"><strong>{{$data["po_number"]}}</strong></td>
    </tr>
    <tr>
        <td>4. DELIVERY RECEIPT NO:</td>
        <td class="align-left"><strong>{{$data["delivery_number"]}} ({{$data["delivery_date"]}})</strong></td>
    </tr>
    <tr>
        <td>5. INVOICE NO:</td>
        <td class="align-left">
        <strong>
        {{$data["invoices"]}}
        </strong></td>
    </tr>
    <tr>
        <td>6. PLACE OF DELIVERY:</td>
        <td class="align-left"><strong>{{$data["place"]}}</strong></td>
    </tr>
    <tr>
        <td>7. NATURE OF DELIVERY:</td>
        <td class="align-left"><strong>{{$data["nature_of_delivery"]}}</strong></td>
    </tr>
    <tr>
        <td>8. DATE OF INSPECTION:</td>
        <td class="align-left"><strong>{{$data["inspection_date"]}}</strong></td>
    </tr>
    <tr>
        <td>9. FINDINGS:</td>
        <td class="align-left"><strong>{{$data["findings"]}}</strong></td>
    </tr>
    <tr>
        <td>10. REMARKS/RECOMMENDATIONS:</td>
        <td class="align-left"><strong>{{$data["recommendation"]}}</strong></td>
    </tr>
</table>

<table class="printable-form__body__table no-border no-padding">
    <tr>
        <td width="30%"></td>
        <td class="align-bottom align-left" width="40%" height="80px">
            <strong>{{$data["approver_ranks"]}} {{$data["approver_name"]}} {{$data["approver_branch"]}}</strong><br>
             {{$data["approver_designation"]}}
        </td>
        <td width="30%"></td>
    </tr>
    <tr>
        <td width="30%"></td>
        <td class="align-bottom align-left" width="40%" height="80px">
            <strong>{{$data["requestor_ranks"]}} {{$data["requestor_name"]}} {{$data["requestor_branch"]}}</strong><br>
             {{$data["requestor_designation"]}}
        </td>
        <td width="30%"></td>
    </tr>
    <tr>
        <td width="30%"></td>
        <td class="align-bottom align-left" width="40%" height="80px">
            <strong>{{$data["receiver_ranks"]}} {{$data["receiver_name"]}} {{$data["receiver_branch"]}}</strong><br>
             {{$data["receiver_designation"]}}
        </td>
        <td width="30%"></td>
    </tr>
</table>