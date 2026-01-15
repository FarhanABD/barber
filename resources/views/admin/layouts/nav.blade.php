  <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li>
                     <a href="#" id="sidebarToggle" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right justify-content-end rightsidetop">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img alt="image" src="{{ asset('frontend/images/logo-barber-fix-no-hoax.png') }}" class="rounded-circle-custom">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <script>
document.getElementById('sidebarToggle').addEventListener('click', function (e) {
    e.preventDefault();
    document.body.classList.toggle('sidebar-mini');
});
</script>
