<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= esc($festa['nome_festa']) ?> — Festas Lulinas<?= $this->endSection() ?>

<?= $this->section('meta') ?>
<?php
    // ─── Dados para Open Graph ─────────────────────────────────────────
    $ogUrl   = base_url('festa/' . ($festa['slug'] ?: $festa['id']));

    // Imagem: foto do festeiro > primeira midia aprovada > logo padrao
    $ogImage = base_url('assets/img/og-default.jpg');
    if (!empty($perfil['foto'])) {
        $ogImage = base_url('uploads/perfil/' . $perfil['foto']);
    } elseif (!empty($midias)) {
        foreach ($midias as $_m) {
            if (($_m['tipo'] ?? '') !== 'video') {
                $ogImage = base_url('uploads/galeria/' . $_m['arquivo']);
                break;
            }
        }
    }

    // Descricao rica: data + hora + endereco + organizacao
    $dataFesta = date('d/m/Y', strtotime($festa['data_hora']));
    $horaFesta = date('H:i',   strtotime($festa['data_hora']));
    $endFesta  = trim(
        ($festa['logradouro'] ? $festa['logradouro'] .
            ($festa['numero'] ? ', ' . $festa['numero'] : '') . ' - ' : '') .
        $festa['cidade'] . '/' . $festa['uf']
    );
    $ogDesc = $dataFesta . ' as ' . $horaFesta . ' - ' . $endFesta;
    if (!empty($festa['organizacao']))   $ogDesc .= ' | ' . $festa['organizacao'];
    if (!empty($festa['tamanho_festa'])) $ogDesc .= ' | ' . $festa['tamanho_festa'];
?>
<!-- Open Graph (WhatsApp, Telegram, Facebook, LinkedIn) -->
<meta property="og:type"         content="website">
<meta property="og:site_name"    content="Festas Lulinas">
<meta property="og:url"          content="<?= esc($ogUrl) ?>">
<meta property="og:title"        content="<?= esc($festa['nome_festa']) ?> — Festas Lulinas">
<meta property="og:description"  content="<?= esc($ogDesc) ?>">
<meta property="og:image"        content="<?= esc($ogImage) ?>">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale"       content="pt_BR">
<!-- Twitter / X Card -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= esc($festa['nome_festa']) ?> — Festas Lulinas">
<meta name="twitter:description" content="<?= esc($ogDesc) ?>">
<meta name="twitter:image"       content="<?= esc($ogImage) ?>">
<!-- Canonical URL (SEO) -->
<link rel="canonical" href="<?= esc($ogUrl) ?>">
<!-- Referrer policy — necessário para embeds YouTube (evita Erro 153) -->
<meta name="referrer" content="strict-origin-when-cross-origin">
<!-- Schema.org Event (Google rich results) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "<?= esc($festa['nome_festa']) ?>",
  "startDate": "<?= date('c', strtotime($festa['data_hora'])) ?>",
  "location": {
    "@type": "Place",
    "name": "<?= esc($festa['local_evento'] ?? ($festa['cidade'] . '/' . $festa['uf'])) ?>",
    "address": {
      "@type": "PostalAddress",
      "streetAddress":   "<?= esc(trim(($festa['logradouro'] ?? '') . ' ' . ($festa['numero'] ?? ''))) ?>",
      "addressLocality": "<?= esc($festa['cidade'] ?? '') ?>",
      "addressRegion":   "<?= esc($festa['uf'] ?? '') ?>",
      "postalCode":      "<?= esc($festa['cep'] ?? '') ?>",
      "addressCountry":  "BR"
    }
  },
  "organizer": {
    "@type": "Organization",
    "name": "<?= esc($festa['organizacao'] ?? 'Festas Lulinas') ?>"
  },
  "image": "<?= esc($ogImage) ?>",
  "url": "<?= esc($ogUrl) ?>"
}
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ══════════════════════════════════════════════════════ -->
<!-- HERO                                                   -->
<!-- ══════════════════════════════════════════════════════ -->
<div class="text-white py-5 mb-0 shadow-sm"
     style="background:linear-gradient(135deg,#b71c1c,#c62828,#7f0000);">
    <div class="container text-center">
        <h6 class="text-uppercase fw-bold mb-2" style="color:#C9971C;letter-spacing:.15em;">
            <i class="bi bi-star-fill me-1"></i> Festa Lulina Registrada
        </h6>
        <h1 class="display-4 fw-bold mb-3" style="font-family:'Bebas Neue',Impact,sans-serif;">
            <?= esc($festa['nome_festa']) ?>
        </h1>

        <div class="d-flex justify-content-center gap-4 flex-wrap fs-5 mb-3" style="color:rgba(255,255,255,.8);">
            <span><i class="bi bi-calendar-event me-1"></i>
                <?= date('d/m/Y', strtotime($festa['data_hora'])) ?>
                às <?= date('H:i', strtotime($festa['data_hora'])) ?>
            </span>
            <span><i class="bi bi-geo-alt-fill me-1"></i>
                <?= esc($festa['cidade']) ?> — <?= esc($festa['uf']) ?>
            </span>
            <?php if (!empty($festa['local_evento'])): ?>
            <span><i class="bi bi-building me-1"></i> <?= esc($festa['local_evento']) ?></span>
            <?php endif; ?>
        </div>

        <?php if (!empty($festa['organizacao'])): ?>
            <span class="badge px-3 py-2 fs-6" style="background:#C9971C;color:#000;">
                <?= esc($festa['organizacao']) ?>
            </span>
        <?php endif; ?>
        <?php if (!empty($festa['tamanho_festa'])): ?>
            <span class="badge bg-light text-dark px-3 py-2 fs-6 ms-2">
                <i class="bi bi-people-fill me-1"></i><?= esc($festa['tamanho_festa']) ?>
            </span>
        <?php endif; ?>
    </div>
</div>

<div class="container pb-5 mt-4">

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 1) VÍDEO PRINCIPAL — embed 80% centralizado               -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <?php
        $embedPrincipal   = video_embed_url($festa['video_principal']   ?? null);
        $embedSecundario  = video_embed_url($festa['video_secundario']  ?? null);
        $fotos = array_values(array_filter($midias ?? [], fn($m) => ($m['tipo'] ?? '') !== 'video'));
    ?>
    <section class="mb-5 d-flex justify-content-center">
        <div style="width:80%;">
            <?php if ($embedPrincipal): ?>
                <div class="position-relative">
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow" id="video-wrapper-1">
                        <iframe id="video-frame-1"
                                src="<?= esc($embedPrincipal) ?>"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen class="w-100 h-100">
                        </iframe>
                    </div>
                    <!-- Fallback: aparece se o YouTube bloquear embed (Erro 153 etc.) -->
                    <div id="video-fallback-1" class="d-none text-center py-4 px-3 rounded-3 border bg-light">
                        <i class="bi bi-exclamation-circle text-warning fs-1 d-block mb-3"></i>
                        <p class="fw-semibold mb-2">Este vídeo não pode ser exibido aqui diretamente.</p>
                        <p class="text-muted small mb-3">O dono do vídeo desativou a incorporação em sites externos.</p>
                        <?php $watchUrl1fb = video_watch_url($festa['video_principal'] ?? null); ?>
                        <a href="<?= esc($watchUrl1fb ?: 'https://youtube.com') ?>" target="_blank" rel="noopener"
                           class="btn btn-danger fw-bold">
                            <i class="bi bi-youtube me-2"></i> Assistir no YouTube
                        </a>
                    </div>
                </div>
                <p class="text-center text-muted small mt-2">
                    <i class="bi bi-play-circle me-1"></i> Vídeo da Festa
                    &nbsp;·&nbsp;
                    <?php $watchUrl1 = video_watch_url($festa['video_principal'] ?? null); ?>
                    <?php if ($watchUrl1): ?>
                    <a href="<?= esc($watchUrl1) ?>" target="_blank" rel="noopener" class="text-muted">
                        Abrir no YouTube <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    <?php endif; ?>
                </p>
            <?php else: ?>
                <!-- Sem vídeo cadastrado — exibe o clipe padrão -->
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow">
                    <video src="<?= base_url('video/clip.mp4') ?>"
                           autoplay muted loop playsinline
                           class="w-100 h-100" style="object-fit:cover;">
                    </video>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 2) FESTEIRO (col-4) + TEXTO DO BLOG (col-8)               -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <?php if (!empty($perfil) || !empty($post)): ?>
    <section class="mb-5">
        <div class="row g-4 align-items-stretch">

            <!-- COL 4 — Perfil do Festeiro -->
            <?php if (!empty($perfil)): ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4"
                     style="border-radius:16px;">
                    <?php if (!empty($perfil['foto'])): ?>
                        <img src="<?= base_url('uploads/perfil/' . $perfil['foto']) ?>"
                             alt="Foto do Festeiro"
                             class="rounded-circle mx-auto mb-3 shadow"
                             style="width:110px;height:110px;object-fit:cover;border:4px solid #C9971C;">
                    <?php else: ?>
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow"
                             style="width:110px;height:110px;background:#b71c1c;">
                            <i class="bi bi-person-fill text-white" style="font-size:2.5rem;"></i>
                        </div>
                    <?php endif; ?>

                    <h5 class="fw-bold mb-0"><?= esc($perfil['nome_display'] ?? '') ?></h5>

                    <?php if (!empty($perfil['mini_curriculo'])): ?>
                    <p class="text-muted small mt-2 mb-0"><?= esc($perfil['mini_curriculo']) ?></p>
                    <?php endif; ?>

                    <div class="mt-3">
                        <span class="badge bg-danger fw-semibold">Festeiro</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- COL 8 — Texto do Blog -->
            <?php if (!empty($post)): ?>
            <div class="col-md-<?= !empty($perfil) ? '8' : '12' ?>">
                <div class="card h-100 border-0 shadow-sm p-4"
                     style="border-radius:16px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-pencil-square text-danger fs-5"></i>
                        <h5 class="fw-bold mb-0 text-danger">Sobre esta Festa</h5>
                    </div>
                    <div class="post-content" style="line-height:1.7;color:#333;">
                        <?= $post['conteudo'] ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 3) GALERIA DE FOTOS — carrossel horizontal                 -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <?php if (!empty($fotos)): ?>
    <section class="mb-5">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-images text-danger fs-5"></i>
            <h5 class="fw-bold mb-0 text-danger">Galeria</h5>
            <span class="badge bg-danger ms-1"><?= count($fotos) ?></span>
        </div>

        <!-- Carrossel de fotos com scroll horizontal -->
        <div id="galeria-scroll"
             class="d-flex gap-3 overflow-auto pb-2"
             style="scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;cursor:grab;">
            <?php foreach ($fotos as $i => $foto): ?>
            <div class="flex-shrink-0"
                 style="width:280px;scroll-snap-align:start;">
                <a href="<?= base_url('uploads/galeria/' . $foto['arquivo']) ?>"
                   target="_blank"
                   class="d-block">
                    <img src="<?= base_url('uploads/galeria/' . $foto['arquivo']) ?>"
                         alt="Foto <?= $i + 1 ?>"
                         loading="lazy"
                         class="w-100 rounded-3 shadow-sm"
                         style="height:200px;object-fit:cover;transition:transform .2s;"
                         onmouseover="this.style.transform='scale(1.03)'"
                         onmouseout="this.style.transform='scale(1)'">
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Navegação discreta por teclado/botão -->
        <div class="d-flex justify-content-end gap-2 mt-2">
            <button onclick="document.getElementById('galeria-scroll').scrollBy({left:-300,behavior:'smooth'})"
                    class="btn btn-sm btn-outline-secondary rounded-circle" style="width:34px;height:34px;padding:0;">
                ‹
            </button>
            <button onclick="document.getElementById('galeria-scroll').scrollBy({left:300,behavior:'smooth'})"
                    class="btn btn-sm btn-outline-secondary rounded-circle" style="width:34px;height:34px;padding:0;">
                ›
            </button>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 4) VÍDEO SECUNDÁRIO — embed (só se houver)                 -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <?php if ($embedSecundario): ?>
    <section class="mb-5 d-flex justify-content-center">
        <div style="width:80%;">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-camera-video text-danger fs-5"></i>
                <h5 class="fw-bold mb-0 text-danger">Mais um Vídeo</h5>
            </div>
            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow" id="video-wrapper-2">
                <iframe id="video-frame-2"
                        src="<?= esc($embedSecundario) ?>"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen class="w-100 h-100">
                </iframe>
            </div>
            <div id="video-fallback-2" class="d-none text-center py-4 px-3 rounded-3 border bg-light mt-2">
                <i class="bi bi-exclamation-circle text-warning fs-2 d-block mb-2"></i>
                <p class="text-muted small mb-2">Incorporação bloqueada pelo YouTube.</p>
                <?php $watchUrl2fb = video_watch_url($festa['video_secundario'] ?? null); ?>
                <a href="<?= esc($watchUrl2fb ?: 'https://youtube.com') ?>" target="_blank" rel="noopener"
                   class="btn btn-sm btn-danger fw-bold">
                    <i class="bi bi-youtube me-1"></i> Assistir no YouTube
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 5) HOMENAGEADOS                                            -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <?php if (!empty($homenageados)): ?>
    <section class="mb-5">
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-star-fill text-warning fs-5"></i>
            <h5 class="fw-bold mb-0 text-danger">Homenageados</h5>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            <?php foreach ($homenageados as $hom): ?>
            <div class="col text-center">
                <?php if (!empty($hom['foto'])): ?>
                    <img src="<?= base_url('uploads/homenageados/' . $hom['foto']) ?>"
                         class="rounded-circle shadow mb-2"
                         style="width:90px;height:90px;object-fit:cover;border:3px solid #C9971C;"
                         alt="<?= esc($hom['nome']) ?>"
                         onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=<?= urlencode($hom['nome']) ?>&size=90&background=1565C0&color=fff&bold=true&rounded=true'">
                <?php else: ?>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($hom['nome']) ?>&size=90&background=b71c1c&color=fff&bold=true&rounded=true"
                         class="rounded-circle shadow mb-2"
                         style="width:90px;height:90px;border:3px solid #C9971C;"
                         alt="<?= esc($hom['nome']) ?>">
                <?php endif; ?>
                <div class="fw-semibold small"><?= esc($hom['nome']) ?></div>
                <?php if (!empty($hom['descricao'])): ?>
                <div class="text-muted" style="font-size:.75rem;"><?= esc($hom['descricao']) ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 6) LINKS APROVADOS                                         -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <?php if (!empty($links)): ?>
    <section class="mb-5">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-link-45deg text-danger fs-5"></i>
            <h5 class="fw-bold mb-0 text-danger">Links</h5>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-3">
            <?php foreach ($links as $link): ?>
            <div class="col">
                <a href="<?= esc($link['url']) ?>" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-3 p-3 text-decoration-none rounded-3 border shadow-sm bg-white"
                   style="transition:box-shadow .2s;"
                   onmouseover="this.style.boxShadow='0 4px 16px rgba(183,28,28,.15)'"
                   onmouseout="this.style.boxShadow=''">
                    <i class="bi bi-box-arrow-up-right text-danger fs-5"></i>
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-truncate"><?= esc($link['titulo']) ?></div>
                        <div class="text-muted small text-truncate"><?= esc($link['url']) ?></div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Voltar -->
    <div class="text-center mt-4">
        <a href="<?= base_url('festas') ?>" class="btn btn-outline-danger btn-sm">
            &larr; Ver outras Festas Lulinas
        </a>
    </div>

