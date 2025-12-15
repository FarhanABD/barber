<nav class="navbar navbar-expand-md navbar-dark bg-light">
        <div class="container">
          <a class="navbar-brand" href="index.html">Antarshuka</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse navbar-light" id="navbarsExample05">
            <ul class="navbar-nav ml-auto pl-lg-5 pl-0">
             <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                  href="{{ route('dashboard') }}">
                    Home
                </a>
            </li>
             <li class="nav-item dropdown {{ request()->routeIs('hairstyle*') ? 'active' : '' }}">
                  <a class="nav-link dropdown-toggle {{ request()->routeIs('hairstyle*') ? 'active' : '' }}" href="{{ route('hairstyle') }}" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Haircut</a>
                  <div class="dropdown-menu" aria-labelledby="dropdown04">
                      <a class="dropdown-item {{ request()->routeIs('hairstyle') ? 'active' : '' }}"href="{{ route('hairstyle') }}">Model Rambut</a>
                  </div>
              </li>
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" 
                    href="{{ route('about') }}">
                      About
                  </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
              </li>
            </ul>
            
          </div>
        </div>
      </nav>