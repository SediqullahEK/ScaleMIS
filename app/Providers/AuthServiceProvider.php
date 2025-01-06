<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        
        Gate::define('access_create_new_revenue',function(User $user){

            return $user->permissions->where('name', 'access_create_new_revenue')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_to_revenues', function(User $user){
            return $user->permissions->where('name','access_to_revenues')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_edit_revenue', function(User $user){
            return $user->permissions->where('name','access_edit_revenue')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_confirm_revenue_information', function(User $user){
            return $user->permissions->where('name','access_confirm_revenue_information')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_send_maktobs_to_provential_directorate', function(User $user){
            return $user->permissions->where('name','access_send_maktobs_to_provential_directorate')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_sended_maktobs_to_provential_directorate', function(User $user){
            return $user->permissions->where('name','access_sended_maktobs_to_provential_directorate')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_save_maktob_revenue', function(User $user){
            return $user->permissions->where('name','access_save_maktob_revenue')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });


        // tarofa and awaze permissions
        Gate::define('access_save_tarofa', function(User $user){
            return $user->permissions->where('name', 'access_save_tarofa')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_confirm_tarofa', function(User $user){
            return $user->permissions->where('name','access_confirm_tarofa')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });
        
        Gate::define('access_print_tarofa', function(User $user){
            return $user->permissions->where('name', 'access_print_tarofa')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_save_reciept', function(User $user){
            return $user->permissions->where('name', 'access_save_reciept')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_to_reports', function(User $user){
            return $user->permissions->where('name', 'access_to_reports')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_tarofa_report', function(User $user){
            return $user->permissions->where('name', 'access_tarofa_report')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        //access to scale report system
        Gate::define('access_scale_reports', function(User $user){
            return $user->permissions->where('name', 'access_scale_reports')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        //////////////////////////////////////////////////////////////
        // revenue system permissiona and rmis users for saving tarofa and awaze.
        Gate::define('access_revenue_system_permissions', function(User $user){
            return $user->permissions->where('name', 'access_revenue_system_permissions')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        Gate::define('access_rmis_users', function(User $user){
            return $user->permissions->where('name', 'access_rmis_users')->first()
                    ? Response::allow()
                    : Response::deny('Access Denied');
        });

        


    }
}
