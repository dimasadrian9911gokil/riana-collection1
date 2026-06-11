<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Hanya memuat data keranjang jika user sedang login untuk menghemat query
        View::composer('layouts.app', function ($view) {
            $cartCount = 0;
            
            if (Auth::check()) {
                $cartCount = Cart::where('user_id', Auth::id())->sum('qty');
            }
            
            $view->with('cartCount', $cartCount);
        });
    }
}