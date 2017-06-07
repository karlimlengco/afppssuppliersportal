<?php

namespace Revlv;

use Illuminate\Database\Eloquent\Model;

use Revlv\Users\UserRepository;
use Revlv\Messages\MessageEloquent;
use Cart;
use Revlv\Items\Orders\Lines\OrderLineEloquent;

class BaseComposer
{

    /**
     * [$user description]
     *
     * @var [type]
     */
    private $user;

    /**
     * @param Model $model
     * @param Department Model $model
     */
    public function __construct()
    {

    }

    /**
     * @param $view
     */
    public function compose($view)
    {
        $user   =   \Sentinel::getUser();
        $view->with('currentUser', $user);
    }
}
