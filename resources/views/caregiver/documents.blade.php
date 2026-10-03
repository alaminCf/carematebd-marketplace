<x-layouts.dashboard>
    <x-slot:title>Verification Documents & NID — CareMate BD</x-slot:title>
    <x-slot:header>Identity & Compliance Documents</x-slot:header>
    <x-slot:subheading>Private encrypted document safe. Documents are inspected only by CareMate Admin and never exposed publicly.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: flex-start;" class="documents-layout">
        <!-- Document Table -->
        <div>
            <!-- Identity Documents -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
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
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
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
                    <label class="form-label">Document Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="title" required placeholder="e.g. Police Clearance 2026" class="glass-input">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Document Category <span style="color: #ef4444;">*</span></label>
                    <select name="type" required class="glass-input">
                        <option value="police_clearance">Police Clearance Certificate</option>
                        <option value="medical_certificate">Medical Fitness / Health Screening</option>
                        <option value="nursing_license">Nursing Council License</option>
                        <option value="training_certificate">Caregiving Training Diploma</option>
                        <option value="other">Other Supporting Document</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">File (PDF or Image, max 5MB) <span style="color: #ef4444;">*</span></label>
                    <input type="file" name="document_file" required accept="image/*,.pdf" class="glass-input">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Upload to Secure Safe
                </button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
