<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Front\StaticPages;

Route::get('/', App\Livewire\Front\HomePage::class);

// Route pour les pages statiques (doit être en dernier pour éviter les conflits)
Route::get('/pages/{slug}', StaticPages::class)->where('slug', '[a-zA-Z0-9\-_]+')->name('page');
