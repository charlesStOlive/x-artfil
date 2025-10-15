@props([
    'backgroundDatas' => [],
    'ambiance' => [],
    'anchor' => null,
    'class' => '',
    'mode' => 'front',
])

@php
    \Log::info('ambiance in section');    
    \Log::info($ambiance);
    $backgroundMode = $backgroundDatas['mode'] ?? 'aucun';
    $backgroundImage = ($backgroundMode === 'image') ? ($backgroundDatas['image_background'] ?? null) : null;
    $coucheBlanc = ($backgroundMode === 'image') ? ($backgroundDatas['couche_blanc'] ?? 'aucun') : 'aucun';
    $gradients = $backgroundDatas['gradients'] ?? 'aucun';
    $useMask = ($backgroundMode === 'filtre');
    $mask = ($backgroundMode === 'filtre') ? ($backgroundDatas['mask'] ?? '') : '';
    $maskColor = ($backgroundMode === 'filtre') ? ($backgroundDatas['mask_color'] ?? '') : '';

    $is_hidden = $ambiance['is_hidden'] ?? false;
    $minH70vh = $ambiance['minH70vh'] ?? false;
    $separator = $ambiance['afficher_separateur'] ?? false;
    $couleurPrimaire = $ambiance['couleur_primaire'] ?? 'primary';
    
@endphp  

<section {{ $anchor ? 'id=' . $anchor : '' }}
    class="{{ $mode === 'preview' ? 'min-h-[120px]' : '' }} relative p-8 md:p-16 {{ $minH70vh ? 'min-h-[70vh]' : '' }} {{ $class }} 
    {{ $backgroundImage ? 'bg-cover bg-center' : 'bg-white' }} flex items-center"
    @if ($backgroundImage) style="background-image: url('{{ $backgroundImage }}')" @endif>

    {{-- Overlays pour image de fond de section --}}
    
        <div  
            class="absolute inset-0 {{ $gradients }}
            @if ($useMask) {{ $maskColor }}  {{ $mask }}  @endif">
        </div>


    @if ($coucheBlanc !== 'aucun')
        <div
            class="absolute inset-0 {{ $coucheBlanc }}">
        </div>
    @endif

    {{-- Contenu de la section via le slot --}}
    <div class="relative z-2 w-full">
        {{ $slot }}
    </div>

    @if ($separator)
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2">
            <div
                class="w-64 h-1 {{ $couleurPrimaire === 'primary' ? 'bg-primary-500' : 'bg-secondary-500' }} rounded-full">
            </div>
        </div>
    @endif

    {{-- Si la section est cachée, on met une surcouche sur l'ensemble de la section avec un blanc transparent à 0.5 --}}
    @if ($is_hidden)
        <div class="absolute inset-0 bg-white/50 flex items-center justify-center z-5">
            <div class="bg-gray-900/80 text-white px-4 py-2 rounded-lg font-semibold">
                Bloc masqué temporairement
            </div>
        </div>
    @endif


</section>
