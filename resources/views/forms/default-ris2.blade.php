<!-- letterhead -->
<div class="printable-form__letterhead">
    <span class="printable-form__letterhead__details">
        {{$data["unitHeader"]}}
    </span>
</div>

<!-- title -->
<span class="printable-form__body__title">Requisition and Issue Slip</span>
<!-- content -->
<table class="printable-form__body__table">
    <tr>
        <td class="align-center" width="10%"><strong>STOCK NO.</strong></td>
        <td class="align-center" width="5%"><strong>UOM</strong></td>
        <td class="align-center" width="30%"><strong>DESCRIPTION</strong></td>
        <td class="align-center" width="10%"><strong>QTY</strong></td>
        <td class="align-center" width="15%"><strong>UNIT COST</strong></td>
        <td class="align-center" width="20%"><strong>TOTAL COST</strong></td>
    </tr>
    <tr>
    <td>
      {{$data["items"]}}
    </td>
    </tr>
    <tr>
        <td class="align-center"  colspan="5" style="text-transform:uppercase">{{$data["bid_amount_word"]}} Pesos Only</td>
        <td  class="align-right" >Php {{$data["bid_amount"]}}</td>
    </tr>
    <tr>
        <td class="align-center" colspan="6"><strong>**************************************************************************************************************************</strong></td>
    </tr>
    <tr>
        <td class="align-center" colspan="6">DEBIT PROPERTY ACCOUNTABILITY OF {{$data["receiver_ranks"]}} {{$data["receiver_name"]}} {{$data["receiver_branch"]}}</td>
    </tr>
    <tr>
        <td class="align-center" colspan="6">{{$data["receiver_designation"]}}</td>
    </tr>
    <tr>
        <td colspan="6">PURPOSE: <strong>{{$data["purpose"]}}</strong></td>
    </tr>
    <tr>
        <td class="align-bottom no-border-bottom" height="60px">Signature</td>
        <td class="align-left no-border-bottom" colspan="5">APPROVED BY:</td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom">Name</td>
        <td class="align-center no-border-top no-border-bottom" colspan="5">
            <strong>{{$data["approver_ranks"]}} {{$data["approver_name"]}} {{$data["approver_branch"]}}</strong>
        </td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom">Designation</td>
        <td class="align-center no-border-top no-border-bottom" colspan="5">{{$data["approver_designation"]}}</td>
    </tr>
    <tr>
        <td class="no-border-top">Date</td>
        <td class="align-center" colspan="5"></td>
    </tr>
    <tr>
        <td class="align-bottom no-border-top no-border-bottom" height="60px">Signature</td>
        <td class="align-left no-border-top no-border-bottom" colspan="2">REQUESTED BY:</td>
        <td class="align-left no-border-top no-border-bottom" colspan="3">RECEIVED BY:</td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom">Name</td>
        <td class="align-center no-border-top no-border-bottom" colspan="2">
            <strong>{{$data["requestor_ranks"]}} {{$data["requestor_name"]}} {{$data["requestor_branch"]}}</strong>
        </td>
        <td class="align-center no-border-top no-border-bottom" colspan="3">
            <strong>{{$data["receiver_ranks"]}} {{$data["receiver_name"]}} {{$data["receiver_branch"]}}</strong>
        </td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom">Designation</td>
        <td class="align-center no-border-top no-border-bottom" colspan="2">
            {{$data["requestor_designation"]}}
        </td>
        <td class="align-center no-border-top no-border-bottom" colspan="3">
            {{$data["recceiver_designation"]}}
        </td>
    </tr>
    <tr>
        <td class="no-border-top">Date</td>
        <td class="align-center" colspan="2"></td>
        <td class="align-center" colspan="3"></td>
    </tr>

</table>