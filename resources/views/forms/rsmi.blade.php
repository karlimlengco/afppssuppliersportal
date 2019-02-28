<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

        <style type="text/css">
            body{
                margin:0;
            }
            @page{margin:0;padding:0;}
        </style>
    </head>

    <body>

            <div class="printable-form-wrapper" style="padding-top:50px">
                <div class="printable-form">
                    <!-- form header -->
                    <div class="printable-form__head">
                        <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                    </div>
                    <!-- form body -->
                    <div class="printable-form__body boxed">
                        <!-- letterhead -->
                        <div class="printable-form__letterhead">
                            <span class="printable-form__letterhead__details">
                            {!!$data['unitHeader']!!}
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
                                <td class="border-right-only" width="50%"><strong>{{$data['supplier']}}</strong></td>
                                <td class="border-right-only" width="40%">Date:</td>
                            </tr>
                            <tr>
                                <td class="border-left-only">PO No.</td>
                                <td class="border-right-only"><strong>{{$data['po_number']}}</strong></td>
                                <td class="border-right-only">No:</td>
                            </tr>
                            <tr>
                                <td class="border-left-only">Amount:</td>
                                <td class="border-right-only"><strong>{{formatPrice($data['bid_amount'])}}</strong></td>
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

                            @foreach($data['items'] as $key=>$item)
                            <tr>

                                <td></td>
                                <td></td>
                                <td class="align-center" >{{$key+1}}</td>
                                <td class="align-left" >{{$item->description}}</td>
                                <td class="align-center" >{{$item->unit}}</td>
                                <td class="align-center" >{{$item->quantity}}</td>
                                <td class="align-right" >{{formatPrice($item->price_unit)}}</td>
                                <td class="align-right" >{{formatPrice($item->total_amount)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="align-center"  colspan="7" style="text-transform:uppercase">{{translateToWords($data['bid_amount'])}}</td>
                                <td  class="align-right" >Php {{formatPrice($data['bid_amount'])}}</td>
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
                                <td><strong>Total Cost</strong></td>
                                <td><strong>Account Code</strong></td>
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
                                    <strong>{{$data['issuer'][1]}} {{$data['issuer'][0]}} {{$data['issuer'][2]}}</strong><br>
                                    {{$data['issuer'][3]}}
                                </td>
                                <td class="align-middle align-center" colspan="4">Accounting Clerk</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

    </body>
</html>