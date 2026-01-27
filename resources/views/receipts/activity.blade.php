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
            background: #f5f5f5;
            color: #1a1a1a;
            font-family: 'Courier Prime', monospace;
            font-size: 11px;
            line-height: 1.5;
            width: 58mm;
            margin: 0 auto;
            padding: 0;
        }

        .receipt {
            width: 58mm;
            margin: 0 auto;
            padding: 10mm;
            background: #fff;
            color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 10mm;
            border-bottom: 1px solid #e0e0e0;
            padding: 6mm 0 8mm 0;
        }

        .logo {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 2mm;
            font-family: 'Cormorant Garamond', serif;
            color: #1a1a1a;
        }

        .subtitle {
            font-size: 9px;
            letter-spacing: 0.5px;
            font-weight: 500;
            color: #555;
        }

        .tagline {
            font-size: 8px;
            margin-top: 2mm;
            font-weight: 500;
            color: #888;
            letter-spacing: 0px;
        }

        /* RECEIPT NUMBER & TIME */
        .receipt-meta {
            font-size: 9px;
            margin-bottom: 6mm;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5mm;
            color: #666;
        }

        .meta-row {
            margin: 2mm 0;
        }

        .label {
            font-weight: 700;
            display: inline-block;
            width: 30mm;
            text-align: left;
            color: #1a1a1a;
        }

        .value {
            text-align: right;
            word-break: break-all;
            color: #555;
        }

        /* MEMBER SECTION */
        .section {
            margin-bottom: 6mm;
            padding-bottom: 5mm;
        }

        .section-title {
            font-weight: 600;
            font-size: 10px;
            letter-spacing: 0.5px;
            margin-bottom: 3mm;
            text-transform: uppercase;
            color: #1a1a1a;
        }

        .member-info {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5mm;
        }

        .member-name {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 3mm;
            color: #1a1a1a;
        }

        .member-detail {
            font-size: 9px;
            margin: 1mm 0;
            color: #666;
        }

        /* ACTIVITY SECTION */
        .activity-details {
            border-bottom: 1px solid #e0e0e0;
        }

        .activity-name {
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 3mm;
            color: #1a1a1a;
        }

        .activity-detail {
            font-size: 9px;
            margin: 1.5mm 0;
            display: flex;
            justify-content: space-between;
            color: #666;
        }

        .activity-detail-label {
            text-align: left;
        }

        .activity-detail-value {
            text-align: right;
            font-weight: 500;
            color: #1a1a1a;
        }

        /* USAGE TRACKER */
        .usage-tracker {
            border-bottom: 1px solid #e0e0e0;
            padding: 5mm 0;
        }

        .usage-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin: 1.5mm 0;
            color: #666;
        }

        .usage-label {
            text-align: left;
        }

        .usage-value {
            text-align: right;
            font-weight: 500;
            color: #1a1a1a;
        }

        .remaining {
            color: #1a1a1a;
            font-weight: 700;
        }

        /* SESSION INFO */
        .session-info {
            border-bottom: 1px solid #e0e0e0;
            padding: 5mm 0;
        }

        .session-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin: 1.5mm 0;
            color: #666;
        }

        .session-label {
            text-align: left;
        }

        .session-value {
            text-align: right;
            font-weight: 500;
            color: #1a1a1a;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 6mm;
            padding-top: 5mm;
        }

        .thank-you {
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 2mm;
            letter-spacing: 0.5px;
            color: #1a1a1a;
        }

        .footer-text {
            font-size: 8px;
            margin: 1mm 0;
            line-height: 1.3;
            color: #888;
        }

        .divider {
            border-bottom: 1px solid #e0e0e0;
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
        <div class="logo">* ASHA STABLES *</div>
        <div class="subtitle">EQUESTRIAN RESORT</div>
        <div class="tagline">Membership Receipt</div>
    </div>

    <!-- RECEIPT META -->
    <div class="receipt-meta">
        <div class="meta-row">
            <span class="label">RECEIPT #</span>
            <span class="value">{{ $activity_log->id }}</span>
        </div>
        <div class="meta-row">
            <span class="label">DATE</span>
            <span class="value">{{ $activity_log->created_at->setTimezone('Asia/Bangkok')->format('m/d/Y') }}</span>
        </div>
        <div class="meta-row">
            <span class="label">TIME</span>
            <span class="value">{{ $activity_log->created_at->setTimezone('Asia/Bangkok')->format('H:i') }}</span>
        </div>
    </div>

    <!-- MEMBER SECTION -->
    <div class="section member-info">
        <div class="section-title">MEMBER</div>
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

    <!-- MEMBERSHIP SECTION -->
    <div class="section activity-details">
        <div class="section-title">MEMBERSHIP</div>
        <div class="activity-name">{{ $activity_log->member->membership->name ?? 'Standard' }}</div>
        
        <div class="activity-detail">
            <span class="activity-detail-label">Membership Type:</span>
            <span class="activity-detail-value">Active</span>
        </div>

        <div class="activity-detail">
            <span class="activity-detail-label">Status:</span>
            <span class="activity-detail-value">Valid</span>
        </div>
    </div>

    <!-- USAGE TRACKER -->
    @if($balance)
    <div class="section usage-tracker">
        <div class="section-title">SESSION STATUS</div>
        
        <div class="usage-row">
            <span class="usage-label">Sessions Used:</span>
            <span class="usage-value">{{ ($balance->remaining_count + 1) - $balance->remaining_count }}</span>
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

    <!-- MEMBERSHIP VALID THROUGH -->
    <div class="section session-info">
        <div class="section-title">MEMBERSHIP VALID</div>
        
        <div class="session-row">
            <span class="session-label">Issued Date:</span>
            <span class="session-value">{{ $activity_log->created_at->setTimezone('Asia/Bangkok')->format('m/d/Y') }}</span>
        </div>

        <div class="session-row">
            <span class="session-label">Status:</span>
            <span class="session-value">ACTIVE</span>
        </div>
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
        <div style="margin-top: 4mm; font-size: 8px; letter-spacing: 0px; color: #ddd;">
            • • • • •
        </div>
    </div>
</div>

<button class="print-btn" onclick="window.print()">Print Receipt</button>

</body>
</html>