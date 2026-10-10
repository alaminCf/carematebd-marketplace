<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CareMate BD — Trusted Caregivers for Elderly, Child, & Nursing Care in Bangladesh' }}</title>
    <meta name="description" content="{{ $description ?? 'CareMate BD is Bangladesh\'s #1 admin-mediated caregiver marketplace. Connect with background-checked caregivers for Elderly Care, Baby Care, Patient Nursing & Medical Transport across Dhaka and all Bangladesh.' }}">
    <meta name="keywords" content="{{ $keywords ?? 'caregiver in bangladesh, elderly care dhaka, baby care dhaka, patient care nurse bangladesh, home nursing service dhaka, background checked caregiver bd, caremate bd, best caregiver agency dhaka, home care service bangladesh, nanny service dhaka' }}">
    <meta name="author" content="CareMate BD">
    <meta name="robots" content="{{ $robots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    <!-- Alternate Language Hreflang Tags (Multilingual SEO) -->
    <link rel="alternate" hreflang="en" href="{{ route('locale.switch', 'en') }}">
    <link rel="alternate" hreflang="bn" href="{{ route('locale.switch', 'bn') }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    <!-- Open Graph (Facebook, WhatsApp, LinkedIn, AI Search) -->
    <meta property="og:site_name" content="CareMate BD">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $title ?? 'CareMate BD — Trusted Caregivers for Elderly, Child, & Nursing Care in Bangladesh' }}">
    <meta property="og:description" content="{{ $description ?? 'Verified, background-checked caregivers across Bangladesh. Elderly Care, Child Care, Nursing Care & Medical Transportation.' }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/hero_caregiver.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="CareMate BD Verified Caregivers in Bangladesh">
    <meta property="og:locale" content="{{ app()->getLocale() === 'bn' ? 'bn_BD' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ app()->getLocale() === 'bn' ? 'en_US' : 'bn_BD' }}">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@CareMateBD">
    <meta name="twitter:creator" content="@CareMateBD">
    <meta name="twitter:title" content="{{ $title ?? 'CareMate BD — Trusted Caregivers in Bangladesh' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Verified, background-checked caregivers across Dhaka and Bangladesh.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/hero_caregiver.jpg') }}">

    <!-- Structured Data (JSON-LD) Global Schemas for Google AI Overviews & Knowledge Graph -->
    <!-- Structured Data (JSON-LD) Global Schemas for Google AI Overviews & Knowledge Graph -->
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '/#organization',
                'name' => 'CareMate BD',
                'alternateName' => ['CareMate', 'কেয়ারমেট বিডি', 'CareMate Bangladesh'],
                'url' => url('/'),
                'logo' => [
                    '@type' => 'ImageObject',
                    '@id' => url('/') . '/#logo',
                    'url' => asset('images/logo.png'),
                    'caption' => 'CareMate BD Logo',
                ],
                'image' => asset('images/hero_caregiver.jpg'),
                'description' => "CareMate BD is Bangladesh's premier admin-mediated caregiver network providing verified, background-checked caregivers for elderly care, child supervision, clinical nursing, and patient medical transport.",
                'telephone' => '+8801610296460',
                'email' => 'contact@carematebd.com',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'E-14/X, ICT Tower (14th Floor), Agargaon',
                    'addressLocality' => 'Dhaka',
                    'postalCode' => '1207',
                    'addressCountry' => 'BD',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => '23.777176',
                    'longitude' => '90.376840',
                ],
                'areaServed' => [
                    ['@type' => 'City', 'name' => 'Dhaka'],
                    ['@type' => 'City', 'name' => 'Chattogram'],
                    ['@type' => 'City', 'name' => 'Sylhet'],
                    ['@type' => 'Country', 'name' => 'Bangladesh'],
                ],
                'sameAs' => [
                    'https://facebook.com/carematebd',
                    'https://twitter.com/carematebd',
                    'https://linkedin.com/company/carematebd',
                ],
                'priceRange' => '৳৳',
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '/#website',
                'url' => url('/'),
                'name' => 'CareMate BD',
                'publisher' => ['@id' => url('/') . '/#organization'],
                'inLanguage' => ['en', 'bn'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => route('marketplace.index') . '?search={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type' => 'MedicalBusiness',
                '@id' => url('/') . '/#localbusiness',
                'name' => 'CareMate BD Care Coordination Desk',
                'image' => asset('images/hero_caregiver.jpg'),
                'telephone' => '+8801610296460',
                'priceRange' => '৳৳',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'E-14/X, ICT Tower (14th Floor), Agargaon',
                    'addressLocality' => 'Dhaka',
                    'addressRegion' => 'Dhaka Division',
                    'postalCode' => '1207',
                    'addressCountry' => 'BD',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => '23.777176',
                    'longitude' => '90.376840',
                ],
                'openingHoursSpecification' => [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => [
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                        'Saturday',
                        'Sunday',
                    ],
                    'opens' => '00:00',
                    'closes' => '23:59',
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>

    @stack('schema')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/caremate.css') }}?v={{ file_exists(public_path('css/caremate.css')) ? filemtime(public_path('css/caremate.css')) : '3.1.0' }}">
    <style>
        html, body {
            overflow-x: clip !important;
        }
        .glass-header {
            position: sticky !important;
            top: 0 !important;
            z-index: 1000 !important;
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border-bottom: 1px solid rgba(226, 232, 240, 0.85) !important;
            box-shadow: 0 4px 20px rgba(10, 57, 74, 0.06) !important;
        }
        .header-container {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
            width: 100% !important;
            padding: 0.65rem 1.25rem !important;
        }
        .header-brand-logo {
            display: flex !important;
            align-items: center !important;
            flex-shrink: 0 !important;
        }
        .header-brand-logo img, .brand-logo-img {
            height: 40px !important;
            max-height: 40px !important;
            width: auto !important;
            object-fit: contain !important;
        }
        .desktop-nav {
            display: flex !important;
            align-items: center !important;
            gap: clamp(0.75rem, 1.2vw, 1.35rem) !important;
            flex-shrink: 0 !important;
        }
        .desktop-nav a {
            white-space: nowrap !important;
            font-weight: 600 !important;
            color: var(--text-secondary, #475569) !important;
            font-size: 0.92rem !important;
            text-decoration: none !important;
            padding: 0.35rem 0.2rem !important;
            display: inline-flex !important;
            align-items: center !important;
        }
        .desktop-nav a:hover {
            color: var(--primary, #0a394a) !important;
        }
        .header-actions {
            display: flex !important;
            align-items: center !important;
            gap: 0.6rem !important;
            flex-shrink: 0 !important;
        }
        .desktop-actions {
            display: flex !important;
            align-items: center !important;
            gap: 0.45rem !important;
            flex-shrink: 0 !important;
        }
        .desktop-actions .btn, .header-whatsapp-btn {
            white-space: nowrap !important;
            font-size: 0.83rem !important;
        }
        @media (max-width: 1180px) {
            .desktop-nav, .desktop-actions {
                display: none !important;
            }
            .mobile-menu-btn {
                display: inline-flex !important;
            }
        }

        /* Auto-hide bottom dock when typing or keyboard is open */
        .mobile-app-dock.dock-keyboard-hidden,
        body:has(input:focus):not(:has(input[type="checkbox"]:focus)):not(:has(input[type="radio"]:focus)) .mobile-app-dock,
        body:has(textarea:focus) .mobile-app-dock,
        body:has(select:focus) .mobile-app-dock {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            transform: translateY(100%) !important;
        }
        @media (max-height: 480px) {
            .mobile-app-dock {
                display: none !important;
            }
        }

        /* Mobile Caregiver Profile Centering & Header CTA */
        .caregiver-name-wrap {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .caregiver-id-pill {
            display: inline-flex;
            align-items: center;
            background: rgba(10, 57, 74, 0.06);
            border: 1px solid rgba(10, 57, 74, 0.12);
            color: var(--brand-primary, #0a394a);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.35rem 0.8rem;
            border-radius: 9999px;
            letter-spacing: 0.03em;
        }

        .caregiver-mobile-booking-box {
            display: none;
        }

        /* Site Footer Base Styles */
        .site-footer {
            margin-top: 5rem;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            padding: 4rem 0 2rem 0;
        }

        .site-footer .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 2fr 1.3fr;
            gap: 3rem;
            margin-bottom: 2.5rem;
            align-items: flex-start;
        }

        .footer-brand-col {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-brand-logo {
            display: inline-block;
            text-decoration: none;
        }

        .footer-logo-img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .footer-concern-tag {
            font-size: 0.82rem;
            font-weight: 700;
            color: #15798e;
            letter-spacing: 0.02em;
        }

        .footer-brand-desc {
            color: var(--text-secondary, #475569);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 320px;
            margin-bottom: 0.5rem;
        }

        .footer-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(10, 57, 74, 0.06);
            border: 1px solid rgba(10, 57, 74, 0.12);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #0a394a;
            width: fit-content;
        }

        .footer-links-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .footer-col {
            min-width: 0;
        }

        .footer-heading {
            font-size: 0.92rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted, #64748b);
            margin-bottom: 1.1rem;
        }

        .footer-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            font-size: 0.9rem;
            margin: 0;
            padding: 0;
        }

        .footer-list a {
            color: var(--text-secondary, #475569);
            text-decoration: none;
            transition: color 0.18s;
        }

        .footer-list a:hover {
            color: var(--brand-primary, #0a394a);
        }

        .footer-browse-link {
            color: var(--brand-primary, #0a394a) !important;
            font-weight: 700 !important;
        }

        .footer-contact-col {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .footer-address-text {
            font-size: 0.88rem;
            color: var(--text-secondary, #475569);
            line-height: 1.5;
            margin-bottom: 0.4rem;
        }

        .footer-contact-chips-grid {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-contact-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.4rem 0.75rem;
            border-radius: 9999px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--brand-primary, #0a394a);
            text-decoration: none;
            width: fit-content;
            transition: all 0.2s;
        }

        .footer-contact-chip:hover {
            background: #e6f1f4;
            border-color: rgba(10, 57, 74, 0.25);
        }

        .footer-chip-whatsapp {
            background: rgba(37, 211, 102, 0.1);
            color: #0d873d;
            border-color: rgba(37, 211, 102, 0.35);
        }

        .footer-chip-whatsapp:hover {
            background: rgba(37, 211, 102, 0.2);
        }

        .footer-operating-hours {
            font-size: 0.78rem;
            color: var(--text-muted, #64748b);
            margin-top: 0.25rem;
        }

        .footer-bottom-row {
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            padding-top: 1.5rem;
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.84rem;
            color: var(--text-muted, #64748b);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-legal-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .footer-legal-links a {
            color: var(--text-muted, #64748b);
            text-decoration: none;
        }

        .footer-legal-links a:hover {
            color: var(--brand-primary, #0a394a);
        }

        @media (max-width: 768px) {
            .caregiver-profile-card {
                padding: 1.5rem 1rem !important;
                margin-bottom: 1.5rem !important;
            }

            .caregiver-header-flex {
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                gap: 1.15rem !important;
            }

            .caregiver-avatar-col {
                display: flex !important;
                justify-content: center !important;
                width: 100% !important;
                margin: 0 auto !important;
            }

            .caregiver-avatar-wrapper,
            .caregiver-avatar-img {
                width: 110px !important;
                height: 110px !important;
                margin: 0 auto !important;
            }

            .caregiver-info-col {
                width: 100% !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
            }

            .caregiver-title-row {
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 0.45rem !important;
                margin-bottom: 0.5rem !important;
                width: 100% !important;
                text-align: center !important;
            }

            .caregiver-name-wrap {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
                gap: 0.35rem !important;
                width: 100% !important;
            }

            .caregiver-name {
                font-size: 1.85rem !important;
                text-align: center !important;
                margin: 0 auto !important;
                width: 100% !important;
            }

            .badge-verified-tag {
                margin: 0 auto !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: 0.35rem !important;
            }

            .caregiver-id-pill {
                margin: 0.2rem auto !important;
                display: inline-flex !important;
                align-items: center !important;
                text-align: center !important;
            }

            .caregiver-serving-location {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
                width: 100% !important;
                gap: 0.35rem !important;
                font-size: 0.92rem !important;
                margin: 0.4rem auto 0.9rem auto !important;
            }

            .caregiver-stats-badges {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 0.55rem !important;
                width: 100% !important;
                margin: 0 auto 1.1rem auto !important;
            }

            .caregiver-stat-pill:first-child {
                grid-column: span 2 !important;
                justify-content: center !important;
                align-items: center !important;
                text-align: center !important;
                background: rgba(255, 255, 255, 0.85) !important;
                border: 1px solid rgba(226, 232, 240, 0.9) !important;
                padding: 0.6rem 0.85rem !important;
            }

            .caregiver-stat-pill:not(:first-child) {
                justify-content: center !important;
                align-items: center !important;
                text-align: center !important;
                padding: 0.55rem 0.45rem !important;
                font-size: 0.82rem !important;
            }

            .caregiver-trust-badges {
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: center !important;
                gap: 0.4rem !important;
                width: 100% !important;
                margin: 0 auto !important;
            }

            .trust-badge {
                font-size: 0.76rem !important;
                padding: 0.3rem 0.6rem !important;
                justify-content: center !important;
                text-align: center !important;
            }

            /* Mobile Top Booking CTA Box in profile header */
            .caregiver-mobile-booking-box {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.75rem !important;
                width: 100% !important;
                margin-top: 1.25rem !important;
                padding-top: 1.25rem !important;
                border-top: 1.5px solid rgba(226, 232, 240, 0.9) !important;
            }

            .mobile-booking-rate-row {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                background: rgba(10, 57, 74, 0.05) !important;
                border: 1px solid rgba(10, 57, 74, 0.12) !important;
                border-radius: 14px !important;
                padding: 0.65rem 1rem !important;
                width: 100% !important;
            }

            .mobile-booking-rate-left {
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
            }

            .mobile-booking-rate-label {
                font-size: 0.72rem !important;
                text-transform: uppercase !important;
                font-weight: 700 !important;
                color: #64748b !important;
                letter-spacing: 0.04em !important;
            }

            .mobile-booking-rate-val {
                font-size: 1.45rem !important;
                font-weight: 800 !important;
                color: #0f172a !important;
                line-height: 1.1 !important;
            }

            .mobile-booking-rate-sub {
                font-size: 0.82rem !important;
                font-weight: 600 !important;
                color: #64748b !important;
            }

            .mobile-booking-monthly-tag {
                font-size: 0.78rem !important;
                font-weight: 700 !important;
                color: #0a394a !important;
                background: #ffffff !important;
                padding: 0.35rem 0.65rem !important;
                border-radius: 9999px !important;
                border: 1px solid rgba(10, 57, 74, 0.12) !important;
            }

            .mobile-booking-actions {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.65rem !important;
                width: 100% !important;
            }

            .mobile-book-btn {
                width: 100% !important;
                font-size: 1.02rem !important;
                font-weight: 700 !important;
                padding: 0.85rem 1.25rem !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 0.5rem !important;
                border-radius: 14px !important;
                background: #0a394a !important;
                color: #ffffff !important;
                box-shadow: 0 4px 14px rgba(10, 57, 74, 0.22) !important;
                text-decoration: none !important;
            }

            .mobile-book-btn:hover {
                background: #062531 !important;
                color: #ffffff !important;
            }

            .mobile-whatsapp-btn {
                width: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 0.5rem !important;
                background: rgba(37, 211, 102, 0.12) !important;
                color: #0d873d !important;
                border: 1.5px solid rgba(37, 211, 102, 0.4) !important;
                font-weight: 700 !important;
                font-size: 0.92rem !important;
                border-radius: 14px !important;
                padding: 0.7rem 1rem !important;
                text-decoration: none !important;
            }

            .mobile-whatsapp-btn:hover {
                background: rgba(37, 211, 102, 0.22) !important;
                color: #0b7233 !important;
            }

            /* Mobile Footer: Compact, Clean & User-friendly */
            .site-footer {
                margin-top: 2rem !important;
                padding: 2rem 0 calc(78px + env(safe-area-inset-bottom, 16px)) 0 !important;
                background: rgba(255, 255, 255, 0.92) !important;
            }

            .footer-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 1.25rem !important;
                margin-bottom: 1rem !important;
            }

            .footer-brand-col {
                text-align: center !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 0.35rem !important;
            }

            .footer-logo-img {
                height: 32px !important;
                margin: 0 auto !important;
            }

            .footer-concern-tag {
                font-size: 0.74rem !important;
                margin-bottom: 0.2rem !important;
            }

            .footer-brand-desc {
                font-size: 0.8rem !important;
                line-height: 1.45 !important;
                max-width: 320px !important;
                margin: 0 auto 0.35rem auto !important;
                color: var(--text-secondary, #475569) !important;
            }

            .footer-verified-badge {
                margin: 0 auto !important;
                font-size: 0.72rem !important;
                padding: 0.25rem 0.6rem !important;
            }

            .footer-links-wrap {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 0.85rem !important;
                background: rgba(248, 250, 252, 0.85) !important;
                border: 1px solid rgba(226, 232, 240, 0.85) !important;
                border-radius: 18px !important;
                padding: 1.1rem 0.9rem !important;
                text-align: left !important;
                box-shadow: 0 2px 10px rgba(10, 57, 74, 0.03) !important;
            }

            .footer-links-wrap .footer-col {
                min-width: 0 !important;
            }

            .footer-links-wrap .footer-heading {
                font-size: 0.8rem !important;
                font-weight: 800 !important;
                margin-bottom: 0.55rem !important;
                color: #0f172a !important;
            }

            .footer-links-wrap .footer-list {
                gap: 0.45rem !important;
                font-size: 0.78rem !important;
            }

            .footer-contact-col {
                width: 100% !important;
                text-align: center !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 0.4rem !important;
            }

            .footer-contact-col .footer-heading {
                font-size: 0.82rem !important;
                font-weight: 800 !important;
                margin-bottom: 0.25rem !important;
                color: #0f172a !important;
            }

            .footer-address-text {
                font-size: 0.76rem !important;
                line-height: 1.4 !important;
                max-width: 320px !important;
                margin: 0 auto 0.45rem auto !important;
                color: var(--text-secondary, #475569) !important;
            }

            .footer-contact-chips-grid {
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: center !important;
                gap: 0.45rem !important;
                width: 100% !important;
            }

            .footer-contact-chip {
                padding: 0.4rem 0.65rem !important;
                font-size: 0.76rem !important;
                font-weight: 700 !important;
            }

            .footer-operating-hours {
                font-size: 0.72rem !important;
                color: var(--text-muted, #64748b) !important;
                margin-top: 0.15rem !important;
            }

            .footer-bottom-row {
                flex-direction: column !important;
                text-align: center !important;
                gap: 0.4rem !important;
                padding-top: 1rem !important;
                margin-top: 1rem !important;
                font-size: 0.75rem !important;
                border-top: 1px solid rgba(226, 232, 240, 0.8) !important;
            }

            .footer-legal-links {
                justify-content: center !important;
                gap: 0.75rem !important;
                font-size: 0.75rem !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top announcement & contact bar -->
    <div class="top-announcement-banner">
        <div class="topbar-container">
            <!-- Left: Short marketing tag -->
            <div class="topbar-tag-col">
                <span class="topbar-sparkle">✨</span>
                <span class="topbar-tag-text">{{ __("Bangladesh's #1 Verified In-Home Care Platform") }}</span>
            </div>

            <!-- Right: Contact info (Phone, Email, Address) -->
            <div class="topbar-contacts">
                <a href="tel:+8801610296460" class="topbar-contact-item" title="{{ __('Call 24/7 Helpline') }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>+880 1610-296460</span>
                </a>
                <span class="topbar-sep">•</span>
                <a href="mailto:contact@carematebd.com" class="topbar-contact-item" title="{{ __('Email Us') }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>contact@carematebd.com</span>
                </a>
                <span class="topbar-sep">•</span>
                <div class="topbar-contact-item topbar-address" title="{{ __('Office Location') }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>ICT Tower, Agargaon, Dhaka</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <header class="glass-header" style="position: sticky; top: 0; z-index: 1000;">
        <div class="container header-container" style="display: flex; align-items: center; justify-content: space-between; max-width: 1400px; width: 100%; margin: 0 auto; padding: 0.65rem 1.25rem;">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="header-brand-logo" style="display: flex; align-items: center; text-decoration: none; flex-shrink: 0;">
                <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" class="brand-logo-img" style="height: 40px; max-height: 40px; width: auto; object-fit: contain;">
            </a>

            <!-- Desktop Nav Links -->
            <nav class="desktop-nav" style="display: flex; align-items: center; gap: clamp(0.75rem, 1.2vw, 1.35rem); flex-shrink: 0;">
                <a href="{{ route('services.index') }}" style="white-space: nowrap; font-weight: 600; color: var(--text-secondary); font-size: 0.92rem; text-decoration: none; padding: 0.35rem 0.2rem;">{{ __('Services') }}</a>
                <a href="{{ route('marketplace.index') }}" style="white-space: nowrap; font-weight: 600; color: var(--text-secondary); font-size: 0.92rem; text-decoration: none; padding: 0.35rem 0.2rem;">{{ __('Caregivers') }}</a>
                <a href="{{ route('how-it-works') }}" style="white-space: nowrap; font-weight: 600; color: var(--text-secondary); font-size: 0.92rem; text-decoration: none; padding: 0.35rem 0.2rem;">{{ __('How It Works') }}</a>
                <a href="{{ route('about') }}" style="white-space: nowrap; font-weight: 600; color: var(--text-secondary); font-size: 0.92rem; text-decoration: none; padding: 0.35rem 0.2rem;">{{ __('About') }}</a>
                <a href="{{ route('faq') }}" style="white-space: nowrap; font-weight: 600; color: var(--text-secondary); font-size: 0.92rem; text-decoration: none; padding: 0.35rem 0.2rem;">{{ __('FAQ') }}</a>
                <a href="{{ route('contact') }}" style="white-space: nowrap; font-weight: 600; color: var(--text-secondary); font-size: 0.92rem; text-decoration: none; padding: 0.35rem 0.2rem;">{{ __('Contact') }}</a>
            </nav>

            <!-- Actions -->
            <div class="header-actions" style="display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0;">
                <!-- Language Switcher Capsule Toggle (Matching user reference design) -->
                <div class="lang-switch-toggle" title="Switch Language / ভাষা পরিবর্তন করুন">
                    <a href="{{ route('locale.switch', 'en') }}" class="lang-switch-pill {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('locale.switch', 'bn') }}" class="lang-switch-pill {{ app()->getLocale() === 'bn' ? 'active' : '' }}">বাংলা</a>
                </div>

                <!-- Desktop-only Action buttons -->
                <div class="desktop-actions" style="display: flex; align-items: center; gap: 0.45rem; flex-shrink: 0;">
                    @auth
                        <a href="{{ route(auth()->user()->role->dashboardRoute()) }}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 0.4rem;">
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" style="width: 24px; height: 24px; border-radius: 50%;">
                            <span>{{ __('Dashboard') }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="background: transparent; color: var(--text-muted); border: none; cursor: pointer;">{{ __('Logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="font-weight: 600;">{{ __('Login') }}</a>
                        <a href="{{ route('caregiver.register') }}" class="btn btn-mint btn-sm" style="font-weight: 600;">{{ __('Become Caregiver') }}</a>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-sm" style="font-weight: 700;">{{ __('Find Caregiver') }}</a>
                    @endauth
                </div>

                <!-- Quick WhatsApp Header Button (Both Mobile & Desktop) -->
                <a href="https://wa.me/8801610296460?text=Hello%20CareMate%20BD%2C%20I%20would%20like%20to%20inquire%20about%20caregiver%20services." target="_blank" rel="noopener noreferrer" class="btn btn-sm header-whatsapp-btn" style="background: rgba(37, 211, 102, 0.14); color: #0d873d; border: 1px solid rgba(37, 211, 102, 0.35); font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;" title="Quick WhatsApp Chat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 2C6.516 2 2.031 6.484 2.031 12C2.031 13.805 2.508 15.5 3.336 16.969L2 22L7.172 20.688C8.594 21.461 10.258 21.906 12.031 21.906C17.547 21.906 22.031 17.422 22.031 12C22.031 6.484 17.547 2 12.031 2ZM12.031 20.156C10.453 20.156 8.969 19.719 7.688 18.969L7.375 18.781L4.312 19.562L5.125 16.578L4.922 16.25C4.109 14.953 3.672 13.5 3.672 12C3.672 7.391 7.422 3.641 12.031 3.641C16.641 3.641 20.391 7.391 20.391 12C20.391 16.609 16.641 20.156 12.031 20.156ZM16.609 14.547C16.359 14.422 15.125 13.812 14.891 13.734C14.656 13.656 14.484 13.609 14.312 13.859C14.141 14.109 13.656 14.688 13.5 14.859C13.344 15.031 13.188 15.047 12.938 14.922C12.688 14.797 11.875 14.531 10.922 13.68C10.172 13.008 9.672 12.18 9.516 11.93C9.359 11.68 9.5 11.539 9.625 11.414C9.734 11.305 9.875 11.125 10 10.984C10.125 10.844 10.172 10.734 10.25 10.578C10.328 10.422 10.281 10.281 10.219 10.156C10.156 10.031 9.656 8.812 9.453 8.312C9.25 7.828 9.047 7.891 8.891 7.891C8.75 7.891 8.578 7.875 8.406 7.875C8.234 7.875 7.953 7.938 7.719 8.188C7.484 8.438 6.828 9.047 6.828 10.281C6.828 11.516 7.734 12.703 7.859 12.875C7.984 13.047 9.641 15.609 12.188 16.703C12.797 16.969 13.266 17.125 13.641 17.25C14.25 17.438 14.812 17.406 15.25 17.344C15.75 17.266 16.781 16.719 17 16.109C17.219 15.5 17.219 14.984 17.156 14.859C17.094 14.734 16.859 14.672 16.609 14.547Z"/></svg>
                    <span class="header-whatsapp-text">WhatsApp</span>
                </a>

                <!-- Mobile Hamburger Toggle Button -->
                <button type="button" class="mobile-menu-btn" id="mobileMenuToggleBtn" aria-label="Toggle navigation menu">
                    <svg id="hamburgerIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    <svg id="closeIcon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div id="mobileNavDrawer" class="mobile-nav-drawer">
            <div style="display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem;">
                <a href="{{ route('services.index') }}" class="mobile-nav-link">
                    <span>🏥 {{ __('Care Services') }}</span>
                    <span>→</span>
                </a>
                <a href="{{ route('marketplace.index') }}" class="mobile-nav-link">
                    <span>👩‍⚕️ {{ __('Browse Caregivers') }}</span>
                    <span>→</span>
                </a>
                <a href="{{ route('how-it-works') }}" class="mobile-nav-link">
                    <span>🔍 {{ __('How It Works') }}</span>
                    <span>→</span>
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link">
                    <span>🛡️ {{ __('Safety & Verification') }}</span>
                    <span>→</span>
                </a>
                <a href="{{ route('faq') }}" class="mobile-nav-link">
                    <span>❓ {{ __('Frequently Asked Questions') }}</span>
                    <span>→</span>
                </a>
                <a href="{{ route('contact') }}" class="mobile-nav-link">
                    <span>📞 {{ __('Contact & Support') }}</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Mobile Quick Actions -->
            <div style="display: flex; flex-direction: column; gap: 0.65rem; padding-top: 1rem; border-top: 1px solid rgba(226, 232, 240, 0.8);">
                @auth
                    <a href="{{ route(auth()->user()->role->dashboardRoute()) }}" class="btn btn-primary" style="width: 100%;">
                        <span>{{ __('Go to Dashboard') }} ({{ ucfirst(auth()->user()->role->value) }})</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">{{ __('Logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('marketplace.index') }}" class="btn btn-primary" style="width: 100%; font-weight: 700;">
                        <span>{{ __('Find a Verified Caregiver') }}</span>
                    </a>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <a href="{{ route('caregiver.register') }}" class="btn btn-mint btn-sm" style="font-weight: 600; text-align: center;">{{ __('Become Caregiver') }}</a>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="font-weight: 600; text-align: center;">{{ __('Login') }}</a>
                    </div>
                @endauth

                <!-- 24/7 Helpline Card -->
                <div style="background: rgba(10, 57, 74, 0.05); border: 1px solid rgba(10, 57, 74, 0.12); border-radius: var(--radius-md); padding: 0.85rem; margin-top: 0.5rem; text-align: center;">
                    <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">{{ __('24/7 Care Coordinator Hotline') }}</div>
                    <a href="tel:+8801610296460" style="font-size: 1.05rem; font-weight: 800; color: #0a394a; text-decoration: none; display: block; margin-top: 0.2rem;">📞 +880 1610-296460</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Flash Messages -->
    <div class="container" style="margin-top: 1rem;">
        @if (session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Column 1: Brand -->
                <div class="footer-brand-col">
                    <a href="{{ route('home') }}" class="footer-brand-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="CareMate BD" class="footer-logo-img">
                    </a>
                    <div class="footer-concern-tag">
                        {{ __('A Concern of Techboloy') }}
                    </div>
                    <p class="footer-brand-desc">
                        {{ __('Care that feels like family, found in minutes. Bangladesh\'s premier verified caregiver marketplace protecting family dignity and providing verified care.') }}
                    </p>
                    <div class="footer-verified-badge">
                        <span>{{ __('✓ Government NID & Police Background Checked') }}</span>
                    </div>
                </div>

                <!-- Link Columns: Side-by-side 2-column on mobile -->
                <div class="footer-links-wrap">
                    <!-- Column 2: Care Services -->
                    <div class="footer-col">
                        <h4 class="footer-heading">{{ __('Care Services') }}</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('services.show', 'elderly-care') }}">{{ __('Elderly Care') }}</a></li>
                            <li><a href="{{ route('services.show', 'child-care') }}">{{ __('Child Care & Nanny') }}</a></li>
                            <li><a href="{{ route('services.show', 'nursing-care') }}">{{ __('Clinical Nursing Care') }}</a></li>
                            <li><a href="{{ route('services.show', 'medical-transportation') }}">{{ __('Medical Transportation') }}</a></li>
                            <li><a href="{{ route('marketplace.index') }}" class="footer-browse-link">{{ __('Browse All Caregivers →') }}</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Platform -->
                    <div class="footer-col">
                        <h4 class="footer-heading">{{ __('Platform') }}</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('how-it-works') }}">{{ __('How CareMate Works') }}</a></li>
                            <li><a href="{{ route('about') }}">{{ __('Safety & Verification') }}</a></li>
                            <li><a href="{{ route('caregiver.register') }}">{{ __('Apply as a Caregiver') }}</a></li>
                            <li><a href="{{ route('faq') }}">{{ __('Frequently Asked Questions') }}</a></li>
                            <li><a href="{{ route('contact') }}">{{ __('Care Coordination Desk') }}</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Column 4: Contact & Support -->
                <div class="footer-contact-col">
                    <h4 class="footer-heading">{{ __('CareDesk Bangladesh') }}</h4>
                    <p class="footer-address-text">
                        📍 {{ __('E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh') }}
                    </p>
                    <div class="footer-contact-chips-grid">
                        <a href="tel:+8801610296460" class="footer-contact-chip">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>+880 1610-296460</span>
                        </a>
                        <a href="https://wa.me/8801610296460?text=Hello%20CareMate%20BD%2C%20I%20would%20like%20to%20inquire%20about%20caregiver%20services." target="_blank" rel="noopener noreferrer" class="footer-contact-chip footer-chip-whatsapp">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 2C6.516 2 2.031 6.484 2.031 12C2.031 13.805 2.508 15.5 3.336 16.969L2 22L7.172 20.688C8.594 21.461 10.258 21.906 12.031 21.906C17.547 21.906 22.031 17.422 22.031 12C22.031 6.484 17.547 2 12.031 2ZM12.031 20.156C10.453 20.156 8.969 19.719 7.688 18.969L7.375 18.781L4.312 19.562L5.125 16.578L4.922 16.25C4.109 14.953 3.672 13.5 3.672 12C3.672 7.391 7.422 3.641 12.031 3.641C16.641 3.641 20.391 7.391 20.391 12C20.391 16.609 16.641 20.156 12.031 20.156ZM16.609 14.547C16.359 14.422 15.125 13.812 14.891 13.734C14.656 13.656 14.484 13.609 14.312 13.859C14.141 14.109 13.656 14.688 13.5 14.859C13.344 15.031 13.188 15.047 12.938 14.922C12.688 14.797 11.875 14.531 10.922 13.68C10.172 13.008 9.672 12.18 9.516 11.93C9.359 11.68 9.5 11.539 9.625 11.414C9.734 11.305 9.875 11.125 10 10.984C10.125 10.844 10.172 10.734 10.25 10.578C10.328 10.422 10.281 10.281 10.219 10.156C10.156 10.031 9.656 8.812 9.453 8.312C9.25 7.828 9.047 7.891 8.891 7.891C8.75 7.891 8.578 7.875 8.406 7.875C8.234 7.875 7.953 7.938 7.719 8.188C7.484 8.438 6.828 9.047 6.828 10.281C6.828 11.516 7.734 12.703 7.859 12.875C7.984 13.047 9.641 15.609 12.188 16.703C12.797 16.969 13.266 17.125 13.641 17.25C14.25 17.438 14.812 17.406 15.25 17.344C15.75 17.266 16.781 16.719 17 16.109C17.219 15.5 17.219 14.984 17.156 14.859C17.094 14.734 16.859 14.672 16.609 14.547Z"/></svg>
                            <span>WhatsApp 24/7</span>
                        </a>
                        <a href="mailto:contact@carematebd.com" class="footer-contact-chip">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span>contact@carematebd.com</span>
                        </a>
                    </div>
                    <div class="footer-operating-hours">
                        {{ __('Operating hours: 24/7 Care Coordination') }}
                    </div>
                </div>
            </div>

            <!-- Local SEO & Search Keyword Navigation for AI Overviews & Search Engines -->
            <div class="footer-seo-bar">
                <div class="footer-seo-title">{{ __('Care Services & Coverage in Bangladesh') }}</div>
                <div class="footer-seo-tags">
                    <a href="{{ route('services.show', 'elderly-care') }}">{{ __('Elderly Care Dhaka') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('services.show', 'child-care') }}">{{ __('Baby Care & Nanny Dhaka') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('services.show', 'nursing-care') }}">{{ __('Patient Care Attendant') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('services.show', 'nursing-care') }}">{{ __('Home Nursing BD') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('services.show', 'medical-transportation') }}">{{ __('Patient Medical Transport') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['gender' => 'female']) }}">{{ __('Female Caregiver BD') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['gender' => 'male']) }}">{{ __('Male Caregiver BD') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Dhaka']) }}">{{ __('Caregiver Gulshan') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Dhaka']) }}">{{ __('Caregiver Banani') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Dhaka']) }}">{{ __('Caregiver Dhanmondi') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Dhaka']) }}">{{ __('Caregiver Uttara') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Dhaka']) }}">{{ __('Caregiver Bashundhara') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Dhaka']) }}">{{ __('Caregiver Mirpur') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Dhaka']) }}">{{ __('Caregiver Mohammadpur') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Chattogram']) }}">{{ __('Caregiver Chattogram') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('marketplace.index', ['district' => 'Sylhet']) }}">{{ __('Caregiver Sylhet') }}</a>
                    <span class="footer-seo-divider">•</span>
                    <a href="{{ route('sitemap') }}">{{ __('XML Sitemap') }}</a>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="footer-bottom-row">
                <div class="footer-copyright-text">
                    © {{ date('Y') }} CareMate BD — {{ __('A Concern of Techboloy') }}. {{ __('All rights reserved.') }}
                </div>
                <div class="footer-legal-links">
                    <a href="{{ route('faq') }}">{{ __('Privacy Policy') }}</a>
                    <span>•</span>
                    <a href="{{ route('faq') }}">{{ __('Terms of Service') }}</a>
                    <span>•</span>
                    <a href="{{ route('about') }}">{{ __('Safety Standard') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('mobileMenuToggleBtn');
            var drawer = document.getElementById('mobileNavDrawer');
            var hamburgerIcon = document.getElementById('hamburgerIcon');
            var closeIcon = document.getElementById('closeIcon');

            if (toggleBtn && drawer) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    var isOpen = drawer.classList.toggle('open');
                    if (hamburgerIcon && closeIcon) {
                        hamburgerIcon.style.display = isOpen ? 'none' : 'block';
                        closeIcon.style.display = isOpen ? 'block' : 'none';
                    }
                });

                document.addEventListener('click', function(e) {
                    if (drawer.classList.contains('open') && !drawer.contains(e.target) && !toggleBtn.contains(e.target)) {
                        drawer.classList.remove('open');
                        if (hamburgerIcon && closeIcon) {
                            hamburgerIcon.style.display = 'block';
                            closeIcon.style.display = 'none';
                        }
                    }
                });
            }
        });
    </script>

    <!-- Mobile Native App Bottom Navigation Dock for Public/Guests -->
    <nav class="mobile-app-dock" aria-label="Mobile Bottom Navigation">
        <a href="{{ route('home') }}" class="mobile-app-dock-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <span>{{ __('Home') }}</span>
        </a>
        <a href="{{ route('marketplace.index') }}" class="mobile-app-dock-item {{ request()->routeIs('marketplace.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <span>{{ __('Caregivers') }}</span>
        </a>
        <a href="{{ route('services.index') }}" class="mobile-app-dock-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
            <span>{{ __('Services') }}</span>
        </a>
        <a href="{{ route('how-it-works') }}" class="mobile-app-dock-item {{ request()->routeIs('how-it-works') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span>{{ __('How It Works') }}</span>
        </a>
        @auth
            @php $authRole = auth()->user()->role->value; @endphp
            <a href="{{ $authRole === 'admin' ? route('admin.dashboard') : ($authRole === 'caregiver' ? route('caregiver.dashboard') : route('client.dashboard')) }}" class="mobile-app-dock-item {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>{{ __('Portal') }}</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="mobile-app-dock-item {{ request()->routeIs('login') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>{{ __('Login') }}</span>
            </a>
        @endauth
    </nav>

    <!-- Auto-hide mobile dock when keyboard opens -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dock = document.querySelector('.mobile-app-dock');
            if (!dock) return;

            const isTextInput = function(el) {
                if (!el) return false;
                const tag = el.tagName;
                if (tag === 'TEXTAREA' || tag === 'SELECT') return true;
                if (tag === 'INPUT') {
                    const type = (el.type || 'text').toLowerCase();
                    return !['checkbox', 'radio', 'button', 'submit', 'reset', 'file'].includes(type);
                }
                return false;
            };

            document.addEventListener('focusin', function(e) {
                if (isTextInput(e.target)) {
                    dock.classList.add('dock-keyboard-hidden');
                    dock.style.setProperty('display', 'none', 'important');
                }
            });

            document.addEventListener('focusout', function(e) {
                if (isTextInput(e.target)) {
                    setTimeout(function() {
                        if (!isTextInput(document.activeElement)) {
                            dock.classList.remove('dock-keyboard-hidden');
                            dock.style.removeProperty('display');
                        }
                    }, 120);
                }
            });

            if (window.visualViewport) {
                var initialH = window.visualViewport.height;
                window.visualViewport.addEventListener('resize', function() {
                    if (window.visualViewport.height < initialH - 120) {
                        dock.classList.add('dock-keyboard-hidden');
                        dock.style.setProperty('display', 'none', 'important');
                    } else if (!isTextInput(document.activeElement)) {
                        dock.classList.remove('dock-keyboard-hidden');
                        dock.style.removeProperty('display');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
