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
                    <span class="printable-form__body__title">Notice of Delivery / Request for Inspection</span>
                    <!-- content -->
                    <p>
                        {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['transaction_date'])->format('d F Y')}}
                    </p>
                    <p>To: All Concerned</p>
                    <ol>
                        <li>Reference: Approved @if($data['po_type'] == 'purchase_order')PURCHASE ORDER No.@elseif($data['po_type'] == 'work_order')WORK ORDER No.@elseif($data['po_type'] == 'job_order')JOB ORDER No.@else CONTRACT ORDER No.@endif {{$data['po_number']}}</li>
                        <li>Per reference above, {{$data['winner']}} will be delivering <span style="text-transform:uppercase">{{translateToWords($data['items'][0]->quantity)}}</span> ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and  <span style="text-transform:uppercase">{{translateToWords( count($data['items']) - 1 )}}</span> ({{ count($data['items']) - 1}}) LI @endif only at {{$data['place']}} on {{\Carbon\Carbon::createFromFormat('!Y-m-d',$data['expected_date'])->format('d F Y')}} in the total amount of <strong>{{translateToWords($data['bid_amount'])}} (Php{{formatPrice($data['bid_amount'])}}) ONLY</strong>. </li>
                        <li>Request acknowledge receipt. </li>
                    </ol>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom align-left" width="45%"></td>
                            <td width="10%"></td>
                            <td class="align-bottom" width="45%">
                                <table class="signatory">
                                    <tr>
                                        <td>
                                            <div class="signatory-name-justify">
                                                <strong>{{$data['signatory'][0]}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="signatory-rank-justify">
                                                <strong>{{$data['signatory'][1]}} {{$data['signatory'][2]}}</strong>
                                                <span></span>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td nowrap>{{$data['signatory'][3]}}</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3">Distributions:</td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" height="40px" nowrap>C, TIAC</td>
                            <td class="align-bottom border-bottom-only" width="100px"></td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" height="30px" nowrap>COA</td>
                            <td class="align-bottom border-bottom-only" width="100px"></td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" height="30px" nowrap>MFO</td>
                            <td class="align-bottom border-bottom-only" width="100px"></td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" height="30px" nowrap>SAO</td>
                            <td class="align-bottom border-bottom-only" width="100px"></td>
                            <td width="65%"></td>
                        </tr>
                        <tr>
                            <td class="align-bottom" width="1%" height="30px" nowrap>Recipient Unit</td>
                            <td class="align-bottom border-bottom-only" width="100px"></td>
                            <td width="65%"></td>
                        </tr>
                    </table>

                </div>
                <!-- form footer -->
            </div>

        </div>

    </body>
</html>