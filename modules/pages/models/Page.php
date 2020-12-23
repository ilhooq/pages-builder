<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\pages\models;

use piko\DbRecord;
use piko\Piko;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string  $title;
 * @property string  $alias;
 * @property string  $content;
 * @property string  $meta_description;
 * @property string  $meta_keywords;
 * @property string  $menus;
 * @property integer $author;
 * @property integer $created_at;
 * @property integer $modified_at;
 * @property integer $order;
 */
class Page extends DbRecord
{
    protected $tableName = 'page';

    protected $schema = [
        'id' => self::TYPE_INT,
        'parent_id' => self::TYPE_INT,
        'title' => self::TYPE_STRING,
        'alias' => self::TYPE_STRING,
        'content' => self::TYPE_STRING,
        'meta_description' => self::TYPE_STRING,
        'meta_keywords' => self::TYPE_STRING,
        'menus' => self::TYPE_STRING,
        'author' => self::TYPE_STRING,
        'created_at' => self::TYPE_INT,
        'modified_at' => self::TYPE_INT,
        'order' => self::TYPE_INT,
    ];

    public $errors = [];

    /**
     * Load row data by alias.
     * @param number $string The value of the alias.
     * @throws \RuntimeException
     */
    public function loadAlias($alias = '')
    {
        $st = $this->db->prepare('SELECT * FROM `' . $this->tableName . '` WHERE `alias` = ?');
        $st->bindParam(1, $alias, \PDO::PARAM_STR);
        $st->execute();
        $data = $st->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            throw new \RuntimeException("Error while trying to load item with alias {$alias}");
        }

        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     * @see \piko\Model::bind()
     */
    public function bind($data)
    {
        if (isset($data['menus']) && is_array($data['menus'])) {
            $data['menus'] = implode(',', $data['menus']);
        }

        parent::bind($data);
    }

    /**
     * {@inheritDoc}
     * @see \piko\Model::validate()
     */
    public function validate()
    {
        if (empty($this->title)) {
            $this->errors['title'] = Piko::t('pages', 'Title must be filled.');
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    protected function beforeSave($insert)
    {
        if (empty($this->alias)) {
            $this->alias = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', strtolower($this->title))));
        }

        if (empty($this->content)) {
            $this->content = '';
        }

        if (empty($this->author)) {
            $this->author= Piko::get('user')->getIdentity()->username;
        }

        if (empty($this->meta_description)) {
            $this->meta_description = '';
        }

        if (empty($this->meta_keywords)) {
            $this->meta_keywords = '';
        }

        if (empty($this->created_at)) {
            $this->created_at = time();
        }

        if (!empty($this->id)) {
            $this->modified_at = time();
        }

        if (empty($this->order)) {
            $this->order = 0;
        }

        return true;
    }

    public static function getBlocks()
    {
        $path = Piko::getAlias(Piko::$app->config['blocksPath']);

        if (!file_exists($path)) {
            throw \RuntimeException("Blocks path ($path) was not found");
        }

        $files = scandir($path);

        $list = [];

        $matches = [];

        foreach ($files as $file) {
            if (preg_match('/(.+)\.php/', $file, $matches)) {
                $blockName = $matches[1];
                $list[$blockName] = require $path . '/' . $file;
            }
        }

        return $list;
    }

    public static function find($filters = [], $order='', $start = 0, $limit = 0)
    {
        /* @var $db \piko\Db */
        $db = Piko::get('db');
        $query = 'SELECT * FROM page';
        $where = [];

        if (!empty($filters['search'])) {
            $where[] = 'title LIKE :search';
        }

        if (!empty($filters['status'])) {
            $where[] = 'status = :status';
        }

        if (!empty($where)) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }

        $query .= ' ORDER BY ' . (empty($order) ? '`id` DESC' : $order);

        if (!empty($start)) {
            $query .= ' OFFSET ' . (int) $start;
        }

        if (!empty($limit)) {
            $query .= ' LIMIT ' . (int) $limit;
        }

        $sth = $db->prepare($query);

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $sth->bindParam(':search', $search , \PDO::PARAM_STR);
        }

        if (!empty($filters['status'])) {
            $sth->bindParam(':status', $filters['status'], \PDO::PARAM_INT);
        }

        $sth->execute();

        return $sth->fetchAll();
    }
}
