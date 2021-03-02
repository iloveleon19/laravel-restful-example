<?php

namespace App\Providers;

use App\Policies\AnimalPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Animal::class => AnimalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        
        // access_token 設定核發15天後過期
        Passport::tokensExpireIn(now()->addDays(15));

        // refresh_token 設定核發後30天後過期
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
