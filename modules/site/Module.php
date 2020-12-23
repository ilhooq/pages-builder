<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\site;

use piko\Piko;

class Module extends \piko\Module
{
    public function bootstrap()
    {
        /* @var $view \Piko\View */
        $view = Piko::get('view');
        $module = $this;

        $view->on('beforeRenderGjsHtml', function(&$html, $model) use ($module) {
            if (strpos($html, '{{CONTACT_FORM}}')) {
                $form = $module->run('default', 'contact');
                $html = str_replace('{{CONTACT_FORM}}', $form, $html);
            }
        });
    }
}