<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Receipt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #fff;
            color: #000;
            font-family: 'Courier Prime', monospace;
            font-size: 11px;
            line-height: 1.4;
            width: 58mm;
            margin: 0 auto;
            padding: 0;
        }

        .receipt {
            width: 58mm;
            margin: 0 auto;
            padding: 8mm;
            background: #fff;
            color: #000;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 8mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 6mm;
        }

        .logo {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 2mm;
            font-family: 'Cormorant Garamond', serif;
        }

        .subtitle {
            font-size: 9px;
            letter-spacing: 1px;
        }

        .tagline {
            font-size: 8px;
            margin-top: 2mm;
            font-style: italic;
        }

        /* RECEIPT NUMBER & TIME */
        .receipt-meta {
            font-size: 9px;
            margin-bottom: 6mm;
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 4mm;
        }

        .meta-row {
            margin: 2mm 0;
        }

        .label {
            font-weight: 700;
            display: inline-block;
            width: 30mm;
            text-align: left;
        }

        .value {
            text-align: right;
            word-break: break-all;
        }

        /* MEMBER SECTION */
        .section {
            margin-bottom: 6mm;
            padding-bottom: 4mm;
        }

        .section-title {
            font-weight: 700;
            font-size: 10px;
            letter-spacing: 1px;
            margin-bottom: 3mm;
            text-transform: uppercase;
        }

        .member-info {
            border-bottom: 1px dashed #000;
        }

        .member-name {
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 2mm;
        }

        .member-detail {
            font-size: 9px;
            margin: 1mm 0;
        }

        /* ACTIVITY SECTION */
        .activity-details {
            border-bottom: 1px dashed #000;
        }

        .activity-name {
            font-weight: 700;
            font-size: 11px;
            margin-bottom: 2mm;
        }

        .activity-detail {
            font-size: 9px;
            margin: 1.5mm 0;
            display: flex;
            justify-content: space-between;
        }

        .activity-detail-label {
            text-align: left;
        }

        .activity-detail-value {
            text-align: right;
            font-weight: 700;
        }

        /* USAGE TRACKER */
        .usage-tracker {
            border-bottom: 1px dashed #000;
            padding: 4mm 0;
        }

        .usage-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin: 1.5mm 0;
        }

        .usage-label {
            text-align: left;
        }

        .usage-value {
            text-align: right;
            font-weight: 700;
        }

        .remaining {
            color: #000;
        }

        /* SESSION INFO */
        .session-info {
            border-bottom: 1px dashed #000;
            padding: 4mm 0;
        }

        .session-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin: 1.5mm 0;
        }

        .session-label {
            text-align: left;
        }

        .session-value {
            text-align: right;
            font-weight: 700;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 6mm;
            padding-top: 4mm;
        }

        .thank-you {
            font-weight: 700;
            font-size: 11px;
            margin-bottom: 2mm;
            letter-spacing: 1px;
        }

        .footer-text {
            font-size: 8px;
            margin: 1mm 0;
            line-height: 1.3;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 4mm 0;
        }

        /* PRINT STYLES */
        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 58mm;
            }

            .receipt {
                padding: 2mm;
            }

            button {
                display: none;
            }
        }

        .print-btn {
            margin-top: 8mm;
            padding: 6mm 12mm;
            background: #000;
            color: #fff;
            border: none;
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: none;
        }

        @media screen {
            .print-btn {
                display: block;
            }
        }
    </style>
</head>

<body onload="window.print()">

<div class="receipt">
    <!-- HEADER -->
    <div class="header">
        <div class="logo">★ ASHA STABLES ★</div>
        <div class="subtitle">EQUESTRIAN RESORT</div>
        <div class="tagline">Activity Session Receipt</div>
    </div>

    <!-- RECEIPT META -->
    <div class="receipt-meta">
        <div class="meta-row">
            <span class="label">RECEIPT #</span>
            <span class="value">{{ $activity_log->id }}</span>
        </div>
        <div class="meta-row">
            <span class="label">DATE</span>
            <span class="value">{{ $activity_log->created_at->format('m/d/Y') }}</span>
        </div>
        <div class="meta-row">
            <span class="label">TIME</span>
            <span class="value">{{ $activity_log->created_at->format('h:i A') }}</span>
        </div>
    </div>

    <!-- MEMBER SECTION -->
    <div class="section member-info">
        <div class="section-title">— MEMBER —</div>
        <div class="member-name">{{ $activity_log->member->name }}</div>
        <div class="member-detail">
            ID: {{ $activity_log->member->card_id }}
        </div>
        <div class="member-detail">
            CARD: {{ $activity_log->member->card_uid }}
        </div>
        <div class="member-detail">
            Membership: {{ $activity_log->member->membership->name ?? 'Standard' }}
        </div>
    </div>

    <!-- ACTIVITY SECTION -->
    <div class="section activity-details">
        <div class="section-title">— ACTIVITY —</div>
        <div class="activity-name">{{ $activity_log->activity->name }}</div>
        
        <div class="activity-detail">
            <span class="activity-detail-label">Duration:</span>
            <span class="activity-detail-value">{{ $activity_log->activity->duration ?? 1 }} time</span>
        </div>

        <div class="activity-detail">
            <span class="activity-detail-label">Instructor:</span>
            <span class="activity-detail-value">{{ $activity_log->staff->name ?? 'Staff' }}</span>
        </div>
    </div>

    <!-- USAGE TRACKER -->
    @if($balance)
    <div class="section usage-tracker">
        <div class="section-title">— SESSION STATUS —</div>
        
        <div class="usage-row">
            <span class="usage-label">Sessions Used:</span>
            <span class="usage-value">{{ 0 }}</span>
        </div>

        <div class="usage-row">
            <span class="usage-label">Sessions Left:</span>
            <span class="usage-value remaining">{{ $balance->remaining_count }}</span>
        </div>

        <div class="usage-row">
            <span class="usage-label">Total Allowed:</span>
            <span class="usage-value">{{ $balance->remaining_count + 1 }}</span>
        </div>
    </div>
    @endif

    <!-- SESSION DETAILS -->
    <div class="section session-info">
        <div class="section-title">— SESSION DETAILS —</div>
        
        <div class="session-row">
            <span class="session-label">Status:</span>
            <span class="session-value">RESERVED</span>
        </div>

        @if($activity_log->check_in_time)
        <div class="session-row">
            <span class="session-label">Check-in:</span>
            <span class="session-value">{{ $activity_log->check_in_time->format('h:i A') }}</span>
        </div>
        @endif

        @if($activity_log->check_out_time)
        <div class="session-row">
            <span class="session-label">Check-out:</span>
            <span class="session-value">{{ $activity_log->check_out_time->format('h:i A') }}</span>
        </div>
        @endif
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div class="divider"></div>
        <div class="thank-you">THANK YOU!</div>
        <div class="footer-text">
            Please keep this receipt for your records.
        </div>
        <div class="footer-text">
            For support, contact staff.
        </div>
        <div style="margin-top: 4mm; font-size: 8px; letter-spacing: 2px;">
            ★★★★★
        </div>
    </div>
</div>

<button class="print-btn" onclick="window.print()">Print Receipt</button>

</body>
</html>