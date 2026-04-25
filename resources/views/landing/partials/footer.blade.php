<footer class="landing-footer">
    <div class="landing-container">
        <div class="landing-footer-grid">
            {{-- Brand & About --}}
            <div class="landing-footer-col">
                <a href="{{ url('/') }}" class="landing-footer-logo-link">
                    <img src="{{ asset('LOGO-MALKIA-KONNECT-removebg-preview.png') }}" alt="Malkia Konnect" class="landing-footer-logo">
                </a>
                <p class="landing-footer-desc">
                    Supporting women through the journey of motherhood with care, knowledge, and empowerment.
                </p>
                <div class="landing-footer-socials">
                    <a href="{{ $siteContact->instagram_url ?? '#' }}" class="landing-footer-social-link" target="_blank" rel="noreferrer">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="footer-social-icon"><path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Z" stroke="currentColor" stroke-width="1.8"/><path d="M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.8"/><path d="M17.5 6.5h.01" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/></svg>
                    </a>
                    <a href="#" class="landing-footer-social-link">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="footer-social-icon"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3V2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="landing-footer-col">
                <h4 class="landing-footer-title">Our Links</h4>
                <ul class="landing-footer-list">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="#">Our Services</a></li>
                    <li><a href="#">Malkia Shop</a></li>
                    <li><a href="{{ route('join') }}">Join Konnect</a></li>
                </ul>
            </div>

            {{-- More Links & Blogs --}}
            <div class="landing-footer-col">
                <h4 class="landing-footer-title">Resources</h4>
                <ul class="landing-footer-list">
                    <li><a href="#">Latest Blogs</a></li>
                    <li><a href="#">Maternity Tips</a></li>
                    <li><a href="#">Community Forum</a></li>
                    <li><a href="#">Expert Advice</a></li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div class="landing-footer-col">
                <h4 class="landing-footer-title">Newsletter</h4>
                <p class="landing-footer-desc">Subscribe to get latest updates and resources.</p>
                <form action="#" class="landing-footer-newsletter">
                    <input type="email" placeholder="Email Address" required>
                    <button type="submit">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="newsletter-icon"><path d="m22 2-11 11M22 2l-7 20-4-9-9-4 20-7Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="landing-footer-bottom">
            <div class="landing-footer-copy">
                © {{ date('Y') }} Malkia Konnect LTD. All Rights Reserved.
            </div>
            <div class="landing-footer-legal">
                <a href="{{ route('privacy') }}">Privacy Policy</a>
                <a href="{{ route('terms') }}">Terms of Service</a>
                <a href="{{ route('legal') }}">Legal</a>
            </div>
        </div>
    </div>
</footer>
