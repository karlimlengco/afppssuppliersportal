<!-- letterhead -->
<div class="printable-form__letterhead" style="padding-top:50px">
    <span class="printable-form__letterhead__details">
        {{unitHeader}}
    </span>
</div>

<!-- title -->
<span class="printable-form__body__title">PURCHASE ORDER/WORK ORDER/JOB ORDER</span>

<table class="printable-form__body__table">
    <tr>
        <td class="no-border-bottom" colspan="4"></td>
    </tr>
    <tr>
        <td class="border-left-only" width="80px" nowrap>Supplier</td>
        <td class="no-border" width="50%"><strong>{{winner_name}}</strong></td>
        <td class="no-border" width="80px" nowrap>P.O. No.</td>
        <td class="border-right-only" width="30%"><strong>{{po_number}}</strong></td>
    </tr>
    <tr>
        <td class="border-left-only" nowrap>Address</td>
        <td class="no-border"><strong>{{winner_address}}</strong></td>
        <td class="no-border" nowrap>Date</td>
        <td class="border-right-only"><strong>{{purchase_date}}</strong></td>
    </tr>
    <tr>
        <td class="border-left-only" nowrap>Email Address</td>
        <td class="no-border"><strong>{{winner_email}}</strong></td>
        <td class="no-border" nowrap>Proc Mode</td>
        <td class="border-right-only"><strong>{{mode}}</strong></td>
    </tr>
    <tr>
        <td class="border-left-only" nowrap>Telephone No</td>
        <td class="no-border"><strong>{{winner_phone}}</strong></td>
        <td class="no-border" nowrap></td>
        <td class="border-right-only"><strong></strong></td>
    </tr>
    <tr>
        <td class="border-left-only" nowrap>TIN</td>
        <td class="border-right-only" colspan="3"><strong>{{winner_tin}}</strong></td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom" colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4">
            Sir/Madam,<br>Please furnish this office the following articles subject to the terms and conditions contained herein:
        </td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom" colspan="4"></td>
    </tr>
    <tr>
        <td class="border-left-only" nowrap>Place of Delivery</td>
        <td class="no-border"><strong>{{unit}}</strong></td>
        <td class="no-border" nowrap>Delivery Schedule</td>
        <td class="border-right-only"><strong>{{delivery_expected_date}}</strong></td>
    </tr>
    <tr>
        <td class="border-left-only" nowrap>Delivery Term</td>
        <td class="no-border"><strong>{{delivery_term}} Calendar Days upon conformed of this PURCHASE ORDER</strong></td>
        <td class="no-border" nowrap>Payment Term</td>
        <td class="border-right-only"><strong>{{term}}</strong></td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom" colspan="4"></td>
    </tr>
</table>


<table class="printable-form__body__table">
    <tr>
        <td class="align-center" width="10%"><strong>ITEM NO</strong></td>
        <td class="align-center" width="10%"><strong>UOM</strong></td>
        <td class="align-center" width="40%"><strong>DESCRPTION</strong></td>
        <td class="align-center" width="10%"><strong>QTY</strong></td>
        <td class="align-center" width="15%"><strong>UNIT COST</strong></td>
        <td class="align-center" width="15%"><strong>AMOUNT</strong></td>
    </tr>
    <tr>
      <td> {{items}} </td>
    </tr>

    <tr>
        <td class="align-center" colspan="4"><strong style="text-transform:uppercase">{{total_word}} PESOS ONLY.</strong></td>
        <td colspan="2" class="align-right"><strong>{{total}}</strong></td>
    </tr>
</table>

