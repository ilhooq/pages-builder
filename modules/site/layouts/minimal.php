<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
use piko\Piko;

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

  <?= $content ?>
  <script src="<?= Piko::getAlias('@web/assets/js/jquery.min.js') ?>"></script>
  <script src="<?= Piko::getAlias('@web/assets/js/bootstrap.min.js') ?>"></script>
  <?= $this->endBody() ?>
</body>
</html>
