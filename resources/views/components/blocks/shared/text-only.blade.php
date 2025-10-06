@props([
    'content' => null,
    'couleurPrimaire' => 'secondary',
    'styleListes' => 'alternance',
    'class' => ''
])

@if ($content)
    <div class="max-w-4xl mx-auto {{ $class }}">
        <x-blocks.shared.html-reader 
            :content="$content" 
            :couleur-primaire="$couleurPrimaire" 
            :style-listes="$styleListes" />
    </div>
@endif