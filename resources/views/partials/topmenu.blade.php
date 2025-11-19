<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-2">
    <div class="container-fluid">
        <!-- Logo (Left) -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}" style="font-weight: 600; font-size: 1.25rem;">
            <img 
                src="{{ asset('assets/dashboard-assets/images/web-logo.png') }}" 
                alt="POSPAY Logo" 
                class="img-fluid" 
                style="height: 42px; width: auto; object-fit: contain;"
            >
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topMenu" aria-controls="topMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Main Menu (Center) -->
        <div class="collapse navbar-collapse justify-content-center" id="topMenu">
            <ul class="navbar-nav main-menu align-items-center">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Entry --}}
                @if(auth()->user()->admin?->is_purchase_enabled)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Str::startsWith(request()->path(),'admin/purchase') ? 'active' : '' }}"
                        href="#" data-bs-toggle="dropdown">
                        <iconify-icon icon="mdi:account-group-outline" class="menu-icon"></iconify-icon>
                        <span>Entry</span>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        @if(auth()->user()->admin?->is_purchase_enabled)
                        <li><a class="dropdown-item" href="{{ route('purchase.index') }}">Purchase</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- User Management --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Str::startsWith(request()->path(), 'admin/permission') || Str::startsWith(request()->path(), 'admin/seatnumber') || Str::startsWith(request()->path(), 'admin/role') || Str::startsWith(request()->path(), 'admin/sellers') || Str::startsWith(request()->path(), 'admin/admin') || Str::startsWith(request()->path(), 'admin/vendor') || Str::startsWith(request()->path(), 'admin/user') ? 'active' : '' }}"

                        href="#" data-bs-toggle="dropdown">
                        <iconify-icon icon="mdi:account-group-outline" class="menu-icon"></iconify-icon>
                        <span>User Management</span>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        @canany(['Permission View'])
                            <li><a class="dropdown-item" href="{{ route('permission.index') }}">Permissions</a></li>
                        @endcanany
                        @canany(['Role View'])
                            <li><a class="dropdown-item" href="{{ route('role.index') }}">Roles</a></li>
                        @endcanany
                        @canany(['Admin View'])
                            <li><a class="dropdown-item" href="{{ route('admin.index') }}">Admins</a></li>
                        @endcanany
                        @canany(['Vendor View'])
                            <li><a class="dropdown-item" href="{{ route('vendor.index') }}">Branch</a></li>
                        @endcanany
                        @canany(['SeatNumber View'])
                            <li><a class="dropdown-item" href="{{ route('seatnumber.index') }}">Seat Number</a></li>
                        @endcanany
                        @canany(['User View'])
                            <li><a class="dropdown-item" href="{{ route('user.index') }}">Users</a></li>
                        @endcanany
                        @canany(['Seller Master View'])
                            <li><a class="dropdown-item" href="{{ route('sellers.index') }}">Sellers</a></li>
                        @endcanany
                    </ul>
                </li>

                {{-- Product Management --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Str::startsWith(request()->path(), 'admin/category') || Str::startsWith(request()->path(), 'admin/brand') || Str::startsWith(request()->path(), 'admin/units') || Str::startsWith(request()->path(), 'admin/hsncode') || Str::startsWith(request()->path(), 'admin/product') ? 'active' : '' }}"
                        href="#" data-bs-toggle="dropdown">
                        <iconify-icon icon="mdi:package-variant" class="menu-icon"></iconify-icon>
                        <span>Product Management</span>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        @canany(['Category View'])
                            <li><a class="dropdown-item" href="{{ route('category.index') }}">Categories</a></li>
                        @endcanany

                        @canany(['Brand View'])
                            <li><a class="dropdown-item" href="{{ route('brand.index') }}">Brands</a></li>
                        @endcanany

                        @canany(['Unit Master View'])
                            <li><a class="dropdown-item" href="{{ route('units.index') }}">Unit Master</a></li>
                        @endcanany

                        @canany(['Hsncode View'])
                            <li><a class="dropdown-item" href="{{ route('hsncode.index') }}">HSN Codes</a></li>
                        @endcanany

                        @canany(['Product View'])
                            <li><a class="dropdown-item" href="{{ route('product.index') }}">Products</a></li>
                        @endcanany

                        {{-- @if(auth()->user()->is_purchase_enabled)
                        <li><a class="dropdown-item" href="{{ route('purchase.index') }}">Purchase</a></li>
                        @endif --}}
                    </ul>
                </li>

                {{-- Sales & Orders --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Str::startsWith(request()->path(), 'admin/order') || Str::startsWith(request()->path(), 'admin/get-date-wise-total-payment') ? 'active' : '' }}"
                        href="#" data-bs-toggle="dropdown">
                        <iconify-icon icon="mdi:cart-outline" class="menu-icon"></iconify-icon>
                        <span>Sales & Orders</span>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        @canany(['Order View'])
                            <li><a class="dropdown-item" href="{{ route('order.index') }}">Orders</a></li>
                        @endcanany

                        @canany(['Transaction View'])
                            <li><a class="dropdown-item" href="{{ route('transaction.get-date-wise-total-payment') }}">Transactions</a></li>
                        @endcanany
                    </ul>
                </li>

                {{-- Stock & Sellers --}}
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle 
                        {{ request()->is('seatnumber/*') || request()->is('sellers/*') || request()->routeIs('stock.transactions') ? 'active' : '' }}"
                        href="#" data-bs-toggle="dropdown">
                        <iconify-icon icon="mdi:store-settings-outline" class="menu-icon"></iconify-icon>
                        <span>Inventory</span>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        @if(auth()->user()->is_purchase_enabled)
                            <li><a class="dropdown-item" href="{{ route('stock.transactions') }}">Stock Transaction</a></li>
                        @endif

                        @canany(['SeatNumber View'])
                            <li><a class="dropdown-item" href="{{ route('seatnumber.index') }}">Seat Number</a></li>
                        @endcanany

                        @canany(['Seller Master View'])
                            <li><a class="dropdown-item" href="{{ route('sellers.index') }}">Sellers</a></li>
                        @endcanany
                    </ul>
                </li> --}}

                {{-- Reports --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Str::startsWith(request()->path(), 'admin/report') ? 'active' : '' }}"
                    data-bs-toggle="dropdown">
                        <iconify-icon icon="mdi:file-chart-outline" class="menu-icon"></iconify-icon>
                        <span>Reports</span>
                    </a>

                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">

                        <!-- Current Stock -->
                        <li><a class="dropdown-item" href="{{ route('report.stock-report') }}">Current Stock</a></li>

                        <!-- Sales Submenu -->
                        <li class="dropdown-submenu">
                            <a class="dropdown-item submenu-toggle" href="#">
                                Sales ▸
                            </a>
                            <ul class="dropdown-menu shadow border-0 rounded-3 p-2">
                                <li><a class="dropdown-item" href="{{ route('report.sale-list') }}">Summary</a></li>
                                <li><a class="dropdown-item" href="{{ route('report.sale-item') }}">Items</a></li>
                            </ul>
                        </li>

                        <!-- Purchase Submenu -->
                        <li class="dropdown-submenu">
                            <a class="dropdown-item submenu-toggle" href="#">
                                Purchase ▸
                            </a>
                            <ul class="dropdown-menu shadow border-0 rounded-3 p-2">
                                <li><a class="dropdown-item" href="{{ route('report.purchase-list') }}">Summary</a></li>
                                <li><a class="dropdown-item" href="{{ route('report.purchase-products') }}">Product</a></li>
                            </ul>
                        </li>

                        <!-- Stock Transaction -->
                        @if(auth()->user()->is_purchase_enabled)
                        <li><a class="dropdown-item" href="{{ route('stock.transactions') }}">Stock Transaction</a></li>
                        @endif

                        <!-- Payment -->
                        <li><a class="dropdown-item" href="{{ route('report.payment-list') }}">Payment</a></li>

                        <!-- Expiry -->
                        <li><a class="dropdown-item" href="{{ route('report.expiry-list') }}">Expiry</a></li>

                        <li><a class="dropdown-item" href="{{ route('report.login-logs') }}">Login Logs</a></li>

                    </ul>
                </li>


            </ul>

        </div>


        <!-- Profile / Logout (Right) -->
        <div class="d-flex align-items-center">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/dashboard-assets/images/user.png') }}" class="rounded-circle" width="32" height="32" alt="User">
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Log Out
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
