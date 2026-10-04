<x-layouts.guest>
    <x-slot:title>How CareMate BD Works — Safe, Admin-Mediated Caregiving in Bangladesh</x-slot:title>
    <x-slot:description>Learn why CareMate BD is Bangladesh's most trusted caregiver network. Discover our background verification, escrow model, and dedicated Care Managers.</x-slot:description>

    <div class="container how-it-works-container">
        <div class="page-intro-header">
            <div class="page-intro-tag">
                {{ __('Safety Architecture') }}
            </div>
            <h1 class="page-intro-title">
                {{ __('Care with Complete Peace of Mind') }}
            </h1>
            <p class="page-intro-subtitle">
                {{ __('We engineered CareMate BD to solve the deep anxiety families face when welcoming outside caregivers into their homes. Here is how our admin-mediated system protects everyone.') }}
            </p>
        </div>

        <!-- 4 Steps Detailed Grid -->
        <div class="steps-detail-wrapper">
            <!-- Step 1 -->
            <div class="glass-card step-detail-card">
                <div class="step-badge step-badge-1">
                    01
                </div>
                <div class="step-body">
                    <h3>
                        {{ __('Rigorous 9-Step Verification & Clinical Screening') }}
                    </h3>
                    <p>
                        {{ __('Before any caregiver appears on our marketplace, their government National ID card is cross-referenced with national databases. We verify local police clearance certificates, clinical nurse diplomas, reference checks with past employers, and in-person interviews at our Care Operations Center in ICT Tower, Agargaon.') }}
                    </p>
                    <div class="step-checklist" style="color: #059669;">
                        <span>✓ {{ __('Government NID verification') }}</span>
                        <span>✓ {{ __('Police clearance certificate') }}</span>
                        <span>✓ {{ __('CPR & First-aid certification check') }}</span>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="glass-card step-detail-card">
                <div class="step-badge step-badge-2">
                    02
                </div>
                <div class="step-body">
                    <h3>
                        {{ __('Admin-Mediated Matching & Schedule Confirmation') }}
                    </h3>
                    <p>
                        {{ __('When you select a caregiver and submit your booking request, our Care Coordinators personally evaluate your patient\'s mobility, medical requirements, and specific dietary needs. We confirm the caregiver\'s availability and review the care plan before sending the assignment to the caregiver.') }}
                    </p>
                    <div class="step-checklist" style="color: #0a394a;">
                        <span>✓ {{ __('No spam calls') }}</span>
                        <span>✓ {{ __('Zero harassment guarantee') }}</span>
                        <span>✓ {{ __('Objective medical triage') }}</span>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="glass-card step-detail-card">
                <div class="step-badge step-badge-3">
                    03
                </div>
                <div class="step-body">
                    <h3>
                        {{ __('Escrow Protection (bKash, Nagad & Bank)') }}
                    </h3>
                    <p>
                        {{ __('You never hand over cash directly to unfamiliar staff. Payments are held in secure CareMate escrow. If a caregiver fails to report or fails to satisfy your standards, our Care Managers immediately initiate a replacement or refund according to our service guarantee.') }}
                    </p>
                    <div class="step-checklist" style="color: #d97706;">
                        <span>✓ {{ __('bKash Merchant Checkout') }}</span>
                        <span>✓ {{ __('Nagad & Mobile Banking') }}</span>
                        <span>✓ {{ __('Official Money Receipts & Invoices') }}</span>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="glass-card step-detail-card">
                <div class="step-badge step-badge-4">
                    04
                </div>
                <div class="step-body">
                    <h3>
                        {{ __('24/7 Care Coordination & Replacement Guarantee') }}
                    </h3>
                    <p>
                        {{ __('Throughout the booking duration, our support team monitors daily attendance. In the rare event of caregiver illness or personal emergency, our standby pool deploys an experienced replacement caregiver to your home within 4 hours.') }}
                    </p>
                    <div class="step-checklist" style="color: #0d9488;">
                        <span>✓ {{ __('4-Hour replacement commitment') }}</span>
                        <span>✓ {{ __('Daily shift attendance tracking') }}</span>
                        <span>✓ {{ __('24/7 Emergency CareDesk Hotline') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Banner -->
        <div class="glass-card cta-banner-card">
            <h3 class="cta-banner-title">
                {{ __('Ready to find compassionate care for your family?') }}
            </h3>
            <p class="cta-banner-subtitle">
                {{ __('Explore verified profiles in your area or speak with our senior care coordinator today.') }}
            </p>
            <div class="cta-banner-actions">
                <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-lg">{{ __('Browse Verified Caregivers') }}</a>
                <a href="{{ route('contact') }}" class="btn btn-secondary btn-lg">{{ __('Contact Care Desk') }}</a>
            </div>
        </div>
    </div>
</x-layouts.guest>
