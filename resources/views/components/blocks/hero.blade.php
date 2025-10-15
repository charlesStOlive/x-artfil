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

    // Section styles pour le composant section
    $sectionStyles = [
        'background_image' => $data['image_background'] ?? null,
        'couche_blanc' => $data['couche_blanc'] ?? 'aucun',
        'direction_couleur' => $data['direction_couleur'] ?? 'aucun',
        'is_hidden' => $data['is_hidden'] ?? false,
        'minH70vh' => $data['minH70vh'] ?? false,
        'use_mask' =>  $data['use_mask'] ?? false,
        'mask' => $data['mask'] ?? '',
        'mask_color' => $data['mask_color'] ?? '',
    ];

    
@endphp

{{-- Utilisation du composant section réutilisable avec classes spécifiques au hero --}}
<x-blocks.shared.section :data="$sectionStyles" :anchor="$data['anchor'] ?? ''" :mode="$mode">
    <div class="max-w-7xl mx-auto flex flex-col space-y-12 justify-center text-center">
        @if ($data['html_title'] ?? null)
            <x-blocks.shared.title :title="$data['html_title']" :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'" isH1=true class="fade-in-up" />
        @endif
        @if ($data['description'] ?? null)
            <x-blocks.shared.description :description="$data['description']" class="fade-in-up" data-animation-delay="200" />
        @endif
        <x-blocks.shared.button-group :boutons="$data['boutons'] ?? []" class="fade-in-up" data-animation-delay="400" />
    </div>
</x-blocks.shared.section>