</div>


<script>
// Detecta se o iframe do YouTube bloqueou (Erro 153 etc.)
// Como cross-origin não permite acesso ao conteúdo do iframe,
// usamos postMessage do YouTube para detectar o erro.
window.addEventListener('message', function(e) {
    if (!e.data || typeof e.data !== 'string') return;
    try {
        var d = JSON.parse(e.data);
        if (d.event === 'onError' || d.info === 150 || d.info === 101 || d.info === 2) {
            ['1','2'].forEach(function(n) {
                var w = document.getElementById('video-wrapper-'+n);
                var f = document.getElementById('video-fallback-'+n);
                if (w && f) { w.classList.add('d-none'); f.classList.remove('d-none'); }
            });
        }
    } catch(err) {}
});
// Também detecta via load timeout: se o iframe não carregou em 5s
['1','2'].forEach(function(n) {
    var fr = document.getElementById('video-frame-'+n);
    if (!fr) return;
    var loaded = false;
    fr.addEventListener('load', function() { loaded = true; });
    setTimeout(function() {
        if (!loaded) {
            var w = document.getElementById('video-wrapper-'+n);
            var f = document.getElementById('video-fallback-'+n);
            if (w && f) { w.classList.add('d-none'); f.classList.remove('d-none'); }
        }
    }, 8000); // 8 segundos
});
</script>
<style>
#galeria-scroll:active { cursor: grabbing; }
#galeria-scroll { scrollbar-width: thin; scrollbar-color: #b71c1c #f0f0f0; }
#galeria-scroll::-webkit-scrollbar { height: 6px; }
#galeria-scroll::-webkit-scrollbar-thumb { background: #b71c1c; border-radius: 3px; }
</style>

<?= $this->endSection() ?>