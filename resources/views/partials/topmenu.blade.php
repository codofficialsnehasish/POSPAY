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

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                @canany(['Permission Create', 'Permission View', 'Permission Edit', 'Permission Delete'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('permission.*') ? 'active' : '' }}" href="{{ route('permission.index') }}">
                        <iconify-icon icon="mdi:key-outline" class="menu-icon"></iconify-icon>
                        <span>Permissions</span>
                    </a>
                </li>
                @endcanany

                @canany(['Role Create', 'Role View', 'Role Edit', 'Role Delete'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('role.*') ? 'active' : '' }}" href="{{ route('role.index') }}">
                        <iconify-icon icon="mdi:shield-account-outline" class="menu-icon"></iconify-icon>
                        <span>Roles</span>
                    </a>
                </li>
                @endcanany

                @canany(['Admin Create', 'Admin View', 'Admin Edit', 'Admin Delete'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                        <iconify-icon icon="mdi:account-tie-outline" class="menu-icon"></iconify-icon>
                        <span>Admins</span>
                    </a>
                </li>
                @endcanany

                @canany(['Vendor Create', 'Vendor View', 'Vendor Edit', 'Vendor Delete'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('vendor.*') ? 'active' : '' }}" href="{{ route('vendor.index') }}">
                        <iconify-icon icon="mdi:store-outline" class="menu-icon"></iconify-icon>
                        <span>Branch</span>
                    </a>
                </li>
                @endcanany

                @canany(['User Create', 'User View','User Edit','User Delete'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                        <iconify-icon icon="mdi:account-multiple-outline" class="menu-icon"></iconify-icon>
                        <span>Users</span>
                    </a>
                </li>
                @endcanany

                @canany(['Order Create', 'Order View','Order Edit','Order Delete'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('order.*') ? 'active' : '' }}" href="{{ route('order.index') }}">
                        <iconify-icon icon="mdi:cart-outline" class="menu-icon"></iconify-icon>
                        <span>Orders</span>
                    </a>
                </li>
                @endcanany

                @canany(['Transaction View'])
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transaction.*') ? 'active' : '' }}" href="{{ route('transaction.get-date-wise-total-payment') }}">
                        <iconify-icon icon="mdi:cash-multiple" class="menu-icon"></iconify-icon>
                        <span>Transactions</span>
                    </a>
                </li>
                @endcanany

                @if(auth()->user()->is_purchase_enabled)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('stock.transactions') ? 'active' : '' }}" href="{{ route('stock.transactions') }}">
                        <iconify-icon icon="mdi:scale-balance" class="menu-icon"></iconify-icon>
                        <span>Stock Transaction</span>
                    </a>
                </li>
                @endif

                @canany(['SeatNumber Create', 'SeatNumber View', 'SeatNumber Edit', 'SeatNumber Delete'])
                    <li>
                        <a class="nav-link {{ request()->routeIs('seatnumber.index') ? 'active' : '' }}" href="{{ route('seatnumber.index') }}">
                            <iconify-icon icon="mdi:seat-outline" class="menu-icon"></iconify-icon>
                            <span>Seat Number</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Category Create', 'Category View','Category Edit','Category Delete'])
                    <li>
                        <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}" href="{{ route('category.index') }}">
                            <iconify-icon icon="mdi:shape-outline" class="menu-icon"></iconify-icon>
                            <span>Category</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Hsncode Create', 'Hsncode View', 'Hsncode Edit', 'Hsncode Delete'])
                    <li>
                        <a class="nav-link {{ request()->routeIs('hsncode.index') ? 'active' : '' }}" href="{{ route('hsncode.index') }}">
                            <iconify-icon icon="mdi:barcode" class="menu-icon"></iconify-icon>
                            <span>Hsncodes</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Brand Create', 'Brand View', 'Brand Edit', 'Brand Delete'])
                    <li>
                        <a class="nav-link {{ request()->routeIs('brand.index') ? 'active' : '' }}" href="{{ route('brand.index') }}">
                            <iconify-icon icon="mdi:tag-outline" class="menu-icon"></iconify-icon>
                            <span>Brands</span>
                        </a>
                    </li>
                @endcanany

                @canany(['Unit Master Create', 'Unit Master View','Unit Master Edit','Unit Master Delete'])
                <li>
                    <a class="nav-link {{ request()->routeIs('units.index') ? 'active' : '' }}" href="{{ route('units.index') }}">
                        <iconify-icon icon="mdi:scale-balance" class="menu-icon"></iconify-icon>
                        <span>Units Master</span>
                    </a>
                </li>
                @endcanany

                @canany(['Seller Master Create', 'Seller Master View','Seller Master Edit','Seller Master Delete'])
                <li>
                    <a class="nav-link {{ request()->routeIs('sellers.index') ? 'active' : '' }}" href="{{ route('sellers.index') }}">
                        <iconify-icon icon="mdi:handshake-outline" class="menu-icon"></iconify-icon>
                        <span>Sellers Master</span>
                    </a>
                </li>
                @endcanany

                @canany(['Product Create', 'Product View','Product Edit','Product Delete'])
                    <li>
                        <a class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}" href="{{ route('product.index') }}">
                            <iconify-icon icon="mdi:package-variant-closed" class="menu-icon"></iconify-icon>
                            <span>Products</span>
                        </a>
                    </li>
                @endcanany

                <li>
                    <a class="nav-link {{ request()->routeIs('purchase.index') ? 'active' : '' }}" href="{{ route('purchase.index') }}">
                        <iconify-icon icon="mdi:package-variant-closed" class="menu-icon"></iconify-icon>
                        <span>Purchase</span>
                    </a>
                </li>

                <!-- Reports Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('report/*') ? 'active' : '' }}" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <iconify-icon icon="mdi:file-chart-outline" class="menu-icon"></iconify-icon>
                        <span>Reports</span>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2" aria-labelledby="reportsDropdown">
                        <li><a class="dropdown-item" href="{{ route('report.stock-report') }}">Current Stock</a></li>
                        <li><a class="dropdown-item" href="{{ route('report.sale-list') }}">Sales</a></li>
                        <li><a class="dropdown-item" href="{{ route('report.sale-item') }}">Sales Item</a></li>
                        <li><a class="dropdown-item" href="{{ route('report.purchase-list') }}">Purchase</a></li>
                        <li><a class="dropdown-item" href="{{ route('report.payment-list') }}">Payment</a></li>
                        <li><a class="dropdown-item" href="{{ route('report.expiry-list') }}">Expiry</a></li>
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
