<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The layout used by the controller.
     *
     * @var \Illuminate\View\View
     */
    protected $layout = 'layouts.main';

    public function __construct()
    {
        $this->middleware('revlv.auth', ['except' => ['login','showImage','getFooter']]);
        // $this->middleware('revlv.permissions', ['except' => ['login','showImage','choosePlan','usePlan']]);
    }

    /**
     * Set the specified view
     *
     * @param  string $path
     * @param  array $data
     * @return void
     */
    public function view($path, $data = [])
    {
        $this->layout->content = view($path, $data);
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = view($this->layout);
        }
    }

    /**
     * [callAction description]
     * @param  [type] $method     [description]
     * @param  [type] $parameters [description]
     * @return [type]             [description]
     */
    public function callAction($method, $parameters)
    {
        $this->setupLayout();

        $response = call_user_func_array(array($this, $method), $parameters);


        if (is_null($response) && ! is_null($this->layout))
        {
            $response = $this->layout;
        }

        return $response;
    }


}
