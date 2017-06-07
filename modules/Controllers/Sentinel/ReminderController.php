<?php

namespace Revlv\Controllers\Sentinel;

use App\Http\Controllers\Controller;
use Revlv\Users\Traits\RemindsUsers;

class ReminderController extends Controller
{
    use RemindsUsers;

    /***
     *
     */
    public function index()
    {
        return view('modules.sentinel.reminder');
    }
}
