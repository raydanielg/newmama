<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Our Products – {{ config('app.name', 'Mamacare AI') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --malkia-red: #f82249;
            --malkia-dark: #0e1b4d;
        }
        body {
            background-color: #f8f9fa;
        }
        
        /* New Simple Header */
        .simple-header {
            background: #fff;
            padding: 10px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .header-logo {
            height: 35px;
            width: auto;
        }
        .header-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--malkia-dark);
            margin: 0;
        }

        /* Filter Toggle Button */
        .filter-toggle-btn {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 50px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: var(--malkia-dark);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .filter-toggle-btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        /* Filter Sidebar (Drawer) */
        .filter-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 300px;
            background: white;
            z-index: 2000;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 5px 0 25px rgba(0,0,0,0.1);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
        }
        .filter-sidebar.open {
            transform: translateX(0);
        }
        .filter-sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .filter-sidebar-header h5 {
            font-weight: 800;
            margin: 0;
            color: var(--malkia-dark);
        }
        .filter-sidebar-close {
            background: none;
            border: none;
            color: #999;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .filter-link {
            display: block;
            padding: 12px 15px;
            border-radius: 12px;
            color: #666;
            text-decoration: none !important;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .filter-link:hover {
            background: #f8f9fa;
            color: var(--malkia-red);
        }
        .filter-link.active {
            background: var(--malkia-red);
            color: white !important;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .filter-sidebar {
                width: 280px;
            }
        }

        .product-card {
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            cursor: pointer;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            height: 100%;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-image-wrapper {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
        }
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-info {
            padding: 10px;
            text-align: center;
        }
        .product-name {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 4px;
            color: var(--malkia-dark);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.4rem;
        }
        .product-price {
            font-size: 1rem;
            font-weight: 800;
            color: var(--malkia-red);
        }

        .offer-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ffc107;
            color: #000;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.6rem;
            font-weight: 800;
            z-index: 10;
            text-transform: uppercase;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Floating Cart Counter */
        .floating-cart-badge {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--malkia-red);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(248, 34, 73, 0.4);
            z-index: 1060;
            cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .floating-cart-badge.active {
            display: flex;
            animation: bounceIn 0.5s;
        }
        .floating-cart-badge:hover {
            transform: scale(1.1);
        }
        .cart-count-bubble {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--malkia-dark);
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 10px;
            border: 2px solid white;
        }

        /* Overlays */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
        }
        .overlay.active {
            display: block;
        }

        #cart-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            height: 100vh;
            width: 350px;
            background: white;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            z-index: 1050;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        #cart-sidebar.open {
            transform: translateX(0);
        }
        @media (max-width: 400px) {
            #cart-sidebar {
                width: 100%;
            }
        }

        .btn-malkia {
            background-color: var(--malkia-red);
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 700;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-malkia:hover {
            background-color: #d11a3d;
            transform: scale(1.05);
            color: white;
        }
        .btn-malkia:disabled {
            background-color: #ccc;
        }

        .step-container {
            display: none;
        }
        .step-container.active {
            display: block;
        }
        .copy-link-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }
        .copy-link-btn:hover {
            background: var(--malkia-red);
            color: white;
        }

        /* Order Summary Table */
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .summary-table tr:last-child td {
            border-bottom: none;
        }
    </style>
    <script src="https://checkout.snippe.sh/v1/checkout.js"></script>
