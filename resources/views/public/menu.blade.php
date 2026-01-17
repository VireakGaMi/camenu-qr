<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $restaurant->name }} – Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @if ($restaurant->logo)
        <link rel="icon" type="image/png" href="{{ asset('storage/logos/' . $restaurant->logo) }}">
    @endif

    <style>
        :root {
            --brand: #198754;
            --brand-dark: #157347;
            --brand-soft: #e9f7ef;
            --bg: #f5f7fa;
            --radius: 20px;
        }

        body {
            background: var(--bg);
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
        }

        /* ================= HEADER ================= */
        .menu-header {
            background: linear-gradient(160deg, #ffffff, #f1f4f7);
            border-radius: 30px;
            padding: 40px 24px 34px;
            text-align: center;
            box-shadow: 0 22px 48px rgba(0, 0, 0, .15);
            margin-top: 24px;
            position: relative;
            overflow: hidden;
        }

        .menu-header::after {
            content: "";
            position: absolute;
            top: -40%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(25, 135, 84, .18), transparent 70%);
        }

        .logo-wrap {
            width: 100px;
            height: 100px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .28);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .logo-wrap img {
            max-width: 64px;
            max-height: 64px;
            object-fit: contain;
        }

        .restaurant-name {
            font-weight: 800;
            letter-spacing: .4px;
            position: relative;
            z-index: 1;
        }

        .restaurant-meta {
            font-size: .85rem;
            color: #6c757d;
            position: relative;
            z-index: 1;
        }

        /* ================= CATEGORY BAR ================= */
        .category-wrapper {
            position: sticky;
            top: 0;
            z-index: 200;
            background: rgba(245, 247, 250, .92);
            backdrop-filter: blur(12px);
            padding: 14px 0 10px;
            margin-top: 28px;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
        }

        .category-bar {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 0 8px;
            scrollbar-width: none;
        }

        .category-bar::-webkit-scrollbar {
            display: none;
        }

        .category-pill {
            padding: 9px 18px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 700;
            border: 1.5px solid var(--brand);
            color: var(--brand);
            background: #fff;
            transition: all .25s ease;
            white-space: nowrap;
        }

        .category-pill.active,
        .category-pill:hover {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 6px 18px rgba(25, 135, 84, .35);
        }

        /* ================= MENU ================= */
        .menu-title {
            font-weight: 900;
            letter-spacing: .4px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .menu-title span {
            width: 6px;
            height: 26px;
            background: var(--brand);
            border-radius: 6px;
        }

        .menu-card {
            border-radius: var(--radius);
            background: #fff;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .12);
            overflow: hidden;
            transition: all .35s ease;
        }

        .menu-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 26px 56px rgba(0, 0, 0, .2);
        }

        .menu-img {
            height: 170px;
            width: 100%;
            object-fit: cover;
            background: #e9ecef;
        }

        .menu-body {
            padding: 14px 16px 18px;
        }

        .menu-name {
            font-weight: 700;
            font-size: .95rem;
        }

        .menu-desc {
            font-size: .78rem;
            color: #6c757d;
        }

        .price {
            font-weight: 900;
            color: var(--brand-dark);
            margin-top: 6px;
            font-size: .95rem;
        }

        .empty-box {
            background: #fff;
            border-radius: 22px;
            padding: 48px 20px;
            text-align: center;
            color: #6c757d;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .12);
        }

        footer {
            font-size: .75rem;
            color: #adb5bd;
            text-align: center;
            margin: 60px 0 30px;
        }
    </style>
</head>

<body>

    @php
        $categoryId = request('category');

        $categories = $restaurant->menus
            ->flatMap(fn($menu) => $menu->menuItems)
            ->pluck('category')
            ->filter()
            ->unique('id')
            ->values();
    @endphp

    <div class="container pb-4">

        {{-- HEADER --}}
        <div class="menu-header">
            @if ($restaurant->logo)
                <div class="logo-wrap">
                    <img src="{{ asset('storage/logos/' . $restaurant->logo) }}">
                </div>
            @endif

            <h2 class="restaurant-name">{{ $restaurant->name }}</h2>

            <div class="restaurant-meta">
                {{ $restaurant->address }}
                @if ($restaurant->phone)
                    <br>{{ $restaurant->phone }}
                @endif
            </div>
        </div>

        {{-- CATEGORY FILTER --}}
        @if ($categories->count())
            <div class="category-wrapper">
                <div class="category-bar">

                    <a href="{{ route('public.menu.show', $restaurant->slug) }}"
                        class="category-pill {{ !$categoryId ? 'active' : '' }} text-decoration-none">
                        All
                    </a>

                    @foreach ($categories as $category)
                    {{-- remove the underline --}}
                        <a href="{{ route('public.menu.show', [$restaurant->slug, 'category' => $category->id]) }}"
                            class="category-pill {{ $categoryId == $category->id ? 'active' : '' }} text-decoration-none">
                            {{ $category->name }}
                        </a>
                    @endforeach

                </div>
            </div>
        @endif

        {{-- MENUS --}}
        <div class="mt-5">
            @forelse ($restaurant->menus as $menu)

                @php
                    $items = $menu->menuItems->when($categoryId, fn($q) => $q->where('category_id', $categoryId));
                @endphp

                @if ($items->count())
                    <div class="mb-5">

                        <div class="menu-title">
                            <span></span> Menu
                        </div>

                        <div class="row g-3">
                            @foreach ($items as $item)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="menu-card h-100">

                                        @if ($item->image)
                                            <img src="{{ asset('storage/images/' . $item->image) }}" class="menu-img">
                                        @else
                                            <div
                                                class="menu-img d-flex align-items-center justify-content-center text-muted small">
                                                No Image
                                            </div>
                                        @endif

                                        <div class="menu-body">
                                            <div class="menu-name text-truncate">
                                                {{ $item->name }}
                                            </div>

                                            @if ($item->description)
                                                <div class="menu-desc text-truncate">
                                                    {{ $item->description }}
                                                </div>
                                            @endif

                                            <div class="price">
                                                ${{ number_format($item->price, 2) }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif

            @empty
                <div class="empty-box mt-5">
                    No menu available.
                </div>
            @endforelse
        </div>

        <footer>
            Powered by <strong>CaMenu-QR</strong>
        </footer>

    </div>

</body>

</html>
