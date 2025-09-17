<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo" style="justify-content: center;">
            <img src="{{ asset('assets/dashboard-assets/images/web-logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/dashboard-assets/images/web-logo.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/dashboard-assets/images/web-logo.png') }}" alt="site logo" class="logo-icon">
            <!--<h6>YOUR LOGO</h6>-->
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="">
                <a href="{{ route('dashboard') }}">
                    <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Application</li>

            @canany(['Permission Create', 'Permission View', 'Permission Edit', 'Permission Delete'])
                <li>
                    <a href="{{ route('permission.index') }}">
                        <iconify-icon icon="mdi:key-outline" class="menu-icon"></iconify-icon>
                        <span>Permission</span>
                    </a>
                </li>
            @endcanany

            @canany(['Role Create', 'Role View', 'Role Edit', 'Role Delete'])
                <li>
                    <a href="{{ route('role.index') }}">
                        <iconify-icon icon="mdi:shield-account-outline" class="menu-icon"></iconify-icon>
                        <span>Role</span>
                    </a>
                </li>
            @endcanany

            @canany(['Admin Create', 'Admin View', 'Admin Edit', 'Admin Delete'])
                <li>
                    <a href="{{ route('admin.index') }}">
                        <iconify-icon icon="mdi:account-tie-outline" class="menu-icon"></iconify-icon>
                        <span>Admin</span>
                    </a>
                </li>
            @endcanany

            @canany(['Vendor Create', 'Vendor View', 'Vendor Edit', 'Vendor Delete'])
                <li>
                    <a href="{{ route('vendor.index') }}">
                        <iconify-icon icon="mdi:store-outline" class="menu-icon"></iconify-icon>
                        <span>Vendor</span>
                    </a>
                </li>
            @endcanany

            @canany(['User Create', 'User View','User Edit','User Delete'])
                <li>
                    <a href="{{ route('user.index') }}">
                        <iconify-icon icon="mdi:account-multiple-outline" class="menu-icon"></iconify-icon>
                        <span>Users</span>
                    </a>
                </li>
            @endcanany

            @canany(['Order Create', 'Order View','Order Edit','Order Delete'])
                <li>
                    <a href="{{ route('order.index') }}">
                        <iconify-icon icon="mdi:cart-outline" class="menu-icon"></iconify-icon>
                        <span>Orders</span>
                    </a>
                </li>
            @endcanany

            @canany(['SeatNumber Create', 'SeatNumber View', 'SeatNumber Edit', 'SeatNumber Delete'])
                <li>
                    <a href="{{ route('seatnumber.index') }}">
                        <iconify-icon icon="mdi:seat-outline" class="menu-icon"></iconify-icon>
                        <span>Seat Number</span>
                    </a>
                </li>
            @endcanany

            @canany(['Category Create', 'Category View','Category Edit','Category Delete'])
                <li>
                    <a href="{{ route('category.index') }}">
                        <iconify-icon icon="mdi:shape-outline" class="menu-icon"></iconify-icon>
                        <span>Category</span>
                    </a>
                </li>
            @endcanany

            @canany(['Hsncode Create', 'Hsncode View', 'Hsncode Edit', 'Hsncode Delete'])
                <li>
                    <a href="{{ route('hsncode.index') }}">
                        <iconify-icon icon="mdi:barcode" class="menu-icon"></iconify-icon>
                        <span>Hsncodes</span>
                    </a>
                </li>
            @endcanany

            @canany(['Brand Create', 'Brand View', 'Brand Edit', 'Brand Delete'])
                <li>
                    <a href="{{ route('brand.index') }}">
                        <iconify-icon icon="mdi:tag-outline" class="menu-icon"></iconify-icon>
                        <span>Brands</span>
                    </a>
                </li>
            @endcanany

            @canany(['Unit Master Create', 'Unit Master View','Unit Master Edit','Unit Master Delete'])
            <li>
                <a href="{{ route('units.index') }}">
                    <iconify-icon icon="mdi:scale-balance" class="menu-icon"></iconify-icon>
                    <span>Units Master</span>
                </a>
            </li>
            @endcanany

            @canany(['Seller Master Create', 'Seller Master View','Seller Master Edit','Seller Master Delete'])
            <li>
                <a href="{{ route('sellers.index') }}">
                    <iconify-icon icon="mdi:handshake-outline" class="menu-icon"></iconify-icon>
                    <span>Sellers Master</span>
                </a>
            </li>
            @endcanany

            @canany(['Product Create', 'Product View','Product Edit','Product Delete'])
                <li>
                    <a href="{{ route('product.index') }}">
                        <iconify-icon icon="mdi:package-variant-closed" class="menu-icon"></iconify-icon>
                        <span>Products</span>
                    </a>
                </li>
            @endcanany
        </ul>

    </div>
</aside>
