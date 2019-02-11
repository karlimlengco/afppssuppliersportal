<!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

    </head>

    <body>

        <div class="printable-form-wrapper" style="padding-top:50px">
            <div class="printable-form">
                <p style="height: 20px">
                    <span style="text-align:left;float:left">OFM, AFP Form Nr 1</span>
                    <span style="text-align:right;float:right;width:400px">Annex A to AFP FML, NR 2018-08 dtd 25 October 2018</span>
                </p>
                <!-- form header -->
                {{-- <div class="printable-form__head"> --}}
                    {{-- <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div> --}}

                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    {{-- <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span>
                        <span class="printable-form__letterhead__details">
                            {!!$data['unitHeader']!!}
                        </span>
                    </div> --}}
                    <!-- title -->
                    <span class="printable-form__body__title">Delivered Item(s) Inspected Report</span>
                    <!-- content -->
                    <p>Section A: Request for Inspection</p>
                    <p>
                        {{$data['supplier']->owner}}<br>
                        {{$data['supplier']->name}}<br>
                        {{$data['supplier']->address}}
                    </p>

                    <p>Request conduct inspection of delivered items described as follows:</p>
                    <table class="printable-form__body__table classic">
                        <tr>
                            <td class="align-left">
                                <span class="label">Unit</span>
                                {{$data['units']}}
                            </td>
                            <td class="align-left" colspan="3">
                                <span class="label">Name of Dealer/Supplier</span>
                                {{$data['supplier']->name}}
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" width="25%">
                                <span class="label">Date of Delivery</span>
                                {{$data['date']}}
                            </td>
                            <td class="align-left" width="25%">
                                <span class="label">Place of Delivery</span>
                                {{$data['place']}}
                            </td>
                            <td class="align-left" width="30%">
                                <span class="label">Sales Invoice/Delivery Receipt Nr</span>
                                @foreach($data['invoice'] as $invoice)
                                    {{$invoice->invoice_number}} / {{$data['delivery_number']}}
                                @endforeach
                            </td>
                            <td class="align-left" width="20%">
                                <span class="label">Date of Invoice Receipt</span>
                                @foreach($data['invoice'] as $invoice)
                                    {{$invoice->invoice_date}} / {{$data['delivery_date']}}
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="has-child" colspan="4" >
                                <table class="printable-form__body__table classic" >
                                    <tr>
                                        <td class="align-center" width="10%">Qty</td>
                                        <td class="align-center" width="10%">U/I</td>
                                        <td class="align-center" width="40%">Article/Nomenclature</td>
                                        <td class="align-center" width="20%">Amount Unit Item</td>
                                        <td class="align-center" width="20%">Total Amount</td>
                                    </tr>
                                    <?php  $total = 0; ?>
                                    @foreach($data['items'] as $key=>$item)
                                        <tr>
                                            <td class="align-center" >{{$item->quantity}}</td>
                                            <td class="align-center">{{$item->unit}}</td>
                                            <td class="align-left" >{{$item->description}}</td>
                                            <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                                            <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                                        </tr>
                                        <?php  $total = $total + $item->total_amount; ?>
                                    @endforeach
                                    <tr>
                                        <td colspan="4"  class="align-right">Total</td>
                                        <td class="align-right">Php {{formatPrice($total)}}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-center" colspan="5">*** Nothing Follows ***</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" colspan="2">
                                <span class="label">Purchase Order/Contract Nr</span>
                                {{$data['po_number']}}
                            </td>
                            <td class="align-left" colspan="2">
                                <span class="label">Date of Serving of PO</span>
                                {{$data['po_date']}}
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" colspan="2" >
                                <span class="label">Requested by:</span>
                                <br>
                                <span style="padding-left: 50px ">__________________________________</span>
                                <br>
                                <br>
                                <span style="padding-left: 50px ">__________________________________</span>
                                <br>
                                <b style="padding-left: 50px ">SAO</b>
                                <br>
                                <span style="padding-left: 50px ">Date signed ________________________</span>
                            </td>
                            <td class="align-left" colspan="2" >
                                <span class="label">Noted by:</span>
                                <br>
                                <span style="padding-left: 50px ">__________________________________</span>
                                <br>
                                <br>
                                <span style="padding-left: 50px ">__________________________________</span>
                                <br>
                                <b style="padding-left: 50px ">Procurement Officer</b>
                                <br>
                                <span style="padding-left: 50px ">Date signed ________________________</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" colspan="4" >
                                <b style="font-weight: bold">Section B: Report of Inspection and Action Taken:</b>
                                <br>
                                <span style="padding-left: 50px ">To:  _______________________________</span>
                                <br>
                                <span style="padding-left: 75px ">_______________________________</span>
                                <br>
                                <br>
                                <span style="padding-left: 75px ">The above delivered items have been inspected with the following findings and observations:
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" colspan="4" >
                                <b style="font-weight: bold">FINDINGS/OBSERVATIONS:</b>
                                <br>
                                <span >_____________________________________________________________________________________________________</span>
                                <br>
                                <span >_____________________________________________________________________________________________________</span>
                                <br>
                                <span >_____________________________________________________________________________________________________</span>
                                <br>
                                <span >_____________________________________________________________________________________________________</span>
                                <br>
                                <span >_____________________________________________________________________________________________________</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" colspan="4" >
                                <b style="font-weight: bold">Date of Inspection:</b>
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-left" colspan="2" >
                                <b style="font-weight: bold">Inspected by:</b>
                                <br>
                                <span>_______________________________</span>
                                <br>
                                <span>_______________________________</span>
                                <br>
                                OFM Inspector
                                <br>
                                <span>Date signed _____________________</span>
                                
                            </td>
                            <td class="align-left" colspan="1" >
                                <b style="font-weight: bold">Witnessed by:</b>
                                <br>
                                <span>_______________________________</span>
                                <br>
                                <span>_______________________________</span>
                                <br>
                                <br>
                                <span>Date signed _____________________</span>
                            </td>
                            <td class="align-left" colspan="1" >
                                <b style="font-weight: bold">Certified by:</b>
                                <br>
                                <span>_______________________________</span>
                                <br>
                                <span>_______________________________</span>
                                <br>
                                Chief, Pre-Audit Div OFM
                                <br>
                                <span>Date signed _____________________</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- form content -->

            </div>

        </div>

    </body>
</html>