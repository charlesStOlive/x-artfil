@php
    $block = \App\Services\BlockDataParser::createBlockFromPreview('hero', get_defined_vars());
@endphp

<x-blocks.hero :block="$block" mode="preview" />