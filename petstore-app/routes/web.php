<?php
use App\Http\Controllers\PetController;

Route::get('/', function () {
    return redirect()->route('pets.index');
});

Route::resource('pets', PetController::class);
Route::post('pets/{id}/uploadImage', [PetController::class, 'uploadImage'])->name('pets.uploadImage');