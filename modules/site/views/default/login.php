<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */

use piko\Piko;

/* @var $this piko\View */
/* @var $message array */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

if (!empty($message)) {
    $this->params['message'] = $message;
}
?>

<form action="" method="post" class="container mt-5">
  <h1><?= $this->title ?></h1>
  <hr class="container mb-5">
  <div class="form-group row">
    <label for="loginform-username" class="col-sm-2 col-form-label"><?= Piko::t('site', 'Username') ?></label>
    <div class="col-sm-10 col-md-4">
      <input type="text" id="loginform-username" class="form-control" name="username" autofocus required>
    </div>
  </div>
  <div class="form-group row">
   <label for="loginform-password" class="col-sm-2 col-form-label"><?= Piko::t('site', 'Password') ?></label>
   <div class="col-sm-10 col-md-4">
     <input type="password" id="loginform-password" class="form-control" name="password" value="" required>
   </div>
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-primary" name="login-button"><?= Piko::t('site', 'Login') ?></button>
  </div>
</form>
