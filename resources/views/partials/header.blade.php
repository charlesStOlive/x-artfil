<nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center">
                    <svg class="h-8 w-8 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900">X-Artfil</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                    Accueil
                </a>
                @foreach($headerPages as $page)
                    <a href="{{ route('page', ['slug' => $page->slug]) }}" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                        {{ $page->titre }}
                    </a>
                @endforeach
                
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden pb-4">
            <div class="flex flex-col space-y-2">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                    Accueil
                </a>
                @foreach($headerPages as $page)
                    <a href="{{ route('page', ['slug' => $page->slug]) }}" class="text-gray-700 hover:text-amber-600 px-3 py-2 text-sm font-medium transition-colors">
                        {{ $page->titre }}
                    </a>
                @endforeach
                
            </div>
        </div>
    </div>
</nav>