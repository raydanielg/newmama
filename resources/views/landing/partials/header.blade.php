<header class="landing-header">
    <div class="landing-topbar">
        <div class="landing-container landing-topbar-inner">
            <div class="landing-topbar-left">
                <a class="landing-topbar-link" href="tel:{{ $siteContact->phone ?? '+255700000000' }}">
                    <span class="landing-topbar-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 4h3l2 5-2 1c1.2 2.6 3.4 4.8 6 6l1-2 5 2v3c0 1.1-.9 2-2 2-9.4 0-17-7.6-17-17 0-1.1.9-2 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>{{ $siteContact->phone ?? '+255 700 000 000' }}</span>
                </a>
                <a class="landing-topbar-link" href="mailto:{{ $siteContact->email ?? 'info@mamacare.ai' }}">
                    <span class="landing-topbar-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.8"/>
                            <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>{{ $siteContact->email ?? 'info@mamacare.ai' }}</span>
                </a>
            </div>

            <div class="landing-topbar-right">
                <a class="landing-social" href="{{ $siteContact->instagram_url ?? 'https://instagram.com/mamacareai' }}" target="_blank" rel="noreferrer">
                    <span class="landing-topbar-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Z" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M17.5 6.5h.01" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="landing-social-text">Instagram</span>
                </a>
            </div>
        </div>
    </div>

    <div class="landing-container landing-header-inner">
        <a class="landing-brand" href="{{ url('/') }}">
            <img class="landing-logo" src="{{ asset('LOGO-MALKIA-KONNECT-removebg-preview.png') }}" alt="Mamacare AI" />
        </a>

        <button class="landing-nav-toggle" type="button" aria-label="Toggle menu" data-landing-nav-toggle>
            <svg viewBox="0 0 24 24" fill="none" width="22" height="22" stroke="currentColor" stroke-width="2">
                <path d="M4 6h16" />
                <path d="M4 12h16" />
                <path d="M4 18h16" />
            </svg>
        </button>

        <div class="landing-nav-wrap" data-landing-nav-wrap>
            <nav class="landing-nav">
                <a class="landing-nav-link" href="{{ url('/') }}">Home</a>
                <a class="landing-nav-link" href="{{ route('articles') }}">Articles</a>
                <a class="landing-nav-link" href="{{ route('about') }}">About</a>
            </nav>

            <div class="landing-nav-actions">
                <a class="landing-nav-cta" href="{{ route('join') }}">Join Now</a>
            </div>
        </div>
    </div>
</header>

<script>
    (function () {
        const btn = document.querySelector('[data-landing-nav-toggle]');
        const wrap = document.querySelector('[data-landing-nav-wrap]');
        if (!btn || !wrap) return;
        btn.addEventListener('click', function () {
            wrap.classList.toggle('open');
        });
    })();
</script>
