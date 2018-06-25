  <!DOCTYPE html>
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="{{base_path('fonts/Nunito_Sans/css/nunitosans.css')}}">
        <link rel="stylesheet" href="{{base_path('public/css/main.css')}}">
        <style type="text/css">
            body{
                margin:0;
                padding:0;
            }
            @page{
                margin:0;
                padding:0;
            }
        </style>
    </head>

    <body>

        <div class="printable-form-wrapper" style="padding-top:50px">

            <div class="printable-form">

                <!-- form header -->
                <div class="printable-form__head">
                    <p class="printable-form__head__vision">AFP Vision 2028: A World-class Armed Forces, Source of National Pride</p>
                </div>

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
                    <span class="printable-form__body__title">Certificate of Posting</span>
                    <!-- content -->
                    <p>{{\Carbon\Carbon::createFromFormat('Y-m-d',$data['date'])->format('d F Y')}}</p>
                    <p class="indent-first-line">THIS IS TO CERTIFY that the procurement project of the {{$data['center']}}  for the procurement of {{$data['items'][0]->quantity}} ({{$data['items'][0]->quantity}}) {{$data['items'][0]->item_description}} @if(count($data['items']) > 1) and {{count($data['items']) - 1}} {{count($data['items']) - 1}} @endif only for the use of {{$data['unit']}} was posted at conspicuous place at bulletin board of {{$data['center']}} from {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date_from'])->format('d F Y')}} to {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data['date_to'])->format('d F Y')}}</p>
                    <p class="indent-first-line">This certification is issued to support the requirement for REQUEST FOR QUOTATION No. {{$data['ref_number']}}</p>
                    <table class="printable-form__body__table no-border no-padding">
                        <tr>
                            <td colspan="3" height="60px"></td>
                        </tr>
                        <tr>
                            <td width="45%"></td>
                            <td width="10%"></td>
                            <td width="45%">
                            @if($data['signatory'] != null)

                              <table class="signatory">
                                  <tr>
                                      <td>
                                          <div class="signatory-name-justify">
                                              <strong  style="text-transform: uppercase;">{{$data['signatory'][0]}}</strong>
                                              <span></span>
                                          </div>
                                      </td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <div class="signatory-rank-justify">
                                              <strong style="text-transform: uppercase;">{{$data['signatory'][1]}} {{$data['signatory'][2]}}</strong>
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
                            @endif
                        </tr>
                    </table>
                </div>

            </div>

        </div>

    </body>
</html>