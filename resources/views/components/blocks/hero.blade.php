@props(['block', 'mode' => 'front', 'page' => null])

@php
    $mode = 'front';
    $data = $block['data'] ?? [];
    if(empty($data)) {
        $allVars = get_defined_vars();
        $data = \App\Services\BlockDataParser::extractDataFromBladeVars($allVars);
        $mode = 'preview';
    }
    $parser = \App\Services\BlockDataParser::fromBlockData($data, $mode, $page);
    $title = $parser->getHtmlFrom('title');
    $description = $parser->getDataFrom('description');
    $anchor = $parser->getDataFrom('anchor');
    $boutons = $parser->getDataFrom('boutons', []);
    $sectionStyles = $parser->getSectionStyles();
@endphp

{{-- Utilisation du composant section réutilisable avec classes spécifiques au hero --}}
<x-blocks.shared.section 
    :data="$sectionStyles" 
    :anchor="$anchor"
    default-classes="relative flex w-full items-center justify-center min-h-[500px] sm:p-1 lg:p-8 {{ $mode === 'preview' ? 'min-h-[120px]' : '' }}">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <div class="text-5xl md:text-7xl font-bold text-gray-900 mb-6 fade-in-left font-heading prose-brush">
                {!! $title !!}
            </div>
            @if ($description)
                <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-4xl mx-auto fade-in-right whitespace-pre-line" data-animation-delay="200">
                    {{ $description }}
                </p>
            @endif
            <x-blocks.shared.button-group :boutons="$boutons" class="fade-in-up" data-animation-delay="400" />
        </div>
    </div>
</x-blocks.shared.section>
