@props([
    'textContent1' => null,
    'textContent2' => null,
    'couleurPrimaire' => 'secondary',
    'styleListes' => 'alternance',
    'class' => ''
])

@if ($textContent1 || $textContent2)
    <div class="grid md:grid-cols-2 gap-12 items-start {{ $class }}">
        @if ($textContent1)
            <x-blocks.shared.html-reader 
                :content="$textContent1" 
                :couleur-primaire="$couleurPrimaire" 
                :style-listes="$styleListes"
                class="fade-in-left" />
        @endif

        @if ($textContent2)
            <x-blocks.shared.html-reader 
                :content="$textContent2" 
                :couleur-primaire="$couleurPrimaire" 
                :style-listes="$styleListes"
                class="fade-in-right" />
        @endif
    </div>
@endif