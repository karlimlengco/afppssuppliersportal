<?php

namespace Revlv;

use Illuminate\Database\Eloquent\Model;

use Revlv\Users\UserRepository;
use Revlv\Messages\MessageEloquent;
use Cart;

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
    public function __construct(UserRepository $user)
    {
        $this->user =   $user;
    }

    /**
     * @param $view
     */
    public function compose($view)
    {

        $userId         =   \Sentinel::getUser()->id;

        $userModel      =   $this->user->findById($userId);

        $view->with('currentUser', $userModel);
    }
}
