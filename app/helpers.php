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
     * Get a specific admin setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function admin_setting(string $key, $default = null)
    {
        $settings = admin_settings();
        
        return $settings->{$key} ?? $default;
    }
}