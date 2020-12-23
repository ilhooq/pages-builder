<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\pages\controllers;

use piko\Piko;
use piko\HttpException;
use app\modules\pages\models\Page;

class DefaultController extends \piko\Controller
{
    public function viewAction()
    {
        $id = isset($_GET['id'])? $_GET['id'] : '';
        $alias = isset($_GET['alias'])? $_GET['alias'] : '';
        $layout = isset($_GET['layout'])? $_GET['layout'] : '';

        if ($layout == 'false') {
            $this->layout = false;
        }

        $model = new Page();

        try {
            if ($id) {
                $model->load($id);
            } else if ($alias) {
                $model->loadAlias($alias);
            } else {
                $model->loadAlias('home');
            }
        } catch (\RuntimeException $e) {
            throw new HttpException(Piko::t('pages', 'Page not found'), 404);
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }
}