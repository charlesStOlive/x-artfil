@props([
    'textContent' => null,
    'photoUrl' => null,
    'photoDisplayType' => 'mask_brush_square',
    'photoPosition' => 'center',
    'imageRight' => false,
    'couleurPrimaire' => 'secondary',
    'styleListes' => 'alternance',
    'class' => ''
])

@if ($textContent || $photoUrl)
    <div class="grid md:grid-cols-2 gap-12 items-center {{ $class }}">
        @if ($photoUrl)
            <x-blocks.shared.photo-display 
                :photo-url="$photoUrl"
                :display-type="$photoDisplayType"
                :position="$photoPosition"
                :image-right="$imageRight"
                class=" {{ $imageRight ? 'md:order-2 fade-in-left' : 'fade-in-right' }}" />
        @endif

        @if ($textContent)
            <x-blocks.shared.html-reader 
                :content="$textContent" 
                :couleur-primaire="$couleurPrimaire" 
                :style-listes="$styleListes"
                class="fade-in-right {{ $imageRight ? 'md:order-1 fade-in-left' : 'fade-in-right' }}" />
        @endif
    </div>
@endif