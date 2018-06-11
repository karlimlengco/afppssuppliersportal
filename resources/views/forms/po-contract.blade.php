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
            <!-- third-upr.xps -->
            <div class="printable-form">
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>
                <!-- form body -->
                <div class="printable-form__body">
                    <!-- letterhead -->
                    <div class="printable-form__letterhead">
                        <span class="printable-form__letterhead__details">
                        {!! $data['unitHeader']!!}
                        </span>
                    </div>
                    <!-- title -->
                    <span class="printable-form__body__title">
                        Contract Agreement<br>
                        {{$data['ref_number']}}
                    </span>
                    <!-- content -->
                    <p>KNOW ALL MEN BY THESE PRESENTS:</p>
                    <p class="indent-first-line">THIS CONTRACT is being made and entered in this ______ day of _________________, of between <strong>{{$data['supplier']->name}}</strong> with business address at <strong>{{$data['supplier']->address}}</strong> reprensented by the Proprietress <strong> {{$data['supplier']->owner}} </strong> herein after referred to as the "First Party".</p>
                    <p class="align-center">and</p>
                    <p class="indent-first-line"><strong>AFPPS</strong> at Camp General Emilio Aguinaldo, Quezon City represented by CO, GHQ Procurement Center in behalf of End-user and its duty authorized representative, herein after referred to as the "Second Party:.</p>
                    <p class="align-center">WITNESSETH</p>
                    <p class="align-center">That the parties hereto, agree and stipulate:</p>
                    <p class="indent-first-line">That for and in consideration of the payment to be made by <strong>{{$data['requestor'][1]}} {{$data['requestor'][0]}} {{$data['requestor'][2]}}</strong> the First Party willfully and faithfully agrees to perform the following services:</p>
                    <p class="indent-first-line">To provide catering service used by {{$data['unit']}} (see attached training/event schedule), 2018:</p>
                    <table class="printable-form__body__table">
                        <tr>
                            <td class="align-center" width="5%"><strong>L/I</strong></td>
                            <td class="align-center" width="5%"><strong>Qty</strong></td>
                            <td class="align-center" width="5%"><strong>UOM</strong></td>
                            <td class="align-center" width="60%"><strong>Description</strong></td>
                            <td class="align-center" width="10%"><strong>U/P</strong></td>
                            <td class="align-center" width="15%"><strong>T/P</strong></td>
                        </tr>

                        <?php $total = 0; ?>
                        <?php $count = 1; ?>
                        @foreach($data['items'] as $key=>$item)
                            @if($data['type'] == 'contract')
                                <tr>
                                    <td class="align-center">{{$count++}}</td>
                                    <td class="align-center">{{$item->quantity}}</td>
                                    <td class="align-center">{{$item->unit}}</td>
                                    <td class="align-left">{{$item->description}}</td>
                                    <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                                    <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                                        <?php $total += $item->total_amount; ?>
                                </tr>
                            @elseif($data['type'] == 'contract_and_po' && $item->type == 'contract')
                                <tr>
                                    <td class="align-center">{{$count++}}</td>
                                    <td class="align-center">{{$item->quantity}}</td>
                                    <td class="align-center">{{$item->unit}}</td>
                                    <td class="align-left">{{$item->description}}</td>
                                    <td class="align-right">{{formatPrice($item->price_unit)}}</td>
                                    <td class="align-right">{{formatPrice($item->total_amount)}}</td>
                                    <?php $total += $item->total_amount; ?>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="align-right"><strong>TOTAL</strong></td>
                            <td class="align-right"><strong>{{formatPrice($total)}}</strong></td>
                        </tr>
                    </table>
                    <p class="indent-first-line">That the Second Party, after fulfillment of all the terms and conditions of this contract will pay the First Party sum of <strong style="text-transform:uppercase">{{translateToWords($total)}} PESOS ONLY.</strong>.</p>
                    <div style="page-break-inside: avoid">

                    <p class="indent-first-line">IN WITNESS HEREOF, the Parties aforementioned have hereunto placed their hands on the date herein above.</p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td width="10%"></td>
                            <td width="35%">
                              <table class="signatory">
                                  <tr>
                                      <td>
                                          <div class="signatory-name-justify">
                                              <strong>{{$data['requestor'][0]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <div class="signatory-rank-justify">
                                              <strong>{{$data['requestor'][1]}} {{$data['requestor'][2]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td nowrap>{{$data['requestor'][3]}}</td>
                                      <td></td>
                                  </tr>
                              </table>
                            </td>
                            <td width="10%"></td>
                            <td width="35%">

                              <table class="signatory">
                                  <tr>
                                      <td>
                                          <div class="signatory-name-justify">
                                              <strong>{{$data['supplier']->owner}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <div class="signatory-rank-justify">
                                              <strong>Proprietor</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td nowrap>{{$data['supplier']->name}}</td>
                                      <td></td>
                                  </tr>
                              </table>
                            </td>
                            <td width="10%"></td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td width="35%"></td>
                            <td width="35%">
                              <table class="signatory">
                                  <tr>
                                      <td>
                                          <div class="signatory-name-justify">
                                              <strong>{{$data['approver'][0]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <div class="signatory-rank-justify">
                                              <strong>{{$data['approver'][1]}} {{$data['approver'][2]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td nowrap>{{$data['approver'][3]}}</td>
                                      <td></td>
                                  </tr>
                              </table>
                            </td>
                            <td width="30%"></td>
                        </tr>
                    </table>


                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="1" height="60px"></td>
                            <td colspan="1">Funds Available:</td>
                        </tr>
                        <tr>
                            <td width="10%"></td>
                            <td width="45%">
                              @if(strpos($data['accounting'][0], 'ABAIGAR')  !== false)
                              <table class="signatory">
                                  <tr>
                                      <td nowrap>
                                          <div class="">
                                              <strong>{{$data['accounting'][1]}} {{$data['accounting'][0]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <div class="">
                                              <strong>{{$data['accounting'][2]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                    <td nowrap>Chief Accountant, <br> AFP Accounting Center</td>
                                    <td></td>
                                  </tr>
                              </table>
                              @else
                              <table class="signatory">
                                  <tr>
                                      <td>
                                          <div class="signatory-name-justify">
                                              <strong>{{$data['accounting'][0]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <div class="signatory-rank-justify">
                                              <strong>{{$data['accounting'][1]}} {{$data['accounting'][2]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                    <td nowrap>{{$data['accounting'][3]}}</td>
                                    <td></td>
                                  </tr>
                              </table>
                              @endif
                            </td>
                            <td></td>
                            <td width="35%"></td>
                            <td width="10%"></td>
                        </tr>
                    </table>

                    </div>

                    <div style="page-break-before: always">
                      <p>REPUBLIC OF THE PHILIPPINES)</p>
                      <p>QUEZON CITY                ) S.S</p>
                      <br>
                      <p class="indent-first-line">BEFORE ME, a notary public for in Quezon City, Philippines on __________ day of __________</p>
                      <p class="indent-first-line"> All known to me known to be the same person who executed the foregoing instrument and they acknowledged before me their aforesaid acts their own free and voluntarily act and deed, as well as the instrumentalities they respectively represents, for the uses and purpose therein stated.
                      </p>
                      <p class="indent-first-line"> This instrument refers to the catering services uses in connection with their various activities consisting of the {{translateToWords($totalPages)}} ({{$totalPages}}) pages including this page in their instrument withnesses at each every page. </p>

                      <p><strong>WITNESS MY HAND SEAL at the place and on the first herein above written.</strong></p>

                      <br>

                      <p style="line-height: 5px">Doc No. &nbsp;&nbsp;&nbsp;&nbsp;_________________</p>
                      <p style="line-height: 5px">Page No.&nbsp;&nbsp;&nbsp;_________________</p>
                      <p style="line-height: 5px">Book No.&nbsp;&nbsp;&nbsp;_________________</p>
                      <p style="line-height: 5px">Series of.&nbsp;&nbsp;&nbsp;_________________</p>

                    </div>



                </div>
            </div>

        </div>
    </body>
</html>