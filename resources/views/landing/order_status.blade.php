<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Order Status – {{ config('app.name', 'Mamacare AI') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://checkout.snippe.sh/v1/checkout.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --malkia-red: #f82249;
            --malkia-dark: #0e1b4d;
        }
        body { background-color: #f4f7f6; }
        .receipt-card {
            max-width: 500px;
            margin: 50px auto;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .receipt-header {
            background: var(--malkia-dark);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        .status-unpaid { background: #fff3cd; color: #856404; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-expired { background: #f8d7da; color: #721c24; }
        
        .item-row {
            border-bottom: 1px dashed #eee;
            padding: 10px 0;
        }
        .item-row:last-child { border-bottom: none; }
        
        .btn-pay {
            background: var(--malkia-red);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px;
            font-weight: 800;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(248, 34, 73, 0.3);
            color: white;
        }
        .whatsapp-btn {
            background: #25D366;
            color: white;
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card receipt-card">
            <div class="receipt-header">
                <img src="{{ asset('meetup_3669956.png') }}" alt="{{ config('app.name', 'Mamacare AI') }}" class="order-logo">
                <h4 class="mb-1">Order #{{ $order->order_number }}</h4>
                <p class="mb-0 opacity-75 small">Generated on {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
            
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="text-muted fw-bold">Payment Status</span>
                    <span class="status-badge status-{{ $order->payment_status }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Customer Details</h6>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>Name:</span>
                        <span class="fw-bold">{{ $order->customer->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Phone:</span>
                        <span class="fw-bold">{{ $order->customer->phone }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Order Items</h6>
                    @foreach($order->items as $item)
                    <div class="item-row d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold small">{{ $item->name }}</div>
                            <small class="text-muted">{{ (int)$item->quantity }} x {{ number_format($item->unit_price) }} TZS</small>
                        </div>
                        <div class="fw-bold small">{{ number_format($item->subtotal) }} TZS</div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-light p-3 rounded-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 mb-0 fw-bold">Total Amount</span>
                        <span class="h5 mb-0 fw-bold text-danger">{{ number_format($order->grand_total) }} TZS</span>
                    </div>
                </div>

                @if($order->payment_status === 'unpaid')
                    <button id="pay-now" class="btn btn-pay mb-3">
                        Pay Online Now
                    </button>
                    <p class="text-center text-muted small px-3">
                        Please complete payment within 24 hours to avoid order cancellation.
                    </p>
                    
                    <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '255742710054' }}?text={{ urlencode('Hello Mamacare AI, I want to confirm payment for Order #' . $order->order_number) }}" class="whatsapp-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.353-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.149-1.613a11.881 11.881 0 005.899 1.558h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Confirm on WhatsApp
                    </a>
                @else
                    <div class="alert alert-success rounded-4 text-center">
                        <h5 class="fw-bold">Payment Successful!</h5>
                        <p class="small mb-0">Your order is being processed. You will receive updates via SMS/WhatsApp.</p>
                    </div>
                @endif
            </div>
            
            <div class="card-footer bg-white border-0 text-center pb-4">
                <a href="{{ route('products.index') }}" class="text-muted text-decoration-none small">← Back to Shop</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('pay-now')?.addEventListener('click', function() {
            const snippePublicKey = @json($settings['snippe_public_key'] ?? 'pk_test_malkia_konnect_dummy');
            
            const checkout = new SnippeCheckout({
                publicKey: snippePublicKey,
                amount: {{ $order->grand_total }},
                currency: 'TZS',
                email: 'customer@example.com',
                phone: '{{ $order->customer->phone }}',
                reference: '{{ $order->order_number }}',
                onSuccess: function(response) {
                    fetch("{{ route('orders.pay', $order->payment_link_token) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Confirmed!',
                            text: 'Thank you for your order.',
                            confirmButtonColor: '#f82249'
                        }).then(() => location.reload());
                    });
                }
            });
            checkout.open();
        });
    </script>
</body>
</html>
