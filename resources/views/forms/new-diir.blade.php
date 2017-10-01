<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">

    </head>

    <body>

        <div class="printable-form-wrapper">
            <div class="printable-form">
                <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>

                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__logo">
                            <img src="{{base_path('public/img/form-logo.png')}}" alt="">
                        </span>
                        <span class="printable-form__letterhead__details">
                            {!!$data['unitHeader']!!}
                        </span>
                    </div>
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
                            <td class="has-child" colspan="4">
                                <table class="printable-form__body__table classic">
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
                    </table>
                </div>
                <!-- form content -->

            </div>

        </div>

    </body>
</html>