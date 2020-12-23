<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
use piko\Piko;
use app\modules\site\widgets\BootstrapNav;

/* @var $this \piko\View */
/* @var $content string */
/* @var $user \piko\User */
/* @var $router \piko\Router */

$app = Piko::$app;
$user = Piko::get('user');
$router = Piko::get('router');
?>
<!DOCTYPE html>
  <html lang="<?= $app->language ?>">
  <head>
  <meta charset="<?= $app->charset ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $this->escape($this->title) ?></title>
  <link rel="stylesheet" href="<?= Piko::getAlias('@web/assets/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= Piko::getAlias('@web/assets/css/site.css') ?>">
  <?= $this->head() ?>
</head>
<body>
  <nav id="mainnav" class="navbar navbar-expand-lg navbar-dark bg-info fixed-top">
    <div class="container">
      <a class="navbar-brand" href="<?= Piko::getAlias('@web/') ?>"><?= Piko::$app->config['siteName'] ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="mainmenu" class="collapse navbar-collapse">
      <?= BootstrapNav::render($this->params['menus']['main'], ['class' => 'navbar-nav ml-auto']) ?>
      <?php if (!$user->isGuest()): ?>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?= $router->getUrl('site/default/logout') ?>">
          <?= Piko::t('site', 'Logout')?> (<?= $user->getIdentity()->username ?>)</a>
        </li>
      </ul>
      <?php endif?>
      </div>
    </div>
  </nav>

  <div role="main" class="wrap">
    <?php if (isset($this->params['breadcrumbs'])): $count = count($this->params['breadcrumbs']) ?>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= Piko::getAlias('@web/') ?>">Home</a></li>
      <?php foreach ($this->params['breadcrumbs'] as $k => $breadcrumb): ?>
        <li class="breadcrumb-item<?= ($count == $k+1) ? ' active' : '' ?>"><?= $breadcrumb ?></li>
      <?php endforeach ?>
      </ol>
    <?php endif ?>

    <?php if (isset($this->params['message']) && is_array($this->params['message'])): ?>
    <div class="container alert alert-<?= $this->params['message']['type'] ?> alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <?= $this->params['message']['content'] ?>
    </div>
    <?php endif ?>

    <?= $content ?>
  </div>

  <footer class="footer py-2">
    <div class="container">
      <div class="row">
        <div class="col-sm">
          <span class="nav-link">&copy; <?= Piko::$app->config['siteName'] ?> <?= date('Y') ?></span>
        </div>
        <div class="col-sm">
        <?= BootstrapNav::render($this->params['menus']['footer'], ['class' => 'nav']) ?>
        </div>
        <div class="col-sm text-right">
          <span class="nav-link">
            Powered by <a href="https://github.com/piko-framework/piko" rel="external">Piko Framework</a>
          </span>
        </div>
      </div>
    </div>
  </footer>

  <script src="<?= Piko::getAlias('@web/assets/js/jquery.slim.min.js') ?>"></script>
  <script src="<?= Piko::getAlias('@web/assets/js/bootstrap.min.js') ?>"></script>

  <?= $this->endBody() ?>
</body>
</html>
