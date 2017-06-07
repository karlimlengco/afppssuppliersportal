<?php

namespace Revlv\Sentinel\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Revlv\LandLord\Companies\CompanyRepository;

class Permission
{

    /**
     * Whitelisted Routes
     *
     * @var array
     */
    protected $allowedRoutes = [
        'user.avatar.show',
        'datatables.*',
    ];

    protected $tenant;

    /**
     * [__construct description]
     * @param ProjectRepository $project [description]
     */
    public function __construct(CompanyRepository $tenant)
    {
        $this->tenant       =   $tenant;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the current user of this session
        $this->user = \Sentinel::getUser();

        /**
         * Handle permissions and other stuffs
         */
        $currentRoute = \Route::currentRouteName();

        if ( ! in_array($currentRoute, $this->allowedRoutes))
        {
            if ( ! $this->checkPermissions($currentRoute))
            {
                \App::abort('403', 'You are not allowed to access '.$currentRoute);
            }
        }

        return $next($request);
    }

    /**
     * @param $currentRoute
     * @return bool
     */
    private function checkPermissions($currentRoute)
    {
        $user = \Sentinel::getUser();

        $current    =   $this->tenant->findById($user->company_id);
        // $name       =   $current->db_name;
        // $name   =   "timelines_". strtolower($tenant->company_name);
        if(array_key_exists("superuser", $this->user->permissions) ){
            return true;
        }
        // $this->tenant->changeDatabase($name);
        if($user->inRole('admin')){
            return true;
        }
        // Check if the user has access to this route
        if ( ! $user->hasAccess($currentRoute))
        {
            return false;
        }

        return true;
    }
}
