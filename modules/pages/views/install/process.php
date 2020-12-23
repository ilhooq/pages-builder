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
/* @var $message string */

$this->title = 'Page builder installation';
?>

<div class="container my-5">
  <h1 class="h3"><?= $message ?></h1>
  <hr>
  <p><a class="btn btn-primary" href="<?= Piko::get('router')->getUrl('pages/admin/pages')?>">
    <?= Piko::t('pages', 'Create pages')?></a>
  </p>
</div>


