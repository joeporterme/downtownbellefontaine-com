@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('description', 'How the Downtown Bellefontaine Partnership collects, uses, and protects your personal information.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Legal"
    title="Privacy Policy"
    subtitle="How we collect, use, and protect your information."
    image="/images/home/downtown-bellefontaine-1.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Privacy Policy']]" />

<section class="py-14 md:py-20 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $h2 = 'text-xl md:text-2xl font-bold text-theme-primary mt-12 mb-4';
            $h3 = 'text-lg font-semibold text-theme-primary mt-6 mb-2';
            $p  = 'text-theme-secondary leading-relaxed mb-4';
            $ul = 'list-disc pl-6 space-y-2 text-theme-secondary mb-4 marker:text-primary-400';
        @endphp

        <p class="text-sm text-theme-tertiary mb-2">Last updated: July 20, 2026</p>
        <p class="{{ $p }}">
            This Privacy Policy explains how the <strong>Downtown Bellefontaine Partnership</strong> (&ldquo;Downtown Bellefontaine,&rdquo; &ldquo;we,&rdquo; &ldquo;us,&rdquo; or &ldquo;our&rdquo;) collects, uses, and shares information when you visit
            <a href="{{ url('/') }}" class="text-primary-600 dark:text-primary-400 hover:underline">this website</a> or interact with us. By using our website, you agree to the practices described below.
        </p>

        <h2 class="{{ $h2 }}">Information We Collect</h2>

        <h3 class="{{ $h3 }}">Information you give us</h3>
        <p class="{{ $p }}">We collect information you choose to provide, such as when you:</p>
        <ul class="{{ $ul }}">
            <li><strong>Contact us</strong> through our contact form &mdash; your name, email address, and message.</li>
            <li><strong>Sign up for our newsletter</strong> or updates &mdash; your email address (and any details you include).</li>
            <li><strong>Create a business account or submit a listing or event</strong> &mdash; your name, email address, password, business or event details, address, phone number, website, social links, and any photos you upload.</li>
        </ul>
        <p class="{{ $p }}">
            Please remember that business listings, events, and related content you submit for publication are <strong>displayed publicly</strong> on the website.
        </p>

        <h3 class="{{ $h3 }}">Information collected automatically</h3>
        <p class="{{ $p }}">
            When you visit the website, we and our analytics providers may automatically collect certain technical information, including your IP address, browser type, device information, pages viewed, referring pages, and the dates and times of your visits. We collect this information using cookies and similar technologies (see &ldquo;Cookies &amp; Analytics&rdquo; below).
        </p>

        <h2 class="{{ $h2 }}">How We Use Your Information</h2>
        <p class="{{ $p }}">We use the information we collect to:</p>
        <ul class="{{ $ul }}">
            <li>Operate, maintain, and improve the website;</li>
            <li>Publish and manage business listings, events, and related content;</li>
            <li>Respond to your questions, messages, and requests;</li>
            <li>Send newsletters and updates you have signed up for;</li>
            <li>Understand how visitors use the site so we can make it better;</li>
            <li>Protect the security and integrity of the website and prevent abuse; and</li>
            <li>Comply with legal obligations.</li>
        </ul>

        <h2 class="{{ $h2 }}">How We Share Your Information</h2>
        <p class="{{ $p }}">
            <strong>We do not sell your personal information.</strong> We share information only in these limited circumstances:
        </p>
        <ul class="{{ $ul }}">
            <li><strong>Service providers.</strong> We use trusted third parties to help us run the website and communicate with you &mdash; including <strong>HubSpot</strong> (forms and email/newsletter), <strong>Google Analytics</strong> (website analytics), and <strong>Google Maps</strong> (maps and location features). These providers process information on our behalf and are subject to their own privacy policies.</li>
            <li><strong>Publicly submitted content.</strong> Information you submit for a public listing or event is displayed to other visitors.</li>
            <li><strong>Legal and safety reasons.</strong> We may disclose information if required by law, or to protect the rights, property, or safety of the Downtown Bellefontaine Partnership, our visitors, or others.</li>
            <li><strong>Business transfers.</strong> Information may be transferred as part of a merger, reorganization, or similar event.</li>
        </ul>

        <h2 class="{{ $h2 }}">Cookies &amp; Analytics</h2>
        <p class="{{ $p }}">
            We use cookies and similar technologies to keep the site working (for example, to remember your light/dark theme preference and to secure form submissions) and to understand website traffic. We use <strong>Google Analytics</strong> to help us measure and improve the site. Google Analytics may set cookies and collect usage data as described in
            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline">Google&rsquo;s Privacy Policy</a>. You can opt out of Google Analytics using Google&rsquo;s
            <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline">opt-out browser add-on</a>.
        </p>
        <p class="{{ $p }}">
            Most browsers let you control cookies through their settings. Disabling cookies may affect how parts of the website function.
        </p>

        <h2 class="{{ $h2 }}">Third-Party Links</h2>
        <p class="{{ $p }}">
            Our website links to businesses, events, press coverage, and other third-party websites and services. We are not responsible for the privacy practices or content of those third parties, and we encourage you to review their privacy policies.
        </p>

        <h2 class="{{ $h2 }}">Data Security</h2>
        <p class="{{ $p }}">
            We take reasonable measures to protect your information from loss, misuse, and unauthorized access. However, no method of transmission or storage is completely secure, and we cannot guarantee absolute security.
        </p>

        <h2 class="{{ $h2 }}">Data Retention</h2>
        <p class="{{ $p }}">
            We keep personal information for as long as needed to provide our services, fulfill the purposes described in this policy, and comply with our legal obligations. If you have an account, you may update or request deletion of your information as described below.
        </p>

        <h2 class="{{ $h2 }}">Your Choices &amp; Rights</h2>
        <ul class="{{ $ul }}">
            <li><strong>Access &amp; updates.</strong> You may request access to, or correction of, the personal information we hold about you.</li>
            <li><strong>Deletion.</strong> You may request that we delete your personal information, subject to information we are required to keep for legal, security, or administrative reasons.</li>
            <li><strong>Email opt-out.</strong> You can unsubscribe from our newsletters at any time using the link in any email, or by contacting us.</li>
            <li><strong>Cookies &amp; analytics.</strong> You can manage cookies in your browser and opt out of Google Analytics as described above.</li>
        </ul>
        <p class="{{ $p }}">To exercise any of these choices, contact us using the details below.</p>

        <h2 class="{{ $h2 }}">Children&rsquo;s Privacy</h2>
        <p class="{{ $p }}">
            Our website is not directed to children under 13, and we do not knowingly collect personal information from children under 13. If you believe a child has provided us personal information, please contact us and we will take appropriate steps to remove it.
        </p>

        <h2 class="{{ $h2 }}">Changes to This Policy</h2>
        <p class="{{ $p }}">
            We may update this Privacy Policy from time to time. When we do, we will revise the &ldquo;Last updated&rdquo; date above. Your continued use of the website after any changes indicates your acceptance of the updated policy.
        </p>

        <h2 class="{{ $h2 }}">Contact Us</h2>
        <p class="{{ $p }}">
            If you have questions about this Privacy Policy or how we handle your information, please contact us:
        </p>
        <ul class="{{ $ul }}">
            <li><strong>Downtown Bellefontaine Partnership</strong></li>
            <li>Bellefontaine, Ohio</li>
            <li>Email: <a href="mailto:info@downtownbellefontaine.com" class="text-primary-600 dark:text-primary-400 hover:underline">info@downtownbellefontaine.com</a></li>
            <li>Phone: <a href="tel:9374412681" class="text-primary-600 dark:text-primary-400 hover:underline">937-441-2681</a></li>
        </ul>
    </div>
</section>
@endsection
