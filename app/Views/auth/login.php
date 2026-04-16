<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white text-center py-3">
                    <h4 class="mb-0">Acessar Conta</h4>
                </div>
                <div class="card-body p-4">

                    <form action="<?= url_to('login') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="floatingEmailInput" class="form-label">Email</label>
                            <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="seu@email.com" value="<?= old('email') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="floatingPasswordInput" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="Sua senha" required>
                        </div>

                        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="flexCheckDefault" <?php if (old('remember')): ?> checked<?php endif ?>>
                                <label class="form-check-label" for="flexCheckDefault">
                                    Lembrar de mim
                                </label>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">Entrar</button>
                        </div>
                    </form>

                </div>
                <div class="card-footer text-center bg-light py-3">
                    <?php if (setting('Auth.allowRegistration')) : ?>
                        <p class="mb-0">Ainda não tem conta? <a href="<?= url_to('register') ?>" class="text-danger fw-bold">Cadastre-se</a></p>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>  
</div>
<?= $this->endSection() ?>