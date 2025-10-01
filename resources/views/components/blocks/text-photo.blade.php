@props(['block', 'mode' => 'front', 'page' => null])

@php
    $parser = \App\Services\BlockDataParser::fromBlockData($block['data'], $mode, $page);
    $text = $parser->getHtmlFrom('text');
    $layout = $parser->getDataFrom('layout', 'left'); // 'left' ou 'right'
    $imageUrl = $parser->getImageFrom('single_image'); // Collection = nom du champ
@endphp

{{-- Structure finale avec classes dynamiques pour le mode preview --}}
<section class=" bg-white {{ $mode === 'preview' ? 'py-2' : 'py-8' }}">
    <div class="container mx-auto px-6">
        <div class="max-w-7xl mx-auto">

            {{-- Layout flex responsive avec inversion selon $layout --}}
            <div
                class="flex flex-col lg:flex-row items-center  justify-center gap-8 {{ $layout === 'right' ? 'lg:flex-row-reverse' : '' }} {{ $mode === 'preview' ? 'gap-4' : '' }}">

                {{-- Image --}}
                @if ($imageUrl)
                    <div class="flex">
                        <img src="{{ $imageUrl }}" alt="Image" class="w-auto object-contain max-h-[500px] rounded ">
                    </div>
                @endif

                {{-- Texte --}}
                <div class="flex">
                    @if ($text)
                        <div
                            class="prose prose-headings:mt-4 prose-headings:mb-1 prose-p:my-1 prose-ul:my-1 prose-li:my-0 prose-li:mb-1  max-w-2xl text-gray-700 {{ $mode === 'preview' ? 'prose-sm' : '' }}">
                            {!! $text !!}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>
