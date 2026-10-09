<!-- Sidebar Menu -->
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
    @if (Auth::user()->role == 'admin')
      <li class="nav-item">
        <a href="{{ route('dashboard.index') }}" class="nav-link">
          <i class="nav-icon fas fa-home"></i>
          <p>
            Dashboard
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-table"></i>
          <p>
            Master Data
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="{{ route('barang.index') }}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Barang</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('gudang.index') }}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Gudang</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pelanggan.index') }}" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Pelanggan</p>
            </a>
          </li>
        </ul>
      </li>
    @endif
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="nav-icon fas fa-exchange-alt"></i>
        <p>
          Transaksi
          <i class="fas fa-angle-left right"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ route('transaksi.index') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Daftar Transaksi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('transaksi.create', 'masuk') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Barang Masuk</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('transaksi.create', 'penjualan') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Penjualan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('transaksi.create', 'transfer') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Transfer Gudang</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="nav-icon fas fa-search"></i>
        <p>
          Setting
          <i class="fas fa-angle-left right"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Profil</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</nav>
<!-- /.sidebar-menu -->
