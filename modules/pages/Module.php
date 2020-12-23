<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\pages;

use piko\Piko;
use piko\Controller;

class Module extends \piko\Module
{
    public function bootstrap()
    {
        $module = $this;

        Controller::when('beforeAction', function($controller, $actionId) use ($module) {
            if ($controller->id != 'install') {
                $module->buildMenus();
            }
        });
    }

    public function buildMenus()
    {
        /* @var $db \piko\Db */
        /* @var $view \piko\View */
        $db = Piko::get('db');
        $view = Piko::get('view');

        $query = 'SELECT id, parent_id, title, alias, menus, `order` FROM page ORDER BY `order` ASC';
        $sth = $db->prepare($query);

        $sth->execute();

        $items = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $menus = [];

        foreach ($items as $item) {
            if (!empty($item['menus'])) {
                $itemMenus = explode(',', $item['menus']);
                foreach ($itemMenus as $menu) {
                    $menus[$menu][] = $item;
                }
            }
        }

        foreach ($menus as $menuName => $menu) {
            $view->params['menus'][$menuName] = $this->buildMenuTree($menu);
        }
    }

    protected function buildMenuTree(&$items, $parentId = 0)
    {
        /* @var $router \piko\Router */
        $router = Piko::get('router');
        $branch = [];

        foreach ($items as &$item) {

            $item['url'] = $item['alias'] == 'home' ? Piko::getAlias('@web/') :
                           $router->getUrl('pages/default/view', ['alias' => $item['alias']]);
            if ($item['parent_id'] == $parentId) {
                $children = $this->buildMenuTree($items, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $branch[$item['id']] = $item;
                unset($item);
            }
        }

        return $branch;
    }
}