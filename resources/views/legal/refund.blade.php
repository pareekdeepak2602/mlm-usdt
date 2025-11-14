@extends('frontend_partials.app')

@section('content')
<section class="section-padding bg-white pt-24">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Refund Policy</h1>
            <p class="text-xl text-gray-600">Our commitment to fair and transparent transactions</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="prose max-w-none">
                <div class="mb-8">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-yellow-700">Please read this policy carefully before making any investments.</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Investment Refund Policy</h2>
                    <p class="text-gray-600 mb-4">Due to the nature of digital investments and market conditions, we have specific guidelines regarding refunds and cancellations.</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">1. General Policy</h2>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Investment deposits are generally non-refundable once processed</li>
                        <li>Returns are based on market performance and selected investment plans</li>
                        <li>Withdrawals of earned returns are processed according to plan terms</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Exceptional Circumstances</h2>
                    <p class="text-gray-600 mb-4">Refunds may be considered under exceptional circumstances:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Technical errors causing duplicate transactions</li>
                        <li>Unauthorized account access and transactions</li>
                        <li>Platform service interruptions affecting investments</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">3. Refund Process</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-sm font-bold">1</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Submit Request</h4>
                                <p class="text-gray-600">Contact support with your transaction details and reason for refund.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-sm font-bold">2</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Review Period</h4>
                                <p class="text-gray-600">Our team will review your request within 5-7 business days.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-sm font-bold">3</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">Resolution</h4>
                                <p class="text-gray-600">You'll receive notification of our decision and next steps.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-3 text-red-800">Important Notice</h3>
                    <p class="text-red-700">Digital investments carry inherent market risks. Please invest only what you can afford to lose and ensure you understand the risks involved before proceeding.</p>
                </div>

                <div class="bg-purple-50 rounded-lg p-6 mt-6">
                    <h3 class="text-xl font-semibold mb-3">Need Assistance?</h3>
                    <p class="text-gray-700 mb-4">If you have questions about our refund policy or need to submit a refund request, contact our support team.</p>
                    <a href="#contact" class="inline-flex items-center text-purple-600 hover:text-purple-700">
                        <i class="fas fa-headset mr-2"></i>
                        <span>Contact Support Team</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection