@props([
    'boutons' => [],
    'class' => '',
    ])

@if (!empty($boutons))
    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center {{ $class }}">
        @foreach ($boutons as $bouton)
            @php
                $href = match($bouton['type_lien']) {
                    'page' => ($bouton['page_id'] === 'same_page' || !isset($bouton['page_id'])) 
                        ? ($bouton['ancre'] ?? '#') 
                        : '/' . $bouton['page_id'] . (isset($bouton['ancre']) && $bouton['ancre'] ? $bouton['ancre'] : ''),
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