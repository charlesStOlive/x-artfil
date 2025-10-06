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
    $anchor = $parser->getDataFrom('anchor');
    $title = $parser->getHtmlFrom('title');
    $description = $parser->getDataFrom('description');
    $couleurPrimaire = $parser->getDataFrom('couleur_primaire', 'secondary');
    $styleListes = $parser->getDataFrom('style_listes', 'alternance');
    $afficherSeparateur = $parser->getDataFrom('afficher_separateur', true);
    // Récupération des données photo via la nouvelle méthode
    $sectionStyles = $parser->getSectionStyles();
@endphp

{{-- Utilisation du composant section réutilisable --}}
<x-blocks.shared.section :data="$sectionStyles" :anchor="$anchor ?: ''">
    <div>
        <div class="mx-auto relative z-2 max-w-7xl  px-4 sm:px-6 lg:px-8 ">
            @if ($title || $description)
                <div class="text-center mb-16">
                    @if ($title)
                        <x-blocks.shared.title :title="$title" :couleur-primaire="$couleurPrimaire" />
                    @endif
                    @if ($description)
                        <x-blocks.shared.description :description="$description" />
                    @endif
                </div>
            @endif


            {{-- Boucle des sous-contenus --}}
            @if (isset($data['subcontents']) && is_array($data['subcontents']))
                @foreach ($data['subcontents'] as $index => $subBlock)
                    @php
                        $subType = $subBlock['type'] ?? '';
                        $subData = $subBlock['data'] ?? [];
                        
                        // Parser pour les données du sous-bloc
                        $subParser = \App\Services\BlockDataParser::fromBlockData($subData, $mode, $page);
                        $texts = $subParser->getHtmlFrom('texts');
                        $secondaryText = $subParser->getHtmlFrom('secondary_text');
                        $photoData = $subParser->getDataForPhotoFrom('photo_config');
                        
                        $hasImage = $photoData['url'] ?? null;
                    @endphp
                    
                    <div class="mb-12 {{ !$loop->last ? 'pb-12 border-b border-gray-200' : '' }}">
                        @if ($subType === 'texte-photo')
                            {{-- Texte + Photo --}}
                            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center">
                                <x-blocks.shared.html-reader 
                                    :content="$texts" 
                                    :couleur-primaire="$couleurPrimaire" 
                                    :style-listes="$styleListes"
                                    class="fade-in-left md:order-1 max-w-4xl" />
                                    
                                @if ($hasImage)
                                    <x-blocks.shared.photo-display 
                                        class="fade-in-right md:order-2"
                                        :data="$photoData" />
                                @endif
                            </div>
                            
                        @elseif ($subType === 'photo-texte')
                            {{-- Photo + Texte --}}
                            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center">
                                @if ($hasImage)
                                    <x-blocks.shared.photo-display 
                                        class="fade-in-left md:order-1"
                                        :data="$photoData" />
                                @endif
                                
                                <x-blocks.shared.html-reader 
                                    :content="$texts" 
                                    :couleur-primaire="$couleurPrimaire" 
                                    :style-listes="$styleListes"
                                    class="fade-in-right md:order-2 max-w-4xl" />
                            </div>
                            
                        @elseif ($subType === 'texte-texte')
                            {{-- Texte + Texte --}}
                            <div class="grid md:grid-cols-2 gap-12 items-start">
                                <x-blocks.shared.html-reader 
                                    :content="$texts" 
                                    :couleur-primaire="$couleurPrimaire" 
                                    :style-listes="$styleListes"
                                    class="fade-in-left max-w-4xl" />
                                    
                                <x-blocks.shared.html-reader 
                                    :content="$secondaryText" 
                                    :couleur-primaire="$couleurPrimaire" 
                                    :style-listes="$styleListes"
                                    class="fade-in-right max-w-4xl" />
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            @if ($afficherSeparateur)
                <div class="mt-16 flex justify-center">
                    <div
                        class="w-24 h-1 {{ $couleurPrimaire === 'primary' ? 'bg-primary' : 'bg-secondary' }} rounded-full">
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-blocks.shared.section>
