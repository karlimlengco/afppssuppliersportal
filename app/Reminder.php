<?php

namespace App;

use Cartalyst\Sentinel\Reminders\EloquentReminder;

use Illuminate\Database\Eloquent\Model;

class Reminder extends EloquentReminder
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'reminders';
}
