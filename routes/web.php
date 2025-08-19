<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Auth;


// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');

// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');

// Password Reset Routes
Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request')->middleware('guest');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('guest');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update')->middleware('guest');

// Email Verification Routes (uncomment if needed)

Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice')->middleware('auth');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationNotification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Password Confirmation Routes (uncomment if needed for sensitive operations)

Route::get('/password/confirm', [AuthController::class, 'showConfirmForm'])->name('password.confirm')->middleware('auth');
Route::post('/password/confirm', [AuthController::class, 'confirm'])->name('password.confirm.post')->middleware('auth');

// The 'guest' middleware has been removed from these routes
Route::get('/2fa-verify', [AuthController::class, 'showVerifyForm'])->name('auth.2fa-verify');
Route::post('/2fa-verify', [AuthController::class, 'verify'])->name('auth.2fa.verify.post');


Route::middleware('auth')->group(function () {

    // User's Dashboard (formerly 'home')
    Route::get('/dashboard', function () {
        return view('home'); // Still uses 'home.blade.php' as the view for the dashboard
    })->name('dashboard');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create')->middleware('role:seller,admin');

    Route::post('/items', [ItemController::class, 'store'])->name('items.store')->middleware('role:seller,admin');

    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit')->middleware('role:seller,admin');

    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update')->middleware('role:seller,admin');

    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware('role:seller,admin');

    Route::post('/items/{item}/bid', [BidController::class, 'store'])->name('bids.store')->middleware('role:bidder');

    Route::get('/dashboard/my-listings', [DashboardController::class, 'myListings'])->name('dashboard.my_listings');

    Route::get('/dashboard/my-bids', [DashboardController::class, 'myBids'])->name('dashboard.my_bids');

    Route::get('/dashboard/my-winnings', [DashboardController::class, 'myWinnings'])->name('dashboard.my_winnings');

 
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');

    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/user/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Show feedback form for an item
    Route::get('/items/{item}/feedback', [FeedbackController::class, 'create'])->name('feedback.create');

    // Store submitted feedback
    Route::post('/items/{item}/feedback', [FeedbackController::class, 'store'])->name('feedback.store');


    Route::get('/messages', [MessageController::class,'index'])->name('messages.inbox');
    Route::get('/messages/user/{user}', [MessageController::class, 'getMessages'])->name('messages.user');
    Route::post('/messages', [MessageController::class,'store'])->name('messages.store');

});

Route::get('/', [ItemController::class, 'index'])->name('items.index');

Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');