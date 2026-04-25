@extends('layouts.app')

@section('content')
<div class="landing-body">
    @include('landing.partials.header')

    <div class="landing-section">
        <div class="landing-container">
            <div class="landing-section-head animate__animated animate__fadeInUp">
                <div class="landing-chip">LEGAL</div>
                <h1 class="landing-h1">Privacy Policy</h1>
                <p class="landing-p">Last Updated: {{ date('F d, Y') }}</p>
            </div>

            <div class="landing-panel animate__animated animate__fadeInUp animate__delay-1s">
                <div class="landing-legal-content">
                    <h3>1. Introduction</h3>
                    <p>At Malkia Konnect, we respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website.</p>

                    <h3>2. The Data We Collect</h3>
                    <p>We may collect, use, store and transfer different kinds of personal data about you which we have grouped together as follows:</p>
                    <ul>
                        <li>Identity Data (name, username)</li>
                        <li>Contact Data (email address, telephone numbers)</li>
                        <li>Technical Data (IP address, browser type)</li>
                        <li>Usage Data (information about how you use our website)</li>
                    </ul>

                    <h3>3. How We Use Your Data</h3>
                    <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data to provide our services and notify you about changes to our services.</p>

                    <h3>4. Data Security</h3>
                    <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way.</p>
                </div>
            </div>
        </div>
    </div>

    @include('landing.partials.footer')
</div>
@endsection
