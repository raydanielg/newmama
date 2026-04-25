<section class="landing-section landing-commitment">
    <div class="landing-container landing-commitment-inner animate__animated animate__fadeInUp">
        <div class="landing-chip">OUR COMMITMENT</div>
        <h2 class="landing-h2">With Malkia, you are never alone</h2>
        <p class="landing-p">We are committed to walking this journey with every mother — from the first trimester to postpartum recovery and beyond.</p>
        <p class="landing-p">You are supported, understood, and empowered every step of the way.</p>

        <div class="landing-testimonials" data-testimonials>
            <div class="landing-testimonials-track" data-testimonials-track>
                @forelse(($testimonials ?? collect()) as $t)
                    <div class="landing-testimonial">
                        <div class="landing-testimonial-quote">“{{ $t->quote }}”</div>
                        <div class="landing-testimonial-meta">
                            <span class="landing-testimonial-name">{{ $t->name }}</span>
                            @if (!empty($t->role) || !empty($t->location))
                                <span class="landing-testimonial-sep">·</span>
                                <span class="landing-testimonial-sub">{{ trim(($t->role ?? '') . ' ' . (!empty($t->location) ? ('— ' . $t->location) : '')) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="landing-testimonial">
                        <div class="landing-testimonial-quote">“Malkia Konnect made me feel calm and supported. I got guidance when I needed it most.”</div>
                        <div class="landing-testimonial-meta">
                            <span class="landing-testimonial-name">Amina S.</span>
                            <span class="landing-testimonial-sep">·</span>
                            <span class="landing-testimonial-sub">New Mother — Dar es Salaam</span>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="landing-testimonials-dots" data-testimonials-dots></div>
        </div>
    </div>
</section>
