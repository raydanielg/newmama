@extends('layouts.app')

@section('content')
<div class="landing-body">
    @include('landing.partials.header')

    <div class="landing-section">
        <div class="landing-container">
            <div class="landing-section-head animate__animated animate__fadeInUp">
                <div class="landing-chip">LEGAL</div>
                <h1 class="landing-h1">Terms of Service</h1>
                <p class="landing-p">Last Updated: {{ date('F d, Y') }}</p>
            </div>

            <div class="landing-panel animate__animated animate__fadeInUp animate__delay-1s">
                <div class="landing-legal-content">
                    <h3>1. Terms</h3>
                    <p>By accessing the website at Malkia Konnect, you are agreeing to be bound by these terms of service, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws.</p>

                    <h3>2. Use License</h3>
                    <p>Permission is granted to temporarily download one copy of the materials (information or software) on Malkia Konnect's website for personal, non-commercial transitory viewing only.</p>

                    <h3>3. Disclaimer</h3>
                    <p>The materials on Malkia Konnect's website are provided on an 'as is' basis. Malkia Konnect makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>

                    <h3>4. Limitations</h3>
                    <p>In no event shall Malkia Konnect or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Malkia Konnect's website.</p>
                </div>
            </div>
        </div>
    </div>

    @include('landing.partials.footer')
</div>
@endsection
