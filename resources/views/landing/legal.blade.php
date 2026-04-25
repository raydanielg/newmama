@extends('layouts.app')

@section('content')
<div class="landing-body">
    @include('landing.partials.header')

    <div class="landing-section">
        <div class="landing-container">
            <div class="landing-section-head animate__animated animate__fadeInUp">
                <div class="landing-chip">LEGAL</div>
                <h1 class="landing-h1">Legal Notice</h1>
                <p class="landing-p">Malkia Konnect LTD</p>
            </div>

            <div class="landing-panel animate__animated animate__fadeInUp animate__delay-1s">
                <div class="landing-legal-content">
                    <h3>Corporate Information</h3>
                    <p>Malkia Konnect LTD is a registered company in the United Republic of Tanzania.</p>
                    
                    <h3>Contact Information</h3>
                    <p>Email: support@malkiakonnect.co.tz</p>
                    <p>Phone: +255 700 000 000</p>

                    <h3>Intellectual Property</h3>
                    <p>All content, logos, and materials on this website are the property of Malkia Konnect LTD and are protected by applicable copyright and trademark law.</p>

                    <h3>Regulatory Information</h3>
                    <p>Malkia Konnect operates in accordance with all relevant health and data regulations in Tanzania.</p>
                </div>
            </div>
        </div>
    </div>

    @include('landing.partials.footer')
</div>
@endsection
