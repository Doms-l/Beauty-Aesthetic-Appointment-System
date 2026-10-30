@php
    $a        = $appointment;
    $timeText = \Carbon\Carbon::parse($a->appointment_time)->format('h:i A');
    $who      = $a->staff?->user?->full_name
                ?: ($a->with_owner ? 'The Owner (requested)' : 'Any available staff');
@endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.5;border-collapse:collapse;">
    <tr><td style="padding:8px 0;color:#8a7a80;width:120px;border-bottom:1px solid #f3e4e9;">Service</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;">{{ $a->service->name }}</td></tr>
    <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;">Date</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;">{{ $a->appointment_date->format('l, M d, Y') }}</td></tr>
    <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;">Time</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;">{{ $timeText }}</td></tr>
    <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;">Served by</td><td style="padding:8px 0;font-weight:bold;border-bottom:1px solid #f3e4e9;">{{ $who }}</td></tr>
    @if($a->notes)
        <tr><td style="padding:8px 0;color:#8a7a80;border-bottom:1px solid #f3e4e9;vertical-align:top;">Notes</td><td style="padding:8px 0;border-bottom:1px solid #f3e4e9;">{{ $a->notes }}</td></tr>
    @endif
</table>
