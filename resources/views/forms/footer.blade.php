<!DOCTYPE html>
<html lang="en">
<head>
<style type="text/css">

</style>
</head>
<body>
{{-- <script>
    function subst() {
        var vars={};
        var x=document.location.search.substring(1).split('&');
        for (var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
        var x=['topage','page'];
        for (var i in x) {
            var y = document.getElementsByClassName(x[i]);
            for (var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
        }
    }
</script> --}}
{{-- <table style="width: 100%; border:0; margin: 0;" onload="subst()">
    <tr>
        <td style="background: #7D9CA4; color: #FFFFFF; float: left; padding: 10px; font-weight: bold; text-align: center;">
            <span class="page" style="color:black"></span> of <span style="color:black" class="topage"></span>
        </td>
        <td style="color: #000000; float: left; font-weight: bold; padding-top: 10px; text-align: center; width: 85%;">
            Text block
        </td>
    </tr>
</table>
 --}}
<div class="printable-form__foot" style="position: absolute; bottom: 10px;  left: 0; right: 0; width: 100%;font-size: 12px; font-weight: 400; color: #545454;">
    <table class="printable-form__foot__table" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td colspan="2" style="width: 50%; padding: 0 15px;">
                <p class="printable-form__foot__values" style="display: block;  width: 100%; text-align: center; padding: 0; margin: 0 0 20px 0;">AFP Core Values: Honor, Service, Patriotism</p>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; padding: 0 15px;">
                <span class="printable-form__foot__ref" style="display: block; width: 100%; font-size: 8px; font-weight: 800; text-transform: uppercase; text-align: left;">11111 1111 11111</span>
            </td>
            <td style="width: 50%; padding: 0 15px;">
                <span class="printable-form__foot__code" style="display: block; height: 20px; text-align: right;"><img style="height: 100%;" src="{{base_path('public/img/barcode.png')}}" alt=""></span>
            </td>
        </tr>
    </table>
</div>
</body>
</html>