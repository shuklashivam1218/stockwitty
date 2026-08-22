<?php

namespace App\Http\Controllers;

use App\Helpers\Privilege;
use App\Models\UnlistedLead;
use App\Models\UnlistedOrder;
use App\Models\UnlistedStock;
use App\Models\User;

class AdminController extends Controller
{
    public function redirectToDashboard()
    {
        if (!empty(Privilege::get('admin'))) {
            return redirect()->route('admin.dashboard');
        }
        if (!empty(Privilege::get('user_master'))) {
            return redirect()->route('admin.users');
        }
        if (!empty(Privilege::get('unlisted.stockx'))) {
            return redirect()->route('admin.unlisted');
        }
        if (!empty(Privilege::get('unlisted.leads')) || !empty(Privilege::get('unlisted.leads_allocation'))) {
            return redirect()->route('admin.unlisted.leads');
        }
        if (!empty(Privilege::get('pg.margin'))) {
            return redirect()->route('admin.pg.margin');
        }
        if (!empty(Privilege::get('pg.margin_error'))) {
            return redirect()->route('admin.pg.margin-error');
        }

        abort(403, 'You do not have admin access.');
    }

    public function dashboard()
    {
        $totalUsers  = User::count();
        $adminUsers  = User::whereNotNull('privilege')->get()->filter(fn($u) => !empty($u->privilege['admin']))->count();
        $memberUsers = User::where('user_type', 'member')->count();
        $kycPending  = User::where('user_type', 'member')
                           ->where(fn($q) => $q->whereNull('user_pan_verified')->orWhere('user_pan_verified', 0))
                           ->count();

        $totalStocks  = UnlistedStock::count();
        $activeStocks = UnlistedStock::where('UL_STOCKS_STATUS', '1')->count();

        $totalLeads  = UnlistedLead::count();
        $totalOrders = UnlistedOrder::count();

        return view('admin.dashboard', compact(
            'totalUsers', 'adminUsers', 'memberUsers', 'kycPending',
            'totalStocks', 'activeStocks',
            'totalLeads', 'totalOrders'
        ));
    }
}
