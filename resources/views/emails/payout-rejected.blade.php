<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; }
        .footer { background: #343a40; color: white; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 5px 5px; }
        .details { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #dc3545; }
        .label { font-weight: bold; color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>❌ Payout Request Rejected</h1>
        </div>
        <div class="content">
            <p>Dear {{ $propfirm->name }},</p>
            
            <p>Your payout request has been <strong>rejected</strong>.</p>
            
            <div class="details">
                <p><span class="label">Payout ID:</span> {{ $payout->payout_id }}</p>
                <p><span class="label">Amount:</span> {{ $payout->formatted_amount }}</p>
                <p><span class="label">Beneficiary:</span> {{ $payout->beneficiary_name }}</p>
                <p><span class="label">Rejected At:</span> {{ $payout->rejected_at->format('F d, Y H:i:s') }}</p>
                @if($payout->rejection_reason)
                <p><span class="label">Reason:</span> {{ $payout->rejection_reason }}</p>
                @endif
            </div>
            
            <p>If you believe this is an error, please contact our support team.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tradefluenza. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
