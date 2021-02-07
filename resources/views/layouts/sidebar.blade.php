  <!-- Brand Logo -->
  <a href="/" class="brand-link">

    <img src="{{ URL::asset('images/favicon.png') }}"
         alt="AdminLTE Logo"
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Admin Laundry</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ auth()->user()->photo != '' ? asset('storage/images/user/' . auth()->user()->photo) : asset('images/admin.png') }}" class="img-circle elevation-2" alt="User Image">

      </div>
      <div class="info">
        <a href="#" class="d-block">{{Auth::user()->name}}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @if (auth()->user()->can('Beranda'))
        <li class="nav-item">
          <a href="/" class="nav-link {{ (request()->is('/')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-home"></i>
            <p>
              Beranda
            </p>
          </a>
        </li>
        @endif
        @if (auth()->user()->can('Transaksi'))
        <li class="nav-item">
          <a href="/transaction" class="nav-link {{ (request()->is('transaction') || request()->is('transaction/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Transaksi
            </p>
          </a>
        </li>
        @endif
        @if (auth()->user()->can('Laporan'))
        <li class="nav-item">
          <a href="/report" class="nav-link {{ (request()->is('report') || request()->is('report/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>
              Laporan
            </p>
          </a>
        </li>
        @endif
        @if (auth()->user()->can('Data Master'))
        <li class="nav-item has-treeview {{ (request()->is('user') || request()->is('user/*') || request()->is('role') || request()->is('role/*') || request()->is('customer') || request()->is('customer/*') || request()->is('product') || request()->is('product/*') | request()->is('product_type') || request()->is('product_type/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('user') || request()->is('user/*') || request()->is('role') || request()->is('role/*') || request()->is('customer') || request()->is('customer/*') || request()->is('product') || request()->is('product/*') | request()->is('product_type') || request()->is('product_type/*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-server"></i>
              <p>Data Master<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
              @if (auth()->user()->can('Pengguna'))
              <li class="nav-item">
                  <a href="/user" class="nav-link {{ (request()->is('user') || request()->is('user/*')) ? 'active' : '' }}">
                      <i class="nav-icon fas fa-user"></i>
                      <p>Pengguna</p>
                  </a>
              </li>
              @endif
              @if (auth()->user()->can('Hak Akses'))
              <li class="nav-item">
                  <a href="/role" class="nav-link {{ (request()->is('role') || request()->is('role/*')) ? 'active' : '' }}">
                      <i class="fas fa-user-tag nav-icon"></i>
                      <p>Hak Akses</p>
                  </a>
              </li>
              @endif
              @if (auth()->user()->can('Pelanggan'))
              <li class="nav-item">
                  <a href="/customer" class="nav-link {{ (request()->is('customer') || request()->is('customer/*')) ? 'active' : '' }}">
                      <i class="nav-icon fas fa-users"></i>
                      <p>Pelanggan</p>
                  </a>
              </li>
              @endif
              @if (auth()->user()->can('Produk'))
              <li class="nav-item">
                  <a href="/product" class="nav-link {{ (request()->is('product') || request()->is('product/*')) ? 'active' : '' }}">
                      <i class="nav-icon fas fa-tshirt"></i>
                      <p>Produk</p>
                  </a>
              </li>
              @endif
              @if (auth()->user()->can('Produk'))
              <li class="nav-item">
                  <a href="/product_type" class="nav-link {{ (request()->is('product_type') || request()->is('product_type/*')) ? 'active' : '' }}">
                      <i class="nav-icon fas fa-tshirt"></i>
                      <p>Tipe Produk</p>
                  </a>
              </li>
              @endif
          </ul>
        </li>
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
