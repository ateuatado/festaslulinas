<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// === Rotas públicas ===
$routes->get('/', 'Home::index');
$routes->get('festa/(:any)', 'Festa::ver/$1');  // aceita slug ou id numérico
$routes->get('loja', 'Loja::index');   // Loja geral pública (sem festaId)

// === Shield — login, registro, logout, etc. ===
service('auth')->routes($routes);

// === Carrinho — público (ver e montar sem login) ===
$routes->get('carrinho', 'Carrinho::index');
$routes->post('carrinho/adicionar', 'Carrinho::adicionar');
$routes->post('carrinho/remover/(:num)', 'Carrinho::remover/$1');
$routes->post('carrinho/limpar', 'Carrinho::limpar');
// Finalizar exige login
$routes->post('carrinho/finalizar', 'Carrinho::finalizar', ['filter' => 'session']);

// === Dashboard (exige login) ===
$routes->group('dashboard', ['filter' => 'session'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('nova', 'Dashboard::nova');
    $routes->post('salvar', 'Dashboard::salvar');
    $routes->get('editar/(:num)', 'Dashboard::editar/$1');
    $routes->post('atualizar/(:num)', 'Dashboard::atualizar/$1');
    $routes->get('excluir/(:num)', 'Dashboard::excluir/$1');
});

// === Perfil de Festeiro (exige login) ===
$routes->group('perfil', ['filter' => 'session'], function ($routes) {
    $routes->get('/', 'PerfilController::index');
    $routes->post('salvar', 'PerfilController::salvar');
});

// === Painel da Festa — Blog, Links, Homenageados (exige login) ===
$routes->group('festa-panel', ['filter' => 'session'], function ($routes) {
    $routes->get('(:num)/blog',                         'FestaPanel::blog/$1');
    $routes->post('(:num)/blog/salvar',                 'FestaPanel::salvarBlog/$1');
    $routes->get('(:num)/links',                        'FestaPanel::links/$1');
    $routes->post('(:num)/links/adicionar',             'FestaPanel::adicionarLink/$1');
    $routes->post('(:num)/links/remover/(:num)',         'FestaPanel::removerLink/$1/$2');
    $routes->get('(:num)/homenageados',                 'FestaPanel::homenageados/$1');
    $routes->post('(:num)/homenageados/adicionar',      'FestaPanel::adicionarHomenageado/$1');
    $routes->post('(:num)/homenageados/remover/(:num)', 'FestaPanel::removerHomenageado/$1/$2');
});

// === Loja vinculada a uma festa (exige login) ===
$routes->group('loja', ['filter' => 'session'], function ($routes) {
    $routes->get('(:num)', 'Loja::index/$1');           // loja/{festaId}
    $routes->post('salvar/(:num)', 'Loja::salvar/$1');  // confirmar pedido
});

// === Galeria (exige login) ===
$routes->group('galeria', ['filter' => 'session'], function ($routes) {
    $routes->get('(:num)', 'Galeria::index/$1');
    $routes->post('upload/(:num)', 'Galeria::upload/$1');
    $routes->post('delete/(:num)', 'Galeria::delete/$1'); // BUG 2 FIX: POST only
});

// === Admin (exige login — controle de grupo feito no controller) ===
$routes->group('admin', ['filter' => 'session'], function ($routes) {
    $routes->get('/', 'Admin::midias');

    // Moderação de mídia
    $routes->get('midias', 'Admin::midias');
    $routes->get('midia/(:num)/(:segment)', 'Admin::statusMidia/$1/$2');

    // Apoiadores
    $routes->get('apoiadores', 'Admin::apoiadores');
    $routes->post('apoiadores/salvar', 'Admin::salvarApoiador');
    $routes->post('apoiadores/delete/(:num)', 'Admin::deletarApoiador/$1'); // BUG 2 FIX

    // Festas
    $routes->get('festas', 'Admin::festas');
    $routes->post('excluirFesta/(:num)', 'Admin::excluirFesta/$1');         // BUG 2 FIX

    // Produtos / Loja
    $routes->get('produtos', 'Admin::produtos');
    $routes->post('salvarProduto', 'Admin::salvarProduto');
    $routes->post('excluirProduto/(:num)', 'Admin::excluirProduto/$1');     // BUG 2 FIX

    // Usuários
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->post('usuarios/criar', 'Admin::criarUsuario');
    $routes->post('usuarios/grupo/(:num)/(:segment)', 'Admin::toggleGrupo/$1/$2');
    $routes->post('usuarios/desativar/(:num)', 'Admin::desativarUsuario/$1');
});
