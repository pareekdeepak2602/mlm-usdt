<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Approved - Smart Choice</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            background-color: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .withdrawal-success {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
        }
        .withdrawal-amount {
            font-size: 32px;
            font-weight: bold;
            color: #10b981;
            margin: 10px 0;
        }
        .transaction-details {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #4b5563;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }
        .status-badge {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 15px 0;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.2);
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .processing-info {
            background-color: #fffbeb;
            border: 1px solid #fcd34d;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .next-steps {
            background-color: #eff6ff;
            border: 1px solid #93c5fd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Withdrawal Approved! 🎉</h1>
            <p>Your funds have been processed successfully</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <div class="withdrawal-success">
                <h3>✅ Withdrawal Processed Successfully!</h3>
                <div class="withdrawal-amount">${{ number_format($amount, 2) }} USDT</div>
                <p>has been sent to your USDT wallet</p>
                <span class="status-badge">COMPLETED</span>
            </div>

            <div class="transaction-details">
                <h3 style="margin-top: 0; color: #374151;">Transaction Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value">#{{ $transactionId }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount Sent:</span>
                    <span class="detail-value" style="color: #10b981; font-weight: bold;">${{ number_format($amount, 2) }} USDT</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Processed Date:</span>
                    <span class="detail-value">{{ $processedDate }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #10b981;">✅ Completed</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">USDT Address:</span>
                    <span class="detail-value" style="font-family: monospace; font-size: 12px;">{{ $usdtAddress }}</span>
                </div>
            </div>

            <div class="processing-info">
                <h4 style="color: #d97706; margin-top: 0;">⏳ Processing Information</h4>
                <p><strong>Blockchain Confirmation:</strong> The transaction has been processed and sent to your wallet address. It may take some time to appear in your wallet depending on network congestion.</p>
                <p><strong>Network:</strong> BEP20 (Binance Smart Chain)</p>
            </div>

            <div class="next-steps">
                <h4 style="color: #1d4ed8; margin-top: 0;">📝 What's Next?</h4>
                <p>1. Check your USDT wallet for the incoming transaction</p>
                <p>2. Verify the transaction on BscScan using your transaction hash</p>
                <p>3. Contact support if you don't see the funds within 24 hours</p>
            </div>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ config('app.url') }}/dashboard" class="cta-button">View Transaction History</a>
            </div>

            <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h4 style="color: #059669; margin-top: 0;">💎 Continue Your Journey</h4>
                <p>Ready to grow your earnings again? You can make new deposits anytime to continue earning daily returns and referral commissions.</p>
            </div>

            <p>If you have any questions about this withdrawal or need assistance, please don't hesitate to contact our support team.</p>
            
            <p>Thank you for being a valued member of Smart Choice!</p>
            
            <p>Best regards,<br><strong>The Smart Choice Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Smart Choice. All rights reserved.</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>For support, contact: support@smartchoice.com</p>
        </div>
    </div>
</body>
</html>