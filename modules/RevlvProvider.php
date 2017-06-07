<?php

namespace Revlv;

use Illuminate\Support\ServiceProvider;


class RevlvProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {


    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register the composer
        $this->app->view->composer('layouts.main', 'Revlv\BaseComposer');

    }
}
