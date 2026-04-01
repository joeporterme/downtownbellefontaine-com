@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('description', 'Downtown Bellefontaine privacy policy - learn how we collect, use, and protect your personal information.')

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-theme-primary mb-8">Privacy Policy</h1>

        <div class="prose prose-lg max-w-none text-theme-secondary">
            <h2 class="text-2xl font-semibold mb-4 text-theme-primary">Who we are</h2>
            <p class="mb-6">Our website address is: https://downtownbellefontaine.com.</p>

            <h2 class="text-2xl font-semibold mb-4 text-theme-primary">What personal data we collect and why we collect it</h2>

            <h3 class="text-xl font-semibold mb-3 text-theme-primary">Comments</h3>
            <p class="mb-6">When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor's IP address and browser user agent string to help spam detection.</p>

            <h3 class="text-xl font-semibold mb-3 text-theme-primary">Cookies</h3>
            <p class="mb-6">If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>

            <h3 class="text-xl font-semibold mb-3 text-theme-primary">Embedded content from other websites</h3>
            <p class="mb-6">Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>

            <h2 class="text-2xl font-semibold mb-4 text-theme-primary">How long we retain your data</h2>
            <p class="mb-6">For users that register on our website, we store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>

            <h2 class="text-2xl font-semibold mb-4 text-theme-primary">What rights you have over your data</h2>
            <p>If you have an account on this site, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>
        </div>
    </div>
</div>
@endsection
