@extends('frontend_partials.app')

@section('content')
<section class="section-padding bg-white pt-24">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Terms of Service</h1>
            <p class="text-xl text-gray-600">Last updated: December 2023</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="prose max-w-none">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Acceptance of Terms</h2>
                    <p class="text-gray-600 mb-4">By accessing and using Smart Choice investment platform, you accept and agree to be bound by the terms and provision of this agreement.</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Investment Services</h2>
                    <p class="text-gray-600 mb-4">Smart Choice provides investment services in USDT BEP20. All investments are subject to market risks and past performance doesn't guarantee future results.</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Minimum investment: $50</li>
                        <li>Daily returns based on selected plan</li>
                        <li>Withdrawals processed within 24-48 hours</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">3. User Responsibilities</h2>
                    <p class="text-gray-600 mb-4">Users are responsible for:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Maintaining account security</li>
                        <li>Providing accurate information</li>
                        <li>Complying with applicable laws</li>
                        <li>Reporting suspicious activities</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">4. Risk Disclosure</h2>
                    <p class="text-gray-600 mb-4">Investing in digital assets involves substantial risk of loss and is not suitable for every investor. Please ensure you understand the risks involved.</p>
                </div>

                <div class="bg-purple-50 rounded-lg p-6 mt-8">
                    <h3 class="text-xl font-semibold mb-3">Need Help?</h3>
                    <p class="text-gray-700 mb-4">If you have any questions about these Terms of Service, please contact us at <span class="font-semibold">legal@smartchoice.com</span></p>
                    <a href="#contact" class="inline-flex items-center text-purple-600 hover:text-purple-700">
                        <span>Contact Support</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection