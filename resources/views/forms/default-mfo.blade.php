<!-- letterhead -->
<div class="printable-form__letterhead">
    <span class="printable-form__letterhead__details">
        {{$data["unitHeader"]}}
    </span>
</div>
<!-- title -->
<span class="printable-form__body__title">MFO Inspection</span>
<!-- content -->
<p>Management FiscalOffice (MFO)</p>
<p>Sir/Madam:</p>
<p>May I respectfully request the availability of a representative from your office to conduct inspection of goods delivered to this office under PURCHASE ORDER No.{{$data["po_number"]}}</p>
<table class="printable-form__body__table no-border">
    <tr>
        <td width="20%">ACCOUNT OF:</td>
        <td class="align-left" width="40%"><strong>{{$data["venue"]}}</strong></td>
        <td width="40%"></td>
    </tr>
    <tr>
        <td>DELIVERED AT:</td>
        <td class="align-left"><strong>{{$data["venue"]}}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>DATE DELIVERED:</td>
        <td class="align-left"><strong>{{$data["delivery_date"]}}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>DEALER/CONTRACTOR:</td>
        <td class="align-left"><strong>{{$data["winner"]}}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>COMMERCIAL INVOICE:</td>
        <td class="align-left"><strong>
        {{$data["invoice"]}}
        </strong></td>
        <td></td>
    </tr>
    <tr>
        <td>DELIVERY RECEIPT NO.:</td>
        <td class="align-left"><strong>{{$data["delivery_number"]}} {{$data["delivery_date"]}}</strong></td>
        <td></td>
    </tr>
</table>
<table class="printable-form__body__table no-border no-padding">
    <tr>
        <td class="align-bottom align-left" width="45%" height="60px">
            <strong>{{$data["sao_ranks"]}} {{$data["sao_name"]}} {{$data["sao_branch"]}}</strong><br>
            {{$data["sao_designation"]}}
        </td>
        <td width="10%"></td>
        <td class="align-bottom" width="45%"></td>
    </tr>
    <tr>
        <td class="border-bottom-only" colspan="3" height="30px"></td>
    </tr>
</table>
<p class="align-center">
    <strong>MFO INSPECTION REPORT</strong><br>
    (For MFO Portion Only)
</p>
<p class="align-right">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p>TO:</p>
<p>Please conduct inspection on the above-cited request for inspection.</p>
<p>FINDINGS/REMARKS:</p>
<p class="align-center"></p>
<table class="printable-form__body__table no-border no-padding">
    <tr>
        <td colspan="4" height="40px"></td>
    </tr>
    <tr>
        <td width="45%"></td>
        <td width="10%"></td>
        <td width="1%" nowrap>INSPECTED BY:</td>
        <td class="align-bottom" width="45%" height="80px">
            <strong></strong><br>

        </td>
    </tr>
    <tr>
        <td class="align-top" width="45%" height="50px">NOTED BY:</td>
        <td width="10%"></td>
        <td width="1%"></td>
        <td width="45%"></td>
    </tr>
    <tr>
        <td width="45%">
            <strong></strong><br>

        </td>
        <td width="10%"></td>
        <td width="1%"></td>
        <td width="45%"></td>
    </tr>
    <tr>
        <td width="45%"></td>
        <td width="10%"></td>
        <td width="1%" nowrap>WITNESSED BY:</td>
        <td class="align-bottom" width="45%" height="80px">
            <strong></strong><br>

        </td>
    </tr>
</table>