</head>
<body class="landing-body">
    <!-- Simple Header (Icon + Name Only) -->
    <header class="simple-header">
        <div class="container">
            <div class="header-content">
                <img src="/LOGO-MALKIA-KONNECT-removebg-preview.png" alt="Mamacare AI" class="header-logo">
                <h1 class="header-title">Mamacare AI</h1>
            </div>
        </div>
    </header>

    <main class="py-3">
        <div class="container">
            {{-- Step 1: Product Selection --}}
            <div id="step-1" class="step-container active animate__animated animate__fadeIn">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <button id="filter-toggle-btn" class="filter-toggle-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                            </svg>
                            <span>Filter Categories</span>
                        </button>
                        <h4 class="fw-bold m-0" id="current-category-name">All Products</h4>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="product-grid" id="product-list">
                    @forelse($products as $product)
                        <div class="product-item" data-category="{{ $product->category }}">
                            <div class="card product-card shadow-sm add-to-cart" 
                                 data-id="{{ $product->id }}" 
                                 data-name="{{ $product->name }}" 
                                 data-price="{{ $product->selling_price }}">
                                <div class="product-image-wrapper">
                                    @if($product->category == 'Packages')
                                        <span class="offer-badge">Best Offer</span>
                                    @endif
                                    <button class="copy-link-btn" title="Copy Product Link" data-url="{{ url('/products?id=' . $product->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                            <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                                        </svg>
                                    </button>
                                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300?text=' . urlencode($product->name) }}" class="product-image" alt="{{ $product->name }}">
                                </div>
                                <div class="product-info">
                                    <h6 class="product-name">{{ $product->name }}</h6>
                                    <div class="product-price">{{ number_format($product->selling_price, 0) }} TZS</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">No products available at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <div class="text-center mt-5">
                    <button id="proceed-to-step-2" class="btn btn-lg btn-malkia px-5 shadow" disabled>
                        Review Selection & Checkout
                    </button>
                </div>
            </div>

            <!-- Filter Drawer Panel -->
            <div class="filter-sidebar">
                <div class="filter-sidebar-header">
                    <h5>Categories</h5>
                    <button class="filter-sidebar-close" id="close-filter">&times;</button>
                </div>
                <div class="flex-grow-1">
                    <a href="javascript:void(0)" class="filter-link filter-btn active" data-category="all">
                        All Products
                    </a>
                    <a href="javascript:void(0)" class="filter-link filter-btn" data-category="Packages">
                        Special Packages <span class="badge bg-warning text-dark ms-2">Offer</span>
                    </a>
                    @foreach($categories as $category)
                        @if($category != 'Packages')
                            <a href="javascript:void(0)" class="filter-link filter-btn" data-category="{{ $category }}">
                                {{ $category }}
                            </a>
                        @endif
                    @endforeach
                </div>
                
                <div class="mt-4 border-top pt-4">
                    <div class="bg-light p-3 rounded-4">
                        <small class="text-muted d-block mb-2">Special Offer</small>
                        <p class="small fw-bold mb-0">Get free delivery for all Mother Care Packages!</p>
                    </div>
                </div>
            </div>

            {{-- Step 2: Customer Details & Order Summary --}}
            <div id="step-2" class="step-container animate__animated animate__fadeIn">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-5 bg-light p-4 border-end">
                                    <h5 class="fw-bold mb-4">Order Summary</h5>
                                    <div id="order-summary-items" class="mb-4">
                                        <!-- Summary items injected here -->
                                    </div>
                                    <div class="border-top pt-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">Total Amount</span>
                                            <span class="fw-bold text-danger h5" id="summary-total">0 TZS</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 p-5">
                                    <h3 class="fw-bold mb-4">Complete Order</h3>
                                    <form id="request-form">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Full Name</label>
                                            <input type="text" name="customer_name" class="form-control form-control-lg" placeholder="Enter your name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Phone Number (M-Pesa/Tigo-Pesa)</label>
                                            <input type="tel" name="customer_phone" class="form-control form-control-lg" placeholder="0XXXXXXXXX" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Payment Method</label>
                                            <select name="payment_method" id="payment_method" class="form-select form-select-lg" required>
                                                <option value="online" selected>Pay Online (M-Pesa, Tigo-Pesa, Card)</option>
                                                <option value="whatsapp">Book via WhatsApp</option>
                                                <option value="cash">Cash on Delivery</option>
                                            </select>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <button type="submit" id="pay-btn" class="btn btn-malkia btn-lg py-3 fw-bold shadow">
                                                Pay Now & Confirm
                                            </button>
                                            <button type="button" id="back-to-step-1" class="btn btn-link text-muted mt-2 text-decoration-none">← Back to Selection</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Floating Cart Badge -->
    <div id="floating-cart" class="floating-cart-badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        <span class="cart-count-bubble" id="floating-cart-count">0</span>
    </div>

    <!-- Cart Sidebar -->
    <div class="overlay" id="cart-overlay"></div>
    <div id="cart-sidebar">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Your Selection</h4>
            <button class="btn-close" id="close-cart"></button>
        </div>
        <div id="cart-items" class="flex-grow-1 overflow-auto">
            <!-- Items injected here -->
        </div>
        <div class="mt-4 border-top pt-3">
            <div class="d-flex justify-content-between mb-3">
                <span class="h5">Total:</span>
                <span class="h5 fw-bold text-danger" id="cart-total">0 TZS</span>
            </div>
            <div class="d-grid gap-2">
                <button id="book-whatsapp" class="btn btn-success w-100 py-3 fw-bold shadow d-flex align-items-center justify-content-center gap-2">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.353-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.149-1.613a11.881 11.881 0 005.899 1.558h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Book via WhatsApp
                </button>
                <button id="buy-now" class="btn btn-malkia w-100 py-3 fw-bold shadow">
                    Buy Now & Pay Online
                </button>
            </div>
        </div>
    </div>

    {{-- @include('landing.partials.footer') --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = [];
            const productList = document.getElementById('product-list');
            const cartSidebar = document.getElementById('cart-sidebar');
            const cartOverlay = document.getElementById('cart-overlay');
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');
            const proceedBtn = document.getElementById('proceed-to-step-2');
            const selectedProductId = @json($selectedProductId ?? null);
            const snippePublicKey = @json($settings['snippe_public_key'] ?? 'pk_test_mamacare_ai_dummy');
            const whatsappNumber = @json($settings['whatsapp_number'] ?? '255742710054');

            // Auto-select product if ID is in URL
            if (selectedProductId) {
                const productBtn = document.querySelector(`.add-to-cart[data-id="${selectedProductId}"]`);
                if (productBtn) {
                    setTimeout(() => {
                        productBtn.click();
                        // Scroll to the product
                        productBtn.closest('.product-item').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 500);
                }
            }

            // Copy Link Functionality
            document.querySelectorAll('.copy-link-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const url = this.dataset.url;
                    navigator.clipboard.writeText(url).then(() => {
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg>';
                        this.style.backgroundColor = '#28a745';
                        this.style.color = 'white';
                        
                        setTimeout(() => {
                            this.innerHTML = originalHtml;
                            this.style.backgroundColor = '';
                            this.style.color = '';
                        }, 2000);
                        
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Link copied to clipboard'
                        });
                    });
                });
            });

            // Filter Toggle
            const filterToggleBtn = document.getElementById('filter-toggle-btn');
            const filterSidebar = document.querySelector('.filter-sidebar');
            const closeFilterBtn = document.getElementById('close-filter');
            
            if (filterToggleBtn) {
                filterToggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    filterSidebar.classList.add('open');
                    cartOverlay.classList.add('active');
                });
            }

            if (closeFilterBtn) {
                closeFilterBtn.addEventListener('click', () => {
                    filterSidebar.classList.remove('open');
                    cartOverlay.classList.remove('active');
                });
            }

            // Filtering
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Update header title
                    document.getElementById('current-category-name').innerText = 
                        category === 'all' ? 'All Products' : category;

                    document.querySelectorAll('.product-item').forEach(item => {
                        if (category === 'all' || item.dataset.category === category) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Close sidebar after selection
                    filterSidebar.classList.remove('open');
                    cartOverlay.classList.remove('active');
                });
            });

            // Mobile Cart Toggle
            const floatingCart = document.getElementById('floating-cart');
            if (floatingCart) {
                floatingCart.addEventListener('click', () => {
                    cartSidebar.classList.add('open');
                    cartOverlay.classList.add('active');
                });
            }

            // Cart Logic
            function updateCartUI() {
                cartItemsContainer.innerHTML = '';
                const summaryContainer = document.getElementById('order-summary-items');
                if (summaryContainer) summaryContainer.innerHTML = '';
                
                let total = 0;
                let count = 0;
                
                cart.forEach(item => {
                    total += item.price * item.quantity;
                    count += item.quantity;
                    
                    // Sidebar UI
                    const itemEl = document.createElement('div');
                    itemEl.className = 'd-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded';
                    itemEl.innerHTML = `
                        <div class="flex-grow-1 pe-2">
                            <h6 class="mb-0 fw-bold small text-truncate" style="max-width: 180px;">${item.name}</h6>
                            <small class="text-muted">${item.price.toLocaleString()} TZS</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-secondary px-2 py-0 decrease-qty" data-id="${item.id}">-</button>
                            <span class="mx-2 fw-bold">${item.quantity}</span>
                            <button class="btn btn-sm btn-outline-secondary px-2 py-0 increase-qty" data-id="${item.id}">+</button>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemEl);

                    // Summary UI (Step 2)
                    if (summaryContainer) {
                        const summaryEl = document.createElement('div');
                        summaryEl.className = 'd-flex justify-content-between mb-2 small';
                        summaryEl.innerHTML = `
                            <span>${item.name} x ${item.quantity}</span>
                            <span>${(item.price * item.quantity).toLocaleString()} TZS</span>
                        `;
                        summaryContainer.appendChild(summaryEl);
                    }
                });

                const formattedTotal = total.toLocaleString() + ' TZS';
                cartTotalElement.innerText = formattedTotal;
                if (document.getElementById('summary-total')) {
                    document.getElementById('summary-total').innerText = formattedTotal;
                }
                document.getElementById('floating-cart-count').innerText = count;
                
                if (count > 0) {
                    floatingCart.classList.add('active');
                    proceedBtn.disabled = false;
                } else {
                    floatingCart.classList.remove('active');
                    proceedBtn.disabled = true;
                }
            }

            document.addEventListener('click', function(e) {
                const addBtn = e.target.closest('.add-to-cart');
                if (addBtn && !e.target.closest('.copy-link-btn')) {
                    const id = addBtn.dataset.id;
                    const name = addBtn.dataset.name;
                    const price = parseFloat(addBtn.dataset.price);
                    
                    const existing = cart.find(i => i.id === id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        cart.push({ id, name, price, quantity: 1 });
                    }
                    updateCartUI();
                    
                    // Small pop effect on floating cart
                    floatingCart.style.transform = 'scale(1.2)';
                    setTimeout(() => floatingCart.style.transform = '', 200);
                }

                if (e.target.classList.contains('increase-qty')) {
                    const id = e.target.dataset.id;
                    cart.find(i => i.id === id).quantity++;
                    updateCartUI();
                }

                if (e.target.classList.contains('decrease-qty')) {
                    const id = e.target.dataset.id;
                    const item = cart.find(i => i.id === id);
                    if (item.quantity > 1) {
                        item.quantity--;
                    } else {
                        cart = cart.filter(i => i.id !== id);
                    }
                    updateCartUI();
                }
            });

            document.getElementById('close-cart').addEventListener('click', () => {
                cartSidebar.classList.remove('open');
                cartOverlay.classList.remove('active');
            });

            cartOverlay.addEventListener('click', () => {
                cartSidebar.classList.remove('open');
                filterSidebar.classList.remove('open');
                cartOverlay.classList.remove('active');
            });

            // Payment Method Logic
            const paymentMethodSelect = document.getElementById('payment_method');
            const payBtn = document.getElementById('pay-btn');
            
            paymentMethodSelect.addEventListener('change', function() {
                if (this.value === 'online') {
                    payBtn.innerText = 'Pay Now & Confirm';
                    payBtn.className = 'btn btn-malkia btn-lg py-3 fw-bold shadow';
                } else if (this.value === 'whatsapp') {
                    payBtn.innerText = 'Confirm Order';
                    payBtn.className = 'btn btn-success btn-lg py-3 fw-bold shadow';
                } else {
                    payBtn.innerText = 'Place Order';
                    payBtn.className = 'btn btn-malkia btn-lg py-3 fw-bold shadow';
                }
            });

            // Form Submission
            const requestForm = document.getElementById('request-form');
            requestForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = {
                    customer_name: formData.get('customer_name'),
                    customer_phone: formData.get('customer_phone'),
                    payment_method: formData.get('payment_method'),
                    items: cart.map(item => ({
                        id: item.id,
                        quantity: item.quantity
                    }))
                };

                payBtn.disabled = true;
                payBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

                fetch("{{ route('products.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        if (data.payment_method === 'whatsapp' || result.redirect_url) {
                            const whatsappMsg = `Hello Mamacare AI, I've just placed an order (Order Token: ${result.token}). Please send me the payment link. View my order here: ${result.redirect_url}`;
                            const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMsg)}`;
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Placed!',
                                text: result.message,
                                confirmButtonText: 'Open WhatsApp',
                                confirmButtonColor: '#25D366',
                                showCancelButton: true,
                                cancelButtonText: 'View Order Status'
                            }).then((choice) => {
                                if (choice.isConfirmed) {
                                    window.open(whatsappUrl, '_blank');
                                    window.location.href = result.redirect_url;
                                } else {
                                    window.location.href = result.redirect_url;
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message,
                                confirmButtonColor: '#f82249'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    } else {
                        throw new Error(result.message || 'Something went wrong');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message
                    });
                    payBtn.disabled = false;
                    payBtn.innerText = data.payment_method === 'online' ? 'Pay Now & Confirm' : 'Confirm Order';
                });
            });

            // Step Navigation
            document.getElementById('proceed-to-step-2').addEventListener('click', () => {
                document.getElementById('step-1').classList.remove('active');
                document.getElementById('step-2').classList.add('active');
                window.scrollTo(0, 0);
                updateCartUI();
            });

            document.getElementById('back-to-step-1').addEventListener('click', () => {
                document.getElementById('step-2').classList.remove('active');
                document.getElementById('step-1').classList.add('active');
                window.scrollTo(0, 0);
            });

            document.getElementById('buy-now').addEventListener('click', () => {
                paymentMethodSelect.value = 'online';
                paymentMethodSelect.dispatchEvent(new Event('change'));
                document.getElementById('proceed-to-step-2').click();
                cartSidebar.classList.remove('open');
                cartOverlay.classList.remove('active');
            });

            document.getElementById('book-whatsapp').addEventListener('click', () => {
                paymentMethodSelect.value = 'whatsapp';
                paymentMethodSelect.dispatchEvent(new Event('change'));
                document.getElementById('proceed-to-step-2').click();
                cartSidebar.classList.remove('open');
                cartOverlay.classList.remove('active');
            });
        });
    </script>
</body>
</html>                    payBtn.innerText = 'Confirm & Send to WhatsApp';
                    payBtn.className = 'btn btn-success btn-lg py-3 fw-bold shadow';
                } else {
                    payBtn.innerText = 'Confirm Order';
                    payBtn.className = 'btn btn-malkia btn-lg py-3 fw-bold shadow';
                }
            });

            // Navigation
            proceedBtn.addEventListener('click', () => {
                document.getElementById('step-1').classList.remove('active');
                document.getElementById('step-2').classList.add('active');
                cartSidebar.classList.remove('open');
                cartOverlay.classList.remove('active');
                updateCartUI(); // Refresh summary
                window.scrollTo(0, 0);
            });

            document.getElementById('buy-now').addEventListener('click', () => {
                proceedBtn.click();
            });

            function getWhatsAppMessage() {
                let message = "Habari Mamacare AI, naomba kuagiza bidhaa zifuatazo:\n\n";
                let total = 0;
                cart.forEach((item, index) => {
                    const subtotal = item.price * item.quantity;
                    total += subtotal;
                    message += `${index + 1}. ${item.name} (Idadi: ${item.quantity}) - ${subtotal.toLocaleString()} TZS\n`;
                });
                message += `\n*Jumla Kuu: ${total.toLocaleString()} TZS*`;
                return message;
            }

            document.getElementById('book-whatsapp').addEventListener('click', () => {
                if (cart.length === 0) return;
                const message = getWhatsAppMessage();
                const encodedMessage = encodeURIComponent(message);
                window.open(`https://wa.me/${whatsappNumber}?text=${encodedMessage}`, '_blank');
            });

            document.getElementById('back-to-step-1').addEventListener('click', () => {
                document.getElementById('step-2').classList.remove('active');
                document.getElementById('step-1').classList.add('active');
                window.scrollTo(0, 0);
            });

            // Form Submission with Snippe Integration
            document.getElementById('request-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const paymentMethod = formData.get('payment_method');
                
                const customerData = {
                    customer_name: formData.get('customer_name'),
                    customer_phone: formData.get('customer_phone'),
                    payment_method: paymentMethod,
                    items: cart
                };

                if (paymentMethod === 'whatsapp') {
                    const message = getWhatsAppMessage() + `\n\n*Mteja:* ${customerData.customer_name}\n*Simu:* ${customerData.customer_phone}`;
                    window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`, '_blank');
                    // Still save to DB
                }

                // Handle Online Payment with Snippe
                if (paymentMethod === 'online') {
                    let totalAmount = 0;
                    cart.forEach(i => totalAmount += (i.price * i.quantity));

                    const handler = Snippe.configure({
                        key: snippePublicKey,
                        onClose: function() {
                            Swal.fire('Payment Cancelled', 'You closed the payment window.', 'info');
                        },
                        onSuccess: function(response) {
                            // response contains reference
                            customerData.payment_reference = response.reference;
                            submitToDatabase(customerData);
                        }
                    });

                    handler.open({
                        amount: totalAmount,
                        currency: 'TZS',
                        email: 'customer@malkia.com', // Optional
                        phone: customerData.customer_phone,
                        metadata: {
                            customer_name: customerData.customer_name,
                            items: JSON.stringify(cart)
                        }
                    });
                } else {
                    submitToDatabase(customerData);
                }
            });

            async function submitToDatabase(data) {
                try {
                    const response = await fetch('{{ route("products.submit") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Confirmed!',
                            text: result.message,
                            confirmButtonColor: '#f82249'
                        }).then(() => {
                            window.location.href = '/';
                        });
                    } else {
                        Swal.fire('Error', 'Something went wrong saving your order.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Network error. Please try again.', 'error');
                }
            }
        });
    </script>
</body>
</html>
