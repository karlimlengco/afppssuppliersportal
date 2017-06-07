<?php

namespace Revlv\Sidebar;

use Illuminate\Support\Facades\File;

class SidebarGenerator
{

    /**
     * Current route of the user
     *
     * @var
     */
    protected $route;

    protected $sidebar;

    protected $user;

    public function __construct($route, $path = 'modules/Sidebar')
    {

        $this->route   = $route;
        $this->path    = $path;
        $this->user    = $this->getCurrentUser();

        $this->generateRoute($route);
    }

    /**
     * Return the sidebar object
     *
     * @return mixed
     */
    public function getRoutes()
    {
        return json_decode(File::get(base_path($this->path).'/sidebar.json'));
    }

    /**
     * Get sidebar object
     *
     * @return mixed
     */
    public function getSidebar()
    {
        return $this->sidebar;
    }

    /**
     * Get the current user
     *
     * @return mixed
     */
    private function getCurrentUser()
    {
        return \Sentinel::getUser();
    }

    /**
     * Generate route based on permissions
     *
     * @param $route
     * @return mixed
     */
    private function generateRoute($route)
    {
        $routes = $this->getRoutes();

        $accessibleRoute = [];
        foreach($routes as $group => $route)
        {
            foreach($route as $subRoute)
            {
                $navigation = $subRoute->navigation;
                $subRoute->navigation   =   [];
                foreach($navigation as $key => $nav)
                {
                    $subRoute->navigation[]         =   $nav;
                    if ($this->user->permissions != null )
                    {
                        $accessibleRoute[$group]        = $subRoute;
                    }
                    else
                    {
                        if ($this->checkPermissions($nav->route))
                        {
                            $accessibleRoute[$group]        = $subRoute;
                        }
                    }
                }
            }
        }

        $this->sidebar = $accessibleRoute;

    }

    /**
     * @param $currentRoute
     * @return bool
     */
    private function checkPermissions($currentRoute)
    {
        if(array_key_exists("superuser", $this->user->permissions) || array_key_exists("admin", $this->user->permissions))
        {
            return true;
        }

        // Check if the user has access to this route
        if ( ! $this->user->hasAccess($currentRoute))
        {
            return false;
        }

        return true;
    }
}
