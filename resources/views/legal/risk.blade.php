@extends('frontend_partials.app')

@section('content')
<section class="section-padding bg-white pt-24">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Risk Disclosure</h1>
            <p class="text-xl text-gray-600">Understanding investment risks is crucial</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="prose max-w-none">
                <div class="mb-8">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-red-700 font-semibold">Important: Investing involves risk of loss. Past performance doesn't guarantee future results.</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Investment Risks</h2>
                    <p class="text-gray-600 mb-4">All investments carry varying degrees of risk. It's important you understand these risks before investing.</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Market Risks</h2>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Digital asset prices can be highly volatile</li>
                        <li>Market conditions can change rapidly</li>
                        <li>Economic factors affect investment returns</li>
                        <li>Global events impact market performance</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Platform Risks</h2>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Technical issues may affect service availability</li>
                        <li>Cybersecurity threats exist in digital platforms</li>
                        <li>Regulatory changes may impact operations</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">3. Investment Specific Risks</h2>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Potential loss of invested capital</li>
                        <li>Returns are not guaranteed</li>
                        <li>Liquidity constraints may apply</li>
                        <li>Plan-specific risks and limitations</li>
                    </ul>
                </div>

                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-3">Risk Management</h3>
                    <p class="text-gray-700 mb-4">We employ various risk management strategies, but cannot eliminate all investment risks.</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2">
                        <li>Diversification across different plans</li>
                        <li>Regular security audits and updates</li>
                        <li>Transparent reporting and communication</li>
                        <li>Compliance with regulatory requirements</li>
                    </ul>
                </div>

                <div class="bg-yellow-50 rounded-lg p-6 mt-6">
                    <h3 class="text-xl font-semibold mb-3">Your Responsibility</h3>
                    <p class="text-yellow-700">You should carefully consider whether investing is suitable for you in light of your financial situation, investment experience, and risk tolerance.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection