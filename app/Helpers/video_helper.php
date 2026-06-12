<?php

if (! function_exists('video_embed_url')) {
    /**
     * Converte qualquer formato em URL de embed limpa.
     * Usa youtube-nocookie.com para evitar o Erro 153 (referrer policy).
     * Remove o parâmetro ?si= (session tracker do YouTube que pode causar bloqueios).
     *
     * Aceita:
     *   - Link curto:   https://youtu.be/ID?si=...
     *   - Link longo:   https://www.youtube.com/watch?v=ID
     *   - Shorts:       https://youtube.com/shorts/ID
     *   - Embed direta: https://www.youtube.com/embed/ID
     *   - Código iframe completo: <iframe ... src="..." ...></iframe>
     *   - Vimeo:        https://vimeo.com/ID
     */
    function video_embed_url(?string $url): ?string
    {
        if (empty($url)) return null;

        $url = trim($url);

        // ── 1. Código <iframe> completo — extrai o src ─────────────────────
        if (str_contains($url, '<iframe')) {
            if (preg_match('/src=["\']([^"\']+)["\']/', $url, $m)) {
                return video_embed_url(html_entity_decode($m[1]));
            }
            return null;
        }

        // ── 2. Já é embed do YouTube — normaliza para nocookie ─────────────
        if (preg_match('/(?:youtube(?:-nocookie)?\.com)\/embed\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }

        // ── 3. Já é embed do Vimeo ─────────────────────────────────────────
        if (str_contains($url, 'player.vimeo.com/video/')) {
            return $url;
        }

        // ── 4. YouTube watch: youtube.com/watch?v=ID ──────────────────────
        if (preg_match('/youtube\.com\/watch\?.*[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }

        // ── 5. YouTube link curto: youtu.be/ID ────────────────────────────
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }

        // ── 6. YouTube Shorts: youtube.com/shorts/ID ──────────────────────
        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/' . $m[1];
        }

        // ── 7. Vimeo: vimeo.com/ID ─────────────────────────────────────────
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }
}

if (! function_exists('video_watch_url')) {
    /**
     * Retorna a URL de assistir (youtu.be ou youtube.com/watch) a partir
     * de qualquer formato: link curto, link longo, embed ou código iframe.
     * Útil para o link "Abrir no YouTube".
     */
    function video_watch_url(?string $url): ?string
    {
        if (empty($url)) return null;

        $url = trim($url);

        // Extrai src de iframe
        if (str_contains($url, '<iframe')) {
            if (preg_match('/src=["\']([^"\']+)["\']/', $url, $m)) {
                return video_watch_url(html_entity_decode($m[1]));
            }
            return null;
        }

        // Já é link curto
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://youtu.be/' . $m[1];
        }

        // Link longo com v=
        if (preg_match('/youtube\.com\/watch\?.*[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://youtu.be/' . $m[1];
        }

        // URL de embed (youtube.com/embed/ ou youtube-nocookie.com/embed/)
        if (preg_match('/(?:youtube(?:-nocookie)?\.com)\/embed\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://youtu.be/' . $m[1];
        }

        // Shorts
        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://youtu.be/' . $m[1];
        }

        // Vimeo — retorna como está
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://vimeo.com/' . $m[1];
        }

        return null;
    }
}
