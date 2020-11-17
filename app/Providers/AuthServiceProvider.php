<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->defineGateCategory();
        $this->defineGateNew();
        $this->defineGateUser();
        $this->defineGateRole();
    }

    /*
     * Xác thực quyền truy cập với category
     * CreatedBy: LTQUAN (16/11/2020)
     */
    public function defineGateCategory(){
        Gate::define('view_category', 'App\Policies\CategoryPolicy@view');
        Gate::define('add_category', 'App\Policies\CategoryPolicy@create');
        Gate::define('edit_category', 'App\Policies\CategoryPolicy@update');
        Gate::define('delete_category', 'App\Policies\CategoryPolicy@delete');
    }

    /*
     * Xác thực quyền truy cập với new
     * CreatedBy: LTQUAN (16/11/2020)
     */
    public function defineGateNew(){
        Gate::define('view_new', 'App\Policies\NewPolicy@view');
        Gate::define('add_new', 'App\Policies\NewPolicy@create');
        Gate::define('edit_new', 'App\Policies\NewPolicy@update');
        Gate::define('delete_new', 'App\Policies\NewPolicy@delete');
    }

    /*
     * Xác thực quyền truy cập với user
     * CreatedBy: LTQUAN (16/11/2020)
     */
    public function defineGateUser(){
        Gate::define('view_member', 'App\Policies\UserPolicy@view');
        Gate::define('add_member', 'App\Policies\UserPolicy@create');
        Gate::define('edit_member', 'App\Policies\UserPolicy@update');
        Gate::define('delete_member', 'App\Policies\UserPolicy@delete');
    }

    /*
     * Xác thực quyền truy cập với role
     * CreatedBy: LTQUAN (16/11/2020)
     */
    public function defineGateRole(){
        Gate::define('view_role', 'App\Policies\RolePolicy@view');
        Gate::define('add_role', 'App\Policies\RolePolicy@create');
        Gate::define('edit_role', 'App\Policies\RolePolicy@update');
        Gate::define('delete_role', 'App\Policies\RolePolicy@delete');
    }
}
