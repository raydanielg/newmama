<section class="landing-hero">
    <div class="landing-container landing-hero-grid">
        <div class="landing-hero-copy animate__animated animate__fadeInLeft">
            <div class="landing-kicker">Mamacare AI</div>
            <h1 class="landing-hero-title">Your wellness, your motherhood, your support — all in one place</h1>
            <p class="landing-hero-subtitle">Mamacare AI brings trusted maternal care guidance, community, and products together — with AI-powered support delivered directly on WhatsApp.</p>

            <div class="landing-hero-actions">
                <a class="landing-btn" href="{{ route('mother.login') }}">Mama Login</a>
                <a class="landing-btn landing-btn-ghost" href="https://wa.me/{{ $siteContact && $siteContact->phone ? preg_replace('/[^0-9]/', '', $siteContact->phone) : '' }}">Chat with AI</a>
            </div>
        </div>
    </div>
</section>
