<section class="landing-hero">
    <div class="landing-container landing-hero-grid">
        <div class="landing-hero-copy animate__animated animate__fadeInLeft">
            <div class="landing-kicker">Mamacare AI — Maternal Health Management</div>
            <h1 class="landing-hero-title">Empowering Mothers. Enabling Care. Transforming Lives.</h1>
            <p class="landing-hero-subtitle">Trusted maternal care guidance, health monitoring, and emergency support—powered by AI and delivered directly to your hands via WhatsApp.</p>

            <div class="landing-hero-actions">
                <a class="landing-btn" href="{{ route('mother.login') }}">
                    <i class="fas fa-heart-pulse"></i> Mother Portal
                </a>
                <a class="landing-btn landing-btn-ghost" href="https://wa.me/{{ $siteContact && $siteContact->phone ? preg_replace('/[^0-9]/', '', $siteContact->phone) : '' }}">
                    <i class="fab fa-whatsapp"></i> Chat with AI
                </a>
            </div>
        </div>
    </div>
</section>
