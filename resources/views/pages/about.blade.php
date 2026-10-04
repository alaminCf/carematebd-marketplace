<x-layouts.guest>
    <x-slot:title>About Us — CareMate BD | Transforming Family Care in Bangladesh</x-slot:title>
    <x-slot:description>CareMate BD was founded with a mission to bring dignity, safety, and reliability to caregiver recruitment across Bangladesh.</x-slot:description>

    <div class="container about-page-container">
        <div class="page-intro-header">
            <div class="page-intro-tag">
                {{ __('A Concern of Techboloy') }}
            </div>
            <h1 class="page-intro-title">
                {{ __('Dignified Care for Bangladesh Families') }}
            </h1>
            <p class="page-intro-subtitle">
                {{ __('Bridging the gap between families in need of compassionate, reliable support and verified healthcare workers seeking dignified, protected employment.') }}
            </p>
        </div>

        <!-- Mission Cards -->
        <div class="about-mission-grid">
            <div class="glass-card about-card">
                <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(10, 57, 74, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                    ❤️
                </div>
                <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">
                    {{ __('For Families') }}
                </h3>
                <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.7;">
                    {{ __('Finding care for an ailing parent or newborn should not be a gamble with informal brokers or unverified social media posts. We provide certified background checks, admin supervision, transparent daily rates, and immediate replacement guarantees so you never have to worry.') }}
                </p>
            </div>

            <div class="glass-card about-card">
                <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                    🤝
                </div>
                <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">
                    {{ __('For Caregivers') }}
                </h3>
                <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.7;">
                    {{ __('Caregivers and nurses perform essential, noble work. On CareMate BD, caregivers enjoy guaranteed timely payments into their bKash/Nagad accounts, protection against harassment, formal shift tracking, and professional skill enhancement.') }}
                </p>
            </div>
        </div>

        <!-- Operational Standards -->
        <div class="glass-card about-pillars-card">
            <h2 style="font-size: 1.85rem; font-weight: 800; color: #0f172a; margin-bottom: 1.75rem; text-align: center;">
                {{ __('Our Three Core Pillars') }}
            </h2>
            <div class="about-pillars-grid">
                <div>
                    <h4 style="font-size: 1.15rem; font-weight: 700; color: #0a394a; margin-bottom: 0.5rem;">1. {{ __('Integrity') }}</h4>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                        {{ __('Strict adherence to document verification. No falsified credentials or unvetted staff are ever permitted on our network.') }}
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1.15rem; font-weight: 700; color: #059669; margin-bottom: 0.5rem;">2. {{ __('Empathy') }}</h4>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                        {{ __('Every patient is treated with familial respect, tenderness, and customized daily care regimens designed for their comfort.') }}
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1.15rem; font-weight: 700; color: #d97706; margin-bottom: 0.5rem;">3. {{ __('Accountability') }}</h4>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6;">
                        {{ __('Our CareDesk mediation ensures complete dispute resolution, transparent pricing, and 100% adherence to scheduled duty hours.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Headquarters Info -->
        <div class="glass-card about-hq-card">
            <div style="font-size: 0.85rem; font-weight: 700; color: #15798e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                {{ __('A Concern of Techboloy') }}
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                {{ __('CareMate Operations Center') }}
            </h3>
            <p style="font-size: 0.95rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
                {{ __('E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh') }}<br>
                {{ __('Contact: +880 1610-296460 (24/7 Hotline) • Email: contact@carematebd.com') }}
            </p>
            <a href="{{ route('contact') }}" class="btn btn-primary" style="padding: 0.85rem 2rem;">{{ __('Contact Care Desk') }}</a>
        </div>
    </div>
</x-layouts.guest>
