<x-layouts.guest>
    <x-slot:title>Frequently Asked Questions — CareMate BD</x-slot:title>
    <x-slot:description>Got questions about caregiver hiring, safety checks, payments, or working with CareMate BD? Find answers here.</x-slot:description>

    <div class="container container-narrow" style="padding: 3rem 1.25rem 5rem 1.25rem;">
        <div style="text-align: center; margin-bottom: 3.5rem;">
            <div style="display: inline-block; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--brand-primary); margin-bottom: 0.5rem;">
                Help & Answers
            </div>
            <h1 style="font-size: 2.8rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                Frequently Asked Questions
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; line-height: 1.6;">
                Everything you need to know about our verification standard, admin mediation, and booking process.
            </p>
        </div>

        <!-- Section 1: For Families & Clients -->
        <div style="margin-bottom: 3.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(10, 57, 74, 0.1); display: flex; align-items: center; justify-content: center; color: #0a394a; font-weight: 800;">
                    1
                </div>
                <h2 style="font-size: 1.6rem; font-weight: 800; color: #0f172a;">Questions from Families & Clients</h2>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse ($clientFaqs as $faq)
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            {{ $faq->question }}
                        </h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.65;">
                            {{ $faq->answer }}
                        </p>
                    </div>
                @empty
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            How do you verify caregivers?
                        </h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.65;">
                            Every caregiver must upload their Government National ID (NID), police clearance certificate, past employer references, and clinical certificates. Our admin team verifies each document manually before approving any profile.
                        </p>
                    </div>
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            Why can't I call the caregiver directly?
                        </h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.65;">
                            To prevent harassment, wage theft, unverified no-shows, and safety issues, CareMate BD operates on a strictly admin-mediated model. All scheduling and coordination is supervised by our Care Desk.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Section 2: For Caregivers -->
        <div style="margin-bottom: 3.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(16, 185, 129, 0.15); display: flex; align-items: center; justify-content: center; color: #059669; font-weight: 800;">
                    2
                </div>
                <h2 style="font-size: 1.6rem; font-weight: 800; color: #0f172a;">Questions from Caregivers & Nurses</h2>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse ($caregiverFaqs as $faq)
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            {{ $faq->question }}
                        </h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.65;">
                            {{ $faq->answer }}
                        </p>
                    </div>
                @empty
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            How and when do I receive payment?
                        </h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.65;">
                            Payments are guaranteed through our escrow system. Once the client verifies completed duty hours, earnings are credited to your CareMate balance and you can request instant withdrawal to your bKash, Nagad, or Bank account.
                        </p>
                    </div>
                    <div class="glass-card" style="padding: 1.5rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
                            What happens if a client asks for extra tasks outside the care agreement?
                        </h4>
                        <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.65;">
                            You should immediately notify your CareMate Care Coordinator through your support portal. We ensure duties are clearly bounded by your job specification.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Help box -->
        <div class="glass-card" style="text-align: center; padding: 2.5rem; border-radius: var(--radius-xl);">
            <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                Still have a question?
            </h3>
            <p style="font-size: 0.95rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                Our care specialists in Agargaon, Dhaka are on standby 24/7 to address any inquiry.
            </p>
            <a href="{{ route('contact') }}" class="btn btn-primary">Reach Out to Care Desk</a>
        </div>
    </div>
</x-layouts.guest>
