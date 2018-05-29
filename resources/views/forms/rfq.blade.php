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
        </style>
    </head>

    <body>

            <div class="printable-form-wrapper" style="padding-top:50px">
                <div class="printable-form">
                    <div class="printable-form__head">
                        <p class="printable-form__head__vision"> AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
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
                        <span class="printable-form__body__title">Request for Quotation</span>
                        <!-- content -->
                        <table class="printable-form__body__table no-border no-padding">
                            <tr>
                                <td class="align-left" width="50%">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['transaction_date'])->format('d F Y')}}</td>
                                <td class="align-right" width="50%">RFQ No: {{  str_replace('AMP', 'RFQ', $data['rfq_number'])}}</td>
                            </tr>
                        </table>
                        <p>SIR/MADAME,</p>
                        <p>Please quote your lowest price on the items listed below, subject to the following conditions and submit your quotation duly signed by you or your representative not later than {{$data['deadline']->format('dHi F y') }}</p>
                        <table class="printable-form__body__table no-border no-padding">
                            <tr>
                                <td class="align-bottom" width="45%"></td>
                                <td width="10%"></td>
                                <td class="align-bottom align-left" width="45%" height="60px">
                                    @if( count($data['chief']) > 1)
                                    <strong>   {{$data['chief'][1]}} {{$data['chief'][0]}} {{$data['chief'][2]}} </strong><br>
                                    {{$data['chief'][3]}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="30px"></td>
                            </tr>
                        </table>
                        <ol>
                            <li>DELIVERY PERIOD IS ATLEAST ___ CALENDAR DAYS AT {{$data['place']}}</li>
                            {!! $data['content'] !!}
                            <li>APPROVED BUDGET FOR THE CONTRACT IS: <strong><strong style="text-transform:uppercase">{{translateToWords(str_replace(',', '', $data['total_amount']))}} PESOS ONLY. ( Php{{formatPrice($data['total_amount'])}})</strong></strong></li>
                        </ol>
                        <table class="printable-form__body__table">
                            <tr>
                                <td class="align-center" width="10%"><strong>Item No.</strong></td>
                                <td class="align-center" width="10%"><strong>Qunatity</strong></td>
                                <td class="align-center" width="10%"><strong>UOM</strong></td>
                                <td class="align-center" width="30%"><strong>Description</strong></td>
                                <td class="align-center" width="10%"><strong>Unit Price</strong></td>
                                <td class="align-center" width="20%"><strong>Total Price</strong></td>
                            </tr>

                            <?php $count = 1; ?>

                            @foreach($data['items'] as $key=>$item)
                            <tr>
                                <td class="align-center">{{$count++}}</td>
                                <td class="align-center">{{$item->quantity}}</td>
                                <td class="align-center">{{$item->unit_measurement}}</td>
                                <td class="align-left">{{$item->item_description}}</td>
                                <td class="align-right"></td>
                                <td class="align-right"></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="align-center" >x-x-x-x-x Nothing Follows x-x-x-x-x</td>
                            </tr>
                            <tr>
                                <td colspan="6"  class="align-center"><strong>TOTAL PhP</strong></td>
                            </tr>
                        </table>
                        <br>
                        <br>
                        <br>

                        <div style="page-break-inside: avoid!important;">
                          <p>After having carefully read and accepted your General Conditions, I/We hereby certify the item/s quoted is of the quality/standard at the price/s noted above.</p>
                          <!-- form signatories -->
                          <table class="printable-form__body__table no-border no-padding" >
                              <tr>
                                  <td width="45%"></td>
                                  <td width="10%"></td>
                                  <td width="45%" class="align-bottom align-center" height="40px"></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center border-top-only">Signature over Printed Name</td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center" height="30px"></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center border-top-only">Business Name</td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center" height="30px"></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center border-top-only">Business Address</td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center" height="30px"></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center border-top-only">Tel No. / Cell No.</td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center" height="30px"></td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td class="align-bottom align-center border-top-only">Date</td>
                              </tr>
                          </table>
                        </div>
                    </div>
            </div>

    </body>
</html>