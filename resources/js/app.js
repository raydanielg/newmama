import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form.auth-form');

    forms.forEach((form) => {
        form.addEventListener('submit', () => {
            const submitBtn = form.querySelector('button[type="submit"].auth-submit');

            if (!submitBtn) {
                return;
            }

            submitBtn.classList.add('is-loading');
            submitBtn.disabled = true;
        });
    });

    const sliders = document.querySelectorAll('[data-testimonials]');

    sliders.forEach((slider) => {
        const track = slider.querySelector('[data-testimonials-track]');
        const dotsWrap = slider.querySelector('[data-testimonials-dots]');

        if (!track || !dotsWrap) {
            return;
        }

        const slides = Array.from(track.children);

        if (slides.length <= 1) {
            return;
        }

        let index = 0;
        let timerId = null;
        const intervalMs = 5200;

        const setActive = (nextIndex) => {
            index = (nextIndex + slides.length) % slides.length;
            track.style.transform = `translateX(-${index * 100}%)`;

            const dots = Array.from(dotsWrap.querySelectorAll('button'));
            dots.forEach((btn, i) => {
                btn.classList.toggle('is-active', i === index);
                btn.setAttribute('aria-current', i === index ? 'true' : 'false');
            });
        };

        dotsWrap.innerHTML = '';
        slides.forEach((_, i) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'landing-dot' + (i === 0 ? ' is-active' : '');
            btn.setAttribute('aria-label', `Go to testimonial ${i + 1}`);
            btn.setAttribute('aria-current', i === 0 ? 'true' : 'false');
            btn.addEventListener('click', () => {
                setActive(i);
                restart();
            });
            dotsWrap.appendChild(btn);
        });

        const start = () => {
            if (timerId) {
                return;
            }
            timerId = window.setInterval(() => setActive(index + 1), intervalMs);
        };

        const stop = () => {
            if (!timerId) {
                return;
            }
            window.clearInterval(timerId);
            timerId = null;
        };

        const restart = () => {
            stop();
            start();
        };

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        slider.addEventListener('focusin', stop);
        slider.addEventListener('focusout', start);

        setActive(0);
        start();
    });

    // Newsletter AJAX Handling
    const newsletterForms = document.querySelectorAll('.landing-footer-newsletter, .sidebar-newsletter');
    
    newsletterForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const emailInput = form.querySelector('input[type="email"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnContent = submitBtn.innerHTML;
            
            if (!emailInput.value) return;

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            try {
                const response = await fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: emailInput.value })
                });

                const data = await response.json();

                if (response.ok) {
                    // Success
                    emailInput.value = '';
                    submitBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" width="18" height="18" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                    
                    // Show message (simple alert for now, can be improved)
                    alert(data.message);
                } else {
                    // Error from server
                    alert(data.message || 'Something went wrong. Please try again.');
                }
            } catch (error) {
                console.error('Newsletter Error:', error);
                alert('An error occurred. Please check your connection and try again.');
            } finally {
                // Reset button after delay
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnContent;
                }, 3000);
            }
        });
    });
});
