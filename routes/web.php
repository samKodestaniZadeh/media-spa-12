<?php

<<<<<<< HEAD
=======
use App\Http\Middleware\Check404;
use App\Http\Middleware\Check503;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SikllController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TarahiController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\BankAdminController;
use App\Http\Controllers\BlogAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestCartController;
use App\Http\Controllers\LinkAdminController;
use App\Http\Controllers\ReqTarahiController;
use App\Http\Controllers\UserModirController;
use App\Http\Controllers\WebDesignController;
use App\Http\Controllers\NamadAdminController;
use App\Http\Controllers\OrderModirController;
use App\Http\Controllers\SikllAdminController;
<<<<<<< HEAD
=======
use App\Http\Controllers\SocialAuthController;
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\OrderSellerController;
use App\Http\Controllers\ReqDesignerController;
use App\Http\Controllers\SocialAdminController;
use App\Http\Controllers\TarahiAdminController;
use App\Http\Controllers\CommentAdminController;
use App\Http\Controllers\DepositAdminController;
use App\Http\Controllers\GuestCommentController;
use App\Http\Controllers\GuestProfileController;
use App\Http\Controllers\GuestSupportController;
use App\Http\Controllers\NetworkAdminController;
use App\Http\Controllers\PaymentAdminController;
use App\Http\Controllers\PaymentModirController;
use App\Http\Controllers\ProductAdminController;
use App\Http\Controllers\ProfileAdminController;
use App\Http\Controllers\ShopCheckoutController;
use App\Http\Controllers\SupportAdminController;
use App\Http\Controllers\DiscountAdminController;
use App\Http\Controllers\IdentityAdminController;
use App\Http\Controllers\SupportSellerController;
use App\Http\Controllers\WebsiteDesignController;
use App\Http\Controllers\TarahiDesignerController;
use App\Http\Controllers\WebDesignAdminController;
use App\Http\Controllers\DiscountVisitorController;
use App\Http\Controllers\NewsletterAdminController;
use App\Http\Controllers\TermsConditionsController;
use App\Http\Controllers\WebsiteTemplatesController;
use App\Http\Controllers\GuestTarahiCommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';
Route::resource('/', GuestController::class);
Route::resource('/website_templates',WebsiteTemplatesController::class);
Route::get('website_templates/{id}/comment',[GuestCommentController::class,'show'])->name('guest_comment.show');
Route::get('website_templates/{id}/support',[GuestSupportController::class,'show'])->name('guest_support.show');

Route::resource('/website_design',WebsiteDesignController::class);
<<<<<<< HEAD
Route::get('website_design/{id}/comment',[GuestTarahiCommentController::class,'show'])->name('guest_tarahi_comment.show');
=======
Route::get('website_design/{id}/comment',[GuestTarahiCommentController::class,'show'])->name('guest_tarahi_comment.show')->middleware(Check404::class);
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
Route::resource('/guest-cart', GuestCartController::class);
Route::resource('/shop-checkout', ShopCheckoutController::class)->middleware('auth');
Route::resource('/cart', CartController::class);
Route::resource('/terms-conditions', TermsConditionsController::class);
Route::resource('/privacy', PrivacyController::class);
Route::resource('/faq', FaqController::class);
<<<<<<< HEAD
Route::resource('/guest-profile',GuestProfileController::class);
Route::resource('/guest-support',GuestSupportController::class);
Route::resource('/blog',BlogController::class);
Route::get('/artisan/{id}', function($id){artisan::call($id);});

