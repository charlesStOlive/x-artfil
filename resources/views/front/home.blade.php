<x-layouts.front title="Accueil - X-Artfil">
    <x-slot:hero>
        <!-- Hero Section -->
        <section class="gradient-bg py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center observe-me">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6 text-shadow">
                        Bienvenue sur 
                        <span class="gradient-text">X-Artfil</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto">
                        Une application moderne développée avec Laravel et Filament, 
                        offrant une expérience utilisateur exceptionnelle.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button class="btn-primary" @click="$dispatch('open-demo-modal')">
                            Découvrir les fonctionnalités
                        </button>
                        <button class="btn-outline" @click="scrollTo('features')">
                            En savoir plus
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </x-slot:hero>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 observe-me">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Fonctionnalités principales
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Découvrez les outils puissants qui font de X-Artfil une solution complète.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover observe-me" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg mr-4" :class="{ 'scale-110': hover }">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Performance</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Interface ultra-rapide avec Alpine.js et optimisations Vite pour une expérience fluide.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover observe-me" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg mr-4" :class="{ 'scale-110': hover }">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Sécurité</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Protection avancée avec Laravel et panneau d'administration sécurisé Filament.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover observe-me" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg mr-4" :class="{ 'scale-110': hover }">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">UX/UI</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Design moderne et responsive avec mode sombre automatique et animations fluides.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover observe-me" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg mr-4" :class="{ 'scale-110': hover }">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Administration</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Panneau d'administration complet avec Filament v4 pour gérer facilement votre contenu.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card-hover observe-me" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg mr-4" :class="{ 'scale-110': hover }">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Personnalisation</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Thèmes personnalisables et composants réutilisables pour s'adapter à vos besoins.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card-hover observe-me" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg mr-4" :class="{ 'scale-110': hover }">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Évolutivité</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        Architecture modulaire Laravel permettant une croissance et une maintenance simplifiées.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 observe-me">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Démonstration interactive
                </h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Testez les composants Alpine.js en action avec ces exemples interactifs.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Accordion Demo -->
                <div class="glass-card observe-me" x-data="accordion()">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Accordéon interactif</h3>
                    
                    <div class="space-y-2">
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg">
                            <button @click="toggle()" class="w-full px-4 py-3 text-left font-medium text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex justify-between items-center">
                                <span>Qu'est-ce que X-Artfil ?</span>
                                <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="px-4 pb-3 text-gray-600 dark:text-gray-300">
                                X-Artfil est une application web moderne construite avec Laravel et Filament, offrant une interface utilisateur riche et des fonctionnalités d'administration avancées.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Demo -->
                <div class="glass-card observe-me">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Système de notifications</h3>
                    <div class="space-y-4">
                        <button @click="addNotification('Notification de succès!', 'success')" class="btn-primary w-full">
                            Notification de succès
                        </button>
                        <button @click="addNotification('Attention! Quelque chose nécessite votre attention.', 'warning')" class="btn-secondary w-full">
                            Notification d'avertissement
                        </button>
                        <button @click="addNotification('Une erreur est survenue!', 'error')" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors w-full">
                            Notification d'erreur
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Demo -->
    <div x-data="modal()" @open-demo-modal.window="open()" @keydown.escape.window="closeOnEscape($event)">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" @click="close()">
            <div x-show="show" x-transition class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full p-6" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Découvrir X-Artfil</h3>
                    <button @click="close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Cette modale démontre l'utilisation d'Alpine.js pour créer des interactions fluides et modernes.
                </p>
                <div class="flex space-x-4">
                    <button @click="close()" class="btn-primary flex-1">
                        Parfait !
                    </button>
                    <button @click="close()" class="btn-secondary flex-1">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Container -->
    <div class="fixed top-4 right-4 z-50 space-y-2" style="max-width: 320px;">
        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="notification.show" 
                 x-transition:enter="transform ease-out duration-300 transition" 
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" 
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 class="glass rounded-lg p-4 shadow-lg"
                 :class="{
                     'bg-green-50 border-green-200 text-green-800': notification.type === 'success',
                     'bg-yellow-50 border-yellow-200 text-yellow-800': notification.type === 'warning',
                     'bg-red-50 border-red-200 text-red-800': notification.type === 'error'
                 }">
                <div class="flex justify-between items-start">
                    <p class="text-sm" x-text="notification.message"></p>
                    <button @click="removeNotification(notification.id)" class="ml-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    @push('scripts')
    <script>
        // Code JavaScript spécifique à cette page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page d\'accueil chargée avec Alpine.js');
        });
    </script>
    @endpush
</x-layouts.front>