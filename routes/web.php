<?php

use App\Http\Controllers\ClassController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
  
    return redirect()->route('classes.index');
    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('classes', ClassController::class);
    Route::post('classes/join', [ClassController::class, 'join'])->name('classes.join');

    Route::resource('groups', GroupController::class)->only(['store', 'show', 'update', 'destroy', 'create', 'index']);
    Route::resource('boards', BoardController::class)->only(['store', 'show', 'update']);
    Route::resource('lists', ListController::class)->only(['store', 'show', 'update', 'destroy']);
    Route::resource('cards', CardController::class)->only(['store', 'show', 'update', 'destroy']);
    Route::resource('checklists', ChecklistController::class)->only(['store', 'show', 'update', 'destroy']);
    Route::resource('checklist-items', ChecklistItemController::class)->only(['store', 'show', 'update', 'destroy']);

    Route::post('/cards/reorder', [CardController::class, 'reorder'])->name('cards.reorder');
    Route::post('/lists/reorder', [ListController::class, 'reorder'])->name('lists.reorder');


    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::post('/grades/{group}/unlock', [GradeController::class, 'unlock'])->name('grades.unlock');
    Route::post('/groups/members', [GroupController::class, 'storeMember'])->name('groups.members.store');
    Route::get('/groups/{group}/candidates', [GroupController::class, 'searchCandidates'])->name('groups.candidates');
    Route::post('/groups/{group}/transfer-leadership', [GroupController::class, 'transferLeadership'])->name('groups.transfer_leadership');


    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [App\Http\Controllers\DashboardController::class, 'student'])->name('student.dashboard');
});

Route::middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('/lecturer/dashboard', [App\Http\Controllers\DashboardController::class, 'lecturer'])->name('lecturer.dashboard');
});

require __DIR__.'/auth.php';
