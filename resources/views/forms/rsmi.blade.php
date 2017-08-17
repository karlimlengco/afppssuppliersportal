<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

        <style type="text/css">
            body{
                margin:0;
            }
        </style>
    </head>

    <body>

            <div class="printable-form-wrapper">
                <div class="printable-form">

                    <!-- form header -->
                    <div class="printable-form__head">
                        <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                    </div>
                    <!-- form content -->
                    <div class="printable-form__body no-letterhead boxed">
                        <span class="printable-form__head__letterhead__details inside">
                            ARMED FORCES OF THE PHILIPPINES<br>
                            SUPPLY ACCOUNTABLE OFFICER, OTAG<br>
                            Camp General Emilio Aguinaldo, Quezon City
                        </span>
                        <span class="printable-form__body__title">Report of Supplies and Material Issued</span>
                        <table class="printable-form__body__table classic">
                            <tr>
                                <td class="no-border-bottom" colspan="2"></td>
                                <td class="no-border-bottom"></td>
                            </tr>
                            <tr>
                                <td class="border-left-only" width="10%">Supplier</td>
                                <td class="border-right-only" width="50%"><strong>{{$data['supplier']}}</strong></td>
                                <td class="border-right-only" width="40%">Date:</td>
                            </tr>
                            <tr>
                                <td class="border-left-only">PO No.</td>
                                <td class="border-right-only"><strong>{{$data['po']->po_number}}</strong></td>
                                <td class="border-right-only">No:</td>
                            </tr>
                            <tr>
                                <td class="border-left-only">Amount:</td>
                                <td class="border-right-only"><strong>Php {{formatPrice($data['bid_amount'])}}</strong></td>
                                <td class="border-right-only"></td>
                            </tr>
                            <tr>
                                <td class="no-border-bottom no-border-top" colspan="2"></td>
                                <td class="no-border-bottom no-border-top"></td>
                            </tr>
                        </table>
                        <table class="printable-form__body__table classic">
                            <tr>
                                <td colspan="8">To be filled up in the Supply and Property Unit</td>
                            </tr>
                            <tr>
                                <td class="v-align-middle" width="10%"><strong>RIS No.</strong></td>
                                <td class="v-align-middle" width="15%"><strong>Responsibility Code</strong></td>
                                <td class="v-align-middle" width="5%"><strong>Stock No.</strong></td>
                                <td class="v-align-middle" width="25%"><strong>Item</strong></td>
                                <td class="v-align-middle" width="5%"><strong>Unit</strong></td>
                                <td class="v-align-middle" width="5%"><strong>Qty Issued</strong></td>
                                <td class="v-align-middle" width="15%"><strong>Unit Cost</strong></td>
                                <td class="v-align-middle" width="20%"><strong>Amount</strong></td>
                            </tr>
                            @foreach($data['items'] as $key=>$item)
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{$key+1}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->unit}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{formatPrice($item->price_unit)}}</td>
                                <td>{{formatPrice($item->total_amount)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="align-center" colspan="7"><strong style="text-transform:uppercase">{{translateToWords($data['bid_amount'])}} PESOS ONLY.</strong></td>
                                <td><strong>Php{{formatPrice($data['bid_amount'])}}</strong></td>
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
                                    <strong>{{($data['inspector']) ? $data['inspector']->name : ""}}</strong><br>
                                    {{($data['inspector']) ? $data['inspector']->designation : ""}}
                                </td>
                                <td class="v-align-middle align-center" colspan="4">Accounting Clerk</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

    </body>
</html>