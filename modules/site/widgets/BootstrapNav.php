<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\site\widgets;

class BootstrapNav
{
    public static function render($items, $attributes = [])
    {
        $attributesStr = '';

        foreach ($attributes as $attribute => $value) {
            $attributesStr.= ' ' . $attribute . '="' . $value . '"';
        }

        $output = '<ul'. $attributesStr . '>' . PHP_EOL;

        foreach ($items as $item) {

            $class= 'nav-item';
            if ($_SERVER['REQUEST_URI'] == $item['url']) $class .= ' active';
            if (!empty($item['children'])) $class .= ' dropdown';

            $output .= '<li class="' . $class . '">';

            if (!empty($item['children'])) {
                $output .= '<a class="nav-link dropdown-toggle" href="'. $item['url'] . '" role="button"'
                         . ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                         . $item['title'] .'</a>';
                $output .= self::renderSubMenu($item['children']);
            } else {
                $output .= '<a class="nav-link" href="'. $item['url'] . '">' . $item['title'] . '</a>';
            }
            $output .= '</li>'. PHP_EOL;
        }

        $output .= '</ul>'. PHP_EOL;

        return $output;
    }

    protected static function renderSubMenu($items, $level = 0)
    {
        $output = '<ul class="dropdown-menu">' . PHP_EOL;

        foreach ($items as $item) {
            if (!empty($item['children'])) {
                $output .= '<li class="dropdown-submenu"><a class="dropdown-item" href="'. $item['url'] . '">'
                        . $item['title'] .'</a>';
                        $output .= self::renderSubMenu($item['children']);
                        $output .= '</li>'. PHP_EOL;
            } else {
                $output .= '<li class="dropdown-item"><a href="' . $item['url'] . '">' . $item['title']
                         . '</a></li>'. PHP_EOL;
            }
        }

        $output .= '</ul>'. PHP_EOL;

        return $output;
    }
}