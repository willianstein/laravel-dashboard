<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Helpers\Helper;


class AuthServiceProvider extends ServiceProvider
{

    use Helper;
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //TODO AJUSTAR  PARA O USUÁRIO ADMINISTRADOR
       Gate::define('type-user', function (User $user) {
            return Helper::isAdmin()
            ? Response::allow()
            : Response::deny('Você não pode acessar essa rota.');
        });

        Gate::define('type-user-app', function (User $user) {

            return Helper::isPartner()
            ? Response::allow()
            : Response::deny('Você não pode acessar essa rota.');
        });
    }
}
