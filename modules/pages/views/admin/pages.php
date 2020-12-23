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
/* @var $router \piko\Router */
/* @var $pages array */

$router = Piko::get('router');

$this->title = Piko::t('pages', 'Manage pages');

$confirmMsg = Piko::t('pages', 'Are you sure you want to perform this action?');
$script = <<<JS
$(document).ready(function() {
    $('#delete').click(function(e) {
        if (confirm('{$confirmMsg}')) {
            $('#admin-form').attr('action', '/pages/admin/delete-page')
            $('#admin-form').submit()
        }
    });
});
JS;
$this->registerJs($script);

$pageTitles = [];

foreach ($pages as $page) {
    $pageTitles[$page['id']] = $page['title'];
}

?>
<div class="container-fluid">

<h1 class="h3"><?= $this->title ?></h1>

<form action="" method="post" id="admin-form">

  <div class="btn-group mb-4" role="group">
    <a href="<?= $router->getUrl('pages/admin/edit-page') ?>" class="btn btn-primary btn-sm"><?= Piko::t('pages', 'New page') ?></a>
    <button type="button" class="btn btn-danger btn-sm" id="delete"><?= Piko::t('pages', 'Delete') ?></button>
  </div>

  <table id="pags-table" class="table table-striped">
    <thead>
      <tr>
        <th><?= Piko::t('pages', 'Title') ?></th>
        <th><?= Piko::t('pages', 'Alias') ?></th>
        <th><?= Piko::t('pages', 'Parent') ?></th>
        <th><?= Piko::t('pages', 'Menus') ?></th>
        <th><?= Piko::t('pages', 'Order') ?></th>
        <th><?= Piko::t('pages', 'Author') ?></th>
        <th><?= Piko::t('pages', 'Created at') ?></th>
        <th><?= Piko::t('pages', 'Modified at') ?></th>
        <th><?= Piko::t('pages', 'Id') ?></th>
      </tr>
    </thead>
    <tbody>
<?php foreach($pages as $page): ?>
      <tr>
        <td>
          <input type="checkbox" name="items[]" value="<?= $page['id'] ?>">&nbsp;
          <a href="<?= $router->getUrl('pages/admin/edit-page', ['id' => $page['id']])?>"><?= $page['title'] ?></a>
        </td>
        <td><?= $page['alias'] ?></td>
        <td><?= isset($pageTitles[$page['parent_id']]) ? $pageTitles[$page['parent_id']] : '' ?></td>
        <td><?= $page['menus'] ?></td>
        <td><?= $page['order'] ?></td>
        <td><?= $page['author']?></td>
        <td><?= date('Y-m-d H:i', $page['created_at']) ?></td>
        <td><?= date('Y-m-d H:i', $page['modified_at']) ?></td>
        <td><?= $page['id'] ?></td>
    </tr>
<?php endforeach ?>
	</tbody>
  </table>
</form>
</div>
