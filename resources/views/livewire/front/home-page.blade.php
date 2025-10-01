<div>
    {{-- Rendu dynamique des blocs de contenu --}}
    @if($page->contents)
        @foreach($page->contents as $index => $block)
            {{-- Utilisation de vos composants Blade existants de façon dynamique --}}
            <x-dynamic-component 
                :component="'blocks.' . $block['type']" 
                :block="$block" 
                :page="$page" 
                mode="front"
            />
        @endforeach
    @else
        <div class="text-gray-500">
            Aucun contenu défini pour cette page.
        </div>
    @endif
</div>
