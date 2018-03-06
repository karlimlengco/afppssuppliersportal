<?php

Form::macro('textField', function($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('textRegisterField', function($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    return fieldRegisterWrapper($name, $label, $element);
});

Form::macro('numberField', function($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::number($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});


Form::macro('colorpicker', function($name, $label = null, $value = null, $attributes = array())
{
    $attributes = $attributes + ['data-color-picker' => 'true'];

    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('fileField', function($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::file($name, fieldAttributes($name, $attributes));

    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= '<div class="col-md-12 col-sm-12 col-xs-12">';
    $out .= $element;
    $out .= '</div>';
    $out .= fieldMsg($name);
    $out .= '</div>';

    return $out;
});

Form::macro('taggingField', function($name, $label = null, $value = null, $attributes = array())
{
    $attributes = $attributes + ['class' => 'selectize-tagging tagging selectize', 'multiple', 'data-selectize' => 'taggingRawField'];

    $element = Form::text($name, $value, fieldAttributesNoId($name, $attributes));

    return fieldWrapper($name, $label, $element);
});


Form::macro('tagField', function($name, $label = null, $options, $value = null, $attributes = array())
{

       $attributes = $attributes + ['class' => 'selectize-tagging selectize selectizes', 'multiple', 'data-selectize' => 'taggingField'];

    $element = Form::select($name.'[]', [null => 'Select One'] + $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('contactField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    $attributes = $attributes + ['class' => 'selectize-contact', 'multiple'];

    $element = Form::select($name, [null => 'Select One'] + $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('dateRangeField', function($name, $label = null, $value = null, $attributes = [])
{
    $attributes = ['class'=>'daterangepicker input has-feedback-left active'];
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    $out = '<div class="form-group has-feedback';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= '<div class="col-md-12 col-sm-12 col-xs-12">';
    $out .= $element;
    $out .= '<span class="fa fa-calendar-o input-feedback left" aria-hidden="true"></span>';
    $out .= '</div>';
    $out .= fieldMsg($name);
    $out .= "</div>";

    return $out;
});

Form::macro('dateRangeNoIconField', function($name, $label = null, $value = null, $attributes = [])
{
    $attributes = ['class'=>'daterangepicker input '];
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= '<div class="col-md-8 col-sm-8 col-xs-8">';
    $out .= $element;
    $out .= '</div>';
    $out .= fieldMsg($name);
    $out .= "</div>";

    return $out;
});

Form::macro('dateField', function($name, $label = null, $value = null, $attributes = [])
{
    $attributes = ['class'=>"input datepicker", 'data' => 'date-picker', 'data-date-format' => "YYYY-MM-DD HH:mm:ss"];

    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);

    return $out;
});

Form::macro('yearField', function($name, $label = null, $value = null, $attributes = [])
{
    $attributes = ['data' => 'year-picker', 'data-date-format' => "YYYY-MM-DD HH:mm:ss"];
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= '<div class="input-group">';
    $out .= $element;
    $out .= '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
    $out .= '</div>';
    $out .= fieldMsg($name);
    $out .= "</div>";

    return $out;
});

Form::macro('dateMonthField', function($name, $label = null, $value = null)
{
    $attributes = ['data' => 'date-month-picker', 'data-date-format' => "YYYY-MM"];
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= '<div class="input-group">';
    $out .= $element;
    $out .= '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
    $out .= '</div>';
    $out .= fieldMsg($name);
    $out .= "</div>";

    return $out;
});

Form::macro('passwordField', function($name, $label = null, $attributes = array())
{
    $element = Form::password($name, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('textareaField', function($name, $label = null, $value = null, $attributes = array())
{
    $attributes = $attributes + ['class' => 'textarea'];
    $element = Form::textarea($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('ajaxField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    $attributes = $attributes + ['class' => 'selectize selectizes'];

    $element = Form::select($name, [null => 'Select One'] + $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('selectField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    // selectize
    $attributes = $attributes + ['class' => 'selectize selectizes', 'data-selectize' => 'selectField'];

    $element = Form::select($name, [null => 'Select One'] + $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('selectizeField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    $attributes = $attributes + ['data-selectize' => 'selectField'];

    $element = Form::select($name, [null => 'Select One'] + $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('selectMultipleField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    $attributes = array_merge($attributes, ['multiple' => true]);
    $element = Form::select($name, $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('booleanField', function($name, $label = null, $value = null, $attributes = array())
{
    $attributes = $attributes + ['class' => 'selectize selectizes'];

    $element = Form::select($name, [null => 'Select One', 1 => 'Yes', 0 => 'No'], $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('checkboxField', function($name, $label = null, $value = 1, $checked = null, $attributes = array())
{
    $attributes = array_merge(['id' => 'id-field-' . $name], $attributes);

    $out = '<div class="checkbox';
    $out .= fieldError($name) . '">';
    $out .= '<label>';
    $out .= Form::checkbox($name, $value, $checked, $attributes) . ' ' . $label;
    $out .= '</div>';

    return $out;
});


function fieldWrapperNoLabel($name,  $element)
{
    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= $element;
    $out .= fieldMsg($name);
    $out .= '</div>';

    return $out;
}

function fieldRegisterWrapper($name, $label, $element)
{
    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= $element;
    $out .= fieldMsg($name);
    $out .= '</div>';

    return $out;
}

function fieldWrapper($name, $label, $element)
{
    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= $element;
    $out .= fieldMsg($name);
    $out .= '</div>';

    return $out;
}

function fieldMsg($field)
{
    $error = '';

    if ($errors = Session::get('errors'))
    {
        $error = $errors->first($field) ? $errors->first($field) : '';

        if ($error)
        {
            $error = "<p class=\"help-text  col-md-12 col-sm-12 col-xs-12\">$error.</p>";
        }
    }

    return $error;
}

function fieldError($field)
{
    $error = '';

    if ($errors = Session::get('errors'))
    {
        $error = $errors->first($field) ? ' form-group--error' : '';
    }

    return $error;
}

function fieldLabel($name, $label)
{
    if (is_null($label)) return '';

    $name = str_replace('[]', '', $name);

    $out = '<label for="id-field-' . $name . '" class="label">';
    $out .= $label . '</label>';

    return $out;
}

function fieldAttributes($name, $attributes = array())
{
    $name = str_replace('[]', '', $name);

    return array_merge(['class' => 'input', 'id' => 'id-field-' . $name], $attributes);
}

function fieldAttributesNoId($name, $attributes = array())
{
    $name = str_replace('[]', '', $name);

    return array_merge(['class' => 'input', 'id' =>  $name], $attributes);
}


/**
 * Generate pseudo-random v4 uuid for the filename
 * @return string
 */
function obfuscateFileName($ext)
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x.%s',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // file name extension
        $ext
    );
}

function formatPrice($number)
{
    $number =   str_replace(',', '', $number);
    return number_format((float)$number, 2);
}

function convertNumber($number)
{
  list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer{0} == "-")
    {
        $output = "negative ";
        $integer    = ltrim($integer, "-");
    }
    else if ($integer{0} == "+")
    {
        $output = "positive ";
        $integer    = ltrim($integer, "+");
    }

    if ($integer{0} == "0")
    {
        $output .= "zero";
    }
    else
    {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group   = rtrim(chunk_split($integer, 3, " "), " ");
        $groups  = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g)
        {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++)
        {
            if ($groups2[$z] != "")
            {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? " and "
                            : ", "
                    );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0)
    {
        $output .= " point";
        for ($i = 0; $i < strlen($fraction); $i++)
        {
            $output .= " " . convertDigit($fraction{$i});
        }
    }

    return $output;
}

function convertGroup($index)
{
    switch ($index)
    {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3)
{
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
    {
        return "";
    }

    if ($digit1 != "0")
    {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0")
        {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0")
    {
        $buffer .= convertTwoDigit($digit2, $digit3);
    }
    else if ($digit3 != "0")
    {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0")
    {
        switch ($digit1)
        {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else if ($digit1 == "1")
    {
        switch ($digit2)
        {
            case "1":
                return "eleven";
            case "2":
                return "twelve";
            case "3":
                return "thirteen";
            case "4":
                return "fourteen";
            case "5":
                return "fifteen";
            case "6":
                return "sixteen";
            case "7":
                return "seventeen";
            case "8":
                return "eighteen";
            case "9":
                return "nineteen";
        }
    } else
    {
        $temp = convertDigit($digit2);
        switch ($digit1)
        {
            case "2":
                return "twenty-$temp";
            case "3":
                return "thirty-$temp";
            case "4":
                return "forty-$temp";
            case "5":
                return "fifty-$temp";
            case "6":
                return "sixty-$temp";
            case "7":
                return "seventy-$temp";
            case "8":
                return "eighty-$temp";
            case "9":
                return "ninety-$temp";
        }
    }
}

function convertDigit($digit)
{
    switch ($digit)
    {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}



function translateToWords($number)
{

  $num = str_replace(',', '', $number);
  $num = number_format($num,2);
  $num = str_replace(',', '', $num);
  $split = explode('.',$num);
  $cents = '';
  $whole = convertNumber($split[0].".0");
  if(count($split) > 1 && $split[1] != 00){
    $cents .= " and ";
    $cents .= convertNumber($split[1].".0");
    $cents .=" cents";
  }

  return  $whole.$cents;
/*****
     * A recursive function to turn digits into words
     * Numbers must be integers from -999,999,999,999 to 999,999,999,999 inclussive.
     *
     *  (C) 2010 Peter Ajtai
     *    This program is free software: you can redistribute it and/or modify
     *    it under the terms of the GNU General Public License as published by
     *    the Free Software Foundation, either version 3 of the License, or
     *    (at your option) any later version.
     *
     *    This program is distributed in the hope that it will be useful,
     *    but WITHOUT ANY WARRANTY; without even the implied warranty of
     *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *    GNU General Public License for more details.
     *
     *    See the GNU General Public License: <http://www.gnu.org/licenses/>.
     *
     */
    // zero is a special case, it cause problems even with typecasting if we don't deal with it here
    $max_size = pow(10,18);
    $suffix   = "";
    if (!$number) return "zero";
    // is_int($number) &&
    if ($number < abs($max_size))
    {
        switch ($number)
        {
            // set up some rules for converting digits to words
            case $number < 0:
                $prefix = "negative";
                $suffix = translateToWords(-1*$number);
                $string = $prefix . " " . $suffix;
                break;
            case 1:
                $string = "one";
                break;
            case 2:
                $string = "two";
                break;
            case 3:
                $string = "three";
                break;
            case 4:
                $string = "four";
                break;
            case 5:
                $string = "five";
                break;
            case 6:
                $string = "six";
                break;
            case 7:
                $string = "seven";
                break;
            case 8:
                $string = "eight";
                break;
            case 9:
                $string = "nine";
                break;
            case 10:
                $string = "ten";
                break;
            case 11:
                $string = "eleven";
                break;
            case 12:
                $string = "twelve";
                break;
            case 13:
                $string = "thirteen";
                break;
            // fourteen handled later
            case 15:
                $string = "fifteen";
                break;
            case $number < 20:
                $string = translateToWords($number%10);
                // eighteen only has one "t"
                if ($number == 18)
                {
                $suffix = "een";
                } else
                {
                $suffix = "teen";
                }
                $string .= $suffix;
                break;
            case 20:
                $string = "twenty";
                break;
            case 30:
                $string = "thirty";
                break;
            case 40:
                $string = "forty";
                break;
            case 50:
                $string = "fifty";
                break;
            case 60:
                $string = "sixty";
                break;
            case 70:
                $string = "seventy";
                break;
            case 80:
                $string = "eighty";
                break;
            case 90:
                $string = "ninety";
                break;
            case $number < 100:
                $prefix = translateToWords($number-$number%10);
                $suffix = translateToWords($number%10);
                $string = $prefix . "-" . $suffix;
                break;
            // handles all number 100 to 999
            case $number < pow(10,3):
                // floor return a float not an integer
                $prefix = translateToWords(intval(floor($number/pow(10,2)))) . " hundred";
                if ($number%pow(10,2)) $suffix = " and " . translateToWords($number%pow(10,2));
                $string = $prefix . $suffix;
                break;
            case $number < pow(10,6):
                // floor return a float not an integer
                $prefix = translateToWords(intval(floor($number/pow(10,3)))) . " thousand";
                if ($number%pow(10,3)) $suffix = translateToWords($number%pow(10,3));
                $string = $prefix . " " . $suffix;
                break;
            case $number < pow(10,9):
                // floor return a float not an integer
                $prefix = translateToWords(intval(floor($number/pow(10,6)))) . " million";
                if ($number%pow(10,6)) $suffix = translateToWords($number%pow(10,6));
                $string = $prefix . " " . $suffix;
                break;
            case $number < pow(10,12):
                // floor return a float not an integer
                $prefix = translateToWords(intval(floor($number/pow(10,9)))) . " billion";
                if ($number%pow(10,9)) $suffix = translateToWords($number%pow(10,9));
                $string = $prefix . " " . $suffix;
                break;
            case $number < pow(10,15):
                // floor return a float not an integer
                $prefix = translateToWords(intval(floor($number/pow(10,12)))) . " trillion";
                if ($number%pow(10,12)) $suffix = translateToWords($number%pow(10,12));
                $string = $prefix . " " . $suffix;
                break;
            // Be careful not to pass default formatted numbers in the quadrillions+ into this function
            // Default formatting is float and causes errors
            case $number < pow(10,18):
                // floor return a float not an integer
                $prefix = translateToWords(intval(floor($number/pow(10,15)))) . " quadrillion";
                if ($number%pow(10,15)) $suffix = translateToWords($number%pow(10,15));
                $string = $prefix . " " . $suffix;
                break;
        }
    } else
    {
        echo "ERROR with - $number<br/> Number must be an integer between -" . number_format($max_size, 0, ".", ",") . " and " . number_format($max_size, 0, ".", ",") . " exclussive.";
    }
    return $string;
}

function rgbcode($id){
    return '#'.substr(md5($id), 1, 6);
}


/**
 * [createCarbon description]
 *
 * @param  [type] $format [description]
 * @param  [type] $value  [description]
 * @return [type]         [description]
 */
function createCarbon($format, $value)
{
    return \Carbon\Carbon::createFromFormat($format, $value);
}