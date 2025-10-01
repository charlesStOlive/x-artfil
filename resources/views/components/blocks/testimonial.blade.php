@props(['block', 'mode' => 'front', 'page' => null])

@php
    $parser = \App\Services\BlockDataParser::fromBlockData($block['data'], $mode, $page);
    $title = $parser->getDataFrom('title');
    $description = $parser->getHtmlFrom('description');
    $photoUrl = $parser->getImageFrom('author_image'); // Collection = nom du champ
@endphp

{{-- Structure finale avec classes dynamiques pour le mode preview --}}
<section class="py-16 bg-gray-50 {{ $mode === 'preview' ? 'py-8 relative' : '' }}">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            
            {{-- Citations stylées --}}
            <div class="relative">
                <div class="text-6xl text-indigo-200 font-serif leading-none mb-4 {{ $mode === 'preview' ? 'text-3xl mb-2' : '' }}">"</div>
                
                {{-- Témoignage --}}
                @if ($description)
                    <blockquote class="text-xl md:text-2xl text-gray-700 font-light leading-relaxed mb-8 {{ $mode === 'preview' ? 'text-sm mb-4' : '' }}">
                        {!! $description !!}
                    </blockquote>
                @else
                    <blockquote class="text-xl text-gray-400 italic {{ $mode === 'preview' ? 'text-sm' : '' }}">
                        Aucun témoignage saisi...
                    </blockquote>
                @endif
                
                <div class="text-6xl text-indigo-200 font-serif leading-none mb-8 {{ $mode === 'preview' ? 'text-3xl mb-4' : '' }}" style="transform: rotate(180deg); display: inline-block;">"</div>
            </div>
            
            {{-- Auteur avec photo --}}
            <div class="flex items-center justify-center space-x-4">
                @if ($photoUrl)
                    <div class="w-16 h-16 ">
                        <img 
                            src="{{ $photoUrl }}" 
                            alt="{{ $title }}"
                            class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg"
                        >
                    </div>
                @endif
                
                @if ($title)
                    <div class="text-lg font-semibold text-gray-900 {{ $mode === 'preview' ? 'text-sm' : '' }}">
                        {{ $title }}
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</section>

