@props([
    'data' => [],
    'class' => ''
])

@php
    // Extraction des données photo
    $photoUrl = $data['url'] ?? null;
    $displayType = $data['display_type'] ?? 'mask_brush_square';
    $position = $data['position'] ?? 'center';
    
    // Classes CSS pour les différents types d'affichage
    $displayClasses = match($displayType) {
        'mask_brush_square' => 'aspect-square w-full max-w-md relative',
        'mask_brush_169' => 'aspect-video w-full max-w-lg relative',
        'full_cover' => 'w-full h-full relative',
        default => 'aspect-square w-full max-w-md relative'
    };

    // Classes CSS pour les différentes positions de crop
    $positionClasses = match($position) {
        'center' => 'object-center',
        'top' => 'object-top',
        'bottom' => 'object-bottom',
        'left' => 'object-left',
        'right' => 'object-right',
        default => 'object-center'
    };
    
    // Style CSS inline pour les positions combinées
    $customPosition = match($position) {
        'top-left' => 'object-position: left top;',
        'top-right' => 'object-position: right top;',
        'bottom-left' => 'object-position: left bottom;',
        'bottom-right' => 'object-position: right bottom;',
        default => ''
    };

    // Classes d'image selon le type d'affichage
    $imageClasses = match($displayType) {
        'mask_brush_square' => 'w-full h-full bg-contain bg-center mask-brush',
        'mask_brush_169' => 'w-full h-full bg-cover bg-center mask-brush-169',
        'full_cover' => 'w-full h-full object-cover ' . $positionClasses,
        default => 'w-full h-full bg-contain bg-center mask-brush'
    };
@endphp

@if ($photoUrl)
    <div class="flex justify-center {{ $class }}">
        <div class="{{ $displayClasses }}">
            @if (in_array($displayType, ['mask_brush_square', 'mask_brush_169']))
                {{-- Mode masque brush : utilise background-image --}}
                <div class="{{ $imageClasses }}"
                    style="background-image: url('{{ $photoUrl }}');">
                </div>
            @elseif ($displayType === 'full_cover')
                {{-- Mode full cover : utilise une vraie balise img pour object-fit --}}
                <img src="{{ $photoUrl }}" 
                     alt="Photo" 
                     class="{{ $imageClasses }}"
                     @if($customPosition) style="{{ $customPosition }}" @endif>
            @endif
        </div>
    </div>
@endif