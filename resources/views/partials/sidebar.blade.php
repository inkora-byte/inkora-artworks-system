<aside class="sidebar d-flex flex-column flex-shrink-0 p-3" id="sidebar">
    <a href="/dashboard" class="d-flex align-items-center mb-3 text-decoration-none sidebar-brand">
        <span class="fs-5 fw-bold">Inkora Artworks</span>
    </a>
    <hr class="text-white-50">

    <ul class="nav nav-pills flex-column mb-auto">
        {{-- Dashboard: visible to every authenticated user, regardless of role --}}
        <li class="nav-item">
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
            </a>
        </li>

        @can('manage-catalog')
            <li class="sidebar-section-title">Catalog</li>
            <li class="nav-item">
                <a href="/catalog/products" class="nav-link {{ request()->is('catalog/products*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box me-2"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a href="/catalog/designs" class="nav-link {{ request()->is('catalog/designs*') ? 'active' : '' }}">
                    <i class="fa-solid fa-palette me-2"></i> Designs
                </a>
            </li>
            <li class="nav-item">
                <a href="/catalog/product-types" class="nav-link {{ request()->is('catalog/product-types*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags me-2"></i> Product Types
                </a>
            </li>
        @endcan

        @can('manage-inventory')
            <li class="sidebar-section-title">Inventory</li>
            <li class="nav-item">
                <a href="/inventory/stock" class="nav-link {{ request()->is('inventory/stock*') ? 'active' : '' }}">
                    <i class="fa-solid fa-warehouse me-2"></i> Stock Levels
                </a>
            </li>
            <li class="nav-item">
                <a href="/inventory/movements" class="nav-link {{ request()->is('inventory/movements*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i> Movement History
                </a>
            </li>
        @endcan

        @can('manage-materials')
            <li class="sidebar-section-title">Materials</li>
            <li class="nav-item">
                <a href="/materials" class="nav-link {{ request()->is('materials') || (request()->is('materials/*') && ! request()->is('materials/adjustments*')) ? 'active' : '' }}">
                    <i class="fa-solid fa-scroll me-2"></i> Materials
                </a>
            </li>
            <li class="nav-item">
                <a href="/materials/adjustments" class="nav-link {{ request()->is('materials/adjustments*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders me-2"></i> Adjustments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                href="{{ route('categories.index') }}">
                    <i class="fa-solid fa-layer-group"></i> Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}"
                href="{{ route('units.index') }}">
                    <i class="fa-solid fa-ruler"></i> Units
                </a>
            </li>
        @endcan

        @can('manage-purchasing')
            <li class="sidebar-section-title">Purchasing</li>
            <li class="nav-item">
                <a href="/purchase-orders" class="nav-link {{ request()->is('purchase-orders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Purchase Orders
                </a>
            </li>
            <li class="nav-item">
                <a href="/suppliers" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck me-2"></i> Suppliers
                </a>
            </li>
        @endcan

        @can('manage-production')
            <li class="sidebar-section-title">Production</li>
            <li class="nav-item">
                <a href="/production-orders" class="nav-link {{ request()->is('production-orders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-industry me-2"></i> Production Orders
                </a>
            </li>
        @endcan

        @can('manage-sales')
            <li class="sidebar-section-title">Sales</li>
            <li class="nav-item">
                <a href="/orders" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt me-2"></i> Orders
                </a>
            </li>
            <li class="nav-item">
                <a href="/sales-channels" class="nav-link {{ request()->is('sales-channels*') ? 'active' : '' }}">
                    <i class="fa-solid fa-store me-2"></i> Sales Channels
                </a>
            </li>
        @endcan

        @can('manage-custom-orders')
            <li class="sidebar-section-title">Custom Orders</li>
            <li class="nav-item">
                <a href="/custom-orders" class="nav-link {{ request()->is('custom-orders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-paintbrush me-2"></i> Requests / Quotations
                </a>
            </li>
        @endcan

        @can('manage-customers')
            <li class="nav-item mt-2">
                <a href="/customers" class="nav-link {{ request()->is('customers*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users me-2"></i> Customers
                </a>
            </li>
        @endcan

        @can('manage-expenses')
            <li class="nav-item">
                <a href="/expenses" class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-wave me-2"></i> Expenses
                </a>
            </li>
        @endcan

        @can('view-reports')
            <li class="sidebar-section-title">Reports</li>
            <li class="nav-item">
                <a href="/reports" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line me-2"></i> Reports
                </a>
            </li>
        @endcan

        {{-- Notifications: personal to the logged-in user, not module-scoped —
             visible to everyone regardless of role, same reasoning as Dashboard --}}
        <li class="nav-item mt-2">
            <a href="/notifications" class="nav-link {{ request()->is('notifications*') ? 'active' : '' }}">
                <i class="fa-solid fa-bell me-2"></i> Notifications
            </a>
        </li>

        @can('manage-users')
            <li class="sidebar-section-title">Administration</li>
            <li class="nav-item">
                <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear me-2"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a href="/roles" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield me-2"></i> Roles
                </a>
            </li>
        @endcan

        @can('manage-settings')
            <li class="nav-item">
                <a href="/settings" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear me-2"></i> Settings
                </a>
            </li>
        @endcan
    </ul>
</aside>
