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

    // selectize-tagging selectize , 'data-selectize' => 'taggingField'
    $attributes = $attributes + ['class' => 'select', 'multiple'];

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
    $attributes = ['data' => 'date-picker', 'data-date-format' => "YYYY-MM-DD HH:mm:ss"];
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= '<div class="col-md-12 col-sm-12 col-xs-12">';
    $out .= $element;
    $out .= '</div>';
    $out .= fieldMsg($name);
    $out .= "</div>";

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
    $element = Form::textarea($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('ajaxField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    $attributes = $attributes + ['class' => 'selectize'];

    $element = Form::select($name, [null => 'Select One'] + $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('selectField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    // selectize
    $attributes = $attributes + ['class' => 'select', 'data-selectize' => 'selectField'];

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
    $attributes = $attributes + ['class' => 'selectize'];

    $element = Form::select($name, [null => 'Select One', 1 => 'Enabled', 0 => 'Disabled'], $value, fieldAttributes($name, $attributes));

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

function createColumnsArray($end_column, $first_letters = '')
{
  $columns = array();
  $length = strlen($end_column);
  $letters = range('A', 'Z');

  // Iterate over 26 letters.
  foreach ($letters as $letter) {
      // Paste the $first_letters before the next.
      $column = $first_letters . $letter;

      // Add the column to the final array.
      $columns[] = $column;

      // If it was the end column that was added, return the columns.
      if ($column == $end_column)
          return $columns;
  }

  // Add the column children.
  foreach ($columns as $column) {
      // Don't itterate if the $end_column was already set in a previous itteration.
      // Stop iterating if you've reached the maximum character length.
      if (!in_array($end_column, $columns) && strlen($column) < $length) {
          $new_columns = createColumnsArray($end_column, $column);
          // Merge the new columns which were created with the final columns array.
          $columns = array_merge($columns, $new_columns);
      }
  }


  return $columns;
}