@extends('frontend_partials.app')

@section('content')
<section class="section-padding bg-white pt-24">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">About Smart Choice</h1>
            <p class="text-xl text-gray-600">Your trusted investment partner since 2020</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="prose max-w-none">
                <div class="mb-8 text-center">
                    <div class="w-32 h-32 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-white text-4xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Mission</h2>
                    <p class="text-gray-600 text-lg">To democratize investment opportunities and provide secure, transparent financial growth platforms for everyone.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div class="bg-purple-50 rounded-lg p-6">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Our Vision</h3>
                        <p class="text-gray-700">Creating a world where everyone has access to smart investment opportunities regardless of their financial background.</p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-handshake text-white text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Our Values</h3>
                        <p class="text-gray-700">Transparency, security, and customer success are at the core of everything we do.</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Our Journey</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-sm font-bold">1</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">2020 - Foundation</h4>
                                <p class="text-gray-600">Smart Choice was founded with a vision to revolutionize digital investments.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-sm font-bold">2</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">2021 - Growth</h4>
                                <p class="text-gray-600">Expanded our services and reached 10,000+ satisfied investors worldwide.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                <span class="text-white text-sm font-bold">3</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">2023 - Innovation</h4>
                                <p class="text-gray-600">Launched advanced investment plans and enhanced security features.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <h3 class="text-xl font-semibold mb-3">Join Our Community</h3>
                    <p class="text-gray-700 mb-4">Become part of 50,000+ investors growing their wealth with Smart Choice.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <span>Start Investing</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection