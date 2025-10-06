@props([
    'title' => null,
    'couleurPrimaire' => 'secondary',
    'class' => ''
])

@if ($title)
    <div class="text-center mb-16 {{ $class }}">
        <div class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 fade-in-up font-heading {{ $couleurPrimaire === 'primary' ? 'prose-strong:text-primary' : 'prose-strong:text-secondary' }}">
            {!! $title !!}
        </div>
    </div>
@endif