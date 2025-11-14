@extends('frontend_partials.app')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="gradient-bg text-white section-padding pt-24">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Smart Investment with Smart Choice</h1>
                    <p class="text-xl mb-8">Invest in USDT BEP20 and earn daily returns with our secure and transparent investment platform. Join thousands of satisfied investors today.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition text-center">Get Started</a>
                        <a href="#investment-plans" class="px-8 py-3 border border-white rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition text-center">View Plans</a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="float-animation">
                        <img src="https://i.postimg.cc/RCQbjy41/Gemini-Generated-Image-wz8fsxwz8fsxwz8f.png" 
                             alt="Investment" 
                             class="rounded-lg shadow-2xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Investment Plans Section -->
    <section id="investment-plans" class="section-padding bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Investment Plans</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Choose from our tiered investment plans designed to meet different financial goals and referral requirements.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover relative 
                    {{ $plan->is_popular ? 'transform scale-105 border-2 border-yellow-400' : '' }}">
                    
                    @if($plan->is_popular)
                    <div class="absolute top-0 right-0 bg-yellow-400 text-gray-800 px-3 py-1 rounded-bl-lg text-sm font-semibold z-10">
                        Popular
                    </div>
                    @endif
                    
                    <div class="level-badge">{{ $plan->level }}</div>
                    
                    <div class="bg-purple-600 text-white p-6 text-center">
                        <h3 class="text-2xl font-bold mb-2">{{ $plan->name }}</h3>
                        <div class="text-4xl font-bold">{{ $plan->daily_percentage }}% <span class="text-lg font-normal">Daily</span></div>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Min Investment: ${{ number_format($plan->min_investment, 0) }}</span>
                            </li>
                            @if($plan->max_investment)
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Max Investment: ${{ number_format($plan->max_investment, 0) }}</span>
                            </li>
                            @endif
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Asset Hold: ${{ number_format($plan->asset_hold, 0) }}</span>
                            </li>
                            @if($plan->direct_referrals_required || $plan->indirect_referrals_required)
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                                <span>
                                    Referrals Required:
                                    @if($plan->direct_referrals_required)
                                        {{ $plan->direct_referrals_required }}A 
                                    @endif
                                    @if($plan->indirect_referrals_required)
                                        {{ $plan->direct_referrals_required ? '+' : '' }}{{ $plan->indirect_referrals_required }}B+C
                                    @endif
                                </span>
                            </li>
                            @else
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>No Referral Requirements</span>
                            </li>
                            @endif
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span>Total Return: {{ number_format($plan->daily_percentage * $plan->duration_days, 1) }}%</span>
                            </li>
                        </ul>
                        
                        @if($plan->level <= 1)
                        <a href="{{ route('register') }}" class="block w-full py-3 bg-purple-600 text-white rounded-lg text-center hover:bg-purple-700 transition">
                            Get Started
                        </a>
                        @else
                        <div class="text-center">
                            <span class="text-sm text-gray-500">Reach Level {{ $plan->level }} to unlock</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Level Requirements Table -->
            <div class="mt-16 bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Level Requirements & Daily Income</h3>
                <div class="table-container custom-scrollbar">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 px-4 font-semibold text-gray-700">Level</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">Per Day %</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">A</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">B + C</th>
                                <th class="py-3 px-4 font-semibold text-gray-700">Asset Hold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $plan->level }}</td>
                                <td class="py-3 px-4">{{ $plan->daily_percentage }}%</td>
                                <td class="py-3 px-4">{{ $plan->direct_referrals_required ?: '-' }}</td>
                                <td class="py-3 px-4">{{ $plan->indirect_referrals_required ?: '-' }}</td>
                                <td class="py-3 px-4">${{ number_format($plan->asset_hold, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Calculator Section -->
    <section id="calculator" class="section-padding">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Investment Calculator</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Calculate your potential earnings based on your investment level and amount.</p>
            </div>
            
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Investment Details</h3>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2" for="investment-level">Investment Level</label>
                            <select id="investment-level" class="calculator-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                @foreach($plans as $plan)
                                <option value="{{ $plan->level }}" data-daily-rate="{{ $plan->daily_percentage / 100 }}">
                                    Level {{ $plan->level }} - {{ $plan->name }} ({{ $plan->daily_percentage }}% Daily)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2" for="investment-amount">Investment Amount ($)</label>
                            <input id="investment-amount" type="number" min="50" step="10" value="100" class="calculator-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2" for="investment-period">Investment Period (Days)</label>
                            <input id="investment-period" type="number" min="1" max="365" value="30" class="calculator-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none">
                        </div>
                        
                        <button id="calculate-btn" class="w-full py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Calculate Earnings</button>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Potential Returns</h3>
                        
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex justify-between mb-4">
                                <span class="text-gray-600">Daily Return:</span>
                                <span id="daily-return" class="font-semibold">$0.00</span>
                            </div>
                            
                            <div class="flex justify-between mb-4">
                                <span class="text-gray-600">Total Return:</span>
                                <span id="total-return" class="font-semibold">$0.00</span>
                            </div>
                            
                            <div class="flex justify-between mb-4">
                                <span class="text-gray-600">Profit:</span>
                                <span id="profit" class="font-semibold text-green-600">$0.00</span>
                            </div>
                            
                            <div class="border-t pt-4 mt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-800 font-semibold">Final Amount:</span>
                                    <span id="final-amount" class="font-bold text-xl text-purple-600">$0.00</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                            <p class="text-sm text-gray-600">Note: This calculator provides estimates based on the current investment plans. Actual returns may vary based on market conditions and platform performance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Referral Program Section -->
    <section id="referral-program" class="section-padding bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Referral Program</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Earn additional income by referring friends and family to Smart Choice.</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8 mb-8">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">A</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Level A</h3>
                        <p class="text-gray-600 mb-2">Direct Referrals</p>
                        <p class="text-3xl font-bold text-purple-600">10%</p>
                        <p class="text-gray-600">Commission</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">B</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Level B</h3>
                        <p class="text-gray-600 mb-2">Second Level</p>
                        <p class="text-3xl font-bold text-purple-600">5%</p>
                        <p class="text-gray-600">Commission</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-purple-600">C</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Level C</h3>
                        <p class="text-gray-600 mb-2">Third Level</p>
                        <p class="text-3xl font-bold text-purple-600">3%</p>
                        <p class="text-gray-600">Commission</p>
                    </div>
                </div>
                
                <!-- Level Income Table -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-center">Level Income Percentages</h3>
                    <div class="table-container custom-scrollbar">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-3 px-4 font-semibold text-gray-700">Level</th>
                                    <th class="py-3 px-4 font-semibold text-gray-700">A (%)</th>
                                    <th class="py-3 px-4 font-semibold text-gray-700">B (%)</th>
                                    <th class="py-3 px-4 font-semibold text-gray-700">C (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">2</td>
                                    <td class="py-3 px-4">12%</td>
                                    <td class="py-3 px-4">5%</td>
                                    <td class="py-3 px-4">3%</td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">3</td>
                                    <td class="py-3 px-4">13%</td>
                                    <td class="py-3 px-4">6%</td>
                                    <td class="py-3 px-4">4%</td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">4</td>
                                    <td class="py-3 px-4">14%</td>
                                    <td class="py-3 px-4">7%</td>
                                    <td class="py-3 px-4">5%</td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">5</td>
                                    <td class="py-3 px-4">15%</td>
                                    <td class="py-3 px-4">8%</td>
                                    <td class="py-3 px-4">6%</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">6</td>
                                    <td class="py-3 px-4">16%</td>
                                    <td class="py-3 px-4">9%</td>
                                    <td class="py-3 px-4">7%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-3">How It Works</h3>
                    <p class="text-gray-700 mb-4">When someone registers using your referral link and makes a deposit, you earn a commission based on their level in your referral network. Commissions are calculated daily and credited to your account automatically.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-center">Join Now</a>
                        <a href="#contact" class="px-6 py-3 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition text-center">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section-padding">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose Smart Choice</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">We offer a range of features designed to make your investment experience seamless and profitable.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-shield-alt text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Secure Platform</h3>
                        <p class="text-gray-600">Our platform uses advanced security measures to protect your investments and personal information.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-coins text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Daily Returns</h3>
                        <p class="text-gray-600">Earn daily returns on your investments with our transparent and reliable system.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Referral Program</h3>
                        <p class="text-gray-600">Earn additional income through our 3-level referral program with competitive commissions.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-mobile-alt text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Mobile Friendly</h3>
                        <p class="text-gray-600">Access your account and manage investments on any device with our responsive platform.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-headset text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                        <p class="text-gray-600">Our dedicated support team is available around the clock to assist you with any questions.</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Real-time Analytics</h3>
                        <p class="text-gray-600">Track your investments and earnings with our comprehensive analytics dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-padding bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Contact Us</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Have questions or need assistance? Our support team is here to help.</p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Email</h3>
                                    <p class="text-gray-600">support@smartchoice.com</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Phone</h3>
                                    <p class="text-gray-600">+1 (555) 123-4567</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-clock text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Support Hours</h3>
                                    <p class="text-gray-600">24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold mb-4">Send us a message</h3>
                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2" for="name">Name</label>
                                <input class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" 
                                       id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Your Name" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2" for="email">Email</label>
                                <input class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" 
                                       id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Your Email" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2" for="message">Message</label>
                                <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-600" 
                                          id="message" name="message" rows="4" placeholder="Your Message" required>{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="w-full py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection