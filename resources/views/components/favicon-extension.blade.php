<?php

if (!function_exists('localIcon')) {
    function localIcon($id)
    {
        static $files = null;

        if ($files === null) {
            $directory = base_path("assets/favicon/icons");
            $files = is_dir($directory) ? (scandir($directory) ?: []) : [];
        }

        $pathinfo = "error.error";
        foreach ($files as $file) {
            if (strpos($file, $id . '.') !== false) {
                $pathinfo = $id . "." . pathinfo($file, PATHINFO_EXTENSION);
            }
        }
        return $pathinfo;
    }
}
  
?>
