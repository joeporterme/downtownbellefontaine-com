@extends('layouts.app')

@section('title', 'Food & Drinks')
@section('description', 'Discover the best food and drinks in Downtown Bellefontaine, Ohio - from award-winning pizza at 600 Downtown to craft beer at local breweries.')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    <div class="absolute inset-0">
        <img src="/images/pages/bella-vino.jpg" alt="Dining in Downtown Bellefontaine" class="w-full h-full object-cover opacity-30">
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <p class="text-accent-400 font-display text-lg sm:text-xl mb-3">Downtown Bellefontaine</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">Food & Drinks</h1>
        <p class="text-primary-200 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            Around here, we treat everyone like a regular. Customer service is kind of our thing. Pull up a chair and stay a while.
        </p>
    </div>
</section>

{{-- Story Section --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">More Than a Meal -- It's an Experience</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>When you dine in Bellefontaine, you'll visit restaurants that provide more than a meal; they'll provide an experience. And not just any experience, but those experiences that you rave about for weeks after, ones that beg you to come back.</p>
                    <p>Many of our downtown restaurants were founded and run by Bellefontaine locals, and their passion to keep this town the most loveable in Ohio is rooted in everything they do.</p>
                </div>
            </div>
            <div class="relative">
                <img src="/images/pages/whits-custard.jpg" alt="Whit's Frozen Custard" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -left-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">Taste the Love</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- What are you hungry for --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="relative order-2 md:order-1">
                <img src="/images/pages/six-hundred-pizza.jpg" alt="600 Downtown Pizza" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -right-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">600 Downtown</p>
                </div>
            </div>
            <div class="order-1 md:order-2">
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">So, What Are You Hungry For?</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>Doesn't matter -- Bellefontaine has it. Pizza at <strong class="text-theme-primary">600 Downtown</strong>, barbeque at <strong class="text-theme-primary">2Gs</strong>, burgers and craft beer at <strong class="text-theme-primary">Iron City</strong> or <strong class="text-theme-primary">The Syndicate</strong> -- take your pick.</p>
                    <p>And <em>never</em> forget dessert. Between <strong class="text-theme-primary">City Sweets and Creamery</strong> and <strong class="text-theme-primary">Whit's Frozen Custard</strong>, we have options to satisfy every sweet tooth.</p>
                    <p>All that's left is for you to taste your way across Bellefontaine and have that one-of-a-kind experience.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Businesses Grid --}}
@if($businesses->isNotEmpty())
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-3">Where to Eat & Drink</h2>
            <p class="text-theme-tertiary max-w-xl mx-auto">From craft breweries to frozen custard, explore the restaurants and eateries that make Downtown Bellefontaine delicious.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($businesses as $business)
                <a href="{{ route('businesses.show', $business) }}" class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    <div class="overflow-hidden">
                        @if($business->featured_image)
                            <img src="{{ Storage::url($business->featured_image) }}" alt="{{ $business->name }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300">
                        @elseif($business->logo)
                            <div class="w-full h-44 bg-gray-100 dark:bg-gray-800 flex items-center justify-center p-6">
                                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="w-full h-44 bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-utensils text-4xl text-primary-300 dark:text-primary-700"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-theme-primary mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $business->name }}</h3>

                        @if($business->address)
                            <p class="text-theme-tertiary text-sm flex items-center gap-1.5">
                                <i class="fa-duotone fa-light fa-location-dot text-primary-500 flex-shrink-0"></i>
                                <span class="truncate">{{ $business->address }}</span>
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('businesses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fa-duotone fa-light fa-grid-2"></i>
                View All Businesses
            </a>
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-16 bg-primary-700 dark:bg-primary-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <i class="fa-duotone fa-light fa-utensils text-4xl text-accent-400 mb-4"></i>
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Own a Restaurant in Bellefontaine?</h2>
        <p class="text-primary-200 mb-8 max-w-lg mx-auto">Get your restaurant listed in our directory for free. Reach hungry visitors and join our growing downtown community.</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
            <i class="fa-duotone fa-light fa-rocket"></i>
            List Your Business
        </a>
    </div>
</section>
@endsection
