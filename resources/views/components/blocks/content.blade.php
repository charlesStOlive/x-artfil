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
    $description = $parser->getDataFrom('description');
    $anchor = $parser->getDataFrom('anchor');
    $texts = $parser->getHtmlFrom('texts');

    $imageRight = $parser->getDataFrom('image_right', false);
    $couleurPrimaire = $parser->getDataFrom('couleur_primaire', 'secondary');
    $styleListes = $parser->getDataFrom('style_listes', 'alternance');
    $afficherSeparateur = $parser->getDataFrom('afficher_separateur', true);
    // Récupération des données photo via la nouvelle méthode
    $photoData = $parser->getDataForPhotoFrom('photo_config');
    //
    $sectionStyles = $parser->getSectionStyles();
@endphp

{{-- Utilisation du composant section réutilisable --}}
<x-blocks.shared.section :data="$sectionStyles" :anchor="$anchor ?: ''" :mode="$mode" :separator="$afficherSeparateur" :couleur-primaire="$couleurPrimaire">
    <div class=" flex flex-col space-y-12 justify-center">  
            @if ($title)
                <x-blocks.shared.title :title="$title" :couleur-primaire="$couleurPrimaire" class="fade-in-up" />
            @endif
            @if ($description)
                <x-blocks.shared.description :description="$description" />
            @endif

            @php
                $hasImage = $photoData['url'] ?? null;
            @endphp
            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center ">
                @if ($hasImage ?? null)
                    <x-blocks.shared.photo-display :data="$photoData" class="fade-in-left {{ $imageRight ? 'md:order-2' : 'md:order-1' }}"  />
                @endif
                <x-blocks.shared.html-reader :content="$texts" :couleur-primaire="$couleurPrimaire" :style-listes="$styleListes"
                    class="fade-in-right {{ $imageRight ? 'md:order-1' : 'md:order-2' }}" />
            </div>

            
        </div>

</x-blocks.shared.section>
