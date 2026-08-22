<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UnlistedLeadsController;
use App\Http\Controllers\UnlistedStocksController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UnlistedOrdersController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UnlistedReportController;
use App\Http\Controllers\CmsPagesController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StocksController;

// ── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest.only');

Route::post('/login',    [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('throttle:5,1');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ── Public pages ─────────────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'welcome'])->name('home');

Route::get('/unlisted', function () {
    return view('public.unlisted');
});

Route::get('/unlisted-shares/{slug}', [StocksController::class, 'company'])->name('stocks.company');

Route::get('/blog', function () {
    return view('public.blog');
})->name('blog');

Route::get('/disclaimer', [CmsPagesController::class, 'showDisclaimer'])->name('disclaimer');

// Profile placeholder (admin dropdown link)
Route::get('/profile', function () {
    return redirect('/admin');
})->name('profile');

// ── Admin entry ───────────────────────────────────────────────────────────────
Route::get('/admin', [AdminController::class, 'redirectToDashboard'])->name('admin.index');

Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('privilege:admin')
        ->name('dashboard');

    // ── Users ─────────────────────────────────────────────────────────────────
    Route::get('/users', [UsersController::class, 'index'])
        ->middleware('privilege:user_master')
        ->name('users');

    Route::get('/users/{uid}/kyc-docs', [UsersController::class, 'getKycDocsModal'])
        ->middleware('privilege:user_master')
        ->name('admin.users.kyc.docs');

    Route::post('/users/{uid}/kyc/{type}/verify', [UsersController::class, 'verifyKyc'])
        ->middleware('privilege:user_master')
        ->name('admin.users.kyc.verify');

    Route::get('/users/{uid}/kyc/{type}', [UsersController::class, 'serveKycFile'])
        ->middleware('privilege:user_master')
        ->name('admin.users.kyc');

    Route::post('/users/{uid}/reset-lockout', [UsersController::class, 'resetLockout'])
        ->middleware('privilege:user_master')
        ->name('users.reset-lockout');

    Route::get('/users/{uid}/privilege', [UsersController::class, 'getPrivilegeModal'])
        ->middleware('privilege:user_master')
        ->name('users.privilege.modal');

    Route::post('/users/{uid}/privilege', [UsersController::class, 'savePrivilege'])
        ->middleware('privilege:user_master')
        ->name('users.privilege');

    // ── Unlisted Stocks ───────────────────────────────────────────────────────
    Route::get('/unlisted', [UnlistedStocksController::class, 'index'])
        ->middleware('privilege:unlisted')
        ->name('unlisted');

    Route::post('/unlisted/stocks', [UnlistedStocksController::class, 'storeStock'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.store');

    Route::post('/unlisted/industries', [UnlistedStocksController::class, 'storeIndustry'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.industries.store');

    Route::post('/unlisted/stocks/{fincode}/toggle', [UnlistedStocksController::class, 'toggleStockStatus'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.toggle');

    Route::get('/unlisted/stocks/{fincode}/price', [UnlistedStocksController::class, 'getPriceModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.price');

    Route::post('/unlisted/stocks/{fincode}/price', [UnlistedStocksController::class, 'storePriceData'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.price.store');

    Route::post('/unlisted/stocks/{fincode}/price-list', [UnlistedStocksController::class, 'getPriceList'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.price.list');

    Route::patch('/unlisted/stocks/{fincode}/price/{date}', [UnlistedStocksController::class, 'updatePriceEntry'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.price.update');

    Route::delete('/unlisted/stocks/{fincode}/price/{date}', [UnlistedStocksController::class, 'deletePriceEntry'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.price.delete');

    Route::get('/unlisted/stocks/{fincode}/financials', [UnlistedStocksController::class, 'getFinancialsModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.financials');

    Route::post('/unlisted/stocks/{fincode}/financials', [UnlistedStocksController::class, 'storeFinancialsData'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.financials.store');

    Route::post('/unlisted/stocks/{fincode}/financials-list', [UnlistedStocksController::class, 'getFinancialsListModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.financials.list');

    Route::get('/unlisted/stocks/{fincode}/financials/{periodEnd}/{type}/{noMonths}/edit', [UnlistedStocksController::class, 'getFinancialsEditModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.financials.edit');

    Route::put('/unlisted/stocks/{fincode}/financials/{periodEnd}/{type}/{noMonths}', [UnlistedStocksController::class, 'updateFinancialsData'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.financials.update');

    Route::delete('/unlisted/stocks/{fincode}/financials/{periodEnd}/{type}/{noMonths}', [UnlistedStocksController::class, 'softDeleteFinancial'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.financials.delete');

    Route::get('/unlisted/stocks/{fincode}/thesis', [UnlistedStocksController::class, 'getThesisModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.thesis');

    Route::post('/unlisted/stocks/{fincode}/thesis', [UnlistedStocksController::class, 'saveThesis'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.thesis.save');

    Route::post('/unlisted/stocks/{fincode}/thesis/upload-image', [UnlistedStocksController::class, 'uploadThesisImage'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.thesis.upload');

    Route::get('/unlisted/stocks/{fincode}/about', [UnlistedStocksController::class, 'getAboutModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.about');

    Route::post('/unlisted/stocks/{fincode}/about', [UnlistedStocksController::class, 'saveAbout'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.about.save');

    Route::post('/unlisted/stocks/{fincode}/about/upload-image', [UnlistedStocksController::class, 'uploadAboutImage'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.about.upload');

    Route::get('/unlisted/stocks/{fincode}/overview', [UnlistedStocksController::class, 'getOverviewModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.overview');

    Route::post('/unlisted/stocks/{fincode}/overview', [UnlistedStocksController::class, 'updateOverview'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.overview.update');

    Route::get('/unlisted/stocks/{fincode}/faqs-list', [UnlistedStocksController::class, 'getFaqListModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.faqs.list');

    Route::get('/unlisted/stocks/{fincode}/faqs', [UnlistedStocksController::class, 'getFaqModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.faqs');

    Route::post('/unlisted/stocks/{fincode}/faqs', [UnlistedStocksController::class, 'storeFaq'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.faqs.store');

    Route::get('/unlisted/stocks/{fincode}/faqs/{faqId}/edit', [UnlistedStocksController::class, 'getFaqEditModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.faqs.edit');

    Route::put('/unlisted/stocks/{fincode}/faqs/{faqId}', [UnlistedStocksController::class, 'updateFaq'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.faqs.update');

    Route::delete('/unlisted/stocks/{fincode}/faqs/{faqId}', [UnlistedStocksController::class, 'deleteFaq'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.faqs.delete');

    // ── Unlisted Leads ────────────────────────────────────────────────────────
    Route::get('/unlisted/leads', [UnlistedLeadsController::class, 'leads'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.leads');

    Route::get('/unlisted/leads/data', [UnlistedLeadsController::class, 'leadsData'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.leads.data');

    Route::post('/unlisted/leads/{leadId}/allocate', [UnlistedLeadsController::class, 'allocateLead'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.leads.allocate');

    Route::post('/unlisted/leads/{leadId}/disposition', [UnlistedLeadsController::class, 'saveDisposition'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.leads.disposition');

    Route::post('/unlisted/leads/{leadId}/clear-callback-request', [UnlistedLeadsController::class, 'clearCallbackRequest'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.leads.clearCallbackRequest');

    Route::get('/unlisted/leads/{leadId}/activity', [UnlistedLeadsController::class, 'leadActivity'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.leads.activity');

    // ── User Dashboard Modal ──────────────────────────────────────────────────
    Route::prefix('/users/{uid}/dashboard')->middleware('privilege:admin,user_master,unlisted')->group(function () {
        Route::get('/',                [UserDashboardController::class, 'profile']);
        Route::get('/orders',          [UserDashboardController::class, 'orders']);
        Route::get('/demat',           [UserDashboardController::class, 'demat']);
        Route::get('/portfolio',       [UserDashboardController::class, 'portfolio']);
        Route::get('/transactions',    [UserDashboardController::class, 'transactions']);
        Route::get('/request-history',                          [UserDashboardController::class, 'requestHistory']);
        Route::post('/request-history/{requestId}/cancel',      [UserDashboardController::class, 'cancelRequest']);
        Route::get('/bank-demat',      [UserDashboardController::class, 'bankDemat']);
        Route::get('/communication',   [UserDashboardController::class, 'getCommunication']);
        Route::post('/communication',  [UserDashboardController::class, 'saveCommunication']);
        Route::get('/withdraw',        [UserDashboardController::class, 'withdrawForm']);
        Route::post('/withdraw',       [UserDashboardController::class, 'saveWithdraw']);
    });

    // ── Unlisted Orders ───────────────────────────────────────────────────────
    Route::get('/unlisted/orders', [UnlistedOrdersController::class, 'orders'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.orders');

    Route::get('/unlisted/orders/data', [UnlistedOrdersController::class, 'ordersData'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.orders.data');

    Route::post('/unlisted/orders/{orderId}/update', [UnlistedOrdersController::class, 'updateOrder'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.orders.update');

    // ── Unlisted Reports ──────────────────────────────────────────────────────
    Route::get('/unlisted/reports', [UnlistedReportController::class, 'index'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.reports');
    Route::post('/unlisted/reports/orders', [UnlistedReportController::class, 'ordersReport'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.reports.orders');
    Route::post('/unlisted/reports/customers', [UnlistedReportController::class, 'customerOrders'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.reports.customers');
    Route::post('/unlisted/reports/companies', [UnlistedReportController::class, 'companyOrders'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.reports.companies');
    Route::post('/unlisted/reports/combined', [UnlistedReportController::class, 'combinedFinancial'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.reports.combined');
    Route::post('/unlisted/reports/last-insert', [UnlistedReportController::class, 'lastInsert'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.reports.last-insert');

    // ── CMS ───────────────────────────────────────────────────────────────────
    Route::get('/cms', [CmsPagesController::class, 'index'])
        ->middleware('privilege:admin')
        ->name('cms');

    Route::get('/cms/{slug}/edit', [CmsPagesController::class, 'getEditModal'])
        ->middleware('privilege:admin')
        ->name('cms.edit');

    Route::post('/cms/{slug}', [CmsPagesController::class, 'update'])
        ->middleware('privilege:admin')
        ->name('cms.update');

    Route::post('/cms/{slug}/upload-image', [CmsPagesController::class, 'uploadImage'])
        ->middleware('privilege:admin')
        ->name('cms.upload');
});
