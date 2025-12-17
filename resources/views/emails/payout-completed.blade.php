<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; }
        .footer { background: #343a40; color: white; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 5px 5px; }
        .details { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #28a745; }
        .label { font-weight: bold; color: #495057; }
        .proof { margin: 20px 0; text-align: center; }
        .proof img { max-width: 100%; border: 2px solid #dee2e6; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Payout Successfully Completed</h1>
        </div>
        <div class="content">
            <p>Dear {{ $propfirm->name }},</p>
            
            <p>Great news! Your payout has been <strong>successfully completed</strong>.</p>
            
            <div class="details">
                <p><span class="label">Payout ID:</span> {{ $payout->payout_id }}</p>
                <p><span class="label">Amount:</span> {{ $payout->formatted_amount }}</p>
                <p><span class="label">Beneficiary:</span> {{ $payout->beneficiary_name }}</p>
                <p><span class="label">Completed At:</span> {{ $payout->completed_at->format('F d, Y H:i:s') }}</p>
            </div>
            
            <p>Payment proof has been attached to this email for your records. You can also view it in your dashboard.</p>
            
            <p>Thank you for using Tradefluenza!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tradefluenza. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
