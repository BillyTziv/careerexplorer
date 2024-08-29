<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailTemplate\EmailTemplateController;

/**************************************************************************
 * EMAIL TEMPLATES ROUTES
**************************************************************************/
Route::resource('/email-templates', EmailTemplateController::class);
// Route::get('/email-templates/compose-email', [EmailTemplateController::class, 'composeEmail']);
// Route::post('/send-email', [EmailTemplateController::class, 'sendEmail']);

Route::post('/email-templates/{emailTemplate}/send-test-email', [EmailTemplateController::class, 'sendTestEmail'])->name('email-templates.send-test-email');