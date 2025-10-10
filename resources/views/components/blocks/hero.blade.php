@props(['block', 'mode' => 'front', 'page' => null])

@php
    $mode = 'front';
    $data = $block['data'] ?? [];
    if (empty($data)) {
        $allVars = get_defined_vars();
        $data = \App\Services\BlockDataParser::extractDataFromBladeVars($allVars);
        $mode = 'preview';
    }
    $parser = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
    $title = $parser->getHtmlFrom('title');
    $description = $parser->getDataFrom('description');
    $anchor = $parser->getDataFrom('anchor');
    $boutons = $parser->getDataFrom('boutons', []);
    $sectionStyles = $parser->getSectionStyles();
    $couleurPrimaire = $parser->getDataFrom('couleur_primaire', 'secondary');
@endphp

{{-- Utilisation du composant section réutilisable avec classes spécifiques au hero --}}
<x-blocks.shared.section :data="$sectionStyles" :anchor="$anchor" :mode="$mode">
    <div class="max-w-7xl mx-auto flex flex-col space-y-12 justify-center text-center">
        @if ($title)
            <x-blocks.shared.title :title="$title" :couleur-primaire="$couleurPrimaire" isH1=true class="fade-in-up" />
        @endif
        @if ($description)
            <x-blocks.shared.description :description="$description" />
        @endif
        <x-blocks.shared.button-group :boutons="$boutons" class="fade-in-up" data-animation-delay="400" />
    </div>
</x-blocks.shared.section>
