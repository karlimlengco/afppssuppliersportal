<!-- letterhead -->
<div class="printable-form__letterhead">
    <span class="printable-form__letterhead__details">
        {{$data["unitHeader"]}}
    </span>
</div>

<!-- title -->
<span class="printable-form__body__title">Request Acknowledge Receipt</span>
<!-- content -->
<p>07 June, 2017</p>
<p>Office of the Auditor<br>
COA General Headquarters (GHQ)<br>
Camp General Emilio Aguinaldo, Quezon City</p>
<p>Sir/Madam:</p>
<p>In compliance with COA Circuar No. 96-010 dated August 15, 1996 we are furnishing your office copy/ies of invoice/s and Delivery Receipt/s, delivered herin for your information and appropriate</p>
<table class="printable-form__body__table ">
    <tr>
        <td width="20%">SUPPLIER:</td>
        <td class="align-left" width="40%"><strong>{{$data["supplier"]}}</strong></td>
        <td width="40%"></td>
    </tr>
    <tr>
        <td>PURCHASE ORDER</td>
        <td class="align-left"><strong>{{$data["po_number"]}}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>DELIVERY RECEIPT:</td>
        <td class="align-left"><strong>{{$data["delivery_number"]}} ({{$data["delivery_date"]}})</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>INVOICE NUMBER:</td>
        <td class="align-left"><strong>{{$data["invoices"]}}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>PLACE OF DELIVERY:</td>
        <td class="align-left"><strong>{{$data["place"]}}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>AMOUNT:</td>
        <td class="align-left"><strong>Php {{$data["bid_amount"]}}</strong></td>
        <td></td>
    </tr>
</table>
<p>Subject deliveries had/have been inspected and accepted by the Technical Inspection and Acceptance Committee (TIAC) per attached Certificate of date 07 June 2017 and having found in accordance with the specifications/s.</p>
<p>Respectfully yours,</p>
<table class="printable-form__body__table no-border no-padding">
    <tr>
        <td class="align-bottom align-left" width="45%" height="80px">
          <strong>{{$data["requestor_ranks"]}} {{$data["requestor_name"]}} {{$data["requestor_branch"]}}</strong><br>
          {{$data["requestor_designation"]}}
        </td>
        <td width="10%"></td>
        <td class="align-bottom" width="45%"></td>
    </tr>
</table>