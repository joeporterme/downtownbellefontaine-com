@extends('layouts.app')

@section('title', 'First Fridays')
@section('description', 'Join us for First Fridays in Downtown Bellefontaine - monthly community events celebrating local shops, food, and entertainment.')

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-theme-primary mb-8">First Fridays</h1>

        <div class="prose prose-lg max-w-none text-theme-secondary">
            <div class="float-right ml-6 mb-4">
                <img src="/images/pages/first-fridays.jpg" alt="First Fridays in Downtown Bellefontaine" class="rounded-lg w-72">
            </div>

            <p>You gotta love a good Friday. And the Downtown Bellefontaine Partnership, a nonprofit organization, has committed themselves to make every first Friday of the month one for the books. Their mission is to make Bellefontaine the place to be through improving the quality and quantity of commerce, unifying the public and private sectors, and promoting historic preservation to enrich the cultural life of our community.</p>

            <p>How do they do it, you ask?</p>

            <p>By hosting and promoting monthly downtown events that encourage people to come and support local shops in Logan County. Shop, eat, explore, repeat.</p>

            <p class="clear-both">For more information, visit <a href="https://firstfridaysbellefontaine.com/" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">www.firstfridaysbellefontaine.com</a></p>
        </div>
    </div>
</div>
@endsection
