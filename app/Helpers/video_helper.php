<?php

if (! function_exists('video_embed_url')) {
    /**
     * Converte qualquer formato de vídeo em URL de embed limpa.
     * Aceita: URL curta, URL longa, URL de embed direta, ou código <iframe> completo.
     *
     * Exemplos aceitos:
     *   https://youtu.be/eL0nBX0jVBI?si=_w5nKcU_1ZB93YKX
     *   https://www.youtube.com/watch?v=dQw4w9WgXcQ
     *   https://youtube.com/shorts/dQw4w9WgXcQ
     *   https://vimeo.com/123456789
     *   https://player.vimeo.com/video/123456789
     *   https://www.youtube.com/embed/dQw4w9WgXcQ?si=...
     *   <iframe ... src="https://www.youtube.com/embed/..." ...></iframe>
     */
    function video_embed_url(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // ── 1. Código <iframe> completo — extrai apenas o src ───────────────
        if (str_starts_with($url, '<iframe') || str_contains($url, '<iframe ')) {
            if (preg_match('/src=["\']([^"\']+)["\']/', $url, $m)) {
                // Recursivo com a URL extraída do src
                return video_embed_url(html_entity_decode($m[1]));
            }
            return null;
        }

        // ── 2. Já é embed do YouTube — retorna limpa (mantém si= e outros params) ──
        if (str_contains($url, 'youtube.com/embed/')) {
            // Garante que começa com https
            if (str_starts_with($url, '//')) {
                $url = 'https:' . $url;
            }
            return $url;
        }

        // ── 3. Já é embed do Vimeo ───────────────────────────────────────────
        if (str_contains($url, 'player.vimeo.com/video/')) {
            return $url;
        }

        // ── 4. YouTube watch: youtube.com/watch?v=ID ─────────────────────────
        if (preg_match('/youtube\.com\/watch\?.*[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // ── 5. YouTube link curto: youtu.be/ID?si=... ────────────────────────
        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // ── 6. YouTube Shorts: youtube.com/shorts/ID ─────────────────────────
        if (preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // ── 7. Vimeo: vimeo.com/ID ────────────────────────────────────────────
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        // URL não reconhecida
        return null;
    }
}