<div style="page-break-inside: avoid;">
<table class="printable-form__body__table">
    <tr>
        <td colspan="6">
            BASIS: {{purpose}}<br>
            REFERENCES: UPR No. {{upr_number}}
        </td>
    </tr>
    <tr>
        <td class="align-top no-border-bottom" colspan="6" height="40px">In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent (1%) for every day of delay shall be imposed.</td>
    </tr>

    <table class="printable-form__body__table">
        <tr>
            <td class="border-left-only" width="45%"></td>
            <td class="no-border" width="10%"></td>
            <td class="border-right-only" width="45%">
                Very truly yours,
                <br>
                <strong class="margin-top">{{approver_ranks}} {{approver_name}} {{approver_branch}}</strong>
                <br>
                {{approver_designation}}
            </td>
        </tr>
    </table>
    <tr>
        <td class="border-left-only" width="9%" height="30px"></td>
        <td class="no-border" width="1%" height="40px">Conforme:</td>
        <td class="border-bottom-only" width="40%" height="30px"></td>
        <td class="border-right-only" width="50%" height="30px"></td>
    </tr>
    <tr>
        <td class="border-left-only" colspan="2" height="30px"></td>
        <td class="align-center no-padding border-bottom-only" height="30px">Signature over printed name of Supplier</td>
        <td class="border-right-only" height="30px"></td>
    </tr>
    <tr>
        <td class="border-left-only" colspan="2"></td>
        <td class="align-center no-padding no-border">Date</td>
        <td class="border-right-only"></td>
    </tr>
    <tr>
        <td class="no-border-top" colspan="4"></td>
    </tr>
</table>


<table class="printable-form__body__table">
    <tr>
        <td class="border-left-only" width="13%" height="30px">Funds Available:</td>
        <td class="no-border" width="1%"></td>
        <td class="no-border" width="40%"></td>
        <td class="no-border" width="15%"></td>
        <td class="no-border" width="1%"></td>
        <td class="no-border" width="20%"></td>
        <td class="border-right-only" width="10%"></td>
    </tr>
    <tr>
        <td class="no-padding border-left-only"></td>
        <td class="no-padding no-border" colspan="2"><strong>{{accounting_ranks}} {{accounting_name}} {{accounting_branch}}</strong></td>
        <td class="no-padding no-border"></td>
        <td class="no-padding no-border" nowrap>OBR/BUR No</td>
        <td class="no-padding border-bottom-only"></td>
        <td class="no-padding border-right-only"></td>
    </tr>
    <tr>
        <td class="no-padding border-left-only"></td>
        <td class="no-padding no-border" colspan="2">
                {{accounting_designation}}</td>
        <td class="no-padding no-border"></td>
        <td class="no-padding no-border"></td>
        <td class="no-padding no-border"></td>
        <td class="no-padding border-right-only"></td>
    </tr>
    <tr>
        <td class="no-padding border-left-only"></td>
        <td class="no-padding no-border">Date</td>
        <td class="no-padding border-bottom-only"></td>
        <td class="no-padding no-border"></td>
        <td class="no-padding no-border" nowrap>Amount</td>
        <td class="no-padding border-bottom-only"></td>
        <td class="no-padding border-right-only"></td>
    </tr>
    <tr>
        <td class="no-border-top" colspan="7" height="20px"></td>
    </tr>
</table>


<table class="printable-form__body__table">
    <tr>
        <td class="border-left-only" width="12%" height="30px">Approved By:</td>
        <td class="no-border" width="40%"></td>
        <td class="no-border" width="19%"></td>
        <td class="no-border" width="1%"></td>
        <td class="no-border" width="20%"></td>
        <td class="border-right-only" width="8%"></td>
    </tr>
    <tr>
        <td class="no-padding border-left-only"></td>
        <td class="no-padding no-border"><strong>{{coa_signatories_ranks}} {{coa_signatories_name}} {{coa_signatories_branch}}</strong></td>
        <td class="no-padding no-border"></td>
        <td class="no-padding no-border" nowrap>Date</td>
        <td class="no-padding border-bottom-only"></td>
        <td class="no-padding border-right-only"></td>
    </tr>
    <tr>
        <td class="no-padding border-left-only"></td>
        <td class="no-padding no-border">{{coa_signatories_designation}}</td>
        <td class="no-padding no-border"></td>
        <td class="no-padding no-border"></td>
        <td class="no-padding no-border"></td>
        <td class="no-padding border-right-only"></td>
    </tr>
    <tr>
        <td class="no-border-top no-border-bottom" colspan="6" height="15px"></td>
    </tr>
</table>
</div>