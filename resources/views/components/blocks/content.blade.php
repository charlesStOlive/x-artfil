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
<x-blocks.shared.section :data="$sectionStyles" :anchor="$anchor ?: ''">
    <div>
        <div class="mx-auto relative z-2 max-w-7xl  px-4 sm:px-6 lg:px-8 ">
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


            @php
                $hasImage = $photoData['url'] ?? null;
            @endphp

            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center ">
                @if ($hasImage ?? null)
                    {{-- Mode image avec composant photo-display --}}
                    <x-blocks.shared.photo-display class="fade-in-left {{ $imageRight ? 'md:order-2' : 'md:order-1' }}"
                        :data="$photoData" />
                @endif

                <x-blocks.shared.html-reader :content="$texts" :couleur-primaire="$couleurPrimaire" :style-listes="$styleListes"
                    class="fade-in-right {{ $imageRight ? 'md:order-1' : 'md:order-2' }} max-w-4xl" />
            </div>

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
