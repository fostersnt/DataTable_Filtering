<?php
Route::get('all_chale_errors', [ChaleController::class, 'all_chale_errors'])->name('chale.errors.report');

Route::get('/chale_errors_export', [ChaleController::class, 'chale_errors_export'])->name('chale_errors_export');
