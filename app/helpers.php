<?php

if (!function_exists('admin_settings')) {
    /**
     * Get admin settings instance
     *
     * @return \App\Settings\AdminSettings
     */
    function admin_settings(): \App\Settings\AdminSettings
    {
        return app(\App\Settings\AdminSettings::class);
    }
}

if (!function_exists('admin_setting')) {
    /**
     * Get a specific admin setting value with support for nested arrays using dot notation
     *
     * @param string $key Key supports dot notation (e.g., 'construction.activate')
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    function admin_setting(string $key, $default = null)
    {
        $settings = admin_settings();
        
        // Use Arr::get for clean dot notation support
        return \Illuminate\Support\Arr::get($settings->toArray(), $key, $default);
    }
}



