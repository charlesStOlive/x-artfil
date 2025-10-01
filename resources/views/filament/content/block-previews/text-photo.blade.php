@php
    $block = \App\Services\BlockDataParser::createBlockFromPreview('text_photo', get_defined_vars());
@endphp

<x-blocks.text-photo :block="$block" mode="preview" />