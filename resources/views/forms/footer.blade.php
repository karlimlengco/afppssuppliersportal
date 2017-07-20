<!DOCTYPE html>
<html lang="en">
<head>
<style type="text/css">

.printable-form__foot {
  position: absolute;
  bottom: 10px;
  left: 0;
  right: 0;
  width: 100%;
}
.printable-form__foot__table {
  width: 100%;
  border-collapse: collapse;
}
.printable-form__foot__table tr td {
  width: 50%;
  padding: 0 15px;
}
.printable-form__foot__values {
  display: block;
  width: 100%;
  text-align: center;
  padding: 0;
  margin: 0 0 20px 0;
}
.printable-form__foot__ref {
  display: block;
  width: 100%;
  font-size: 8px;
  font-weight: 800;
  text-transform: uppercase;
  text-align: left;
}
.printable-form__foot__code {
  display: block;
  height: 20px;
  text-align: right;
}
.printable-form__foot__code img {
  height: 100%;
}
</style>
</head>
<body>
<div class="printable-form-wrapper">
    <div class="printable-form">
        <div class="printable-form__foot">
            <table class="printable-form__foot__table">
                <tr>
                    <td colspan="2">
                        <p class="printable-form__foot__values">AFP Core Values: Honor, Service, Patriotism</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="printable-form__foot__ref">11111 1111 11111</span>
                    </td>
                    <td>
                        <span class="printable-form__foot__code"><img src="{{base_path('public/img/barcode.png')}}" alt=""></span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>