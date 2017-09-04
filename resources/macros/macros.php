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

function translateToWords($number)
{
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

define('JPAD_LEFT', 1);     // More spaces are added on the left of the line
define('JPAD_RIGHT', 2);    // More spaces are added on the right of the line
define('JPAD_BOTH', 4);     // Tries to evenly distribute the padding
define('JPAD_AVERAGE', 8);  // Tries to position based on a mix of the three algorithms

function justify($input, $width, $mode = JPAD_BOTH)
{
    // We want to have n characters wide of text per line.
    // Use PHP's wordwrap feature to give us a rough estimate.
    $justified = wordwrap($input, $width, "\n", false);
    $justified = explode("\n", $justified);
    $final     = "";

    // Check each line is the required width. If not, pad
    // it with spaces between words.
    foreach($justified as $line)
    {
        if(strlen($line) != $width)
        {
            // Split by word, then glue together
            $words = explode(' ', $line);
            // dd($justified);
            $diff  = $width - strlen($line);

            while($diff > 0)
            {
                // Process the word at this diff
                if     ($mode == JPAD_BOTH)  $words[$diff / count($words)] .= ' ';
                else if($mode == JPAD_AVERAGE)
                    $words[(($diff / count($words)) +
                            ($diff % count($words)) +
                            (count($words) - ($diff % count($words))))
                            / 3] .= ' ';
                else if($mode == JPAD_LEFT)  $words[$diff % count($words)] .= ' ';
                else if($mode == JPAD_RIGHT) $words[count($words) - ($diff % count($words))] .= ' ';

                // Next diff, please...
                $diff--;
            }
        }
        else
        {
            $words = explode(' ', $line);
        }

        $final .= implode(' ',  $words) . "\n";
    }

    // Return the final string
    return $final;
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