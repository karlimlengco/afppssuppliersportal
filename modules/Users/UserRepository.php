<?php

namespace Revlv\Users;

use App\User;
use Datatables;
use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Revlv\Avatar\Support\Laravel\Avatar;
use Revlv\Users\Traits\RegistersUsers;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class UserRepository extends BaseRepository
{
    use RegistersUsers;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Create a new record in the model
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        $data['status']     =   1;
        try
        {
            $user = \Sentinel::registerAndActivate($data);
            return $user;
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param Model $user
     * @param array $data
     * @return Model
     * @throws \Exception
     */
    public function update($data, $id)
    {
        $model = $this
            ->model
            ->find($id);

        unset($data['password']);

            if(isset($data['avatar']))
            {
                $img = \Image::make($data['avatar'])
                    ->resize(300, 300)
                    ->save(public_path('avatars').'/'.obfuscateFileName('jpg'));
                $model->avatar = $img->basename;

                $data['avatar'] = $img->basename;
            }

        return $model->update($data);
    }

    /**
     * Update profile information
     *
     * @param Model $user
     * @param array $data
     * @return Model
     */
    public function updateProfile(Model $user, array $data)
    {
        // If the avatar is uploaded
        if(isset($data['avatar']))
        {
            $img = \Image::make($data['avatar'])
                ->resize(300, 300)
                ->save(public_path('avatars').'/'.obfuscateFileName('jpg'));

            $user->avatar = $img->basename;

            $data['avatar'] = $img->basename;
        }

        return parent::update($data);
    }

    /**
     * Update password
     *
     * @param Model $user
     * @param array $password
     */
    public function updatePassword(Model $user, $password)
    {
        $user->password     =   bcrypt($password);
        $user->save();

        return $user;
    }

    /**
     * Find user by username
     *
     * @param $username
     * @return mixed
     */
    public function findByUsername($username)
    {
        return $this->model->where('username', $username)->first();
    }

    /**
     * Returns the link for the activation link
     *
     * @param $user
     * @param $activation
     * @return string
     */
    public function getActivationUrlToken($user, $activation)
    {
        return $activation->code;
    }

    /**
     * [listFullname description]
     *
     * @return [type] [description]
     */
    public function listFullname($id = null)
    {
        $list = User::select('users.id',
                \DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name")
            );

        if($id != null)
        {
            $list = $list->where('company_id', '=', $id);
        }

        return $list->pluck('full_name', 'id')->all();
    }


    /**
     * [getArchives description]
     *
     * @return [type] [description]
     */
    public function getArchives($limit = 20)
    {
        return $this->model
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->onlyTrashed()
                ->paginate($limit);
    }

    /**
     * [findArchivedById description]
     *
     * @param  [type] $id        [description]
     * @param  array  $eagerLoad [description]
     * @return [type]            [description]
     */
    public function findArchivedById($id, $eagerLoad = [])
    {
        $model = $this->model;

        if (count($eagerLoad))
        {
            $model = $model->with($eagerLoad);
        }

        return $model->onlyTrashed()->find($id);
    }

    /**
     * [getAll description]
     *
     * @param  integer $limit    [description]
     *
     * @param  boolean $paginate [description]
     *
     * @return [type]            [description]
     */
    public function getDatatable($archives = null)
    {
        $model  =   $this->model;

        $model  = $model->select('users.*',
                \DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name")
            );

        if($archives == 'archive')
        {
            $model  =   $model->onlyTrashed();
        }

        return $this->dataTable($model->get());
    }

    /**
     * [dataTable description]
     *
     * @param  [type] $model [description]
     * @return [type]        [description]
     */
    public function dataTable($model)
    {
        return Datatables::of($model)
            ->addColumn('username', function ($data) {
                $route  =  route('settings.users.show', $data->username);
                return '<a href="'.$route.'" > '. $data->username .'</a>';
            })
            ->editColumn('full_name', function ($data) {
                return $data->full_name;
            })
            ->editColumn('position', function ($data) {
                return $data->position;
            })
            ->editColumn('email', function ($data) {
                return $data->email;
            })
            ->editColumn('contact_number', function ($data) {
                return $data->contact_number;
            })
            ->editColumn('gender', function ($data) {
                return $data->gender;
            })
            ->editColumn('avatar', function ($data) {
                $route  =  route('settings.users.show', $data->username);

                $avatar = '<ul class="list-inline">
                              <li>
                                <a href="'.$route.'"><img src="'.route('user.avatar.show', $data->id).'" class="avatar" alt="Avatar"></a>
                              </li>
                            </ul>';
                return $avatar;
            })
            ->rawColumns(['username', 'avatar'])
            ->make(true);
    }

    /**
     * [getByCompanyId description]
     *
     * @param  [type]  $company_id [description]
     * @param  integer $paginate   [description]
     * @return [type]              [description]
     */
    public function getByCompanyId($company_id, $paginate = null, $eager = [])
    {
        $model      =   $this->model;

        $model      =   $model->select([
                    'users.*',
                    \DB::raw("CONCAT(users.surname,', ', users.first_name, ' ', users.middle_name) AS fullname")
        ]);

        $model      =   $model->where('company_id','=', $company_id);

        $model      =   $model->with($eager);

        if($paginate != null)
        {
            $model      =   $model->paginate($paginate);
        }
        else
        {
            $model      =   $model->get();
        }

        return $model;
    }
}
