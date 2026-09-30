<?php

use App\Http\Controllers\ContactListController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\DemoSessionController;
use App\Http\Controllers\NewsRoomController;
use App\Http\Controllers\PressReleaseController;
use App\Http\Controllers\ProfileController;
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

Route::view('/', 'welcome')->name('home');
Route::view('/how-it-works', 'marketing.how-it-works')->name('marketing.how-it-works');
Route::view('/documentation', 'marketing.documentation')->name('marketing.documentation');
Route::view('/about', 'marketing.about')->name('marketing.about');
Route::redirect('/about-page-one', '/about');
Route::post('/demo', [DemoSessionController::class, 'store'])->name('demo.login');

Route::redirect('/dashboard', '/apps-journalist-list')->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/newsroom/{slug}', [NewsRoomController::class, 'publicIndex'])->name('newsroom.public');
Route::get('/newsroom/{slug}/{newsRoom}', [NewsRoomController::class, 'publicShow'])->name('newsroom.show');

Route::middleware('auth')->group(function () {
    Route::get('/apps-journalist-list', [DirectoryController::class, 'journalistsPage'])->name('journalists.page');
    Route::get('/apps-outlet-list', [DirectoryController::class, 'outletsPage'])->name('outlets.page');
    Route::get('/apps-contact-list-inbox', [ContactListController::class, 'page'])->name('contacts.page');
    Route::get('/apps-press-releases', [PressReleaseController::class, 'page'])->name('releases.page');
    Route::get('/apps-press-releases/start', [PressReleaseController::class, 'start'])->name('releases.start');
    Route::get('/apps-press-releases/create', [PressReleaseController::class, 'create'])->name('releases.create');
    Route::get('/apps-press-releases/{pressRelease}/edit', [PressReleaseController::class, 'edit'])->name('releases.edit');
    Route::get('/apps-news-rooms', [NewsRoomController::class, 'page'])->name('newsrooms.page');

    Route::prefix('ajax')->group(function () {
        Route::get('/journalists', [DirectoryController::class, 'journalists'])->name('ajax.journalists');
        Route::get('/outlets', [DirectoryController::class, 'outlets'])->name('ajax.outlets');
        Route::get('/directory/human-status', [DirectoryController::class, 'humanStatus'])->name('ajax.directory.human-status');
        Route::post('/directory/human-verify', [DirectoryController::class, 'verifyHuman'])->middleware('throttle:10,1')->name('ajax.directory.human-verify');
        Route::get('/directory/journalists/{journalist}', [DirectoryController::class, 'journalistDetails'])->middleware('throttle:60,1')->name('ajax.directory.journalist');
        Route::get('/directory/outlets/{outlet}', [DirectoryController::class, 'outletDetails'])->middleware('throttle:60,1')->name('ajax.directory.outlet');
        Route::post('/directory/contact-list', [DirectoryController::class, 'addToList'])->name('ajax.directory.add');
        Route::apiResource('contact-lists', ContactListController::class)->parameters(['contact-lists' => 'contactList']);
        Route::delete('/contact-lists/{contactList}/member', [ContactListController::class, 'detach'])->name('contact-lists.detach');
        Route::post('/press-releases/analyze', [PressReleaseController::class, 'analyze'])->middleware('throttle:12,1')->name('press-releases.analyze');
        Route::apiResource('press-releases', PressReleaseController::class)->except('show')->parameters(['press-releases' => 'pressRelease']);
        Route::apiResource('news-rooms', NewsRoomController::class)->except('show')->parameters(['news-rooms' => 'newsRoom']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
