@props(['block', 'mode' => 'front', 'page' => null])

@php
    // Récupération et traitement automatique des données
    $data = $block['data'] ?? [];
    $mode = 'front';
    if (empty($data)) {
        $allVars = get_defined_vars();
        $data = \App\Services\BlockDataParser::extractDataFromBladeVars($allVars);
        $mode = 'preview';
    } else {
        $data = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
    }
    $ambiance = $data['ambiance'] ?? [];
    $backgroundDatas = $data['background_datas'] ?? [];

   
@endphp

{{-- Utilisation du composant section réutilisable --}}
<x-blocks.shared.section :backgroundDatas="$backgroundDatas" :ambiance="$ambiance" :anchor="$data['anchor'] ?? ''" :mode="$mode" >
    <div>
        <div class="mx-auto relative z-2 max-w-7xl  px-4 sm:px-6 lg:px-8 ">
            @if (($data['html_title'] ?? null) || ($data['description'] ?? null))
                <div class="text-center mb-16">
                    @if ($data['html_title'] ?? null)
                        <x-blocks.shared.title :title="$data['html_title']" :couleur-primaire="$ambiance['couleur_primaire'] ?? 'secondary'" />
                    @endif
                    @if ($data['description'] ?? null)
                        <x-blocks.shared.description :description="$data['description']" />
                    @endif
                </div>
            @endif


            {{-- Boucle des sous-contenus --}}
            @if (isset($data['subcontents']) && is_array($data['subcontents']))
                @foreach ($data['subcontents'] as $index => $subBlock)
                    @php
                        $subType = $subBlock['type'] ?? '';
                        $subData = $subBlock['data'] ?? [];
                        
                        // Traitement des données du sous-bloc
                        $subData = \App\Services\BlockDataParser::fromBlockData($subData, $mode, $page);
                        
                        $hasImage = ($subData['photo_config']['image_url'] ?? null);
                    @endphp
                    
                    <div class="mb-12 {{ !$loop->last ? 'pb-12 border-b border-gray-200' : '' }}">
                        @if ($subType === 'texte-photo')
                            {{-- Texte + Photo --}}
                            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center">
                                <x-blocks.shared.html-reader 
                                    :content="$subData['html_texts'] ?? null" 
                                    :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'" 
                                    :style-listes="$data['style_listes'] ?? 'alternance'"
                                    class="fade-in-left md:order-1 max-w-4xl" />
                                    
                                @if ($hasImage)
                                    <x-blocks.shared.photo-display 
                                        class="fade-in-right md:order-2"
                                        :data="$subData['photo_config']" />
                                @endif
                            </div>
                            
                        @elseif ($subType === 'photo-texte')
                            {{-- Photo + Texte --}}
                            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center">
                                @if ($hasImage)
                                    <x-blocks.shared.photo-display 
                                        class="fade-in-left md:order-1"
                                        :data="$subData['photo_config']" />
                                @endif
                                
                                <x-blocks.shared.html-reader 
                                    :content="$subData['html_texts'] ?? null" 
                                    :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'" 
                                    :style-listes="$data['style_listes'] ?? 'alternance'"
                                    class="fade-in-right md:order-2 max-w-4xl" />
                            </div>
                            
                        @elseif ($subType === 'texte-texte')
                            {{-- Texte + Texte --}}
                            <div class="grid md:grid-cols-2 gap-12 items-start">
                                <x-blocks.shared.html-reader 
                                    :content="$subData['html_texts'] ?? null" 
                                    :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'" 
                                    :style-listes="$data['style_listes'] ?? 'alternance'"
                                    class="fade-in-left max-w-4xl" />
                                    
                                <x-blocks.shared.html-reader 
                                    :content="$subData['html_secondary_text'] ?? null" 
                                    :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'" 
                                    :style-listes="$data['style_listes'] ?? 'alternance'"
                                    class="fade-in-right max-w-4xl" />
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            @if ($data['afficher_separateur'] ?? true)
                <div class="mt-16 flex justify-center">
                    <div
                        class="w-24 h-1 {{ ($data['couleur_primaire'] ?? 'secondary') === 'primary' ? 'bg-primary-500' : 'bg-secondary-500' }} rounded-full">
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-blocks.shared.section>
