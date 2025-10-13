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
    ];
@endphp

{{-- Utilisation du composant section réutilisable --}}
<x-blocks.shared.section class="" :data="$sectionStyles" :anchor="$data['anchor'] ?? ''" :mode="$mode" :separator="$data['afficher_separateur'] ?? true"
    :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'">
    <div class="max-w-7xl mx-auto">
        <div class=" flex flex-col space-y-12 justify-center  mx-auto">
            @if ($data['html_title'] ?? null)
                <x-blocks.shared.title :title="$data['html_title']" :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'" class="fade-in-up" />
            @endif
            @if ($data['description'] ?? null)
                <x-blocks.shared.description :description="$data['description']" />
            @endif

            <div
                class="{{ $data['photo_config']['image_url'] ?? null ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center ">

                <x-blocks.shared.html-reader :content="$data['html_texts'] ?? null" :couleur-primaire="$data['couleur_primaire'] ?? 'secondary'" :style-listes="$data['style_listes'] ?? 'alternance'"
                    class="fade-in-right {{ $data['left_image'] ?? false ? 'md:order-2' : 'md:order-1' }}" />
                @if ($data['photo_config']['image_url'] ?? null)
                    <x-blocks.shared.photo-display :data="$data['photo_config']"
                        class="fade-in-left {{ $data['left_image'] ?? false ? 'md:order-1' : 'md:order-2' }}" />
                @endif
            </div>


        </div>
    </div>
</x-blocks.shared.section>
