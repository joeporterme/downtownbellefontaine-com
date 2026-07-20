@extends('layouts.app')

@section('title', 'Terms of Service')
@section('description', 'The terms and conditions for using the Downtown Bellefontaine website.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Legal"
    title="Terms of Service"
    subtitle="The terms for using the Downtown Bellefontaine website."
    image="/images/home/downtown-bellefontaine-1.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Terms of Service']]" />

<section class="py-14 md:py-20 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $h2 = 'text-xl md:text-2xl font-bold text-theme-primary mt-12 mb-4';
            $p  = 'text-theme-secondary leading-relaxed mb-4';
            $ul = 'list-disc pl-6 space-y-2 text-theme-secondary mb-4 marker:text-primary-400';
        @endphp

        <p class="text-sm text-theme-tertiary mb-2">Last updated: July 20, 2026</p>
        <p class="{{ $p }}">
            These Terms of Service (&ldquo;Terms&rdquo;) govern your access to and use of the website operated by the
            <strong>Downtown Bellefontaine Partnership</strong> (&ldquo;Downtown Bellefontaine,&rdquo; &ldquo;we,&rdquo; &ldquo;us,&rdquo; or &ldquo;our&rdquo;). By accessing or using
            <a href="{{ url('/') }}" class="text-primary-600 dark:text-primary-400 hover:underline">this website</a>, you agree to be bound by these Terms and our
            <a href="{{ route('pages.privacy-policy') }}" class="text-primary-600 dark:text-primary-400 hover:underline">Privacy Policy</a>. If you do not agree, please do not use the website.
        </p>

        <h2 class="{{ $h2 }}">1. About the Website</h2>
        <p class="{{ $p }}">
            Our website is a community resource for Downtown Bellefontaine, Ohio. It provides a directory of local businesses, an events calendar, articles, maps, and related information, and it allows eligible users to create accounts and submit business listings and events for publication. We may add, change, or discontinue features at any time.
        </p>

        <h2 class="{{ $h2 }}">2. Eligibility &amp; Accounts</h2>
        <p class="{{ $p }}">
            You must be at least 18 years old to create an account. When you create an account, you agree to provide accurate, current, and complete information and to keep it up to date. You are responsible for maintaining the confidentiality of your login credentials and for all activity that occurs under your account. Notify us promptly of any unauthorized use.
        </p>

        <h2 class="{{ $h2 }}">3. Business Listings &amp; Event Submissions</h2>
        <p class="{{ $p }}">
            Business listings and event submissions are currently offered free of charge. By submitting a listing, event, photo, or other content, you represent and warrant that:
        </p>
        <ul class="{{ $ul }}">
            <li>The information is accurate and not misleading;</li>
            <li>You own or have the necessary rights and permissions to submit the content and grant the license below;</li>
            <li>The content does not infringe any third party&rsquo;s intellectual property, privacy, or other rights; and</li>
            <li>You are authorized to represent the business or event you are submitting.</li>
        </ul>
        <p class="{{ $p }}">
            We review submissions and may approve, edit, decline, or remove any content at our discretion, and we are not obligated to publish or continue to display any submission.
        </p>

        <h2 class="{{ $h2 }}">4. Your Content &amp; License</h2>
        <p class="{{ $p }}">
            You retain ownership of the content you submit. By submitting content, you grant the Downtown Bellefontaine Partnership a non-exclusive, royalty-free, worldwide license to use, host, store, reproduce, display, publish, and distribute that content in connection with operating and promoting the website and Downtown Bellefontaine (including on social media and in marketing materials). You can request removal of your content at any time by contacting us.
        </p>

        <h2 class="{{ $h2 }}">5. Acceptable Use</h2>
        <p class="{{ $p }}">You agree not to:</p>
        <ul class="{{ $ul }}">
            <li>Post false, misleading, unlawful, defamatory, harassing, obscene, or infringing content;</li>
            <li>Impersonate any person or business or misrepresent your affiliation;</li>
            <li>Submit spam, advertisements unrelated to Downtown Bellefontaine, or automated content;</li>
            <li>Attempt to gain unauthorized access to the website, other accounts, or our systems;</li>
            <li>Introduce malware, scrape the site without permission, or interfere with its operation; or</li>
            <li>Use the website for any unlawful purpose or in violation of these Terms.</li>
        </ul>

        <h2 class="{{ $h2 }}">6. Intellectual Property</h2>
        <p class="{{ $p }}">
            The website and its content &mdash; including text, graphics, logos, the Downtown Bellefontaine name and marks, layout, and design (excluding user-submitted content) &mdash; are owned by or licensed to the Downtown Bellefontaine Partnership and are protected by intellectual property laws. You may not copy, reproduce, or use them without our prior written permission.
        </p>

        <h2 class="{{ $h2 }}">7. Third-Party Links &amp; Services</h2>
        <p class="{{ $p }}">
            The website links to and integrates third-party businesses, events, websites, and services (such as Google Maps). We do not control and are not responsible for third-party content, products, services, or privacy practices. Your use of third-party services is subject to their terms.
        </p>

        <h2 class="{{ $h2 }}">8. Disclaimers</h2>
        <p class="{{ $p }}">
            The website and all content are provided &ldquo;as is&rdquo; and &ldquo;as available,&rdquo; without warranties of any kind, whether express or implied, including warranties of merchantability, fitness for a particular purpose, accuracy, and non-infringement. We do not warrant that the website will be uninterrupted, error-free, or secure, or that information (including business listings and events) is accurate, complete, or current. Any reliance on the website is at your own risk.
        </p>

        <h2 class="{{ $h2 }}">9. Limitation of Liability</h2>
        <p class="{{ $p }}">
            To the fullest extent permitted by law, the Downtown Bellefontaine Partnership and its officers, directors, employees, volunteers, and partners will not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits, data, goodwill, or other intangible losses, arising out of or related to your use of (or inability to use) the website. Our total liability for any claim relating to the website will not exceed one hundred dollars ($100).
        </p>

        <h2 class="{{ $h2 }}">10. Indemnification</h2>
        <p class="{{ $p }}">
            You agree to indemnify and hold harmless the Downtown Bellefontaine Partnership and its officers, directors, employees, volunteers, and partners from any claims, damages, liabilities, and expenses (including reasonable attorneys&rsquo; fees) arising out of your content, your use of the website, or your violation of these Terms or the rights of any third party.
        </p>

        <h2 class="{{ $h2 }}">11. Dispute Resolution &mdash; Binding Arbitration &amp; Class Action Waiver</h2>
        <p class="{{ $p }}">
            <strong>Please read this section carefully &mdash; it affects your legal rights.</strong> Except for claims that qualify for small-claims court, any dispute, claim, or controversy arising out of or relating to these Terms or the website will be resolved by <strong>final and binding arbitration</strong> administered by the American Arbitration Association (AAA) under its Consumer Arbitration Rules. The arbitration will take place in Logan County, Ohio, and judgment on the award may be entered in any court with jurisdiction.
        </p>
        <p class="{{ $p }}">
            <strong>Class Action Waiver.</strong> You and the Downtown Bellefontaine Partnership agree that each may bring claims against the other only in an individual capacity, and not as a plaintiff or class member in any purported class or representative proceeding. The arbitrator may not consolidate more than one person&rsquo;s claims or preside over any form of a representative or class proceeding.
        </p>
        <p class="{{ $p }}">
            <strong>Opt-out.</strong> You may opt out of this arbitration agreement within 30 days of first accepting these Terms by sending written notice to <a href="mailto:info@downtownbellefontaine.com" class="text-primary-600 dark:text-primary-400 hover:underline">info@downtownbellefontaine.com</a> with your name and a statement that you decline arbitration. Opting out will not affect any other part of these Terms.
        </p>

        <h2 class="{{ $h2 }}">12. Governing Law &amp; Venue</h2>
        <p class="{{ $p }}">
            These Terms are governed by the laws of the State of Ohio, without regard to its conflict-of-laws rules. Subject to the arbitration provision above, you agree that any permitted legal action will be brought exclusively in the state or federal courts located in Logan County, Ohio, and you consent to their jurisdiction.
        </p>

        <h2 class="{{ $h2 }}">13. Termination</h2>
        <p class="{{ $p }}">
            We may suspend or terminate your access to the website or your account at any time, with or without notice, if we believe you have violated these Terms or for any other reason. You may stop using the website and request account deletion at any time. Sections that by their nature should survive termination will survive.
        </p>

        <h2 class="{{ $h2 }}">14. Changes to These Terms</h2>
        <p class="{{ $p }}">
            We may update these Terms from time to time. When we do, we will revise the &ldquo;Last updated&rdquo; date above. Your continued use of the website after changes take effect constitutes acceptance of the updated Terms.
        </p>

        <h2 class="{{ $h2 }}">15. Contact Us</h2>
        <p class="{{ $p }}">Questions about these Terms? Contact us:</p>
        <ul class="{{ $ul }}">
            <li><strong>Downtown Bellefontaine Partnership</strong></li>
            <li>Bellefontaine, Ohio</li>
            <li>Email: <a href="mailto:info@downtownbellefontaine.com" class="text-primary-600 dark:text-primary-400 hover:underline">info@downtownbellefontaine.com</a></li>
            <li>Phone: <a href="tel:9374412681" class="text-primary-600 dark:text-primary-400 hover:underline">937-441-2681</a></li>
        </ul>
    </div>
</section>
@endsection
