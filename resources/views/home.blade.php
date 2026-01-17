<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('ui.landing_title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="{{ __('ui.landing_description') }}">

    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-soft: #f1f5f9;
            --bg-dark: #0f172a;
            --primary: #8b5e3c;
            --accent: #d6a56a;
            --text-dark: #020617;
            --text-muted: #64748b;
            --radius: 24px;
        }

        body {
            background: var(--bg-main);
            font-family: Inter, system-ui, sans-serif;
            color: var(--text-dark);
        }

        section {
            padding: 120px 0;
        }

        h1,
        h2 {
            font-weight: 900;
            letter-spacing: -0.8px;
        }

        h1 {
            font-size: 3.6rem;
            line-height: 1.05;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar {
            backdrop-filter: blur(12px);
        }

        .btn-main {
            background: linear-gradient(135deg, var(--primary), #6f4427);
            color: #fff;
            padding: 14px 40px;
            border-radius: 999px;
            font-weight: 600;
            border: none;
            box-shadow: 0 20px 50px rgba(139, 94, 60, .35);
            transition: .25s;
        }

        .btn-main:hover {
            transform: translateY(-3px);
            box-shadow: 0 30px 80px rgba(139, 94, 60, .45);
        }

        .hero {
            background:
                radial-gradient(circle at right, rgba(214, 165, 106, .25), transparent 55%),
                linear-gradient(180deg, #fff, var(--bg-main));
        }

        .hero p {
            color: var(--text-muted);
            font-size: 1.15rem;
            max-width: 560px;
        }

        .story-box {
            background: #fff;
            padding: 56px;
            border-radius: var(--radius);
            box-shadow: 0 25px 70px rgba(0, 0, 0, .08);
        }

        .flow-step {
            background: #fff;
            padding: 44px;
            border-radius: var(--radius);
            height: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .08);
        }

        .flow-step span {
            font-size: 2.6rem;
            font-weight: 900;
            color: var(--primary);
        }

        .section-dark {
            background: linear-gradient(135deg, var(--bg-dark), #1e293b);
            color: #fff;
        }

        .section-dark p {
            color: #cbd5e1;
        }

        .price-card {
            background: #fff;
            border-radius: 40px;
            padding: 64px;
            box-shadow: 0 40px 120px rgba(0, 0, 0, .15);
            height: 100%;
        }

        .price {
            font-size: 3rem;
            font-weight: 900;
            color: var(--primary);
        }

        .cta {
            background: linear-gradient(135deg, var(--primary), #6f4427);
            color: #fff;
            border-radius: 70px;
            padding: 140px 40px;
            text-align: center;
        }

        footer {
            padding: 50px 0;
            text-align: center;
            color: var(--text-muted);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            {{-- Brand --}}
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                <img src="{{ asset('img/logo.png') }}" width="65" class="rounded-circle" alt="CaMenu-QR">
            </a>

            {{-- Right actions --}}
            <div class="d-flex align-items-center gap-3">

                {{-- Language switch --}}
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        {{ app()->getLocale() === 'km' ? '🇰🇭 KM' : '🇺🇸 EN' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">
                                🇺🇸 English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('lang.switch', 'km') }}">
                                🇰🇭 ខ្មែរ
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- CTA --}}
                <button class="btn-main" data-bs-toggle="modal" data-bs-target="#registerModal">
                    {{ __('ui.get_started') }}
                </button>

            </div>
        </div>
    </nav>


    {{-- HERO --}}
    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <small class="text-muted">{{ __('ui.hero_tagline') }}</small>

                    <h1 class="mt-3">
                        {{ __('ui.hero_title_1') }}<br>
                        <span class="gradient-text">{{ __('ui.hero_title_2') }}</span>
                    </h1>

                    <p class="mt-4">
                        {{ __('ui.hero_description') }}
                    </p>

                    <button class="btn-main mt-3" data-bs-toggle="modal" data-bs-target="#registerModal">
                        {{ __('ui.start_digital_menu') }}
                    </button>
                </div>

                <div class="col-lg-5">
                    <div class="story-box">
                        ❌ {{ __('ui.problem_printing') }}<br><br>
                        ❌ {{ __('ui.problem_outdated') }}<br><br>
                        ✅ {{ __('ui.solution_one_qr') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FLOW --}}
    <section>
        <div class="container text-center">
            <h2>{{ __('ui.how_it_works_title') }}</h2>
            <p class="text-muted mb-5">{{ __('ui.how_it_works_subtitle') }}</p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="flow-step">
                        <span>1</span>
                        <h5 class="mt-3">{{ __('ui.flow_create_menu') }}</h5>
                        <p>{{ __('ui.flow_create_menu_desc') }}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="flow-step">
                        <span>2</span>
                        <h5 class="mt-3">{{ __('ui.flow_place_qr') }}</h5>
                        <p>{{ __('ui.flow_place_qr_desc') }}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="flow-step">
                        <span>3</span>
                        <h5 class="mt-3">{{ __('ui.flow_sell_faster') }}</h5>
                        <p>{{ __('ui.flow_sell_faster_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PRICING --}}
    <section id="pricing">
        <div class="container text-center">
            <h2>{{ __('ui.pricing_title') }}</h2>
            <p class="text-muted mb-5">{{ __('ui.pricing_subtitle') }}</p>

            <div class="row g-4 justify-content-center">
                @foreach ($plans as $plan)
                    <div class="col-md-4">
                        <div class="price-card">
                            <h5>{{ $plan->name }}</h5>
                            <div class="price">${{ number_format($plan->price, 2) }}</div>
                            <p class="text-muted">{{ $plan->duration_days }} {{ __('ui.days') }}</p>

                            <ul class="text-start mt-4">
                                @foreach ($plan->features as $f)
                                    <li>✔ {{ $f }}</li>
                                @endforeach
                            </ul>

                            <button class="btn-main w-100 mt-4" data-bs-toggle="modal" data-bs-target="#registerModal"
                                data-plan="{{ $plan->id }}">
                                {{ __('ui.start_with') }} {{ $plan->name }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section>
        <div class="container">
            <div class="cta">
                <h2>{{ __('ui.cta_title') }}</h2>
                <p>{{ __('ui.cta_subtitle') }}</p>

                <button class="btn btn-light btn-lg mt-4" data-bs-toggle="modal" data-bs-target="#registerModal">
                    {{ __('ui.create_menu_now') }}
                </button>
            </div>
        </div>
    </section>

    <footer>
        © {{ date('Y') }} CaMenu-QR
    </footer>

    {{-- REGISTER MODAL --}}
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 rounded-4">
                <form method="POST" action="{{ route('register.order') }}">
                    @csrf

                    <h5>{{ __('ui.create_shop_subscribe') }}</h5>

                    <select class="form-select mt-3" id="planSelect" name="plan_id" required>
                        <option value="">{{ __('ui.select_plan') }}</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" data-price="{{ number_format($plan->price, 2) }}">
                                {{ $plan->name }} — ${{ number_format($plan->price, 2) }}
                            </option>
                        @endforeach
                    </select>

                    <input id="planPrice" class="form-control mt-3" readonly>

                    <input class="form-control mt-3" name="restaurant_name" placeholder="{{ __('ui.shop_name') }}"
                        required>
                    <input class="form-control mt-3" name="phone" placeholder="{{ __('ui.phone') }}" required>
                    <input class="form-control mt-3" name="address" placeholder="{{ __('ui.address') }}" required>
                    <input class="form-control mt-3" type="email" name="email"
                        placeholder="{{ __('ui.owner_email') }}" required>

                    <button class="btn-main w-100 mt-4">
                        {{ __('ui.continue_payment') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const planSelect = document.getElementById('planSelect');
        const planPrice = document.getElementById('planPrice');

        planSelect.onchange = () => {
            const price = planSelect.selectedOptions[0]?.dataset.price;
            planPrice.value = price ? '$' + price : '';
        };
    </script>

</body>

</html>
