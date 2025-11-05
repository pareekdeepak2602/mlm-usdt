    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to Smart Choice</title>
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
            .welcome-bonus {
                background-color: #f0f4ff;
                border-left: 4px solid #667eea;
                padding: 15px;
                margin: 20px 0;
                border-radius: 4px;
            }
            .bonus-amount {
                font-size: 24px;
                font-weight: bold;
                color: #667eea;
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
            .referral-box {
                background-color: #f9f3ff;
                border: 1px dashed #764ba2;
                padding: 15px;
                margin: 20px 0;
                border-radius: 6px;
            }
            .referral-code {
                font-family: monospace;
                background-color: #f0f4ff;
                padding: 5px 10px;
                border-radius: 4px;
                font-weight: bold;
                color: #667eea;
            }
            .footer {
                text-align: center;
                padding: 20px;
                color: #6b7280;
                font-size: 14px;
            }
            .feature-list {
                margin: 20px 0;
            }
            .feature-item {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            .feature-icon {
                color: #667eea;
                margin-right: 10px;
            }
            .activation-notice {
                background-color: #fffbeb;
                border-left: 4px solid #f59e0b;
                padding: 15px;
                margin: 20px 0;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Welcome to Smart Choice</h1>
                <p>Your Journey to Financial Freedom Begins!</p>
            </div>
            
            <div class="content">
                <p>Dear {{ $userName }},</p>
                
                <p>We're thrilled to welcome you to the Smart Choice community! Your account has been successfully created, and you're now on your way to earning daily returns through our secure investment platform.</p>
                
                <div class="welcome-bonus">
                    <h3>🎉 Welcome Bonus Added!</h3>
                    <p>We've credited your account with a <span class="bonus-amount">10 USDT</span> welcome bonus to get you started on your investment journey.</p>
                </div>
                
                <div class="activation-notice">
                    <h3>⚠️ Account Activation Required</h3>
                    <p>To start earning daily returns, please deposit a minimum of 50 USDT to activate your account. Once activated, you'll begin earning daily returns based on your investment plan.</p>
                    <a href="{{ url('/wallet/deposit') }}" class="cta-button">Activate Your Account Now</a>
                </div>
                
                <h3>Maximize Your Earnings with These Opportunities:</h3>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <span class="feature-icon">💰</span>
                        <div>
                            <strong>Invest & Earn Daily Returns:</strong> Choose from our tiered investment plans with returns ranging from 1% to 3.3% daily.
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <span class="feature-icon">👥</span>
                        <div>
                            <strong>Refer & Earn:</strong> Share your referral link and earn commissions from your referrals' investments across 3 levels.
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <span class="feature-icon">📈</span>
                        <div>
                            <strong>Level Up:</strong> Increase your daily returns by meeting referral requirements and advancing through our level system.
                        </div>
                    </div>
                </div>
                
                <div class="referral-box">
                    <h3>🚀 Start Referring Today!</h3>
                    <p>Share your unique referral code and start earning commissions immediately:</p>
                    <p class="referral-code">{{ $referralCode }}</p>
                    <p>Or share this referral link:</p>
                    <p style="word-break: break-all; background-color: #f0f4ff; padding: 10px; border-radius: 4px; margin-top: 10px;">
                        {{ $referralLink }}
                    </p>
                </div>
                
                <p>If you have any questions or need assistance, our support team is available 24/7 to help you succeed.</p>
                
                <p>Thank you for choosing Smart Choice. We look forward to helping you achieve your financial goals!</p>
                
                <p>Best regards,<br>The Smart Choice Team</p>
            </div>
            
            <div class="footer">
                <p>&copy; 2025 Smart Choice. All rights reserved.</p>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>