@extends('layout.admin')

@section('title', 'Admin Dashboard | StockWitty')

@push('styles')
<style>
.dash-section-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    color: #94a3b8;
    margin: 28px 0 12px;
}
.dash-section-title:first-child { margin-top: 0; }

/* Stat cards */
.dash-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
}
.dash-card {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    text-decoration: none;
    transition: box-shadow .15s, border-color .15s;
}
.dash-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.09); border-color: #c8ddd8; }
.dash-card-icon {
    width: 46px;
    height: 46px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.dash-card-icon.green  { background: #e8f5f1; color: #076550; }
.dash-card-icon.teal   { background: #d4f6ee; color: #0a9a78; }
.dash-card-icon.blue   { background: #e3f0fd; color: #1565c0; }
.dash-card-icon.orange { background: #fff3e0; color: #e65100; }
.dash-card-icon.red    { background: #fce4ec; color: #c62828; }
.dash-card-icon.purple { background: #f3e5f5; color: #7b1fa2; }
.dash-card-body { min-width: 0; }
.dash-card-num  { font-size: 26px; font-weight: 700; color: #0f172a; line-height: 1; }
.dash-card-lbl  { font-size: 12px; color: #64748b; font-weight: 500; margin-top: 3px; white-space: nowrap; }

/* Quick links */
.dash-quicklinks {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 12px;
}
.dash-ql {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 10px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    text-decoration: none;
    transition: background .15s, border-color .15s;
}
.dash-ql:hover { background: #f0faf7; border-color: #0ECBA1; }
.dash-ql i { font-size: 20px; color: #076550; }
.dash-ql span { font-size: 13px; font-weight: 600; color: #1e293b; }
.dash-ql small { font-size: 11px; color: #94a3b8; line-height: 1.4; }

@media (max-width: 600px) {
    .dash-stats, .dash-quicklinks { grid-template-columns: 1fr 1fr; }
    .dash-card-num { font-size: 22px; }
}
</style>
@endpush

@section('content')
<div class="admin-main">

    <h1 class="admin-page-title">Dashboard</h1>

    {{-- ── Users ─────────────────────────────────────────── --}}
    <div class="dash-section-title">Users</div>
    <div class="dash-stats">
        <a class="dash-card" href="{{ url('/admin/users') }}">
            <div class="dash-card-icon green"><i class="fa-solid fa-users"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $totalUsers }}</div>
                <div class="dash-card-lbl">Total Users</div>
            </div>
        </a>
        <a class="dash-card" href="{{ url('/admin/users') }}">
            <div class="dash-card-icon purple"><i class="fa-solid fa-user-shield"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $adminUsers }}</div>
                <div class="dash-card-lbl">Admin Users</div>
            </div>
        </a>
        <a class="dash-card" href="{{ url('/admin/users') }}">
            <div class="dash-card-icon blue"><i class="fa-solid fa-user-check"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $memberUsers }}</div>
                <div class="dash-card-lbl">Members</div>
            </div>
        </a>
        <a class="dash-card" href="{{ url('/admin/users') }}">
            <div class="dash-card-icon orange"><i class="fa-solid fa-clock"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $kycPending }}</div>
                <div class="dash-card-lbl">KYC Pending</div>
            </div>
        </a>
    </div>

    {{-- ── Unlisted Stocks ───────────────────────────────── --}}
    <div class="dash-section-title">Unlisted Stocks</div>
    <div class="dash-stats">
        <a class="dash-card" href="{{ url('/admin/unlisted') }}">
            <div class="dash-card-icon green"><i class="fa-solid fa-chart-bar"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $totalStocks }}</div>
                <div class="dash-card-lbl">Total Stocks</div>
            </div>
        </a>
        <a class="dash-card" href="{{ url('/admin/unlisted') }}">
            <div class="dash-card-icon teal"><i class="fa-solid fa-circle-check"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $activeStocks }}</div>
                <div class="dash-card-lbl">Active Stocks</div>
            </div>
        </a>
        <a class="dash-card" href="{{ url('/admin/unlisted') }}">
            <div class="dash-card-icon red"><i class="fa-solid fa-circle-xmark"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $totalStocks - $activeStocks }}</div>
                <div class="dash-card-lbl">Inactive Stocks</div>
            </div>
        </a>
    </div>

    {{-- ── Leads & Orders ────────────────────────────────── --}}
    <div class="dash-section-title">Business</div>
    <div class="dash-stats">
        <a class="dash-card" href="{{ url('/admin/unlisted/leads') }}">
            <div class="dash-card-icon blue"><i class="fa-solid fa-address-book"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $totalLeads }}</div>
                <div class="dash-card-lbl">Total Leads</div>
            </div>
        </a>
        <a class="dash-card" href="{{ url('/admin/unlisted/orders') }}">
            <div class="dash-card-icon orange"><i class="fa-solid fa-file-invoice"></i></div>
            <div class="dash-card-body">
                <div class="dash-card-num">{{ $totalOrders }}</div>
                <div class="dash-card-lbl">Total Orders</div>
            </div>
        </a>
    </div>

    {{-- ── Quick Links ───────────────────────────────────── --}}
    <div class="dash-section-title">Quick Access</div>
    <div class="dash-quicklinks">
        <a class="dash-ql" href="{{ url('/admin/users') }}">
            <i class="fa-solid fa-users"></i>
            <span>Manage Users</span>
            <small>View, KYC verify &amp; manage privileges</small>
        </a>
        <a class="dash-ql" href="{{ url('/admin/unlisted') }}">
            <i class="fa-solid fa-chart-bar"></i>
            <span>Stocks</span>
            <small>Add &amp; manage unlisted stocks</small>
        </a>
        <a class="dash-ql" href="{{ url('/admin/unlisted/leads') }}">
            <i class="fa-solid fa-address-book"></i>
            <span>Leads</span>
            <small>View &amp; allocate leads</small>
        </a>
        <a class="dash-ql" href="{{ url('/admin/unlisted/orders') }}">
            <i class="fa-solid fa-file-invoice"></i>
            <span>Orders</span>
            <small>Track &amp; update order status</small>
        </a>
        <a class="dash-ql" href="{{ url('/') }}" target="_blank">
            <i class="fa-solid fa-globe"></i>
            <span>Public Site</span>
            <small>View the live StockWitty website</small>
        </a>
    </div>

</div>
@endsection
