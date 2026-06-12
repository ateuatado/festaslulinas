<?php

/**
 * Gera um slug SEO-friendly a partir de uma string,
 * com suporte completo a caracteres portugueses (acentos, ç, etc.)
 */
if (! function_exists('str_to_slug')) {
    function str_to_slug(string $str): string
    {
        $str = mb_strtolower($str, 'UTF-8');

        // Transliteração PT-BR
        $from = ['á','à','ã','â','ä','é','è','ê','ë','í','ì','î','ï',
                 'ó','ò','õ','ô','ö','ú','ù','û','ü','ç','ñ','ý','ÿ'];
        $to   = ['a','a','a','a','a','e','e','e','e','i','i','i','i',
                 'o','o','o','o','o','u','u','u','u','c','n','y','y'];
        $str  = str_replace($from, $to, $str);

        // Remove tudo que não for alfanumérico ou espaço
        $str = preg_replace('/[^a-z0-9\s]/u', '', $str);
        // Substitui espaços por hífen
        $str = preg_replace('/\s+/', '-', trim($str));
        // Remove hífens duplicados
        $str = preg_replace('/-+/', '-', $str);

        return $str;
    }
}

/**
 * Gera um slug único verificando o banco antes de persistir.
 *
 * @param string   $str     Texto base (ex: nome da festa)
 * @param int|null $excludeId  ID da festa a ignorar na verificação (para edição)
 */
if (! function_exists('unique_festa_slug')) {
    function unique_festa_slug(string $str, ?int $excludeId = null): string
    {
        $base   = str_to_slug($str);
        $slug   = $base;
        $db     = \Config\Database::connect();
        $i      = 2;

        while (true) {
            $builder = $db->table('festas')->where('slug', $slug)->where('deleted_at IS NULL', null, false);
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }
            if ($builder->countAllResults() === 0) {
                break;
            }
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
