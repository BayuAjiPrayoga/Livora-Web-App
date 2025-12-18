<?php

if (!function_exists('get_image_url')) {
    /**
     * Get image URL - handles both external URLs and local storage paths
     * 
     * @param string $path
     * @return string
     */
    function get_image_url($path)
    {
        if (empty($path)) {
            return asset('images/placeholder.jpg');
        }

        // If it's already a full URL (http:// or https://), return as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Otherwise, treat as storage path
        return asset('storage/' . $path);
    }
}
