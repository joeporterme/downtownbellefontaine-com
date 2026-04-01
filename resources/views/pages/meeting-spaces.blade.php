@extends('layouts.app')

@section('title', 'Meeting Spaces')
@section('description', 'Find the perfect meeting space in Downtown Bellefontaine - from elegant event venues to modern coworking spaces.')

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-theme-primary mb-8">Meeting Spaces</h1>

        <div class="prose prose-lg max-w-none text-theme-secondary">
            <p>You've got business to take care of and we've got the venues to take care of you. Downtown Bellefontaine offers a variety of event spaces for whatever your event needs may be. From meetings to social gatherings to birthday parties, we cater to it all.</p>

            <div class="float-left mr-6 mb-4">
                <img src="/images/pages/bella-vino.jpg" alt="Bella Vino Event Space" class="rounded-lg w-96">
            </div>

            <p><a href="https://syndicatedowntown.com/bookus/" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">The Syndicate</a> offers one of Logan County's finest caterer and event centers. They can host your larger gatherings with their fine dining and venue space.</p>

            <p><a href="http://bellavinoevents.com/" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">Bella Vino</a> is a truly unique destination for your special event. Hidden away in the heart of Downtown, they provide you an old-world atmosphere with curated wine selections and catering options.</p>

            <p><a href="https://themaxwellevents.com/" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">The Maxwell</a> is a premier event space located in the heart of Bellefontaine. It is the perfect venue for celebrating life's most important moments, whether you're hosting a wedding, corporate event, or social gathering. The Maxwell offers a stunning setting for unforgettable experiences.</p>

            <p class="clear-both"><a href="https://buildcowork.com/" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">Build Cowork + Space</a> was designed to inspire, connect, and enable small businesses and solopreneurs. With four different styles of conference rooms available, there is a space to fit every meeting need. BUILD conference rooms also include the highest quality A/V equipment and next-level technology. Rent your space by the hour or reserve a room for the whole day.</p>

            <p><a href="https://www.puttplaygolfcenter.com/" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">Putt and Play Golf Center</a> is that perfect balance of work and play. Have your meeting then have some fun with their laser tag, miniature golf and golf simulation, and virtual reality rooms. It's also a great location for those birthday parties that deserve something extra special.</p>

            <p>Make your next event one you won't forget.</p>

            <p>View more on our <a href="/downtown-map" class="text-primary-600 dark:text-primary-400 hover:underline">Downtown Map</a></p>
        </div>
    </div>
</div>
@endsection
