<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Assignments;
use App\Models\PermissionRequest;
use App\Policies\AgendaPolicy;
use App\Policies\AssignmentPolicy;
use App\Policies\PermissionRequestPolicy;
use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Assignments::class => AssignmentPolicy::class,
        PermissionRequest::class => PermissionRequestPolicy::class,
        Agenda::class => AgendaPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
