@extends('layouts.app')

@section('content')
<div class="join-konnect-page">
    <div class="join-konnect-container">
        {{-- Top Branding & Language --}}
        <div class="join-header">
            <div class="join-brand">
                <img src="{{ asset('meetup_3669956.png') }}" alt="Mamacare AI Logo" class="join-logo">
                <div class="join-brand-text">
                    <span class="brand-malkia">Mamacare</span>
                    <span class="brand-konnect">AI</span>
                </div>
            </div>
            <div class="join-header-actions">
                <div class="join-lang">
                    <button class="lang-btn active" data-lang="sw">SW</button>
                    <span class="lang-sep">|</span>
                    <button class="lang-btn" data-lang="en">EN</button>
                </div>
                <a href="{{ route('mother.login') }}" class="join-signin-btn">
                    <i class="fas fa-user-circle"></i>
                    <span data-sw="Ingia" data-en="Sign In">Ingia</span>
                </a>
            </div>
        </div>

        <div class="join-content-grid">
            {{-- Left Side: Message --}}
            <div class="join-message-side animate__animated animate__fadeInLeft">
                <h1 class="join-welcome-title" 
                    data-sw="Mama, uko nyumbani" 
                    data-en="Mama, you are home">Mama, uko nyumbani</h1>
                <p class="join-welcome-subtitle" 
                   data-sw="Huhitaji kutembea safari hii peke yako, Mama." 
                   data-en="You don't have to walk this journey alone, Mama.">Huhitaji kutembea safari hii peke yako, Mama.</p>
                <p class="join-welcome-text" 
                   data-sw="Kila mama anastahili mtu wa kumwambia 'utakuwa sawa.' Mamacare AI ni rafiki yako ya uzazi, moja kwa moja kwenye WhatsApp yako. Bure. Binafsi. Kwa ajili yako." 
                   data-en="Every mother deserves someone to tell her 'it will be okay.' Mamacare AI is your motherhood friend, right on your WhatsApp. Free. Private. For you.">
                    Kila mama anastahili mtu wa kumwambia "utakuwa sawa." Mamacare AI ni rafiki yako ya uzazi, moja kwa moja kwenye WhatsApp yako. Bure. Binafsi. Kwa ajili yako.
                </p>
            </div>

            {{-- Right Side: Form Card --}}
            <div class="join-form-side animate__animated animate__fadeInRight">
                <div class="join-card">
                    <div class="join-step-indicator">
                        <span class="step-text" data-sw-prefix="Hatua" data-en-prefix="Step">Hatua 1 ya 3</span>
                        <div class="step-progress">
                            <div class="step-progress-bar" style="width: 33%;"></div>
                        </div>
                    </div>

                    <form action="{{ route('join.store') }}" method="POST" id="joinForm" class="join-multi-step-form">
                        @csrf
                        <input type="hidden" name="locale" id="current_locale" value="sw">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{-- Step 1: Personal Info --}}
                        <div id="step1" class="form-step active">
                            <h2 class="join-form-title" data-sw="Tunaomba tukufahamu" data-en="Help us get to know you">Tunaomba tukufahamu</h2>
                            <div class="form-group">
                                <label for="full_name" data-sw="Jina lako kamili *" data-en="Your full name *">Jina lako kamili *</label>
                                <input type="text" id="full_name" name="full_name" data-sw-placeholder="Mfano: Amina Hassan" data-en-placeholder="Example: Amina Hassan" placeholder="Mfano: Amina Hassan" required>
                            </div>

                            <div class="form-group">
                                <label for="whatsapp_number" data-sw="Nambari ya WhatsApp *" data-en="WhatsApp Number *">Nambari ya WhatsApp *</label>
                                <div class="input-with-prefix">
                                    @php($defaultCountry = App\Models\Country::where('iso2', 'TZ')->first())
                                    <input type="hidden" name="country_id" value="{{ $defaultCountry?->id }}">
                                    <span class="prefix" id="phone_prefix">{{ $defaultCountry?->phone_code ?: '+255' }}</span>
                                    <input type="tel" id="whatsapp_number" name="whatsapp_number" placeholder="7XX XXX XXX" required>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group">
                                    <label for="region" data-sw="Mkoa *" data-en="Region *">Mkoa *</label>
                                    <select id="region" name="region_id" required>
                                        <option value="" selected disabled data-sw="Chagua Mkoa" data-en="Select Region">Chagua Mkoa</option>
                                        @foreach(App\Models\Region::orderBy('name')->get() as $region)
                                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="district" data-sw="Wilaya *" data-en="District *">Wilaya *</label>
                                    <select id="district" name="district_id" required disabled>
                                        <option value="" selected disabled data-sw="Chagua Wilaya" data-en="Select District">Chagua Wilaya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Journey Info --}}
                        <div id="step2" class="form-step">
                            <h2 class="join-form-title" data-sw="Safari yako ya uzazi" data-en="Your motherhood journey">Safari yako ya uzazi</h2>
                            <label class="form-label-main" data-sw="Uko katika hali gani sasa? *" data-en="What is your current status? *">Uko katika hali gani sasa? *</label>
                            
                            <div class="status-options">
                                <label class="status-card">
                                    <input type="radio" name="status" value="pregnant" required>
                                    <div class="status-card-content">
                                        <span class="status-emoji">🤰</span>
                                        <div class="status-info">
                                            <span class="status-name" data-sw="Mjamzito" data-en="Pregnant">Mjamzito</span>
                                            <span class="status-desc" data-sw="Nasubiri mtoto" data-en="Expecting a baby">Nasubiri mtoto</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="status-card">
                                    <input type="radio" name="status" value="new_parent">
                                    <div class="status-card-content">
                                        <span class="status-emoji">👶</span>
                                        <div class="status-info">
                                            <span class="status-name" data-sw="Mzazi Mpya" data-en="New Parent">Mzazi Mpya</span>
                                            <span class="status-desc" data-sw="Mtoto wangu amezaliwa" data-en="My baby is born">Mtoto wangu amezaliwa</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="status-card">
                                    <input type="radio" name="status" value="trying">
                                    <div class="status-card-content">
                                        <span class="status-emoji">🌱</span>
                                        <div class="status-info">
                                            <span class="status-name" data-sw="Natafuta mtoto" data-en="Trying to conceive">Natafuta mtoto</span>
                                            <span class="status-desc" data-sw="Nataka kushika mimba" data-en="I want to get pregnant">Nataka kushika mimba</span>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            {{-- Pregnancy Extra Fields --}}
                            <div id="pregnant-fields" class="extra-fields animate__animated animate__fadeIn" style="display: none;">
                                <p class="helper-text" 
                                   data-sw="Ingiza tarehe unayotarajiwa kujifungua. Ukiwa hujui, daktari au mkunga wako anaweza kukusaidia." 
                                   data-en="Enter your expected due date. If you don't know, your doctor or midwife can help you.">Ingiza tarehe unayotarajiwa kujifungua. Ukiwa hujui, daktari au mkunga wako anaweza kukusaidia.</p>
                                
                                <div class="form-group">
                                    <label for="edd_date" data-sw="Tarehe ya kujifungua (EDD) *" data-en="Expected Due Date (EDD) *">Tarehe ya kujifungua (EDD) *</label>
                                    <input type="date" id="edd_date" name="edd_date">
                                </div>

                                <div id="edd-calculation" class="edd-result-card" style="display: none;">
                                    <div class="edd-result-grid">
                                        <div class="edd-stat">
                                            <span class="edd-stat-label" data-sw="Wiki" data-en="Weeks">Wiki</span>
                                            <span class="edd-stat-value" id="calc-weeks">0</span>
                                        </div>
                                        <div class="edd-stat">
                                            <span class="edd-stat-label">Trimester</span>
                                            <span class="edd-stat-value" id="calc-trimester">0</span>
                                        </div>
                                        <div class="edd-stat highlight">
                                            <span class="edd-stat-label" data-sw="Zimebaki" data-en="Remaining">Zimebaki</span>
                                            <span class="edd-stat-value" id="calc-days">0 Siku</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- New Parent Extra Fields --}}
                            <div id="new-parent-fields" class="extra-fields animate__animated animate__fadeIn" style="display: none;">
                                <label class="form-label-main" data-sw="Mtoto wako ana umri gani? *" data-en="How old is your baby? *">Mtoto wako ana umri gani? *</label>
                                <div class="age-grid">
                                    <label class="age-option">
                                        <input type="radio" name="baby_age" value="0">
                                        <div class="age-option-card">
                                            <span class="age-val" data-sw="Chini ya mwezi 1" data-en="Under 1 month">Chini ya mwezi 1</span>
                                        </div>
                                    </label>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <label class="age-option">
                                            <input type="radio" name="baby_age" value="{{ $i }}">
                                            <div class="age-option-card">
                                                <span class="age-val">{{ $i }}</span>
                                                <span class="age-unit" 
                                                      data-sw="{{ $i == 1 ? 'mwezi' : 'miezi' }}" 
                                                      data-en="{{ $i == 1 ? 'month' : 'months' }}">{{ $i == 1 ? 'mwezi' : 'miezi' }}</span>
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            {{-- Trying Extra Fields --}}
                            <div id="trying-fields" class="extra-fields animate__animated animate__fadeIn" style="display: none;">
                                <label class="form-label-main" data-sw="Umekuwa ukijaribu kwa muda gani? *" data-en="How long have you been trying? *">Umekuwa ukijaribu kwa muda gani? *</label>
                                <div class="trying-options">
                                    <label class="status-card mini">
                                        <input type="radio" name="trying_duration" value="recently">
                                        <div class="status-card-content">
                                            <span class="status-name" data-sw="Nimeanza hivi karibuni" data-en="Just started recently">Nimeanza hivi karibuni</span>
                                        </div>
                                    </label>
                                    <label class="status-card mini">
                                        <input type="radio" name="trying_duration" value="1-6_months">
                                        <div class="status-card-content">
                                            <span class="status-name" data-sw="Miezi 1 hadi 6" data-en="1 to 6 months">Miezi 1 hadi 6</span>
                                        </div>
                                    </label>
                                    <label class="status-card mini">
                                        <input type="radio" name="trying_duration" value="6-12_months">
                                        <div class="status-card-content">
                                            <span class="status-name" data-sw="Miezi 6 hadi 12" data-en="6 to 12 months">Miezi 6 hadi 12</span>
                                        </div>
                                    </label>
                                    <label class="status-card mini">
                                        <input type="radio" name="trying_duration" value="more_than_year">
                                        <div class="status-card-content">
                                            <span class="status-name" data-sw="Zaidi ya mwaka 1" data-en="More than 1 year">Zaidi ya mwaka 1</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Confirmation --}}
                        <div id="step3" class="form-step">
                            <h2 class="join-form-title" data-sw="Hakiki Taarifa Zako" data-en="Confirm Your Details">Hakiki Taarifa Zako</h2>
                            <p class="helper-text" 
                               data-sw="Tafadhali hakikisha taarifa ulizojaza ni sahihi kabla ya kukamilisha." 
                               data-en="Please make sure the details you provided are correct before finishing.">Tafadhali hakikisha taarifa ulizojaza ni sahihi kabla ya kukamilisha.</p>
                            
                            <div class="summary-card animate__animated animate__fadeIn">
                                <div class="summary-item">
                                    <span class="summary-label" data-sw="Jina:" data-en="Name:">Jina:</span>
                                    <span class="summary-value" id="summary-name">-</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label" data-sw="WhatsApp:" data-en="WhatsApp:">WhatsApp:</span>
                                    <span class="summary-value" id="summary-whatsapp">-</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label" data-sw="Mahali:" data-en="Location:">Mahali:</span>
                                    <span class="summary-value" id="summary-location">-</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label" data-sw="Hali yako:" data-en="Your status:">Hali yako:</span>
                                    <span class="summary-value" id="summary-status">-</span>
                                </div>
                                <div id="summary-extra-div" class="summary-item" style="display: none;">
                                    <span class="summary-label" id="summary-extra-label" data-sw="Maelezo:" data-en="Details:">Maelezo:</span>
                                    <span class="summary-value" id="summary-extra-value">-</span>
                                </div>
                            </div>

                            <div class="confirmation-check">
                                <label class="check-container">
                                    <input type="checkbox" required>
                                    <span class="checkmark"></span>
                                    <span class="check-text" 
                                          data-sw="Nakubali kupokea ujumbe na ushauri wa uzazi kutoka Mamacare AI kupitia WhatsApp yangu." 
                                          data-en="I agree to receive messages and motherhood advice from Mamacare AI through my WhatsApp.">Nakubali kupokea ujumbe na ushauri wa uzazi kutoka Mamacare AI kupitia WhatsApp yangu.</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" id="prevBtn" class="join-prev-btn" style="display: none;">
                                <svg viewBox="0 0 24 24" fill="none" width="20" height="20" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span data-sw="Rudi" data-en="Back">Rudi</span>
                            </button>
                            <button type="button" id="nextBtn" class="join-submit-btn">
                                <span data-sw="Endelea" data-en="Continue">Endelea</span>
                                <svg viewBox="0 0 24 24" fill="none" width="20" height="20" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Language Switching Logic
    const langBtns = document.querySelectorAll('.lang-btn');
    const localeInput = document.getElementById('current_locale');
    let currentLang = 'sw';

    function updateLanguage(lang) {
        currentLang = lang;
        localeInput.value = lang;
        document.querySelectorAll('[data-' + lang + ']').forEach(el => {
            el.textContent = el.getAttribute('data-' + lang);
        });

        document.querySelectorAll('input[data-' + lang + '-placeholder]').forEach(el => {
            el.placeholder = el.getAttribute('data-' + lang + '-placeholder');
        });

        document.querySelectorAll('option[data-' + lang + ']').forEach(el => {
            el.textContent = el.getAttribute('data-' + lang);
        });

        // Update active class on buttons
        langBtns.forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-lang') === lang);
        });

        updateStep(); // Refresh step text language
    }

    langBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            updateLanguage(btn.getAttribute('data-lang'));
        });
    });

    const regionSelect = document.getElementById('region');
    const districtSelect = document.getElementById('district');
    const joinForm = document.getElementById('joinForm');
    const steps = document.querySelectorAll('.form-step');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const progressBar = document.querySelector('.step-progress-bar');
    const stepText = document.querySelector('.step-text');
    let currentStep = 0;

    function updateStep() {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === currentStep);
        });

        prevBtn.style.display = currentStep === 0 ? 'none' : 'flex';
        
        const btnSpan = nextBtn.querySelector('span');
        if (currentStep === steps.length - 1) {
            btnSpan.textContent = currentLang === 'sw' ? 'Kamilisha' : 'Finish';
            prepareSummary();
        } else {
            btnSpan.textContent = currentLang === 'sw' ? 'Endelea' : 'Continue';
        }

        const progress = ((currentStep + 1) / steps.length) * 100;
        progressBar.style.width = `${progress}%`;
        
        const prefix = stepText.getAttribute('data-' + currentLang + '-prefix');
        const middleText = currentLang === 'sw' ? ' ya ' : ' of ';
        stepText.textContent = `${prefix} ${currentStep + 1}${middleText}${steps.length}`;
    }

    function prepareSummary() {
        const name = document.getElementById('full_name').value;
        const whatsapp = document.getElementById('whatsapp_number').value;
        const regionText = regionSelect.options[regionSelect.selectedIndex].text;
        const districtText = districtSelect.options[districtSelect.selectedIndex].text;
        const status = document.querySelector('input[name="status"]:checked');
        
        document.getElementById('summary-name').textContent = name;
        document.getElementById('summary-whatsapp').textContent = '+255 ' + whatsapp;
        document.getElementById('summary-location').textContent = `${regionText}, ${districtText}`;

        const extraDiv = document.getElementById('summary-extra-div');
        const extraLabel = document.getElementById('summary-extra-label');
        const extraVal = document.getElementById('summary-extra-value');

        if (status) {
            const statusMapSw = {
                'pregnant': 'Mjamzito',
                'new_parent': 'Mzazi Mpya',
                'trying': 'Natafuta Mtoto'
            };
            const statusMapEn = {
                'pregnant': 'Pregnant',
                'new_parent': 'New Parent',
                'trying': 'Trying to conceive'
            };
            
            document.getElementById('summary-status').textContent = currentLang === 'sw' ? statusMapSw[status.value] : statusMapEn[status.value];

            if (status.value === 'pregnant') {
                extraDiv.style.display = 'flex';
                extraLabel.textContent = currentLang === 'sw' ? 'EDD:' : 'EDD:';
                extraVal.textContent = document.getElementById('edd_date').value;
            } else if (status.value === 'new_parent') {
                extraDiv.style.display = 'flex';
                extraLabel.textContent = currentLang === 'sw' ? 'Umri wa Mtoto:' : 'Baby Age:';
                const age = document.querySelector('input[name="baby_age"]:checked');
                if (age) {
                    if (age.value == '0') {
                        extraVal.textContent = currentLang === 'sw' ? 'Chini ya mwezi 1' : 'Under 1 month';
                    } else {
                        const unit = currentLang === 'sw' ? (age.value == '1' ? ' mwezi' : ' miezi') : (age.value == '1' ? ' month' : ' months');
                        extraVal.textContent = age.value + unit;
                    }
                } else {
                    extraVal.textContent = '-';
                }
            } else if (status.value === 'trying') {
                extraDiv.style.display = 'flex';
                extraLabel.textContent = currentLang === 'sw' ? 'Muda wa kujaribu:' : 'Trying duration:';
                const duration = document.querySelector('input[name="trying_duration"]:checked');
                const durationMapSw = {
                    'recently': 'Hivi karibuni',
                    '1-6_months': 'Miezi 1-6',
                    '6-12_months': 'Miezi 6-12',
                    'more_than_year': 'Zaidi ya mwaka 1'
                };
                const durationMapEn = {
                    'recently': 'Recently',
                    '1-6_months': '1-6 Months',
                    '6-12_months': '6-12 Months',
                    'more_than_year': 'More than 1 year'
                };
                extraVal.textContent = duration ? (currentLang === 'sw' ? durationMapSw[duration.value] : durationMapEn[duration.value]) : '-';
            } else {
                extraDiv.style.display = 'none';
            }
        }
    }

    const whatsappInput = document.getElementById('whatsapp_number');

    // Phone Number Formatting & Validation
    whatsappInput.addEventListener('input', function(e) {
        let val = e.target.value.replace(/\D/g, ''); // Remove non-digits
        
        // Handle 0 prefix (e.g., 06... -> 6...)
        if (val.startsWith('0')) {
            val = val.substring(1);
        }
        
        // Limit to 9 digits
        if (val.length > 9) {
            val = val.substring(0, 9);
        }
        
        e.target.value = val;
    });

    nextBtn.addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            // Basic validation for current step
            const activeStep = steps[currentStep];
            const inputs = activeStep.querySelectorAll('input[required], select[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.value) {
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Specific validation for WhatsApp number length (must be 9 digits after formatting)
            if (currentStep === 0 && whatsappInput.value.length !== 9) {
                whatsappInput.classList.add('is-invalid');
                alert(currentLang === 'sw' ? 'Tafadhali ingiza namba ya simu sahihi (tarakimu 9).' : 'Please enter a valid phone number (9 digits).');
                valid = false;
            }

            if (valid) {
                currentStep++;
                updateStep();
            }
        } else {
            // Check final consent
            const consent = document.querySelector('#step3 input[type="checkbox"]');
            if (consent.checked) {
                // Submit the form normally
                nextBtn.disabled = true;
                nextBtn.querySelector('span').textContent = currentLang === 'sw' ? 'Inatuma...' : 'Submitting...';
                joinForm.submit();
            } else {
                alert(currentLang === 'sw' ? 'Tafadhali kubali vigezo ili kuendelea.' : 'Please accept the terms to continue.');
            }
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            updateStep();
        }
    });

    // Step 2 Logic: Show/Hide Pregnancy Fields
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const pregnantFields = document.getElementById('pregnant-fields');
    const newParentFields = document.getElementById('new-parent-fields');
    const tryingFields = document.getElementById('trying-fields');
    const eddDateInput = document.getElementById('edd_date');
    const eddResult = document.getElementById('edd-calculation');

    // Phone prefix (Default TZ)
    const prefixEl = document.getElementById('phone_prefix');
    if (prefixEl && !prefixEl.textContent.trim()) {
        prefixEl.textContent = '+255';
    }

    statusRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            // Hide all extra fields first
            pregnantFields.style.display = 'none';
            newParentFields.style.display = 'none';
            tryingFields.style.display = 'none';
            eddDateInput.required = false;

            if (e.target.value === 'pregnant') {
                pregnantFields.style.display = 'block';
                eddDateInput.required = true;
            } else if (e.target.value === 'new_parent') {
                newParentFields.style.display = 'block';
            } else if (e.target.value === 'trying') {
                tryingFields.style.display = 'block';
            }
        });
    });

    // EDD Calculation Logic
    eddDateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        
        if (isNaN(selectedDate.getTime())) return;

        const timeDiff = selectedDate - today;
        const daysLeft = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

        if (daysLeft < 0) {
            eddResult.style.display = 'none';
            return;
        }

        // Pregnancy is usually 280 days (40 weeks)
        // Calculate weeks pregnant based on EDD
        const totalPregnancyDays = 280;
        const daysPregnant = totalPregnancyDays - daysLeft;
        const weeksPregnant = Math.max(0, Math.floor(daysPregnant / 7));

        // Determine Trimester
        let trimester = 1;
        if (weeksPregnant > 27) trimester = 3;
        else if (weeksPregnant > 13) trimester = 2;

        document.getElementById('calc-weeks').textContent = weeksPregnant;
        document.getElementById('calc-trimester').textContent = trimester;
        const unit = currentLang === 'sw' ? ' Siku' : ' Days';
        document.getElementById('calc-days').textContent = `${daysLeft}${unit}`;
        
        eddResult.style.display = 'block';
    });

    regionSelect.addEventListener('change', async function() {
        const regionId = this.value;
        
        // Reset and disable district select
        districtSelect.innerHTML = `<option value="" selected disabled>${currentLang === 'sw' ? 'Inapakia...' : 'Loading...'}</option>`;
        districtSelect.disabled = true;

        try {
            const response = await fetch(`/api/regions/${regionId}/districts`);
            const districts = await response.json();

            districtSelect.innerHTML = `<option value="" selected disabled>${currentLang === 'sw' ? 'Chagua Wilaya' : 'Select District'}</option>`;
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
        } catch (error) {
            console.error('Error fetching districts:', error);
            districtSelect.innerHTML = `<option value="" selected disabled>${currentLang === 'sw' ? 'Error kupakia wilaya' : 'Error loading districts'}</option>`;
        }
    });
});
</script>
@endsection
