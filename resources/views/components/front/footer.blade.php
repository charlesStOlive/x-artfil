<footer class="bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- À propos -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">X-Artfil</h3>
                <p class="text-gray-600 text-sm">
                    Une application moderne développée avec Laravel et Filament, offrant des solutions innovantes.
                </p>
                <div class="mt-4">
                    <a href="{{ url('/admin') }}" class="inline-flex items-center text-amber-600 hover:text-amber-700 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Panneau d'administration
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Navigation</h3>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">Accueil</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">Services</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">À propos</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Services</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">Développement Web</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">Applications Mobile</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">Conseil IT</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-amber-600 transition-colors text-sm">Maintenance</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        contact@x-artfil.com
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        +33 1 23 45 67 89
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Paris, France
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="mt-8 pt-8 border-t border-gray-200 text-center">
            <p class="text-gray-600 text-sm">
                &copy; {{ date('Y') }} X-Artfil. Tous droits réservés. 
                Développé avec ❤️ en utilisant 
                <a href="https://laravel.com" class="text-amber-600 hover:text-amber-700">Laravel</a> et 
                <a href="https://filamentphp.com" class="text-amber-600 hover:text-amber-700">Filament</a>.
            </p>
        </div>
    </div>
</footer>