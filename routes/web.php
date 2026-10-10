<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCaregiverController;
use App\Http\Controllers\Admin\AdminClientController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFinancialController;
use App\Http\Controllers\Admin\AdminHiringRequestController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Caregiver\CaregiverDashboardController;
use App\Http\Controllers\CaregiverRegistrationController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/locale/{lang}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/api/detect-location', [HomeController::class, 'detectLocation'])->name('api.detect-location');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/caregivers', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/caregivers/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.show');

Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register/client', [AuthController::class, 'showClientRegister'])->name('register.client');
    Route::post('/register/client', [AuthController::class, 'registerClient'])->name('register.client.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Caregiver Registration Wizard (Can be guest or draft caregiver)
Route::get('/become-caregiver', [CaregiverRegistrationController::class, 'show'])->name('caregiver.register');
Route::post('/become-caregiver', [CaregiverRegistrationController::class, 'saveStep'])->name('caregiver.register.step');

/*
|--------------------------------------------------------------------------
| Secure Document Streaming
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function (): void {
    Route::get('/secure/documents/{uuid}', [DocumentController::class, 'showDocument'])->name('secure.document');
    Route::get('/secure/certificates/{uuid}', [DocumentController::class, 'showCertificate'])->name('secure.certificate');
});

/*
|--------------------------------------------------------------------------
| Client Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:client'])->prefix('client')->as('client.')->group(function (): void {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    // Hiring Requests
    Route::get('/requests', [ClientDashboardController::class, 'requests'])->name('requests.index');
    Route::get('/requests/create/{caregiver}', [ClientDashboardController::class, 'createRequest'])->name('requests.create');
    Route::post('/requests/store/{caregiver}', [ClientDashboardController::class, 'storeRequest'])->name('requests.store');
    Route::get('/requests/{request}', [ClientDashboardController::class, 'showRequest'])->name('requests.show');

    // Bookings & Reviews
    Route::get('/bookings', [ClientDashboardController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [ClientDashboardController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings/{booking}/review', [ClientDashboardController::class, 'storeReview'])->name('bookings.review');

    // Favorites
    Route::get('/favorites', [ClientDashboardController::class, 'favorites'])->name('favorites');
    Route::post('/favorites/{caregiver}/toggle', [ClientDashboardController::class, 'toggleFavorite'])->name('favorites.toggle');

    // Support Desk & Disputes
    Route::get('/support', [ClientDashboardController::class, 'support'])->name('support.index');
    Route::post('/support/ticket', [ClientDashboardController::class, 'storeTicket'])->name('support.store');
    Route::get('/support/{ticket}', [ClientDashboardController::class, 'showTicket'])->name('support.show');
    Route::post('/support/{ticket}/reply', [ClientDashboardController::class, 'replyTicket'])->name('support.reply');

    Route::get('/bookings/{booking}/dispute', [ClientDashboardController::class, 'createDispute'])->name('disputes.create');
    Route::post('/bookings/{booking}/dispute', [ClientDashboardController::class, 'storeDispute'])->name('disputes.store');

    // Profile & Family Info
    Route::get('/profile', [ClientDashboardController::class, 'profile'])->name('profile');
    Route::match(['post', 'put'], '/profile', [ClientDashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Caregiver Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:caregiver'])->prefix('caregiver')->as('caregiver.')->group(function (): void {
    Route::get('/dashboard', [CaregiverDashboardController::class, 'index'])->name('dashboard');

    // Assigned Hiring Requests
    Route::get('/requests', [CaregiverDashboardController::class, 'requests'])->name('requests.index');
    Route::get('/requests/{request}', [CaregiverDashboardController::class, 'showRequest'])->name('requests.show');
    Route::post('/requests/{hiringRequest}/accept', [CaregiverDashboardController::class, 'acceptRequest'])->name('requests.accept');
    Route::post('/requests/{hiringRequest}/decline', [CaregiverDashboardController::class, 'declineRequest'])->name('requests.decline');

    // Active & Completed Jobs
    Route::get('/jobs', [CaregiverDashboardController::class, 'jobs'])->name('jobs.index');
    Route::get('/jobs/{booking}', [CaregiverDashboardController::class, 'showJob'])->name('jobs.show');

    // Availability & Date Blocker
    Route::get('/availability', [CaregiverDashboardController::class, 'availability'])->name('availability');
    Route::post('/availability', [CaregiverDashboardController::class, 'updateAvailability'])->name('availability.update');
    Route::post('/availability/block', [CaregiverDashboardController::class, 'blockDate'])->name('availability.block');
    Route::delete('/availability/unblock/{blockedDate}', [CaregiverDashboardController::class, 'unblockDate'])->name('availability.unblock');

    // Earnings & Payouts
    Route::get('/earnings', [CaregiverDashboardController::class, 'earnings'])->name('earnings');
    Route::post('/earnings/payout', [CaregiverDashboardController::class, 'storePayout'])->name('earnings.payout');

    // Verification Documents
    Route::get('/documents', [CaregiverDashboardController::class, 'documents'])->name('documents');
    Route::post('/documents', [CaregiverDashboardController::class, 'storeDocument'])->name('documents.store');

    // Support
    Route::get('/support', [CaregiverDashboardController::class, 'support'])->name('support.index');
    Route::post('/support/ticket', [CaregiverDashboardController::class, 'storeTicket'])->name('support.store');
    Route::get('/support/{ticket}', [CaregiverDashboardController::class, 'showTicket'])->name('support.show');
    Route::post('/support/{ticket}/reply', [CaregiverDashboardController::class, 'replyTicket'])->name('support.reply');

    // Profile & Rates
    Route::get('/profile', [CaregiverDashboardController::class, 'profile'])->name('profile');
    Route::match(['post', 'put'], '/profile', [CaregiverDashboardController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Applications & Caregivers
    Route::get('/applications', [AdminCaregiverController::class, 'applications'])->name('applications.index');
    Route::get('/applications/{caregiver}', [AdminCaregiverController::class, 'showApplication'])->name('applications.show');
    Route::post('/applications/{caregiver}/approve', [AdminCaregiverController::class, 'approveAndPublish'])->name('applications.approve');
    Route::post('/applications/{caregiver}/request-changes', [AdminCaregiverController::class, 'requestChanges'])->name('applications.request-changes');
    Route::post('/applications/{caregiver}/reject', [AdminCaregiverController::class, 'reject'])->name('applications.reject');

    Route::get('/caregivers', [AdminCaregiverController::class, 'index'])->name('caregivers.index');
    Route::get('/caregivers/{caregiver}', [AdminCaregiverController::class, 'show'])->name('caregivers.show');
    Route::get('/caregivers/{caregiver}/edit', [AdminCaregiverController::class, 'edit'])->name('caregivers.edit');
    Route::put('/caregivers/{caregiver}', [AdminCaregiverController::class, 'update'])->name('caregivers.update');
    Route::post('/caregivers/{caregiver}/sort-order', [AdminCaregiverController::class, 'updateSortOrder'])->name('caregivers.sort-order');
    Route::post('/caregivers/{caregiver}/toggle-featured', [AdminCaregiverController::class, 'toggleFeatured'])->name('caregivers.toggle-featured');
    Route::post('/caregivers/{caregiver}/suspend', [AdminCaregiverController::class, 'suspend'])->name('caregivers.suspend');
    Route::post('/caregivers/{caregiver}/reactivate', [AdminCaregiverController::class, 'reactivate'])->name('caregivers.reactivate');

    // Hiring Requests Triage
    Route::get('/hiring-requests', [AdminHiringRequestController::class, 'index'])->name('hiring-requests.index');
    Route::get('/hiring-requests/{hiringRequest}', [AdminHiringRequestController::class, 'show'])->name('hiring-requests.show');
    Route::post('/hiring-requests/{hiringRequest}/approve', [AdminHiringRequestController::class, 'approve'])->name('hiring-requests.approve');
    Route::post('/hiring-requests/{hiringRequest}/request-info', [AdminHiringRequestController::class, 'requestMoreInfo'])->name('hiring-requests.request-info');
    Route::post('/hiring-requests/{hiringRequest}/reject', [AdminHiringRequestController::class, 'reject'])->name('hiring-requests.reject');
    Route::post('/hiring-requests/{hiringRequest}/reassign', [AdminHiringRequestController::class, 'reassign'])->name('hiring-requests.reassign');
    Route::post('/hiring-requests/{hiringRequest}/confirm', [AdminHiringRequestController::class, 'confirmBooking'])->name('hiring-requests.confirm');
    Route::post('/hiring-requests/{hiringRequest}/note', [AdminHiringRequestController::class, 'addNote'])->name('hiring-requests.note');

    // Bookings Management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/start', [AdminBookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{booking}/complete', [AdminBookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');

    // Clients Management
    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [AdminClientController::class, 'show'])->name('clients.show');
    Route::post('/clients/{client}/suspend', [AdminClientController::class, 'suspend'])->name('clients.suspend');
    Route::post('/clients/{client}/reactivate', [AdminClientController::class, 'reactivate'])->name('clients.reactivate');

    // Services Catalog
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

    // Locations Management
    Route::get('/locations', [AdminLocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [AdminLocationController::class, 'store'])->name('locations.store');
    Route::post('/locations/{location}/toggle', [AdminLocationController::class, 'toggle'])->name('locations.toggle');

    // Financial
    Route::get('/payments', [AdminFinancialController::class, 'payments'])->name('payments.index');
    Route::post('/payments/{payment}/mark-paid', [AdminFinancialController::class, 'markPaymentPaid'])->name('payments.mark-paid');
    Route::get('/payouts', [AdminFinancialController::class, 'payouts'])->name('payouts.index');
    Route::post('/payouts/{payout}/approve', [AdminFinancialController::class, 'approvePayout'])->name('payouts.approve');
    Route::post('/payouts/{payout}/mark-paid', [AdminFinancialController::class, 'markPayoutPaid'])->name('payouts.mark-paid');
    Route::post('/payouts/{payout}/reject', [AdminFinancialController::class, 'rejectPayout'])->name('payouts.reject');

    // Support Desk & Disputes
    Route::get('/support/tickets', [AdminSupportController::class, 'tickets'])->name('support.tickets');
    Route::get('/support/tickets/{ticket}', [AdminSupportController::class, 'showTicket'])->name('support.tickets.show');
    Route::post('/support/tickets/{ticket}/reply', [AdminSupportController::class, 'replyTicket'])->name('support.tickets.reply');

    Route::get('/disputes', [AdminSupportController::class, 'disputes'])->name('disputes.index');
    Route::get('/disputes/{dispute}', [AdminSupportController::class, 'showDispute'])->name('disputes.show');
    Route::post('/disputes/{dispute}/resolve', [AdminSupportController::class, 'resolveDispute'])->name('disputes.resolve');
    Route::post('/disputes/{dispute}/note', [AdminSupportController::class, 'addDisputeNote'])->name('disputes.note');

    // Reports, Settings & Audit Logs
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::get('/audit-logs', [AdminSettingController::class, 'auditLogs'])->name('audit-logs');
});
