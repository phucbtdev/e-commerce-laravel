<?php

use App\Livewire\Auth\Forgot;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Reset;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderPage;
use App\Livewire\OrderDetailPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductPage::class);
Route::get('/products/{slug}', ProductDetailPage::class);
Route::get('/cart', CartPage::class);
Route::get('/checkout', CheckoutPage::class);
Route::get('/my-order', MyOrderPage::class);
Route::get('/my-order/{slug}', OrderDetailPage::class);

Route::get('/login', Login::class);
Route::get('/register', Register::class);
Route::get('/forgot', Forgot::class);
Route::get('/reset', Reset::class);

Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);