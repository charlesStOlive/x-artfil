@props(['block', 'mode' => 'front', 'page' => null])

@php
    $parser = \App\Services\BlockDataParser::fromBlockData($block['data'], $mode, $page);
    $title = $parser->getHtmlFrom('title');
    $imageUrl = $parser->getImageFrom('background_image');
    $description = $parser->getDataFrom('description');
    $anchor = $parser->getDataFrom('anchor');
    $boutons = $parser->getDataFrom('boutons', []);
    $coucheBlanc = $parser->getDataFrom('couche_blanc', 'normal');
    $directionCouleur = $parser->getDataFrom('direction_couleur', 'primaire-secondaire');
    \Log::info('Hero Block Data:', $block['data']);
    \Log::info('Hero Block description:' . $description);
@endphp

{{-- Structure finale avec classes dynamiques pour le mode preview --}}
<section {{ $anchor ? 'id=' . $anchor : '' }}
    class="relative flex w-full items-center justify-center min-h-[500px] sm:p-1 lg:p-8 {{ $mode === 'preview' ? 'min-h-[120px]' : '' }}
    {{ $imageUrl ? 'bg-cover bg-center' : '' }}"
    @if ($imageUrl) style="background-image: url('{{ $imageUrl }}')" @endif>

    {{-- Overlays --}}
    @if ($directionCouleur !== 'aucun')
        <div
            class="absolute inset-0 
            @if ($directionCouleur === 'primaire-secondaire') bg-gradient-to-br from-primary/40 to-secondary/40
            @else
                bg-gradient-to-br from-secondary/40 to-primary/40 @endif">
        </div>
    @endif

    @if ($coucheBlanc !== 'aucun')
        <div
            class="absolute inset-0 
            @if ($coucheBlanc === 'normal') bg-gradient-to-b from-white/30 to-white/70
            @else
                bg-gradient-to-b from-white/50 to-white/100 @endif">
        </div>
    @endif



    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <div class="text-5xl md:text-7xl font-bold text-gray-900 mb-6 fade-in-up font-heading prose-brush">
                {!! $title !!}
            </div>
            @if ($description)
                <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-4xl mx-auto fade-in-up" data-animation-delay="200">
                    {{ $description }}
                </p>
            @endif
            @if (!empty($boutons))
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center fade-in-up"
                    data-animation-delay="400">
                    @foreach ($boutons as $bouton)
                        @php
                            $href = match($bouton['type_lien']) {
                                'page' => '/' . ($bouton['page_id'] ?? ''),
                                'ancre' => $bouton['ancre'] ?? '#',
                                'externe' => $bouton['url_externe'] ?? '#',
                                default => '#'
                            };
                        @endphp
                        <a href="{{ $href }}" class="btn-base text-white {{ $bouton['couleur'] === 'primary' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $bouton['texte'] ?? 'Bouton' }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
