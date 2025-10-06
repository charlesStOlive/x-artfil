@php
    $block = \App\Services\BlockDataParser::createBlockFromPreview('hero', get_defined_vars());
@endphp

<div class="relative">
    <x-blocks.hero :block="$block" mode="preview" />
    @if($block['data']['is_hidden'] ?? false)
        <div class="absolute inset-0 bg-white/70 flex items-center justify-center pointer-events-none z-10">
        </div>
    @endif
</div>