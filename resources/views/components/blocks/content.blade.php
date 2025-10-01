@props(['block', 'mode' => 'front', 'page' => null])

@php
    $parser = \App\Services\BlockDataParser::fromBlockData($block['data'], $mode, $page);
    $title = $parser->getHtmlFrom('title');
    $description = $parser->getDataFrom('description');
    $anchor = $parser->getDataFrom('anchor');
    $texts = $parser->getHtmlFrom('texts');
    $imageUrl = $parser->getImageFrom('background_image');
    $imageBgUrl = $parser->getImageFrom('background_image');
    $sectionBgImage = $parser->getImageFrom('section_background_image');
    $imageRight = $parser->getDataFrom('image_right', false);
    $secondaryText = $parser->getDataFrom('secondary_text', false);
    $secondaryContent = $parser->getHtmlFrom('secondary_content');
    $couleurPrimaire = $parser->getDataFrom('couleur_primaire', 'secondary');
    $styleListes = $parser->getDataFrom('style_listes', 'alternance');
    $afficherSeparateur = $parser->getDataFrom('afficher_separateur', false);
    $coucheBlanc = $parser->getDataFrom('couche_blanc', 'aucun');
    $directionCouleur = $parser->getDataFrom('direction_couleur', 'aucun');
@endphp

{{-- Structure finale avec classes dynamiques pour le mode preview --}}
<section {{ $anchor ? 'id=' . $anchor : 'id=art-therapie' }} class="relative pt-8 md:pt-16 
    {{ $sectionBgImage ? 'bg-cover bg-center' : 'bg-white' }}"
    @if ($sectionBgImage) style="background-image: url('{{ $sectionBgImage }}')" @endif>
    
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            @if ($title)
                <div
                    class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 fade-in-up font-heading {{ $couleurPrimaire === 'primary' ? 'prose-strong:text-primary ' : 'prose-strong:text-secondary' }}">
                    {!! $title !!}
                </div>
            @endif
            @if ($description)
                <div class="prose mx-auto fade-in-up max-w-3xl">
                    {{ $description }}
                </div>
            @endif
        </div>



        <div class="grid md:grid-cols-2 gap-12 {{ $secondaryText ? 'items-start' : 'items-center' }}">
            @if ($secondaryText)
                {{-- Mode texte secondaire --}}
                <div class="fade-in-left prose prose-ul:my-0 prose-ul:py-1 prose-li:my-1 prose-li:py-0 prose-li:marker:text-2xl prose-ol:my-0 prose-ol:py-1 prose-ol:li:marker:text-2xl 
                    {{ $couleurPrimaire === 'primary' ? 'prose-a:text-primary prose-blockquote:border-l-primary' : 'prose-a:text-secondary prose-blockquote:border-l-secondary' }}
                    @if($styleListes === 'primary')
                        prose-li:marker:text-primary prose-ol:li:marker:text-primary
                    @elseif($styleListes === 'secondary')
                        prose-li:marker:text-secondary prose-ol:li:marker:text-secondary
                    @else
                        prose-li:odd:marker:text-primary prose-li:even:marker:text-secondary prose-ol:li:nth-child(odd):marker:text-primary prose-ol:li:nth-child(even):marker:text-secondary
                    @endif">
                    {!! $secondaryContent !!}
                </div>
            @else
                {{-- Mode image --}}
                <div class="fade-in-left flex justify-center {{ $imageRight ? 'md:order-2' : 'md:order-1' }}">
                    <div class="aspect-square w-full max-w-md relative">
                        @if ($imageUrl)
                            <div class="w-full h-full bg-contain bg-center mask-brush"
                                style="background-image: url('{{ $imageUrl }}');">
                            </div>
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-primary-200 to-primary-400 mask-brush flex items-center justify-center">
                                <svg class="w-24 h-24 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
                        
            <div class="fade-in-right prose 
                {{ $secondaryText ? '' : ($imageRight ? 'md:order-1' : 'md:order-2') }} 
                 prose-ul:my-0 prose-ul:py-1 prose-li:my-1 prose-li:py-0 prose-li:marker:text-2xl prose-ol:my-0 prose-ol:py-1 prose-ol:li:marker:text-2xl
                 {{ $couleurPrimaire === 'primary' ? 'prose-a:text-primary prose-blockquote:border-l-primary' : 'prose-a:text-secondary prose-blockquote:border-l-secondary' }} 
                 @if($styleListes === 'primary')
                     prose-li:marker:text-primary prose-ol:li:marker:text-primary
                 @elseif($styleListes === 'secondary')
                     prose-li:marker:text-secondary prose-ol:li:marker:text-secondary
                 @else
                     prose-li:odd:marker:text-primary prose-li:even:marker:text-secondary prose-ol:li:nth-child(odd):marker:text-primary prose-ol:li:nth-child(even):marker:text-secondary
                 @endif">
                {!! $texts !!}
            </div>
            </div>
        </div>

        @if ($afficherSeparateur)
            <div class="mt-16 flex justify-center">
                <div class="w-24 h-1 {{ $couleurPrimaire === 'primary' ? 'bg-primary' : 'bg-secondary' }} rounded-full"></div>
            </div>
        @endif
</section>
