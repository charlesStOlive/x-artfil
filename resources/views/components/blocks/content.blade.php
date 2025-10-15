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
    //short
    $orederImgClass = $data['left_image'] ?? false ? 'md:order-1' : 'md:order-2';
    $hasImg = $data['photo_config']['image_url'] ?? false ? true : false;

    
@endphp

{{-- Utilisation du composant section réutilisable --}}
<x-blocks.shared.section class="" :backgroundDatas="$backgroundDatas" :ambiance="$ambiance" :anchor="$data['anchor'] ?? ''" :mode="$mode" >
    <div class="max-w-7xl mx-auto">
        <div class=" flex flex-col space-y-12 justify-center  mx-auto">
            @if ($data['html_title'] ?? null)
                <x-blocks.shared.title :title="$data['html_title']" :couleur-primaire="$ambiance['couleur_primaire'] ?? 'secondary'" class="fade-in-up" />
            @endif
            @if ($data['description'] ?? null)
                <x-blocks.shared.description :description="$data['description']" />
            @endif

            <div class="{{ $hasImg ? 'grid md:grid-cols-2 gap-12' : 'max-w-4xl mx-auto' }} items-center ">
                <x-blocks.shared.html-reader :content="$data['html_texts'] ?? null" :ambiance="$ambiance" class="fade-in-right {{ $orederImgClass }}" />
                @if ($hasImg) 
                    <x-blocks.shared.photo-display :data="$data['photo_config']" class="fade-in-left {{ $orederImgClass }}" /> 
                @endif
            </div>


        </div>
    </div>
</x-blocks.shared.section>
