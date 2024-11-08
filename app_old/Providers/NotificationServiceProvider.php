<?php

namespace App\Providers;

use App\Models\LogsModel;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        \View::composer('*', function ($view) {
            $user = session('user');

            $unread = $user ? LogsModel::query()->where('status', 0)->where('user_id', $user->id)->count() : 0;

            $view->with('unread', $unread);
        });
    }

}
