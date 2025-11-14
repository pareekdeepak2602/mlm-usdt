<!-- Footer -->
<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                    <span class="text-xl font-bold">Smart Choice</span>
                </div>
                <p class="text-gray-400">Your trusted partner for USDT BEP20 investments.</p>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="#home" class="text-gray-400 hover:text-white transition">Home</a></li>
                    <li><a href="#investment-plans" class="text-gray-400 hover:text-white transition">Investment Plans</a></li>
                    <li><a href="#calculator" class="text-gray-400 hover:text-white transition">Calculator</a></li>
                    <li><a href="#referral-program" class="text-gray-400 hover:text-white transition">Referral Program</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold mb-4">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('legal.terms') }}" class="text-gray-400 hover:text-white transition">Terms of Service</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="text-gray-400 hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ route('legal.about') }}" class="text-gray-400 hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('legal.refund') }}" class="text-gray-400 hover:text-white transition">Refund Policy</a></li>
                    <li><a href="{{ route('legal.risk') }}" class="text-gray-400 hover:text-white transition">Risk Disclosure</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://t.me/+6vdcJKUR2KBiNDc1" class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                        <i class="fab fa-telegram"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-700 pt-8 text-center">
            <p class="text-gray-400">&copy; 2023 Smart Choice. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Sticky Telegram Icon -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="https://t.me/+6vdcJKUR2KBiNDc1" 
       target="_blank" 
       class="w-14 h-14 bg-[#0088cc] rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 group animate-bounce"
       title="Join our Telegram channel">
        <i class="fab fa-telegram text-white text-2xl"></i>
        <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
            <span class="text-white text-xs font-bold">Live</span>
        </div>
        
        <!-- Tooltip -->
        <div class="absolute right-16 bottom-1/2 transform translate-y-1/2 bg-gray-800 text-white px-3 py-2 rounded-lg text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
            Join our Telegram
            <div class="absolute top-1/2 right-0 transform -translate-y-1/2 translate-x-1 w-2 h-2 bg-gray-800 rotate-45"></div>
        </div>
    </a>
</div>