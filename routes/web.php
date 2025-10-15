<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Front\StaticPages;

Route::get('/', App\Livewire\Front\HomePage::class)->name('home');

// Page de construction
Route::get('/construction', App\Livewire\Front\ConstructionPage::class)->name('construction');

// Route pour les pages statiques (doit être en dernier pour éviter les conflits)
Route::get('/pages/{slug}', StaticPages::class)->where('slug', '[a-zA-Z0-9\-_]+')->name('page');
