@php
    // Selon la doc Filament, toutes les données du formulaire sont disponibles comme variables
    // Variables système à exclure
    $systemVars = [
        '__env', '__data', 'obLevel', '__path', 'app', 'errors', 'settings', 'user', 
        'component', 'attributes', 'slot', '__componentOriginal', '__currentLoopData', 
        'loop', '__isset_validation_factory'
    ];
    
    // Récupérer toutes les variables définies sauf les variables système
    $allVars = get_defined_vars();
    $blockData = array_diff_key($allVars, array_flip($systemVars));
    
    // Debug : voir ce qu'on a
    \Log::info('=== CONTENT BLOCK PREVIEW VARIABLES ===', [
        'all_vars_keys' => array_keys($allVars),
        'block_data_keys' => array_keys($blockData),
        'sample_data' => array_slice($blockData, 0, 3, true) // Premier échantillon pour éviter trop de logs
    ]);
    
    // Créer la structure de bloc
    $block = [
        'type' => 'content',
        'data' => $blockData
    ];
@endphp

<div class="relative">
    <x-blocks.content :block="$block" mode="preview" />
    @if(($is_hidden ?? false))
        <div class="absolute inset-0 bg-white/70 flex items-center justify-center pointer-events-none z-10">
            <span class="text-gray-500 font-medium">Bloc masqué</span>
        </div>
    @endif
</div>