<x-layouts.dashboard>
    <x-slot:title>Verification Documents & NID — CareMate BD</x-slot:title>
    <x-slot:header>Identity & Compliance Documents</x-slot:header>
    <x-slot:subheading>Private encrypted document safe. Documents are inspected only by CareMate Admin and never exposed publicly.</x-slot:subheading>

    @if (in_array($caregiver->status->value, ['draft', 'changes_required']))
        <div style="background: linear-gradient(135deg, #0a394a 0%, #0f5268 100%); color: #ffffff; border-radius: 18px; padding: 1.15rem 1.35rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-weight: 800; font-size: 1rem;">
                    @if ($caregiver->status->value === 'draft')
                        ⚡ Your 9-Step Verification Application is in Draft (Step {{ $caregiver->application_step }} of 9)
                    @else
                        ⚠️ Verification Changes Requested: {{ $caregiver->status_reason ?? 'Please update your NID or documents.' }}
                    @endif
                </div>
                <div style="font-size: 0.82rem; color: rgba(255,255,255,0.85); margin-top: 0.2rem;">
                    You can upload your NID Front, NID Back, and certificates directly below, or resume the step-by-step registration wizard.
                </div>
            </div>
            <a href="{{ route('caregiver.register') }}" class="btn btn-mint btn-sm" style="font-weight: 800; white-space: nowrap;">
                Resume 9-Step Application →
            </a>
        </div>
    @endif

    <div class="documents-layout">
        <!-- Document Table -->
        <div style="min-width: 0;">
            <!-- Identity Documents -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl); margin-bottom: 1.75rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Government NID & Police Clearances
                </h3>

                <div class="table-container">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Uploaded</th>
                                <th style="text-align: right;">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $doc)
                                <tr>
                                    <td style="font-weight: 700; color: #0f172a;">{{ $doc->title }}</td>
                                    <td><x-badge tone="neutral">{{ strtoupper(str_replace('_', ' ', $doc->type->value)) }}</x-badge></td>
                                    <td><x-badge :tone="$doc->status->value === 'approved' ? 'success' : ($doc->status->value === 'rejected' ? 'danger' : 'warning')">{{ ucfirst($doc->status->value) }}</x-badge></td>
                                    <td style="font-size: 0.82rem; color: var(--text-muted);">{{ $doc->created_at->format('M d, Y') }}</td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('secure.document', $doc->uuid) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                            Stream Securely ↗
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No documents uploaded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Certificates -->
            <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem;">
                    Clinical & Nursing Certificates
                </h3>

                <div class="table-container">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>Certificate Name</th>
                                <th>Institution</th>
                                <th>Year</th>
                                <th>Public Badge</th>
                                <th style="text-align: right;">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($certificates as $cert)
                                <tr>
                                    <td style="font-weight: 700; color: #0f172a;">{{ $cert->name }}</td>
                                    <td>{{ $cert->institution }}</td>
                                    <td>{{ $cert->issue_year ?? '—' }}</td>
                                    <td>
                                        @if ($cert->is_public)
                                            <span style="color: #059669; font-weight: 700; font-size: 0.82rem;">✓ Displayed on Profile</span>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.82rem;">Private</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('secure.certificate', $cert->uuid) }}" target="_blank" class="btn btn-secondary btn-sm" style="font-size: 0.8rem;">
                                            Stream ↗
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No certificates uploaded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upload New Document Box -->
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
            <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                Upload New Document
            </h4>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem;">
                Add updated police clearance, medical screening reports, or training diploma.
            </p>

            <form method="POST" action="{{ route('caregiver.documents.store') }}" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Document Category <span style="color: #ef4444;">*</span></label>
                    <select name="type" id="docTypeSelect" required class="glass-input">
                        <option value="nid_front">🪪 National ID Card (NID Front Side)</option>
                        <option value="nid_back">🪪 National ID Card (NID Back Side)</option>
                        <option value="nursing_registration">🩺 BNMC Nursing Council License</option>
                        <option value="training_certificate">📜 Caregiving Training Diploma / Certificate</option>
                        <option value="police_verification">🛡️ Police Clearance Certificate</option>
                        <option value="academic_certificate">🎓 Academic Certificate (SSC / HSC / Degree)</option>
                        <option value="experience_letter">💼 Hospital / Agency Experience Letter</option>
                        <option value="other">📄 Other Supporting Document</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Document Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="title" id="docTitleInput" required value="{{ old('title', 'National ID (Front)') }}" placeholder="e.g. National ID (Front) or Police Clearance" class="glass-input">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Government NID Number (Optional — updates your profile NID)</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number', $caregiver->nid_number) }}" placeholder="e.g. 199226955000000" class="glass-input">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">File (JPG, PNG, WebP or PDF, max 10MB) <span style="color: #ef4444;">*</span></label>
                    <input type="file" name="file" required accept="image/*,.pdf" class="glass-input">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    Upload to Secure Safe
                </button>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var sel = document.getElementById('docTypeSelect');
                    var titleIn = document.getElementById('docTitleInput');
                    if (sel && titleIn) {
                        var labels = {
                            'nid_front': 'National ID (Front)',
                            'nid_back': 'National ID (Back)',
                            'nursing_registration': 'BNMC Nursing Registration License',
                            'training_certificate': 'Caregiving Training Certificate',
                            'police_verification': 'Police Clearance Certificate',
                            'academic_certificate': 'Academic Certificate',
                            'experience_letter': 'Clinical Experience Letter',
                            'other': 'Supporting Verification Document'
                        };
                        sel.addEventListener('change', function() {
                            if (labels[sel.value]) {
                                titleIn.value = labels[sel.value];
                            }
                        });
                    }
                });
            </script>
        </div>
    </div>
</x-layouts.dashboard>
