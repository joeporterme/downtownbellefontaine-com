@extends('layouts.app')

@section('title', 'Contact Us')
@section('description', 'Contact Downtown Bellefontaine Partnership - get in touch with questions about visiting, businesses, or events in Downtown Bellefontaine, Ohio.')

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-theme-primary mb-8">Contact Us</h1>

        <div class="prose prose-lg max-w-none text-theme-secondary">
            <p class="mb-6">Have questions about Downtown Bellefontaine? We'd love to hear from you!</p>

            <div class="bg-theme-secondary rounded-lg p-6 mb-8 not-prose">
                <h2 class="text-xl font-semibold mb-4 text-theme-primary">Downtown Bellefontaine Partnership</h2>
                <p class="mb-2 text-theme-secondary"><strong>Executive Director:</strong> Katie Cooper</p>
                <p class="mb-2 text-theme-secondary"><strong>Phone:</strong> <a href="tel:937-441-2681" class="text-primary-600 dark:text-primary-400 hover:underline">937-441-2681</a></p>
                <p class="text-theme-secondary"><strong>Email:</strong> <a href="mailto:kncooper9118@gmail.com" class="text-primary-600 dark:text-primary-400 hover:underline">kncooper9118@gmail.com</a></p>
            </div>

            <h2 class="text-2xl font-semibold mb-4 text-theme-primary">Our Mission</h2>
            <p class="mb-6">The Downtown Bellefontaine Partnership, Inc. is dedicated to making the downtown attractive for merchants to do business and individuals to shop, while striving to improve the quality and quantity of commerce, unifying the public and private sectors, and promoting historic preservation to enrich the cultural life of our community.</p>

            <h2 class="text-2xl font-semibold mb-4 text-theme-primary">Our Vision</h2>
            <p>Downtown Bellefontaine will be the Midwest's premier small-town destination for local food, unique shopping and meaningful experiences year around.</p>
        </div>
    </div>
</div>
@endsection
