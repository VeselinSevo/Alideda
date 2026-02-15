<?php

namespace App\Providers;
use App\Models\Product;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

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

        View::composer('layouts.navigation', function ($view) {
            $count = 0;

            if (auth()->check()) {
                $cart = auth()->user()->cart;
                $count = $cart ? $cart->items()->sum('quantity') : 0;
            }

            $view->with('cartCount', $count);
        });

        // View::composer('*', function ($view) {

        //     if (!request()->is('products*')) {
        //         return;
        //     }

        //     $breadcrumbs = [];

        //     // Products (index)
        //     $breadcrumbs[] = [
        //         'label' => 'Products',
        //         'url' => route('products.index')
        //     ];

        //     // Ako postoji product u ruti (route model binding)
        //     // $product_id = request()->route('product');

        //     // $product = Product::find($product_id);


        //     $product = request()->route('product');
        //     if ($product instanceof Product) {

        //         // Show
        //         $breadcrumbs[] = [
        //             'label' => $product->name,
        //             'url' => route('products.show', $product)
        //         ];

        //         // Ako je edit ruta
        //         if (request()->is('products/*/edit')) {
        //             $breadcrumbs[] = [
        //                 'label' => 'Edit',
        //                 'url' => null
        //             ];
        //         }

        //     } elseif (request()->is('products/create')) {

        //         $breadcrumbs[] = [
        //             'label' => 'Create',
        //             'url' => null
        //         ];
        //     }

        //     // Ako smo na show ruti, poslednji element ne treba da bude link
        //     if (request()->routeIs('products.show')) {
        //         $breadcrumbs[count($breadcrumbs) - 1]['url'] = null;
        //     }

        //     $view->with('breadcrumbs', $breadcrumbs);
        // });
    }
}
