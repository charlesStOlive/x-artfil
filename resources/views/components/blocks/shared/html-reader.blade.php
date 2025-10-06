@props([
    'content' => null,
    'couleurPrimaire' => 'secondary',
    'styleListes' => 'alternance',
    'class' => '',
])


<div
    class="prose prose-ul:my-0 prose-ul:py-1 prose-li:my-1 prose-li:py-0 prose-li:marker:text-2xl prose-ol:my-0 prose-ol:py-1 prose-ol:li:marker:text-2xl
        {{ $couleurPrimaire === 'primary' ? 'prose-a:text-primary prose-blockquote:border-l-primary' : 'prose-a:text-secondary prose-blockquote:border-l-secondary' }}
        @if ($styleListes === 'primary') prose-li:marker:text-primary prose-ol:li:marker:text-primary
        @elseif($styleListes === 'secondary')
            prose-li:marker:text-secondary prose-ol:li:marker:text-secondary
        @else
            prose-li:odd:marker:text-primary prose-li:even:marker:text-secondary prose-ol:li:nth-child(odd):marker:text-primary prose-ol:li:nth-child(even):marker:text-secondary @endif  
        {{ $class }}">
    {!! $content !!}
</div>
