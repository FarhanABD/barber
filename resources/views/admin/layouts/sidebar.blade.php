<div class="main-sidebar">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <a href="index.html">Barber Panel</a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="index.html"></a>
                </div>

                <ul class="sidebar-menu">

                    <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-hand-point-right"></i> <span>Dashboard</span></a></li>

                    <li class="{{ Request::is('admin/services') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.services.index') }}"><i class="fas fa-hand-point-right"></i> <span>Layanan</span></a></li>

                    <li class="{{ Request::is('admin/bookings') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.bookings.index') }}"><i class="fas fa-hand-point-right"></i> <span>Bookings</span></a></li>

                    <li class="{{ Request::is('admin/karyawan') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.karyawan.index') }}"><i class="fas fa-hand-point-right"></i> <span>Data Barber</span></a></li>
                    
                    <li class="{{ Request::is('admin/transactions') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.transactions.index') }}"><i class="fas fa-hand-point-right"></i> <span>Data Transaksi</span></a></li>

                    <li class="menu-divider"></li>
                </ul>

            </aside>
            <aside>
                   <div class="sidebar-brand">
                    <a href="index.html">Angkringan Panel</a>
                </div>
                <div class="sidebar-brand sidebar-brand-sm">
                    <a href="index.html"></a>
                </div>
                <ul class="sidebar-menu">
                    <li class="{{ Request::is('admin/categories') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.categories.index') }}"><i class="fas fa-hand-point-right"></i> <span>Category Menu</span></a></li>
                    <li class="{{ Request::is('admin/menus') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.menus.index') }}"><i class="fas fa-hand-point-right"></i> <span>Menu</span></a></li>

                     <li class="{{ Request::is('admin/transaction-angkringan') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.transaction-angkringan.index') }}"><i class="fas fa-hand-point-right"></i> <span>Data Transaksi Angkringan</span></a></li>
                </ul>
            </aside>
            @if(auth()->user()->role === 'admin')
            <aside>
                  <div class="sidebar-brand">
                    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
                </div>
                <ul class="sidebar-menu">
                       <li class="{{ Request::is('admin/mitras') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.mitras.index') }}"><i class="fas fa-hand-point-right"></i> <span>Mitra Angkringan</span></a></li>
                       <li class="{{ Request::is('admin/income') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.income') }}"><i class="fas fa-hand-point-right"></i> <span>Pendapatan</span></a></li>
                </ul>
            </aside>
            @endif
        </div>