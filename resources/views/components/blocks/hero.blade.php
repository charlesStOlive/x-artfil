@props(['block', 'mode' => 'front', 'page' => null])

@php
    $parser = \App\Services\BlockDataParser::fromBlockData($block['data'], $mode, $page);
    $title = $parser->getDataFrom('title');
    $imageUrl = $parser->getImageFrom('background_image');
    $description = $parser->getDataFrom('description');
@endphp

{{-- Structure finale avec classes dynamiques pour le mode preview --}}
<section
    class="relative flex w-full items-center justify-center min-h-[500px]
    {{ $mode === 'preview' ? 'min-h-[120px]' : '' }}"
    @if ($imageUrl) style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{{ $imageUrl }}'); background-size: cover; background-position: center;"
    @else
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" @endif>

    <div class="container mx-auto px-6 text-center text-white ">
        <h1 class="text-5xl md:text-7xl font-bold drop-shadow-lg mb-6">
            {{ $title }}
        </h1>

        @if ($description)
            <div
                class=" text-xl md:text-2xl text-white/90 drop-shadow max-w-4xl mx-auto {{ $mode === 'preview' ? 'text-sm mt-2 opacity-90 max-w-none' : '' }}">
                {{$description}}
            </div>
        @endif
    </div>
</section>
