<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Confirmation - Smart Choice</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .deposit-success {
            background-color: #f0f9ff;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
        }
        .deposit-amount {
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
        .level-benefits {
            background-color: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .benefit-icon {
            color: #667eea;
            margin-right: 10px;
            font-size: 18px;
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
        .wallet-summary {
            background-color: #fef7ff;
            border: 1px solid #e9d5ff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .daily-earnings {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Deposit Confirmed! 🎉</h1>
            <p>Your funds have been successfully deposited</p>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $userName }}</strong>,</p>
            
            <div class="deposit-success">
                <h3>✅ Deposit Successful!</h3>
                <div class="deposit-amount">${{ number_format($depositAmount, 2) }} USDT</div>
                <p>has been credited to your Smart Choice wallet</p>
            </div>

            <div class="transaction-details">
                <h3 style="margin-top: 0; color: #374151;">Transaction Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value">{{ $transactionId }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value">{{ $depositDate }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Network:</span>
                    <span class="detail-value">BEP20 (BSC)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #10b981;">✅ Completed</span>
                </div>
                @if($transactionHash)
                <div class="detail-row">
                    <span class="detail-label">Transaction Hash:</span>
                    <span class="detail-value" style="font-family: monospace; font-size: 12px;">{{ $transactionHash }}</span>
                </div>
                @endif
            </div>

            @if($levelUpgrade)
            <div class="level-benefits">
                <h3 style="color: #667eea; margin-top: 0;">🎊 Level Upgraded!</h3>
                <p>Congratulations! You've been upgraded to <strong>Level {{ $newLevel }}</strong> with enhanced benefits:</p>
                <div class="benefit-item">
                    <span class="benefit-icon">📈</span>
                    <span><strong>Daily Returns:</strong> {{ $dailyPercentage }}% daily</span>
                </div>
                <div class="benefit-item">
                    <span class="benefit-icon">👥</span>
                    <span><strong>Referral Commissions:</strong> Up to {{ $referralCommission }}% from your network</span>
                </div>
                <div class="benefit-item">
                    <span class="benefit-icon">💰</span>
                    <span><strong>Higher Deposit Limits:</strong> Access to larger investment opportunities</span>
                </div>
            </div>
            @endif

            <div class="wallet-summary">
                <h3 style="color: #7c3aed; margin-top: 0;">💰 Updated Wallet Balance</h3>
                <div class="detail-row">
                    <span class="detail-label">Deposit Balance:</span>
                    <span class="detail-value">${{ number_format($walletBalance, 2) }} USDT</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Referral Balance:</span>
                    <span class="detail-value">${{ number_format($referralBalance, 2) }} USDT</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Balance:</span>
                    <span class="detail-value" style="color: #10b981; font-weight: bold;">${{ number_format($totalBalance, 2) }} USDT</span>
                </div>
            </div>

            <div class="daily-earnings">
                <h3 style="color: #059669; margin-top: 0;">💸 Daily Earnings Started!</h3>
                <p>You're now earning <strong>${{ number_format($dailyEarnings, 2) }}</strong> daily</p>
                <p style="font-size: 14px; color: #6b7280;">Based on your current level and deposit balance</p>
            </div>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $dashboardUrl }}" class="cta-button">View Your Dashboard</a>
            </div>

            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h4 style="color: #d97706; margin-top: 0;">💡 Pro Tip</h4>
                <p>Maximize your earnings by referring friends and leveling up! Each level increase boosts your daily returns and referral commissions.</p>
            </div>

            <p>If you have any questions about your deposit or account, please don't hesitate to contact our support team.</p>
            
            <p>Thank you for investing with Smart Choice!</p>
            
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