Route::prefix('users')->group(function()
{
    Route::get('/profile/{id}',[ProfileController::class,'show'])->name('profile.show');
    Route::middleware('auth')->group(function()
=======
Route::resource('/guest-profile',GuestProfileController::class)->middleware(Check404::class);
Route::resource('/guest-support',GuestSupportController::class);
Route::resource('/blog',BlogController::class);

Route::get('/artisan/{id}', function($id){artisan::call($id);});


Route::prefix('users')->group(function()
{
    Route::get('/profile/{id}',[ProfileController::class,'show'])->name('profile.show');
    Route::middleware(['auth'])->group(function()
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
    {
        //buyer
        Route::resource('/dashboard', DashboardController::class); //->middleware('verified')
        Route::resource('/support',SupportController::class);
        Route::resource('/order', OrderController::class);
        Route::resource('/bank', BankController::class);
        Route::resource('/payment', PaymentController::class);
        Route::resource('/profile', ProfileController::class)->middleware('password.confirm');
        Route::resource('/download', DownloadController::class);
        Route::resource('/comment', CommentController::class);
        Route::resource('/favorite', FavoriteController::class);
        Route::resource('/discount', DiscountController::class);
        Route::resource('/menu', MenuController::class);
        Route::resource('/section', SectionController::class);
        Route::resource('/user', UserController::class);
        Route::resource('/tarahi', TarahiController::class);
        Route::resource('/page', PageController::class);
        Route::resource('/factor', FactorController::class);
        Route::resource('/link', LinkController::class);
<<<<<<< HEAD
        Route::resource('/company', CompanyController::class);
=======
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
        Route::resource('/network', NetworkController::class)->middleware('password.confirm');
        Route::resource('/social', SocialController::class)->middleware('password.confirm');
        Route::resource('/description', DescriptionController::class);
        Route::resource('/reqTarahi', ReqTarahiController::class);
        Route::resource('/rate', RateController::class);
        Route::resource('/table', TableController::class);
        Route::resource('/identity', IdentityController::class);
        Route::resource('/sikll', SikllController::class);
        Route::resource('/route', RouteController::class);

        Route::resource('/installment',InstallmentController::class);
        Route::get('/verify', [PaymentController::class,'verify'])->name('verify');
        Route::get('/download/create',[DownloadController::class ,'create'])->name('download.create')->middleware('signed');

        //seller
        Route::resource('/product', ProductController::class);
        Route::resource('/supportSeller',SupportSellerController::class);
        Route::resource('/discountVisitor', DiscountVisitorController::class);
        Route::resource('/orderSeller', OrderSellerController::class);


        //designer
        Route::resource('/tarahiDesigner', TarahiDesignerController::class);
        Route::resource('/reqDesigner', ReqDesignerController::class);

        //admin
        Route::resource('/supportAdmin',SupportAdminController::class);
<<<<<<< HEAD
=======
        Route::resource('/company', CompanyController::class);
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
        Route::resource('/bankAdmin', BankAdminController::class);
        Route::resource('/paymentAdmin', PaymentAdminController::class);
        Route::resource('/profileAdmin', ProfileAdminController::class);
        Route::resource('/commentAdmin',CommentAdminController::class);
        Route::resource('/discountAdmin', DiscountAdminController::class);
        Route::resource('/productAdmin',ProductAdminController::class);
        Route::resource('/tarahiAdmin', TarahiAdminController::class);
        Route::resource('/newsletterAdmin', NewsletterAdminController::class);
        Route::resource('/linkAdmin', LinkAdminController::class);
        Route::resource('/networkAdmin', NetworkAdminController::class);
        Route::resource('/socialAdmin', SocialAdminController::class);
        Route::resource('/identityAdmin', IdentityAdminController::class);
        Route::resource('/sikllAdmin', SikllAdminController::class);
        Route::resource('/blogAdmin',BlogAdminController::class);
        Route::resource('/webdesignAdmin',WebDesignAdminController::class);
        Route::resource('/namadAdmin',NamadAdminController::class);
        Route::resource('/depositAdmin', DepositAdminController::class);
        Route::resource('/session', SessionController::class);
        Route::resource('/coupon', CouponController::class);
        Route::resource('/webdesign',WebDesignController::class);
        Route::delete('/discountAdmin-destroyAll',[DiscountAdminController::class ,'destroyAll'])->name('discountAdmin.destroyAll');


        //modir
        Route::resource('/paymentModir', PaymentModirController::class);
        Route::resource('/orderModir', OrderModirController::class);
        Route::resource('/userModir', UserModirController::class);


    });
});


