<?php

use App\Http\Controllers\MosaicoController;

Route::get('/editor', [MosaicoController::class, 'index']);
