@extends('layouts.app')

@section('title', 'Contact Us')
@section('description', 'Get in touch with Downtown Bellefontaine - questions about visiting, business listings, events, or getting involved downtown.')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    <div class="absolute inset-0">
        <img src="/images/home/downtown-bellefontaine-1.jpg" alt="Downtown Bellefontaine" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/40 to-primary-900/70"></div>
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <p class="text-accent-400 font-display text-lg sm:text-xl mb-3">We'd Love to Hear From You</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">Contact Us</h1>
        <p class="text-primary-200 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            Questions about visiting, businesses, events, or how to get involved? Drop us a note -- we read every one.
        </p>
    </div>
</section>

{{-- Form + Contact Info --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form --}}
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-theme-secondary rounded-2xl border border-theme p-6 sm:p-8">
                    <h2 class="text-2xl font-bold text-theme-primary mb-2">Send a Message</h2>
                    <p class="text-theme-tertiary text-sm mb-6">We'll get back to you within a couple of business days.</p>

                    @if(session('success'))
                        <div class="mb-6 bg-success-100 dark:bg-success-900/40 border border-success-300 dark:border-success-700 text-success-700 dark:text-success-300 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                            <i class="fa-duotone fa-light fa-circle-check text-lg"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 bg-danger-100 dark:bg-danger-900/40 border border-danger-300 dark:border-danger-700 text-danger-700 dark:text-danger-300 px-4 py-3 rounded-lg text-sm">
                            <p class="flex items-center gap-2 font-medium">
                                <i class="fa-duotone fa-light fa-circle-exclamation"></i>
                                Please fix the following:
                            </p>
                            <ul class="list-disc list-inside mt-2 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                        @csrf

                        {{-- Honeypot: hidden from humans, visible to most bots --}}
                        <div class="hidden" aria-hidden="true">
                            <label for="website_url">Website (leave blank)</label>
                            <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-theme-secondary mb-1.5">
                                    <i class="fa-duotone fa-light fa-user text-primary-500 mr-1"></i>
                                    Your Name <span class="text-danger-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-primary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                    placeholder="Jane Smith">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-theme-secondary mb-1.5">
                                    <i class="fa-duotone fa-light fa-envelope text-primary-500 mr-1"></i>
                                    Email <span class="text-danger-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-primary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                    placeholder="jane@example.com">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-theme-secondary mb-1.5">
                                    <i class="fa-duotone fa-light fa-phone text-primary-500 mr-1"></i>
                                    Phone <span class="text-theme-tertiary font-normal">(optional)</span>
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-primary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                    placeholder="(937) 555-0123">
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-theme-secondary mb-1.5">
                                    <i class="fa-duotone fa-light fa-tag text-primary-500 mr-1"></i>
                                    Subject
                                </label>
                                <select id="subject" name="subject"
                                    class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-primary text-theme-primary focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                    <option value="">-- Choose one --</option>
                                    @foreach([
                                        'General Inquiry',
                                        'Planning a Visit',
                                        'Business Listing',
                                        'Event Submission',
                                        'Media & Press',
                                        'Partnership / Sponsorship',
                                        'Something Else',
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('subject') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-theme-secondary mb-1.5">
                                <i class="fa-duotone fa-light fa-message-lines text-primary-500 mr-1"></i>
                                Message <span class="text-danger-500">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required
                                class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-primary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                                placeholder="Tell us what's on your mind...">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
                            <i class="fa-duotone fa-light fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            {{-- Contact info sidebar --}}
            <aside class="lg:col-span-1 order-1 lg:order-2 space-y-5">
                <div class="bg-theme-secondary rounded-2xl border border-theme overflow-hidden">
                    <div class="bg-gradient-to-br from-primary-600 to-primary-800 text-white p-6">
                        <p class="text-accent-300 font-display text-lg">Get in Touch</p>
                        <h3 class="font-bold text-xl mt-1">Downtown Bellefontaine</h3>
                        <p class="text-primary-100 text-sm mt-2">Ohio's most loveable downtown.</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0">
                                <i class="fa-duotone fa-light fa-phone text-primary-600"></i>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-theme-tertiary font-semibold">Phone</p>
                                <a href="tel:937-565-4580" class="font-semibold text-theme-primary hover:text-accent-600 transition-colors">937-565-4580</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-success-100 dark:bg-success-900/40 flex items-center justify-center flex-shrink-0">
                                <i class="fa-duotone fa-light fa-envelope text-success-600"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs uppercase tracking-wider text-theme-tertiary font-semibold">Email</p>
                                <a href="mailto:info@downtownbellefontaine.com" class="font-semibold text-theme-primary hover:text-accent-600 transition-colors break-all">info@downtownbellefontaine.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-theme-secondary rounded-2xl border border-theme p-6">
                    <p class="text-xs uppercase tracking-wider text-theme-tertiary font-semibold mb-3">Stay Connected</p>
                    <div class="flex items-center gap-3">
                        <a href="https://www.facebook.com/DowntownBellefontaine" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-theme-primary border border-theme flex items-center justify-center text-theme-secondary hover:text-blue-600 hover:border-blue-400 transition-colors" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/downtownbellefontaine" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-theme-primary border border-theme flex items-center justify-center text-theme-secondary hover:text-pink-600 hover:border-pink-400 transition-colors" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://x.com/mostloveableoh" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-theme-primary border border-theme flex items-center justify-center text-theme-secondary hover:text-theme-primary hover:border-gray-400 transition-colors" aria-label="X (Twitter)">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection
