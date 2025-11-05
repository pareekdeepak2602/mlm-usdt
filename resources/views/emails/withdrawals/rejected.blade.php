<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Update - Smart Choice</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
        .withdrawal-update {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
        }
        .withdrawal-amount {
            font-size: 32px;
            font-weight: bold;
            color: #ef4444;
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
            background-color: #ef4444;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .rejection-reason {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.2);
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
        .next-steps {
            background-color: #eff6ff;
            border: 1px solid #93c5fd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .funds-info {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Withdrawal Update</h1>
            <p>Important information about your withdrawal request</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <div class="withdrawal-update">
                <h3>⚠️ Withdrawal Request Rejected</h3>
                <div class="withdrawal-amount">${{ number_format($amount, 2) }} USDT</div>
                <p>Your withdrawal request could not be processed</p>
                <span class="status-badge">REJECTED</span>
            </div>

            <div class="transaction-details">
                <h3 style="margin-top: 0; color: #374151;">Transaction Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value">#{{ $transactionId }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Requested Amount:</span>
                    <span class="detail-value">${{ number_format($amount, 2) }} USDT</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Request Date:</span>
                    <span class="detail-value">{{ $requestDate }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #ef4444;">❌ Rejected</span>
                </div>
            </div>

            <div class="rejection-reason">
                <h4 style="color: #dc2626; margin-top: 0;">📋 Reason for Rejection</h4>
                <p style="font-style: italic; color: #7f1d1d;">"{{ $rejectionReason }}"</p>
            </div>

            <div class="funds-info">
                <h4 style="color: #059669; margin-top: 0;">💰 Funds Returned</h4>
                <p><strong>Good news:</strong> The requested amount has been returned to your wallet balance. You can use these funds for new investments or submit a new withdrawal request after addressing the issue.</p>
            </div>

            <div class="next-steps">
                <h4 style="color: #1d4ed8; margin-top: 0;">🔄 Next Steps</h4>
                <p>1. Review the rejection reason above</p>
                <p>2. Update your information or address the issue mentioned</p>
                <p>3. Submit a new withdrawal request when ready</p>
                <p>4. Contact support if you need clarification</p>
            </div>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ config('app.url') }}/dashboard" class="cta-button">Go to Dashboard</a>
                <a href="{{ config('app.url') }}/support" style="display: inline-block; background: #6b7280; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 15px 10px; transition: all 0.3s ease;">
                    Contact Support
                </a>
            </div>

            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h4 style="color: #d97706; margin-top: 0;">💡 Need Help?</h4>
                <p>If you're unsure about the rejection reason or need assistance resolving the issue, our support team is here to help. We're committed to ensuring your experience with Smart Choice is smooth and successful.</p>
            </div>

            <p>We appreciate your understanding and look forward to helping you succeed with Smart Choice.</p>
            
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