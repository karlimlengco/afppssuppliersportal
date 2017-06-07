<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Persistences\PersistenceInterface;

class Persistence extends Model implements PersistenceInterface
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'persistences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'device', 'ip_address', 'os', 'browser'];


    /**
     * The users model name.
     *
     * @var string
     */
    protected static $usersModel = 'User';

    /**
     * {@inheritDoc}
     */
    public function user()
    {
        return $this->belongsTo(static::$usersModel);
    }

    /**
     * Get the users model.
     *
     * @return string
     */
    public static function getUsersModel()
    {
        return static::$usersModel;
    }

    /**
     * Set the users model.
     *
     * @param  string  $usersModel
     * @return void
     */
    public static function setUsersModel($usersModel)
    {
        static::$usersModel = $usersModel;
    }
}
