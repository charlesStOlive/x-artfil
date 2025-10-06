@props([
    'data' => [],
    'anchor' => null,
    'defaultClasses' => 'relative pt-8 md:pt-16'
])

@php
    $backgroundImage = $data['background_image'] ?? null;
    $coucheBlanc = $data['couche_blanc'] ?? 'aucun';
    $directionCouleur = $data['direction_couleur'] ?? 'aucun';
    $is_hidden = $data['is_hidden'] ?? false;
@endphp

<section 
    {{ $anchor ? 'id=' . $anchor : '' }}
    class="{{ $defaultClasses }} {{ $backgroundImage ? 'bg-cover bg-center' : 'bg-white' }}"
    @if ($backgroundImage) 
    style="background-image: url('{{ $backgroundImage }}')"
     @endif
    >
    
    {{-- Overlays pour image de fond de section --}}
    @if ($directionCouleur !== 'aucun')
        <div class="absolute inset-0 
            @if ($directionCouleur === 'primaire-secondaire')
                bg-gradient-to-br from-primary/40 to-secondary/40
            @else
                bg-gradient-to-br from-secondary/40 to-primary/40
            @endif">
        </div>
    @endif
    
    @if ($coucheBlanc !== 'aucun')
        <div class="absolute inset-0 
            @if ($coucheBlanc === 'normal')
                bg-gradient-to-b from-white/30 to-white/70
            @else
                bg-gradient-to-b from-white/50 to-white/100
            @endif">
        </div>
    @endif
    
    {{-- Contenu de la section via le slot --}}
    {{ $slot }}
    
    {{-- Si la section est cachée, on met une surcouche sur l'ensemble de la section avec un blanc transparent à 0.5 --}}
    @if ($is_hidden)
        <div class="absolute inset-0 bg-white/50 flex items-center justify-center z-5">
            <div class="bg-gray-900/80 text-white px-4 py-2 rounded-lg font-semibold">
                Bloc masqué temporairement
            </div>
        </div>
    @endif
</section>