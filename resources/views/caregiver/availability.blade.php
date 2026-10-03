<x-layouts.dashboard>
    <x-slot:title>Availability & Schedule — CareMate BD</x-slot:title>
    <x-slot:header>Availability & Duty Schedule</x-slot:header>
    <x-slot:subheading>Control your weekly shift days and mark blackout dates when you are unavailable.</x-slot:subheading>

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: flex-start;" class="schedule-layout">
        <!-- Left: Weekly Schedule & Live Status -->
        <div>
            <!-- Live Availability Toggle Card -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(226, 232, 240, 0.8); padding-bottom: 0.5rem;">
                    Marketplace Visibility & Weekly Days
                </h3>

                <form method="POST" action="{{ route('caregiver.availability.update') }}">
                    @csrf

                    <div style="margin-bottom: 1.5rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.9); padding: 1.25rem; border-radius: var(--radius-md);">
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                            <input type="checkbox" name="is_available" value="1" {{ $caregiver->is_available ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--brand-primary);">
                            <div>
                                <div style="font-weight: 800; font-size: 1rem; color: #0f172a;">Available for New Assignments</div>
                                <div style="font-size: 0.82rem; color: var(--text-muted);">Uncheck if you are on vacation or currently taking a break.</div>
                            </div>
                        </label>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Available Days in the Week <span style="color: #ef4444;">*</span></label>
                        @php
                            $days = [
                                0 => 'Sunday (রবিবার)',
                                1 => 'Monday (সোমবার)',
                                2 => 'Tuesday (মঙ্গলবার)',
                                3 => 'Wednesday (বুধবার)',
                                4 => 'Thursday (বৃহস্পতিবার)',
                                5 => 'Friday (শুক্রবার)',
                                6 => 'Saturday (শনিবার)',
                            ];
                            $activeDays = $availabilities->pluck('day_of_week')->toArray();
                        @endphp
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                            @foreach ($days as $idx => $d)
                                <label style="display: flex; align-items: center; gap: 0.6rem; padding: 0.75rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(203, 213, 225, 0.8); border-radius: var(--radius-md); font-size: 0.88rem; cursor: pointer;">
                                    <input type="checkbox" name="available_days[]" value="{{ $idx }}" {{ in_array($idx, $activeDays) ? 'checked' : '' }} style="accent-color: var(--brand-primary); width: 16px; height: 16px;">
                                    <span>{{ $d }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Preferred Daily Shift Hours</label>
                        <input type="text" name="preferred_hours" value="{{ old('preferred_hours', $caregiver->preferred_hours ?? '8 AM - 6 PM') }}" placeholder="e.g. 8:00 AM to 6:00 PM" class="glass-input">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Weekly Availability
                    </button>
                </form>
            </div>

            <!-- Blocked Specific Dates List -->
            <div class="glass-card" style="padding: 2rem; border-radius: var(--radius-xl);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                    Blackout / Leave Dates
                </h3>

                @if ($blockedDates->isNotEmpty())
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach ($blockedDates as $bd)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(226, 232, 240, 0.8); border-radius: var(--radius-md);">
                                <div>
                                    <span style="font-weight: 700; color: #0f172a;">{{ $bd->blocked_date->format('l, M d, Y') }}</span>
                                    <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">{{ $bd->reason ?? 'Personal Leave' }}</span>
                                </div>
                                <form method="POST" action="{{ route('caregiver.availability.unblock', $bd->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; color: #ef4444 !important;">
                                        Remove Blackout
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: var(--text-muted); font-size: 0.9rem;">No upcoming blackout dates configured.</p>
                @endif
            </div>
        </div>

        <!-- Right: Block a New Date -->
        <div class="glass-card" style="padding: 1.75rem; border-radius: var(--radius-xl);">
            <h4 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">
                Block Off a Single Date
            </h4>
            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.5;">
                Going to your village or have a family event? Block the day so clients cannot book you for that date.
            </p>

            <form method="POST" action="{{ route('caregiver.availability.block') }}">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Date to Block Off <span style="color: #ef4444;">*</span></label>
                    <input type="date" name="blocked_date" min="{{ now()->format('Y-m-d') }}" required class="glass-input">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Reason</label>
                    <input type="text" name="reason" placeholder="e.g. Village visit / Personal exam" class="glass-input">
                </div>

                <button type="submit" class="btn btn-secondary btn-sm" style="width: 100%;">
                    Block Date
                </button>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
