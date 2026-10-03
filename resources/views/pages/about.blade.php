<x-layouts.guest>
    <x-slot:title>About Us — CareMate BD | Transforming Family Care in Bangladesh</x-slot:title>
    <x-slot:description>CareMate BD was founded with a mission to bring dignity, safety, and reliability to caregiver recruitment across Bangladesh.</x-slot:description>

    <div class="container" style="padding: 3rem 1.25rem 5rem 1.25rem;">
        <div style="text-align: center; max-width: 750px; margin: 0 auto 3.5rem auto;">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(10, 57, 74, 0.08); border: 1px solid rgba(10, 57, 74, 0.2); padding: 0.4rem 1rem; border-radius: var(--radius-pill); font-size: 0.82rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: var(--brand-primary); margin-bottom: 0.75rem;">
                A Concern of Techboloy
            </div>
            <h1 style="font-size: 2.8rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                Dignified Care for Bangladesh Families
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.15rem; line-height: 1.65;">
                Bridging the gap between families in need of compassionate, reliable support and verified healthcare workers seeking dignified, protected employment.
            </p>
        </div>

        <!-- Mission Cards -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-bottom: 4rem;">
            <div class="glass-card" style="padding: 2.5rem; border-radius: var(--radius-xl);">
                <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(10, 57, 74, 0.1); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                    ❤️
                </div>
                <h3 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">
                    For Families
                </h3>
                <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7;">
                    Finding care for an ailing parent or newborn should not be a gamble with informal brokers or unverified social media posts. We provide certified background checks, admin supervision, transparent daily rates, and immediate replacement guarantees so you never have to worry.
                </p>
            </div>

            <div class="glass-card" style="padding: 2.5rem; border-radius: var(--radius-xl);">
                <div style="width: 50px; height: 50px; border-radius: 14px; background: rgba(16, 185, 129, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                    🤝
                </div>
                <h3 style="font-size: 1.45rem; font-weight: 800; color: #0f172a; margin-bottom: 0.75rem;">
                    For Caregivers
                </h3>
                <p style="color: var(--text-secondary); font-size: 1rem; line-height: 1.7;">
                    Caregivers and nurses perform essential, noble work. On CareMate BD, caregivers enjoy guaranteed timely payments into their bKash/Nagad accounts, protection against harassment, formal shift tracking, and professional skill enhancement.
                </p>
            </div>
        </div>

        <!-- Operational Standards -->
        <div class="glass-card" style="padding: 3rem; border-radius: var(--radius-xl); margin-bottom: 4rem;">
            <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; text-align: center;">
                Our Three Core Pillars
            </h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
                <div>
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: #0a394a; margin-bottom: 0.5rem;">1. Integrity</h4>
                    <p style="font-size: 0.92rem; color: var(--text-secondary); line-height: 1.6;">
                        Strict adherence to document verification. No falsified credentials or unvetted staff are ever permitted on our network.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: #059669; margin-bottom: 0.5rem;">2. Empathy</h4>
                    <p style="font-size: 0.92rem; color: var(--text-secondary); line-height: 1.6;">
                        Every patient is treated with familial respect, tenderness, and customized daily care regimens designed for their comfort.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: #d97706; margin-bottom: 0.5rem;">3. Accountability</h4>
                    <p style="font-size: 0.92rem; color: var(--text-secondary); line-height: 1.6;">
                        Our CareDesk mediation ensures complete dispute resolution, transparent pricing, and 100% adherence to scheduled duty hours.
                    </p>
                </div>
            </div>
        </div>

        <!-- Headquarters Info -->
        <div class="glass-card" style="padding: 2.5rem; border-radius: var(--radius-xl); text-align: center;">
            <div style="font-size: 0.85rem; font-weight: 700; color: #15798e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem;">
                A Concern of Techboloy
            </div>
            <h3 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                CareMate Operations Center
            </h3>
            <p style="font-size: 1rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
                E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh<br>
                Contact: +880 1610-296460 (24/7 Hotline) • Email: contact@carematebd.com
            </p>
            <a href="{{ route('contact') }}" class="btn btn-primary">Get In Touch With Our Team</a>
        </div>
    </div>
</x-layouts.guest>
