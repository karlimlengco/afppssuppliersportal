<?php

namespace App\Support;

class Breadcrumb
{
    public $title;
    public $route;
    public $parameters;

    /**
     * [__construct description]
     * @param [type] $route      [description]
     * @param array  $parameters [description]
     */
    public function __construct($title, $route = null, $parameters = [])
    {
        if (str_contains($route, '.')) {
            list($name, $action) = explode('.', $route);
        }

        $this->parameters = $parameters;
        $this->title = $title;
        $this->route = $route;
    }

    /**
     * [title description]
     * @return [type] [description]
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * [hasLink description]
     * @return boolean [description]
     */
    public function hasLink()
    {
        return $this->route;
    }

    /**
     * [link description]
     * @return [type] [description]
     */
    public function link()
    {
        return route($this->route, $this->parameters);
    }
}