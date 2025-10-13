@props([
    'title' => null,
    'couleurPrimaire' => 'secondary',
    'class' => '',
    'isH1' => false,
])

@if ($title)
    <div class="text-center mx-auto  {{ $class }}">
        <div
            class="font-bold text-gray-900 font-heading
            @if ($isH1) text-5xl md:text-7xl
            @else
                text-3xl md:text-5xl @endif
            @if ($couleurPrimaire === 'primary-brush') prose-brush prose-brush-primary
            @elseif ($couleurPrimaire === 'secondary-brush')
                  prose-brush prose-brush-secondary

                
                @elseif ($couleurPrimaire === 'primary')
                    prose-strong:text-primary-500
                @elseif ($couleurPrimaire === 'secondary')
                    prose-strong:text-secondary-500 @endif
">
            {!! $title !!}
        </div>
    </div>
@endif
