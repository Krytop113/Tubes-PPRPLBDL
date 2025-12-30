@php
    $role = auth()->user()->role->name;
    $currentRoute = Route::currentRouteName();
@endphp

<div class="sidebar position-fixed top-0 start-0 vh-100 shadow">
    <div class="p-4">
        <h4 class="text-white fw-bold mb-0">Control Panel</h4>
        <h6 class="text-white fw-bold mb-0">Kriuk Kriuk</h6>
    </div>

    <div class="mt-3">
        <div class="sidebar-heading">Operasional</div>

        @if (in_array($role, ['admin', 'employee']))
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('control/dashboard*') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            <a href="{{ route('control.ingredients.index') }}" class="nav-link {{ request()->is('control/ingredients*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> Bahan Baku
            </a>

            <a href="{{ route('control.recipes.index') }}" class="nav-link {{ request()->is('control/recipes*') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i> Daftar Resep
            </a>

            <a href="{{ route('control.coupons.index') }}" class="nav-link {{ request()->is('control/coupons*') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i> Mengatur Coupon
            </a>

            <a href="{{ route('control.orders.index') }}" class="nav-link {{ request()->is('control/orders*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Laporan Pesanan
            </a>
        @endif

        @if ($role === 'admin')
            <div class="sidebar-heading mt-4">Manajemen User</div>

            <a href="#" class="nav-link {{ request()->is('control/users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Daftar Pengguna
            </a>

            <a href="#" class="nav-link {{ request()->is('control/employees*') ? 'active' : '' }}">
                <i class="fas fa-id-badge"></i> Daftar Karyawan
            </a>

            <div class="sidebar-heading mt-4">Sistem</div>

            <a href="#" class="nav-link {{ request()->is('control/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i> Reports
            </a>

            <a href="#" class="nav-link {{ request()->is('control/settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Settings
            </a>
        @endif
    </div>
</div>
