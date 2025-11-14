@extends('frontend_partials.app')

@section('content')
<section class="section-padding bg-white pt-24">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Privacy Policy</h1>
            <p class="text-xl text-gray-600">How we protect and use your information</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="prose max-w-none">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Information We Collect</h2>
                    <p class="text-gray-600 mb-4">We collect information that you provide directly to us, including:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Personal identification information</li>
                        <li>Financial information for transactions</li>
                        <li>Communication records</li>
                        <li>Technical data and usage information</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">2. How We Use Your Information</h2>
                    <p class="text-gray-600 mb-4">We use the information we collect to:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Provide and maintain our services</li>
                        <li>Process transactions and investments</li>
                        <li>Send important notices and updates</li>
                        <li>Improve our platform and services</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">3. Data Protection</h2>
                    <p class="text-gray-600 mb-4">We implement appropriate security measures to protect your personal information against unauthorized access, alteration, or destruction.</p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">4. Third-Party Services</h2>
                    <p class="text-gray-600 mb-4">We may employ third-party companies to facilitate our service, provide service on our behalf, or assist us in analyzing how our service is used.</p>
                </div>

                <div class="bg-green-50 rounded-lg p-6 mt-8">
                    <h3 class="text-xl font-semibold mb-3">Your Rights</h3>
                    <p class="text-gray-700">You have the right to access, correct, or delete your personal data. Contact us to exercise these rights.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection