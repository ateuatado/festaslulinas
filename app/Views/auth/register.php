<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Cadastro<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white text-center py-3">
                    <h4 class="mb-0">Criar Nova Conta</h4>
                </div>
                <div class="card-body p-4">

                    <form action="<?= url_to('register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="floatingEmailInput" class="form-label">Email</label>
                            <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="seu@email.com" value="<?= old('email') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="floatingUsernameInput" class="form-label">Nome de Usuário</label>
                            <input type="text" class="form-control" id="floatingUsernameInput" name="username" inputmode="text" autocomplete="username" placeholder="Seu nome de usuário" value="<?= old('username') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="floatingPasswordInput" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="new-password" placeholder="Crie uma senha forte" required>
                        </div>

                        <div class="mb-3">
                            <label for="floatingPasswordConfirmInput" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="floatingPasswordConfirmInput" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="Repita a senha" required>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-danger btn-lg">Cadastrar</button>
                        </div>
                    </form>

                </div>
                <div class="card-footer text-center bg-light py-3">
                    <p class="mb-0">Já tem uma conta? <a href="<?= url_to('login') ?>" class="text-danger fw-bold">Fazer Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>