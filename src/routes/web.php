<?php

use App\Http\Controllers\Account\ListingController as AccountListingController;
use App\Http\Controllers\Account\PromotionController;
use App\Http\Controllers\Account\ServiceRequestController as AccountServiceRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\ServiceRequestController as AdminServiceRequestController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CraftsmanApplicationController as AdminCraftsmanApplicationController;
use App\Http\Controllers\CraftsmanApplicationController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Account\StatisticsController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Public\ListingController as PublicListingController;
use App\Http\Controllers\Public\ServiceRequestController as PublicServiceRequestController;
use Illuminate\Support\Facades\Route;

// ── Pagina principala ────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Anunturi publice (toata lumea) ────────────────────────────
Route::get('/anunturi', [PublicListingController::class, 'index'])->name('listings.index');
Route::get('/anunturi/{slug}', [PublicListingController::class, 'show'])->name('listings.show');

// ── Cereri servicii publice (vizibile si meseriasilor neautentificati) ─────
Route::get('/cereri', [PublicServiceRequestController::class, 'index'])->name('service-requests.index');
Route::get('/cereri/{slug}', [PublicServiceRequestController::class, 'show'])->name('service-requests.show');

// ── API: orase per judet (AJAX in formular) ───────────────────
Route::get('/api/counties/{county}/cities', [CityController::class, 'byCounty'])->name('api.cities');

// ── API: verificare email disponibil (AJAX register) ─────────
Route::get('/api/check-email', function (\Illuminate\Http\Request $request) {
    $exists = \App\Models\User::where('email', $request->query('email'))->exists();
    return response()->json(['taken' => $exists]);
})->middleware('guest')->name('api.check-email');
// ── API: verificare telefon disponibil (AJAX register) ────────────
Route::get('/api/check-phone', function (\Illuminate\Http\Request $request) {
    $phone = $request->query('phone');
    // Normalizeaza: +40xxxxxxxxx sau 0040xxxxxxxxx → 0xxxxxxxxx
    $normalized = preg_replace('/^(\+40|0040)/', '0', preg_replace('/[\s\-\.\(\)]/', '', $phone));
    $exists = \App\Models\User::where('phone', $normalized)->exists();
    return response()->json(['taken' => $exists]);
})->middleware('guest')->name('api.check-phone');
// ── Dashboard (redirect pe rol) ───────────────────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isCraftsman())  return redirect()->route('craftsman.listings.index');
    if ($user->isAdmin() || $user->isModerator()) return redirect()->route('admin.dashboard');
    if ($user->isCustomer()) {
        $latestApplication = $user->latestCraftsmanApplication;
        $categories = \App\Domain\Shared\Models\ServiceCategory::active()->ordered()->get();
        return view('dashboard', compact('latestApplication', 'categories'));
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Cerere meserias (client autentificat) ────────────────────
Route::post('/cerere-meserias', [CraftsmanApplicationController::class, 'store'])
    ->middleware(['auth', 'role:customer'])
    ->name('craftsman-application.store');

// ── Profil (orice utilizator autentificat) ────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Favorite
    Route::post('/favorite/{listing}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorites.index');

    // Recenzii
    Route::post('/anunturi/{listing}/recenzii', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/recenzii/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// ── Meserias ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:craftsman'])->prefix('meserias')->name('craftsman.')->group(function () {
    Route::resource('anunturi', AccountListingController::class)->names([
        'index'   => 'listings.index',
        'create'  => 'listings.create',
        'store'   => 'listings.store',
        'show'    => 'listings.show',
        'edit'    => 'listings.edit',
        'update'  => 'listings.update',
        'destroy' => 'listings.destroy',
    ]);
    Route::get('statistici', [StatisticsController::class, 'index'])->name('statistics');
    Route::post('promovare/{listing}/activate', [PromotionController::class, 'activate'])->name('promotion.activate');
    Route::post('promovare/{listing}/deactivate', [PromotionController::class, 'deactivate'])->name('promotion.deactivate');
});

// ── Client ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])->prefix('client')->name('customer.')->group(function () {
    Route::resource('cereri', AccountServiceRequestController::class)
        ->parameters(['cereri' => 'cerere'])
        ->names([
            'index'   => 'requests.index',
            'create'  => 'requests.create',
            'store'   => 'requests.store',
            'show'    => 'requests.show',
            'edit'    => 'requests.edit',
            'update'  => 'requests.update',
            'destroy' => 'requests.destroy',
        ]);
});

// ── Mesagerie (orice utilizator autentificat) ─────────────────
Route::middleware('auth')->prefix('mesaje')->name('messages.')->group(function () {
    Route::get('/', [ConversationController::class, 'index'])->name('index');
    Route::get('/unread-count', [ConversationController::class, 'unreadCount'])->name('unread');
    Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
    Route::post('/{conversation}', [ConversationController::class, 'store'])->name('store');
    Route::get('/{conversation}/poll', [ConversationController::class, 'poll'])->name('poll');

    // Initiere conversatie din anunt / cerere
    Route::post('/start/anunt/{listing}', [ConversationController::class, 'startFromListing'])->name('start.listing');
    Route::post('/start/cerere/{serviceRequest}', [ConversationController::class, 'startFromRequest'])->name('start.request');
});

// ── Admin + Moderator ─────────────────────────────────────────
Route::middleware(['auth', 'role:admin,moderator'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Anunturi
    Route::get('anunturi', [AdminListingController::class, 'index'])->name('listings.index');
    Route::get('anunturi/{listing}', [AdminListingController::class, 'show'])->name('listings.show');
    Route::patch('anunturi/{listing}/approve', [AdminListingController::class, 'approve'])->name('listings.approve');
    Route::patch('anunturi/{listing}/reject', [AdminListingController::class, 'reject'])->name('listings.reject');
    Route::delete('anunturi/{listing}', [AdminListingController::class, 'destroy'])->name('listings.destroy');

    // Cereri servicii
    Route::get('cereri', [AdminServiceRequestController::class, 'index'])->name('requests.index');
    Route::get('cereri/{serviceRequest}', [AdminServiceRequestController::class, 'show'])->name('requests.show');
    Route::patch('cereri/{serviceRequest}/approve', [AdminServiceRequestController::class, 'approve'])->name('requests.approve');
    Route::patch('cereri/{serviceRequest}/reject', [AdminServiceRequestController::class, 'reject'])->name('requests.reject');
    Route::delete('cereri/{serviceRequest}', [AdminServiceRequestController::class, 'destroy'])->name('requests.destroy');

    // Utilizatori
    Route::get('utilizatori', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('utilizatori/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('utilizatori/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Categorii
    Route::resource('categorii', AdminCategoryController::class)
        ->parameters(['categorii' => 'category'])
        ->names('categories');

    // Cereri meserias
    Route::get('cereri-meserias', [AdminCraftsmanApplicationController::class, 'index'])->name('craftsman-applications.index');
    Route::get('cereri-meserias/{craftsmanApplication}', [AdminCraftsmanApplicationController::class, 'show'])->name('craftsman-applications.show');
    Route::patch('cereri-meserias/{craftsmanApplication}/approve', [AdminCraftsmanApplicationController::class, 'approve'])->name('craftsman-applications.approve');
    Route::patch('cereri-meserias/{craftsmanApplication}/reject', [AdminCraftsmanApplicationController::class, 'reject'])->name('craftsman-applications.reject');
});

require __DIR__.'/auth.php';
