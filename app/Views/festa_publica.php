<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($festa['nome_festa']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-danger text-white py-5 mb-5 shadow-sm">
    <div class="container text-center">
        <h6 class="text-uppercase text-warning fw-bold mb-2">Registro Oficial</h6>
        <h1 class="display-4 fw-bold mb-3"><?= esc($festa['nome_festa']) ?></h1>
        
        <div class="d-flex justify-content-center gap-4 flex-wrap text-white-50 fs-5">
            <span><i class="bi bi-calendar-event"></i> <?= date('d/m/Y', strtotime($festa['data_hora'])) ?></span>
            <span><i class="bi bi-geo-alt-fill"></i> <?= esc($festa['cidade']) ?> - <?= esc($festa['uf']) ?></span>
        </div>
        
        <?php if(!empty($festa['organizacao'])): ?>
            <p class="mt-3 mb-0"><span class="badge bg-warning text-dark"><?= esc($festa['organizacao']) ?></span></p>
        <?php endif; ?>
    </div>
</div>

<div class="container pb-5">
    
    <?php if(!empty($festa['descricao'])): ?>
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <p class="lead text-muted"><?= esc($festa['descricao']) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if(empty($midias)): ?>
        <div class="text-center py-5 text-muted bg-light rounded border border-dashed">
            <i class="bi bi-images fs-1 d-block mb-3"></i>
            <h4>Galeria em breve</h4>
            <p>As fotos deste evento ainda estão sendo selecionadas.</p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            <?php foreach($midias as $item): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        
                        <div class="ratio ratio-4x3 bg-light">
                            <?php if ($item['tipo'] == 'video'): ?>
                                <video src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" controls class="object-fit-cover w-100 h-100"></video>
                            <?php else: ?>
                                <a href="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" target="_blank">
                                    <img src="<?= base_url('uploads/galeria/' . $item['arquivo']) ?>" 
                                         class="object-fit-cover w-100 h-100 hover-zoom" 
                                         alt="Registro da Festa">
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="text-center mt-5">
        <a href="<?= base_url() ?>" class="btn btn-outline-danger btn-sm">
            &larr; Ver outras festas
        </a>
    </div>

</div>

<style>
    .hover-zoom {
        transition: transform 0.3s ease;
    }
    .hover-zoom:hover {
        transform: scale(1.05);
    }
</style>

<?= $this->endSection() ?>