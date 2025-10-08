@props(['block', 'mode' => 'front', 'page' => null])

@php
    $data = $block['data'] ?? [];
    $mode = 'front';
    if (empty($data)) {
        $allVars = get_defined_vars();
        $data = \App\Services\BlockDataParser::extractDataFromBladeVars($allVars);
        $mode = 'preview';
    }

    $parser = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
    $title = $parser->getHtmlFrom('title');
    $anchor = $parser->getDataFrom('anchor');
    $description = $parser->getDataFrom('description');
    $sectionStyles = $parser->getSectionStyles();
    $couleurPrimaire = $parser->getDataFrom('couleur_primaire', 'secondary');
    $styleListes = $parser->getDataFrom('style_listes', 'alternance');
    $afficherSeparateur = $parser->getDataFrom('afficher_separateur', true);
@endphp

<x-blocks.shared.section :data="$sectionStyles" :anchor="$anchor ?: ''">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($title || $description)
            <div class="text-center mb-16">
                @if ($title)
                    <x-blocks.shared.title :title="$title" :couleur-primaire="$couleurPrimaire" :class="'fade-in-up'" />
                @endif
                @if ($description)
                    <x-blocks.shared.description :description="$description" />
                @endif
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Informations de contact -->
            <div class="fade-in-left">
                <div class="space-y-8">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-primary mb-1">Email</h4>
                            <p class="text-primary-700">marie.dubois@arttherapie.fr</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-primary mb-1">Téléphone</h4>
                            <p class="">+33 6 12 34 56 78</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-primary mb-1">Adresse</h4>
                            <p class="text-primary-700">123 rue de la Créativité<br>75001 Paris</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-primary mb-1">Horaires</h4>
                            <p class="">Lun-Ven: 9h-18h<br>Sam: 9h-13h</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="fade-in-right">
                @if ($mode != 'preview')
                    {{-- Composant Livewire pour le formulaire de contact --}}
                    <livewire:contact-form />
                @else
                    <div class="h-64 bg-primary-50 border-2 border-dashed border-primary-300 rounded-lg flex items-center justify-center">
                        <span class="text-primary-600 italic">Formulaire de contact Livewire</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-blocks.shared.section>
