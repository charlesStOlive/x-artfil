@props([
    'description' => null,
    'class' => ''
])

@if ($description)
    <div class="text-center mb-16 {{ $class }}">
        <div class="text-lg md:text-xl text-gray-600 mb-12 max-w-4xl mx-auto fade-in-up whitespace-pre-line">
            {{ $description }}
        </div>
    </div>
@endif