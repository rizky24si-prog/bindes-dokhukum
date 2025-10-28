<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
		<div class="sidebar-inner px-4 pt-3">
			<ul class="nav flex-column pt-3 pt-md-0">
				<li class="nav-item">
					<h3>DokdesKu</h3>
				</li>
				<li class="nav-item">
					<a href="{{ route('dashboard') }}" class="nav-link">
						<span class="sidebar-icon">
							<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
								xmlns="http://www.w3.org/2000/svg">
								<path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
								<path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
							</svg>
						</span>
						<span class="sidebar-text">Dashboard</span>
					</a>
				</li>
				<li class="nav-item">
					<span class="nav-link  collapsed  d-flex justify-content-between align-items-center"
						data-bs-toggle="collapse" data-bs-target="#submenu-app">
						<span>
							<span class="sidebar-icon">
								<svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
									xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd"
										d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
										clip-rule="evenodd"></path>
								</svg>
							</span>
							<span class="sidebar-text">Tables</span>
						</span>
						<span class="link-arrow">
							<svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
									clip-rule="evenodd"></path>
							</svg>
						</span>
					</span>
					<div class="multi-level collapse " role="list" id="submenu-app" aria-expanded="false">
						<ul class="flex-column nav">
							<li class="nav-item ">
								<a class="nav-link" href="{{ route('warga.index') }}">
									<span class="sidebar-text">Warga</span>
								</a>
							</li>
						</ul>
					</div>
                    <div class="multi-level collapse " role="list" id="submenu-app" aria-expanded="false">
						<ul class="flex-column nav">
							<li class="nav-item ">
								<a class="nav-link" href="{{ route('jenis-dokumen.index') }}">
									<span class="sidebar-text">Jenis Dokumen</span>
								</a>
							</li>
						</ul>
					</div>
                    <div class="multi-level collapse " role="list" id="submenu-app" aria-expanded="false">
						<ul class="flex-column nav">
							<li class="nav-item ">
								<a class="nav-link" href="{{ route('user.index') }}">
									<span class="sidebar-text">User</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
                <li class="nav-item">
    <a href="{{ route('logout') }}" class="nav-link">
        <span class="sidebar-icon">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
            </svg>
        </span>
        <span class="sidebar-text">Logout</span>
    </a>
</li>
			</ul>
		</div>
	</nav>
