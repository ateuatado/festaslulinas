<?php

if (! function_exists('video_embed_url')) {
    /**
     * Converte uma URL normal do YouTube ou Vimeo em URL de embed.
     * Retorna null se a URL não for reconhecida.
     *
     * Exemplos aceitos:
     *   https://www.youtube.com/watch?v=dQw4w9WgXcQ
     *   https://youtu.be/dQw4w9WgXcQ
     *   https://youtube.com/shorts/dQw4w9WgXcQ
     *   https://vimeo.com/123456789
     *   https://player.vimeo.com/video/123456789   (já é embed)
     *   https://www.youtube.com/embed/dQw4w9WgXcQ  (já é embed)
     */
    function video_embed_url(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // --- Já é embed do YouTube ---
        if (str_contains($url, 'youtube.com/embed/')) {
            return $url;
        }

        // --- Já é embed do Vimeo ---
        if (str_contains($url, 'player.vimeo.com/video/')) {
            return $url;
        }

        // --- YouTube watch ---
        // https://www.youtube.com/watch?v=ID
        if (preg_match('/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // --- YouTube short URL ---
        // https://youtu.be/ID
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // --- YouTube Shorts ---
        // https://youtube.com/shorts/ID
        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // --- Vimeo ---
        // https://vimeo.com/123456789
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        // URL não reconhecida — retorna null
        return null;
    }
}
