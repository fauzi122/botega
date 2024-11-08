<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\CabangModel;
use App\Models\RequestUpdateModel;
use App\Policies\ApprovalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        RequestUpdateModel::class => ApprovalPolicy::class,
        CabangModel::class => CabangModel::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
