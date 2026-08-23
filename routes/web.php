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
use App\Http\Controllers\Sw\CompanyController;

// ── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest.only');

Route::post('/login',    [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit')->middleware('throttle:5,1');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ── Public pages ─────────────────────────────────────────────────────────────
Route::view('/', 'sw.home')->name('home');

Route::get('/v1', [PublicController::class, 'welcome'])->name('home.v1');

Route::get('/unlisted', function () {
    return view('public.unlisted');
});

Route::get('/unlisted-shares/', [CompanyController::class, 'directory'])->name('sw.unlisted-shares');

Route::get('/unlisted-shares/{slug}/', [CompanyController::class, 'index'])->name('sw.unlisted-shares.company');
Route::get('/unlisted-shares/{slug}/about/', [CompanyController::class, 'about'])->name('sw.unlisted-shares.company.about');
Route::get('/unlisted-shares/{slug}/thesis/', [CompanyController::class, 'thesis'])->name('sw.unlisted-shares.company.thesis');

// v1 — old Bootstrap company page, real DB data, kept for rollback/reference.
Route::get('/unlisted-shares-v1/{slug}', [StocksController::class, 'company'])->name('stocks.company');

Route::view('/listed/', 'sw.listed.index')->name('sw.listed');
Route::view('/listed/reliance/', 'sw.listed.reliance')->name('sw.listed.reliance');

Route::view('/mutual-funds/', 'sw.mutual-funds.index')->name('sw.mutual-funds');

Route::view('/pms/', 'sw.pms.index')->name('sw.pms');

Route::view('/fixed-deposits/', 'sw.fixed-deposits.index')->name('sw.fixed-deposits');
Route::view('/fixed-deposits/suryoday/', 'sw.fixed-deposits.suryoday')->name('sw.fixed-deposits.suryoday');

Route::view('/digital-gold/', 'sw.digital-gold.index')->name('sw.digital-gold');

Route::view('/digital-silver/', 'sw.digital-silver.index')->name('sw.digital-silver');

Route::view('/etf/', 'sw.etf.index')->name('sw.etf');

Route::view('/screener/', 'sw.screener.index')->name('sw.screener');

Route::view('/compare/', 'sw.compare.index')->name('sw.compare');
Route::view('/compare/nse-india-vs-nayara-energy/', 'sw.compare.nse-india-vs-nayara-energy')->name('sw.compare.nse-india-vs-nayara-energy');

Route::view('/calculators/', 'sw.calculators.index')->name('sw.calculators');

Route::view('/wittyscore/', 'sw.wittyscore.index')->name('sw.wittyscore');

Route::view('/blog/', 'sw.blog.index')->name('sw.blog.slash');
Route::view('/blog', 'sw.blog.index')->name('sw.blog');
Route::view('/blog/how-to-buy-unlisted-shares/', 'sw.blog.how-to-buy-unlisted-shares')->name('sw.blog.how-to-buy-unlisted-shares');
Route::view('/blog/how-to-sell-unlisted-shares/', 'sw.blog.how-to-sell-unlisted-shares')->name('sw.blog.how-to-sell-unlisted-shares');
Route::view('/blog/is-it-safe-to-buy-unlisted-shares/', 'sw.blog.is-it-safe-to-buy-unlisted-shares')->name('sw.blog.is-it-safe-to-buy-unlisted-shares');
Route::view('/blog/risks-of-investing-in-unlisted-shares/', 'sw.blog.risks-of-investing-in-unlisted-shares')->name('sw.blog.risks-of-investing-in-unlisted-shares');
Route::view('/blog/tax-on-unlisted-shares/', 'sw.blog.tax-on-unlisted-shares')->name('sw.blog.tax-on-unlisted-shares');
Route::view('/blog/unlisted-shares-vs-listed-shares/', 'sw.blog.unlisted-shares-vs-listed-shares')->name('sw.blog.unlisted-shares-vs-listed-shares');
Route::view('/blog/what-are-unlisted-shares/', 'sw.blog.what-are-unlisted-shares')->name('sw.blog.what-are-unlisted-shares');

Route::view('/news/', 'sw.news.index')->name('sw.news');
Route::view('/news/nse-ipo-sebi-noc-2026/', 'sw.news.nse-ipo-sebi-noc-2026')->name('sw.news.nse-ipo-sebi-noc-2026');

Route::view('/case-studies/', 'sw.case-studies.index')->name('sw.case-studies');
Route::view('/case-studies/nse-pre-ipo-journey/', 'sw.case-studies.nse-pre-ipo-journey')->name('sw.case-studies.nse-pre-ipo-journey');
Route::view('/case-studies/first-time-unlisted-kyc/', 'sw.case-studies.first-time-unlisted-kyc')->name('sw.case-studies.first-time-unlisted-kyc');
Route::view('/case-studies/research-over-hype/', 'sw.case-studies.research-over-hype')->name('sw.case-studies.research-over-hype');

Route::view('/why-witty/', 'sw.why-witty.index')->name('sw.why-witty');
Route::view('/login-new/', 'sw.login.index')->name('sw.login');
Route::view('/signup-new/', 'sw.signup.index')->name('sw.signup');

Route::get('/blog-v1', function () {
    return view('public.blog');
})->name('blog.v1');

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

    Route::get('/unlisted/stocks/{fincode}/witty-score', [UnlistedStocksController::class, 'getWittyScoreModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.witty-score');

    Route::post('/unlisted/stocks/{fincode}/witty-score', [UnlistedStocksController::class, 'saveWittyScore'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.witty-score.save');

    Route::get('/unlisted/stocks/{fincode}/about-extra', [UnlistedStocksController::class, 'getAboutExtraModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.about-extra');

    Route::post('/unlisted/stocks/{fincode}/about-extra', [UnlistedStocksController::class, 'saveAboutExtra'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.about-extra.save');

    Route::get('/unlisted/stocks/{fincode}/insights', [UnlistedStocksController::class, 'getCompanyInsightsModal'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.insights');

    Route::post('/unlisted/stocks/{fincode}/insights', [UnlistedStocksController::class, 'saveCompanyInsights'])
        ->middleware('privilege:unlisted')
        ->name('unlisted.stocks.insights.save');

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
