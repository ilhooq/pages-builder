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

class InstallController extends \piko\Controller
{
    public function processAction()
    {
        $this->layout = 'minimal';

        $message = '';

        if (!$this->tableExists()) {
            $db = Piko::get('db');
            $query = $this->getSql();

            if ($db->exec($query) === false) {
                $error = $db->errorInfo();
                throw new \RuntimeException("Install failed with error : {$error[2]}");
            }

            $message = Piko::t('pages', 'Installation success!');
        } else {
            $message = Piko::t('pages', 'Already installed');
        }

        return $this->render('process', [
            'message' => $message
        ]);
    }

    /**
     * @return \PDOStatement or false
     */
    protected function tableExists()
    {
        /* @var $db \piko\Db */
        $db = Piko::get('db');
        return $db->query('SELECT id FROM page');
    }


    protected function getSql()
    {
        $sql = <<<QUERY
CREATE TABLE page (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  parent_id INTEGER,
  title TEXT NOT NULL,
  `alias` TEXT NOT NULL,
  content TEXT NOT NULL,
  meta_description TEXT,
  meta_keywords TEXT,
  menus TEXT,
  author TEXT,
  created_at INTEGER,
  modified_at INTEGER,
  `order` INTEGER
);
QUERY;

        return $sql;
    }
}