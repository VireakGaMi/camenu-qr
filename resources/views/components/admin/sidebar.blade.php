<aside class="sidebar p-3 col-auto">
    <div class="d-flex align-items-center mb-3">
        <div class="flex-grow-1">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <div class="me-2"><i class="bi bi-speedometer2 fs-4 text-white"></i></div>
                <div>
                    <div class="small text-muted">Admin</div>
                    <div class="fw-bold">Panel</div>
                </div>
            </a>
        </div>
    </div>
    <nav>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.menus.index') }}"><i class="bi bi-list me-2"></i>Menus</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags me-2"></i>Categories</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.restaurants.index') }}"><i class="bi bi-shop me-2"></i>Restaurants</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.restaurant-subscriptions.index') }}"><i class="bi bi-card-checklist me-2"></i>Subscriptions</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.subscription-plans.index') }}"><i class="bi bi-stickies me-2"></i>Plans</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Users</a></li>
        </ul>
    </nav>
    <div class="mt-4 small text-muted">Version 1.0</div>
</aside>
