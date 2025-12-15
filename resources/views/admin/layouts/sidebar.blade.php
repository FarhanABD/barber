<div class="main-sidebar">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="index.html">Admin Panel</a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="index.html"></a>
                </div>

                <ul class="sidebar-menu">

                    <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-hand-point-right"></i> <span>Dashboard</span></a></li>

                    {{-- <li class="nav-item dropdown active">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-hand-point-right"></i><span>Dropdown Items</span></a>
                        <ul class="dropdown-menu">
                            <li class="active"><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 1</a></li>
                            <li class=""><a class="nav-link" href=""><i class="fas fa-angle-right"></i> Item 2</a></li>
                        </ul>
                    </li> --}}


                    <li class="{{ Request::is('admin/services') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.services.index') }}"><i class="fas fa-hand-point-right"></i> <span>Layanan</span></a></li>

                    {{-- <li class=""><a class="nav-link" href="invoice.html"><i class="fas fa-hand-point-right"></i> <span>Bookings</span></a></li> --}}

                    <li class="{{ Request::is('admin/karyawan') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.karyawan.index') }}"><i class="fas fa-hand-point-right"></i> <span>Data Barber</span></a></li>
                    
                    <li class="{{ Request::is('admin/transactions') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.transactions.index') }}"><i class="fas fa-hand-point-right"></i> <span>Data Transaksi</span></a></li>

                </ul>
            </aside>
        </div>