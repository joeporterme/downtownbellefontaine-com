{{-- Dismissible newsletter slide-up. First visit only; once dismissed or
     submitted it never reappears (localStorage). Submits to the same HubSpot
     form as the footer signup. --}}
<div id="nlp" role="dialog" aria-label="Newsletter signup" aria-hidden="true"
     class="fixed z-[80] bottom-4 left-4 right-4 sm:right-auto sm:max-w-sm translate-y-[160%] opacity-0 transition-all duration-500 ease-out">
    <div class="relative bg-white dark:bg-primary-900 rounded-2xl shadow-2xl border border-primary-100 dark:border-primary-700 p-5 pr-6">
        <button type="button" id="nlp-close" aria-label="Dismiss"
                class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full flex items-center justify-center text-primary-400 hover:text-primary-700 dark:hover:text-white hover:bg-primary-50 dark:hover:bg-primary-800 transition-colors">
            <i class="fa-duotone fa-light fa-xmark text-lg"></i>
        </button>

        <div id="nlp-body">
            <div class="flex items-center gap-2 mb-1">
                <i class="fa-duotone fa-light fa-envelope-open-text text-accent-500 text-xl"></i>
                <h3 class="font-display text-2xl text-primary-700 dark:text-accent-300">The 43311</h3>
            </div>
            <p class="text-sm text-theme-secondary mb-4">Downtown events, new openings, and happenings — straight to your inbox.</p>

            <form id="nlp-form" novalidate class="space-y-2.5">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" name="firstname" placeholder="First name" aria-label="First name" autocomplete="given-name"
                           class="w-full px-3 py-2.5 text-sm rounded-lg text-primary-900 bg-primary-50 dark:bg-primary-800 dark:text-white border border-primary-200 dark:border-primary-600 focus:outline-none focus:ring-2 focus:ring-accent-400">
                    <input type="text" name="lastname" placeholder="Last name" aria-label="Last name" autocomplete="family-name"
                           class="w-full px-3 py-2.5 text-sm rounded-lg text-primary-900 bg-primary-50 dark:bg-primary-800 dark:text-white border border-primary-200 dark:border-primary-600 focus:outline-none focus:ring-2 focus:ring-accent-400">
                </div>
                <input type="email" name="email" placeholder="you@email.com" aria-label="Email address" autocomplete="email"
                       class="w-full px-3 py-2.5 text-sm rounded-lg text-primary-900 bg-primary-50 dark:bg-primary-800 dark:text-white border border-primary-200 dark:border-primary-600 focus:outline-none focus:ring-2 focus:ring-accent-400">
                <p id="nlp-error" class="hidden text-red-500 text-xs"></p>
                <button type="submit" id="nlp-submit"
                        class="w-full px-4 py-2.5 bg-accent-500 hover:bg-accent-600 text-white text-sm font-semibold rounded-lg transition-colors disabled:opacity-60">
                    <i class="fa-duotone fa-light fa-paper-plane mr-1.5"></i>Subscribe
                </button>
            </form>
        </div>

        <div id="nlp-success" class="hidden text-center py-3">
            <i class="fa-duotone fa-light fa-circle-check text-4xl text-accent-500 mb-2"></i>
            <p class="font-semibold text-theme-primary">Welcome to The 43311!</p>
            <p class="text-sm text-theme-secondary">Keep an eye on your inbox for downtown news.</p>
        </div>
    </div>
</div>

<script>
    (function () {
        var KEY = 'dtb_newsletter_dismissed';
        var el = document.getElementById('nlp');
        if (!el || (window.localStorage && localStorage.getItem(KEY))) return;

        var form = document.getElementById('nlp-form');
        var errEl = document.getElementById('nlp-error');
        var submitBtn = document.getElementById('nlp-submit');
        var endpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/20109775/b1fe3e78-e462-49cc-b1d9-0ea2ed95f6de';

        function dismiss(save) {
            el.classList.add('translate-y-[160%]', 'opacity-0');
            el.setAttribute('aria-hidden', 'true');
            if (save) { try { localStorage.setItem(KEY, '1'); } catch (e) {} }
        }
        function show() {
            el.classList.remove('translate-y-[160%]', 'opacity-0');
            el.setAttribute('aria-hidden', 'false');
        }
        function val(n) { return form.querySelector('[name="' + n + '"]').value.trim(); }

        document.getElementById('nlp-close').addEventListener('click', function () { dismiss(true); });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            errEl.classList.add('hidden');
            var fn = val('firstname'), ln = val('lastname'), em = val('email');
            if (!fn || !ln || !em) { errEl.textContent = 'Please fill in all fields.'; errEl.classList.remove('hidden'); return; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) { errEl.textContent = 'Enter a valid email address.'; errEl.classList.remove('hidden'); return; }
            submitBtn.disabled = true; submitBtn.innerHTML = 'Subscribing…';
            fetch(endpoint, {
                method: 'POST', headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    fields: [
                        { name: 'firstname', value: fn },
                        { name: 'lastname', value: ln },
                        { name: 'email', value: em }
                    ],
                    context: { pageUri: window.location.href, pageName: document.title }
                })
            }).then(function (res) {
                if (res.ok) {
                    document.getElementById('nlp-body').classList.add('hidden');
                    document.getElementById('nlp-success').classList.remove('hidden');
                    try { localStorage.setItem(KEY, '1'); } catch (e) {}
                    setTimeout(function () { dismiss(false); }, 3500);
                } else {
                    errEl.textContent = 'Sorry, something went wrong. Please try again.';
                    errEl.classList.remove('hidden');
                    submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fa-duotone fa-light fa-paper-plane mr-1.5"></i>Subscribe';
                }
            }).catch(function () {
                errEl.textContent = 'Network error. Please try again.';
                errEl.classList.remove('hidden');
                submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fa-duotone fa-light fa-paper-plane mr-1.5"></i>Subscribe';
            });
        });

        // Appear shortly after landing on the first visit.
        setTimeout(show, 2500);
    })();
</script>
