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
/* @var $model \app\modules\pages\models\Page */
/* @var $parents array */
/* @var $blocks array */
/* @var $uploadedFiles array */
/* @var $router \piko\Router */

$id = $model->id ? $model->id : 0;
$router = Piko::get('router');
$this->title = empty($id) ? Piko::t('pages', 'New page') : Piko::t('pages', 'Edit page');

$menus = Piko::$app->config['menus'];

$this->registerCSSFile(Piko::getAlias('@web/assets/css/grapes.min.css'));
$this->registerJsFile(Piko::getAlias('@web/assets/js/grapes.min.js'));
$this->registerJsFile(Piko::getAlias('@web/assets/js/grapesjs-blocks-bootstrap4.min.js'));
$this->registerJsFile(Piko::getAlias('@web/assets/js/grapesjs-custom-code.min.js'));

$loadUrl = $router->getUrl('pages/admin/load-gjs', ['id' => $id]);
$saveUrl = $router->getUrl('pages/admin/save-gjs');
$uploadUrl = $router->getUrl('pages/admin/upload-file');
$deleteUrl = $router->getUrl('pages/admin/delete-file');

$bootstrapStyleUrl = Piko::getAlias('@web/assets/css/bootstrap.min.css');
$jqueryUrl = Piko::getAlias('@web/assets/js/jquery.slim.min.js');
$bootstrapScriptUrl = Piko::getAlias('@web/assets/js/bootstrap.min.js');

$assets = json_encode($uploadedFiles);
$blocks = json_encode($blocks);

$script = <<<JS
$(function() {
    var id = $id;
    var blocks = $blocks;

    var editor = grapesjs.init({
        container : '#gjs',
        plugins: [
            'grapesjs-blocks-bootstrap4',
            'grapesjs-custom-code'
        ],
        assetManager: {
            assets: $assets,
            upload: '{$uploadUrl}',
            uploadName: 'file',
            multiUpload: false,
            autoAdd: 1
        },
        storageManager: {
            type: 'remote',
            autosave: false,
            urlStore: '{$saveUrl}',
            urlLoad: '{$loadUrl}',
            params: {id: id}
        },
        canvas: {
            styles: [
              '{$bootstrapStyleUrl}'
            ],
            scripts: [
              '{$jqueryUrl}',
              '{$bootstrapScriptUrl}'
            ]
        }
    });

    editor.on('storage:start:store', function(store) {
        store.id = id
    });

    editor.on('asset:remove', function(asset) {
        $.post('{$deleteUrl}', {file: asset.id});
    });

    for (var blockName in blocks) {
      editor.BlockManager.add(blockName, blocks[blockName]);
    }

    const pm = editor.Panels;

    pm.addButton('options',{
      id: 'undo',
      className: 'fa fa-undo',
      command: 'core:undo',
      attributes: { title: 'Undo'},
    });

    pm.addButton('options',{
      id: 'undo',
      className: 'fa fa-repeat',
      command: 'core:redo',
      attributes: { title: 'Redo'},
    });

    $('#form-page').submit(function(event) {
        event.preventDefault();

        $('#save-loader').show();

        $('.invalid-feedback').empty();
        $('.is-invalid').removeClass('is-invalid');

        $.post(this.action.replace(/id=\d+/, 'id=' + id), $(this).serialize(), function(data) {
            id = data.id;

            $.each(data.errors, function( key, value ) {
                if ($('#' + key).length) {
                    $('#' + key).addClass('is-invalid');
                    $('#' + key).next('.invalid-feedback').text(value);
                }
            });

            if (data.errors.length == 0) {
                editor.store(function(res) {
                    $('#save-loader').hide();
                });
            } else {
                $('#save-loader').hide();
            }
        }, 'json');
    });
});
JS;

$this->registerJs($script);
?>

<form method="post" action="<?= $router->getUrl('pages/admin/edit-page', ['id' => $id ]) ?>" id="form-page">

  <div class="bg-dark text-light d-flex justify-content-between container-fluid p-2">
    <h1 class="h4 m-0" style="line-height: 1.5"><?= $this->title ?></h1>

    <div class="btn-group" role="group">
      <button type="submit" class="btn btn-primary">
      <span id="save-loader" style="display: none" class="loader-icon mr-2"></span>
      <?= Piko::t('pages', 'Save')?></button>
      <a href="<?= $router->getUrl('pages/admin/pages')?>" class="btn btn-secondary">
      <?= Piko::t('pages', 'Close') ?></a>
    </div>
  </div>

<div class="container-fluid">

  <div class="form-row my-2">
    <div class="col-md-3">
      <div class="row">
        <label for="title" class="col-sm-2 col-form-label"><?= Piko::t('pages', 'Title:') ?></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="title" name="title"
                 value="<?= $model->title ?>" placeholder="<?= Piko::t('pages', 'Title') ?>">
          <div class="invalid-feedback"></div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="row">
        <label for="alias" class="col-sm-2 col-form-label"><?= Piko::t('pages', 'Alias:') ?></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="alias" name="alias"
                 value="<?= $model->alias ?>" placeholder="<?= Piko::t('pages', 'Alias') ?>">
          <div class="invalid-feedback"></div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <select name="parent_id" id="parent_id" class="custom-select">
        <option value=""><?= Piko::t('pages', 'Parent') ?></option>
        <?php foreach ($parents as $row): ?>
        <option value="<?= $row['id'] ?>"<?= ($model->parent_id == $row['id'])? 'selected="selected"' : '' ?>><?= $row['title'] ?></option>
        <?php endforeach ?>
      </select>
    </div>

    <div class="col-md-2">
      <div class="row">
        <label for="order" class="col-sm-3 col-form-label"><?= Piko::t('pages', 'Order:') ?></label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="order" name="order"
                 value="<?= $model->order ?>" placeholder="<?= Piko::t('pages', 'Order') ?>">
        </div>
      </div>
    </div>

    <div class="col-md-1">
      <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#form-more"
              aria-expanded="false" aria-controls="formMore" title="<?= Piko::t('pages', 'More') ?>">+</button>
    </div>
  </div>

  <div class="collapse" id="form-more">
    <div class="form-row my-2">
      <div class="col-md-4">
        <label><?= Piko::t('pages', 'Menus:') ?></label><br>
        <?php $selected = explode(',', $model->menus) ?>
        <?php foreach ($menus as $k => $label): ?>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" id="menu-<?= $k ?>" name="menus[]" value="<?= $k ?>"
          <?php if (in_array($k, $selected)) echo 'checked'?> >
          <label class="form-check-label" for="menu-<?= $k ?>"><?= $label ?></label>
        </div>
        <?php endforeach ?>
      </div>
    </div>

    <div class="form-row my-2">
      <div class="col">
        <label for="meta_description"><?= Piko::t('pages', 'Meta-description:') ?></label>
        <textarea class="form-control" id="meta_description" name="meta_description" cols="50" rows="2"><?= $model->meta_description ?></textarea>
      </div>
      <div class="col">
        <label for="meta_keywords"><?= Piko::t('pages', 'Meta-keywords:') ?></label>
        <textarea class="form-control" id="meta_keywords" name="meta_keywords" cols="50" rows="2"><?= $model->meta_keywords ?></textarea>
      </div>
    </div>
  </div>
</div>

</form>

<div id="gjs"></div>
