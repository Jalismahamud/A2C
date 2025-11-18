<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {

        $this->app->singleton('minimumWithdraw', function () {
            $setting = Setting::first();
            $minimum = $setting->agent_minimum_withdraw;
            return $minimum;
        });

        $this->app->singleton('registration_bonus', function () {
            $setting = Setting::first();
            $registration_bonus = $setting->registration_bonus;
            return $registration_bonus;
        });
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $setting = Setting::first();
        View::share('company_logo', $setting?->company_logo);
        View::share('company_name', $setting?->company_name);
        View::share('company_email', $setting?->company_email);
        View::share('company_phone', $setting?->company_phone);
        View::share('company_address', $setting?->company_address);
    }
}
