<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
// use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Cartalyst\Sentinel\Activations\EloquentActivation;
use Cartalyst\Sentinel\Activations\ActivationInterface;
use Cartalyst\Sentinel\Roles\RoleableInterface;
use Cartalyst\Sentinel\Permissions\PermissibleInterface;
use Cartalyst\Sentinel\Persistences\PersistableInterface;
use Revlv\Users\UserPresenter;

class Activation extends EloquentActivation
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activations';
}