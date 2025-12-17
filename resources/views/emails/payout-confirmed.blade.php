<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #17a2b8; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; }
        .footer { background: #343a40; color: white; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 5px 5px; }
        .details { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #17a2b8; }
        .label { font-weight: bold; color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Payout Request Confirmed</h1>
        </div>
        <div class="content">
            <p>Dear {{ $propfirm->name }},</p>
            
            <p>Your payout request is <strong>confirmed</strong> and has been sent to Tradefluenza for processing.</p>
            
            <div class="details">
                <p><span class="label">Payout ID:</span> {{ $payout->payout_id }}</p>
                <p><span class="label">Amount:</span> {{ $payout->formatted_amount }}</p>
                <p><span class="label">Beneficiary:</span> {{ $payout->beneficiary_name }}</p>
                <p><span class="label">Email:</span> {{ $payout->beneficiary_email }}</p>
                <p><span class="label">Confirmed At:</span> {{ $payout->confirmed_at->format('F d, Y H:i:s') }}</p>
            </div>
            
            <p>The payout will be processed shortly. You will receive another notification once it has been released to our vendor for payment.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tradefluenza. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
