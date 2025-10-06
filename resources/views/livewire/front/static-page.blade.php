<div>
    {{-- Rendu dynamique des blocs de contenu --}}
    @if($page->contents)
        @foreach($page->contents as $index => $block)
            {{-- Vérifier si le bloc est visible --}}
            @if(!$hidden = $block['data']['is_hidden'] ?? false)
                {{-- Utilisation de vos composants Blade existants de façon dynamique --}}
                <x-dynamic-component 
                    :component="'blocks.' . $block['type']" 
                    :block="$block" 
                    :page="$page" 
                    mode="front"
                />
            @endif
        @endforeach
    @else
        <div class="text-gray-500">
            Aucun contenu défini pour cette page.
        </div>
    @endif
</div>
