<!-- letterhead -->
<div class="printable-form__letterhead">
    <span class="printable-form__letterhead__details">
        {{unitHeader}}
    </span>
</div>

<!-- title -->
<span class="printable-form__body__title">Certificate of Inspection</span>
<!-- content -->
<p>THIS IS TO CERTIFY that we, the undersigned had conducted actual physical inventory of delivered goods under the following information.</p>
<table class="printable-form__body__table no-border">
    <tr>
        <td width="30%">1. SUPPLIER:</td>
        <td class="align-left" width="70%"><strong>{{supplier_name}}</strong></td>
    </tr>
    <tr>
        <td>2. ADDRESS:</td>
        <td class="align-left"><strong>{{supplier_address}}</strong></td>
    </tr>
    <tr>
        <td>3. PURCHASE ORDER NO:</td>
        <td class="align-left"><strong>{{po_number}}</strong></td>
    </tr>
    <tr>
        <td>4. DELIVERY RECEIPT NO:</td>
        <td class="align-left"><strong>{{delivery_number}} ({{delivery_date}})</strong></td>
    </tr>
    <tr>
        <td>5. INVOICE NO:</td>
        <td class="align-left">
        <strong>
        {{invoices}}
        </strong></td>
    </tr>
    <tr>
        <td>6. PLACE OF DELIVERY:</td>
        <td class="align-left"><strong>{{place}}</strong></td>
    </tr>
    <tr>
        <td>7. NATURE OF DELIVERY:</td>
        <td class="align-left"><strong>{{nature_of_delivery}}</strong></td>
    </tr>
    <tr>
        <td>8. DATE OF INSPECTION:</td>
        <td class="align-left"><strong>{{inspection_date}}</strong></td>
    </tr>
    <tr>
        <td>9. FINDINGS:</td>
        <td class="align-left"><strong>{{findings}}</strong></td>
    </tr>
    <tr>
        <td>10. REMARKS/RECOMMENDATIONS:</td>
        <td class="align-left"><strong>{{recommendation}}</strong></td>
    </tr>
</table>

<table class="printable-form__body__table no-border no-padding">
    <tr>
        <td width="30%"></td>
        <td class="align-bottom align-left" width="40%" height="80px">
            <strong>{{approver_ranks}} {{approver_name}} {{approver_branch}}</strong><br>
             {{approver_designation}}
        </td>
        <td width="30%"></td>
    </tr>
    <tr>
        <td width="30%"></td>
        <td class="align-bottom align-left" width="40%" height="80px">
            <strong>{{requestor_ranks}} {{requestor_name}} {{requestor_branch}}</strong><br>
             {{requestor_designation}}
        </td>
        <td width="30%"></td>
    </tr>
    <tr>
        <td width="30%"></td>
        <td class="align-bottom align-left" width="40%" height="80px">
            <strong>{{receiver_ranks}} {{receiver_name}} {{receiver_branch}}</strong><br>
             {{receiver_designation}}
        </td>
        <td width="30%"></td>
    </tr>
</table>