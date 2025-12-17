<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; }
        .footer { background: #343a40; color: white; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 5px 5px; }
        .details { background: white; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff; }
        .label { font-weight: bold; color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Payout Released for Processing</h1>
        </div>
        <div class="content">
            <p>Dear {{ $propfirm->name }},</p>
            
            <p>Your payout has been <strong>released to the vendor</strong> for processing.</p>
            
            <div class="details">
                <p><span class="label">Payout ID:</span> {{ $payout->payout_id }}</p>
                <p><span class="label">Amount:</span> {{ $payout->formatted_amount }}</p>
                <p><span class="label">Beneficiary:</span> {{ $payout->beneficiary_name }}</p>
                <p><span class="label">Vendor:</span> {{ $vendor->name }}</p>
                <p><span class="label">Released At:</span> {{ $payout->released_at->format('F d, Y H:i:s') }}</p>
            </div>
            
            <p>Our vendor has been notified and will process the payment shortly. You will receive a final confirmation email with payment proof once the transaction is completed.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Tradefluenza. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
