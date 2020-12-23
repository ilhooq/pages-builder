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

$this->title = $model->title;
// $this->params['breadcrumbs'][] = $this->title;

if (!empty($model->meta_description)) {
    $this->head[] = '<meta name="description" content="' . $this->escape($model->meta_description) .'">';
}

if (!empty($model->meta_keywords)) {
    $this->head[] = '<meta name="keywords" content="' . $this->escape($model->meta_keywords) .'">';
}

if (!empty($model->content)) {
    $data = json_decode($model->content, true);

    $html = $data['gjs-html'];
    $this->trigger('beforeRenderGjsHtml', [&$html, $model]);

    if (!empty($data['gjs-css'])) {
        $this->registerCSS($data['gjs-css']);
    }

    echo $html;
}
?>

