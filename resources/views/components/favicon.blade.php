<?php
use App\Models\Link;

if (!function_exists('getFavIcon')) {
    function getFavIcon($id, $url = null)
    {
        try {
            if ($url === null) {
                $link = Link::find($id);
                $url = $link?->link ?? '';
            }

            if ($url === '') {
                return asset('assets/linkstack/icons/website.svg');
            }

            // Vercel / container runtime has no durable local filesystem, so let the browser fetch the icon.
            if (env('VERCEL') || !is_file(base_path('.env'))) {
                return 'https://www.google.com/s2/favicons?sz=256&domain=' . rawurlencode($url);
            }

            // Use Google's Favicon API
            $faviconUrl = 'https://www.google.com/s2/favicons?sz=256&domain=' . rawurlencode($url);

            // Get the favicon and save it to the desired location
            $favicon = file_get_contents($faviconUrl);
            $filename = $id . '.png';
            $filepath = base_path('assets/favicon/icons') . '/' . $filename;
            file_put_contents($filepath, $favicon);

            return url('assets/favicon/icons/' . $filename);
        } catch (\Throwable $e) {
            if (env('VERCEL') || !is_file(base_path('.env'))) {
                return asset('assets/linkstack/icons/website.svg');
            }

            // Handle the exception by copying the default SVG favicon
            $defaultIcon = base_path('assets/linkstack/icons/website.svg');
            $filename = $id . '.svg';
            $filepath = base_path('assets/favicon/icons') . '/' . $filename;
            @copy($defaultIcon, $filepath);

            return url('assets/favicon/icons/' . $filename);
        }
    }
}