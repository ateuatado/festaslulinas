<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Início<?= $this->endSection() ?>

<?= $this->section('css') ?>
<style>
    .hero-section {
        background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%); /* Vermelho PT */
        color: white;
        padding: 80px 0;
    }
    .hero-title {
        font-weight: 800;
        font-size: 3.5rem;
    }
    .apoiador-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid #d32f2f;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>


<div class="w-100 bg-dark position-relative">
    <img src="<?= base_url('assets/img/banner.jpg') ?>" 
         class="d-block w-100 h-auto" 
         alt="Festas Lulinas - Guerreiro contra a fome"
         style="max-height: 80vh; object-fit: contain; background-color: #9c0909;"> </div>

<div class="bg-lulina text-center py-4 shadow-sm position-relative" style="z-index: 10; margin-top: -5px;">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-8">
                <p class="mb-3 fs-5 fw-bold text-dark d-none d-md-block">
                    Organize seu comitê, chame a comunidade e faça parte dessa história.
                </p>
                
                <div class="d-flex gap-3 justify-content-center">
                    <?php if (auth()->loggedIn()): ?>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-dark fw-bold px-4">
                            <i class="bi bi-grid-fill"></i> Acessar Painel
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('register') ?>" class="btn btn-danger btn-lg px-5 fw-bold shadow-sm">
                            <i class="bi bi-star-fill"></i> QUERO ORGANIZAR
                        </a>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-dark btn-lg px-4 fw-bold">
                            Entrar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="bg-light py-5">
    <div class="container text-center">
        <h3 class="mb-5 fw-bold">Quem Apoia essa Ideia</h3>
        
        <?php if (empty($apoiadores)): ?>
            <p class="text-muted">Galeria em atualização.</p>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 justify-content-center">
                <?php foreach ($apoiadores as $item): ?>
                    
                    <div class="col">
                        <div class="card border-0 bg-transparent h-100">
                            
                            <div class="mx-auto mb-3 position-relative" 
                                style="width:110px; height:110px;"
                                <?php if(!empty($item['frase'])): ?>
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?= esc($item['frase']) ?>"
                                <?php endif; ?> >
                                
                                <img src="<?= base_url('uploads/apoiadores/' . $item['foto']) ?>" 
                                    class="rounded-circle border border-4 border-danger object-fit-cover w-100 h-100 shadow-sm" 
                                    alt="<?= esc($item['nome']) ?>">
                            </div>
                        
                            <h5 class="fw-bold mb-0 text-dark"><?= esc($item['nome']) ?></h5>
                            <small class="text-muted"><?= esc($item['funcao']) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    // Inicializar todos os Tooltips da página
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?= $this->endSection() ?>