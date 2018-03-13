<!-- letterhead -->
<div class="printable-form__letterhead" style="padding-top:50px">
    <span class="printable-form__letterhead__details">
        {{unitHeader}}
    </span>
</div>

<!-- title -->
<span class="printable-form__body__title">Report of Supplies and Material Issued</span>
<!-- content -->
<table class="printable-form__body__table">
    <tr>
        <td class="no-border-bottom" colspan="2"></td>
        <td class="no-border-bottom"></td>
    </tr>
    <tr>
        <td class="border-left-only" width="10%">Supplier</td>
        <td class="border-right-only" width="50%"><strong>{{supplier}}</strong></td>
        <td class="border-right-only" width="40%">Date:</td>
    </tr>
    <tr>
        <td class="border-left-only">PO No.</td>
        <td class="border-right-only"><strong>{{po_number}}</strong></td>
        <td class="border-right-only">No:</td>
    </tr>
    <tr>
        <td class="border-left-only">Amount:</td>
        <td class="border-right-only"><strong>{{bid_amount}}</strong></td>
        <td class="border-right-only"></td>
    </tr>
    <tr>
        <td class="no-border-bottom no-border-top" colspan="2"></td>
        <td class="no-border-bottom no-border-top"></td>
    </tr>
</table>


<table class="printable-form__body__table">
    <tr>
        <td colspan="8">To be filled up in the Supply and Property Unit</td>
    </tr>
    <tr>
        <td class="align-middle align-center" width="10%"><strong>RIS No.</strong></td>
        <td class="align-middle align-center" width="10%"><strong>Responsibility Code</strong></td>
        <td class="align-middle align-center" width="5%"><strong>Stock No.</strong></td>
        <td class="align-middle align-center" width="35%"><strong>Item</strong></td>
        <td class="align-middle align-center" width="5%"><strong>Unit</strong></td>
        <td class="align-middle align-center" width="5%"><strong>Qty Issued</strong></td>
        <td class="align-middle align-center" width="15%"><strong>Unit Cost</strong></td>
        <td class="align-middle align-center" width="15%"><strong>Amount</strong></td>
    </tr>
    <tr>
    <td>
      {{items}}
    </td>
    </tr>
    <tr>
        <td class="align-center"  colspan="7" style="text-transform:uppercase">{{bid_amount_word}} Pesos Only</td>
        <td  class="align-right" >Php {{bid_amount}}</td>
    </tr>
    <tr>
        <td colspan="8" height="80px"></td>
    </tr>
    <tr>
        <td class="align-center" colspan="8"><strong>RECAPITULATION</strong></td>
    </tr>
    <tr>
        <td><strong></strong></td>
        <td><strong>Stock No.</strong></td>
        <td><strong>Qty</strong></td>
        <td><strong></strong></td>
        <td colspan="2"><strong>Unit Cost</strong></td>
        <td><strong>Unit Cost</strong></td>
        <td><strong>Amount</strong></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td class="no-border-bottom" colspan="4" height="60px"></td>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td class="align-center no-border-top" colspan="4">
            {{receiver_ranks}} {{receiver_name}} {{receiver_branch}}</strong><br>
            {{receiver_designation}}
        </td>
        <td class="align-middle align-center" colspan="4">Accounting Clerk</td>
    </tr>
</table>