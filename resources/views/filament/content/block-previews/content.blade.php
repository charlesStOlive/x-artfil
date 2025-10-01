@php
    // Reconstruction automatique du bloc à partir des variables de preview
    $block = \App\Services\BlockDataParser::createBlockFromPreview('content', get_defined_vars());
@endphp

<x-blocks.content :block="$block" mode="preview" />