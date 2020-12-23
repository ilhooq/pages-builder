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
use app\modules\pages\models\Page;

class AdminController extends \piko\Controller
{
    public function init()
    {
        $user = Piko::get('user');

        if (!$user->getId()) {
            $router = Piko::get('router');
            Piko::$app->redirect($router->getUrl('site/default/login'));
        }
    }

    public function pagesAction()
    {
        $pages = Page::find();

        return $this->render('pages', [
            'pages' => $pages
        ]);
    }

    public function editPageAction()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $model = new Page($id);

        $this->layout = 'minimal';

        if (!empty($_POST)) {
            $model->bind($_POST);
            $model->validate() && $model->save();
        }

        if ($this->isAJaxRequest()) {
            $this->layout = false;
            header('Content-type: application/json');
            return json_encode([
                'id' => $model->id,
                'errors' => $model->errors
            ]);
        }

        $parents = Page::find();

        foreach ($parents as &$row) {
            if ($row['id'] == $model->id) unset($row);
        }

        return $this->render('page', [
            'model' => $model,
            'parents' => $parents,
            'blocks' => Page::getBlocks(),
            'uploadedFiles'   => $this->getUploadedFiles()
        ]);
    }

    public function saveGjsAction()
    {
        $this->layout = false;
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['gjs-id'])) {
            $model = new Page($data['gjs-id']);
            unset($data['gjs-id']);
            $model->content = json_encode($data);
            $model->save();
        }

        header('Content-type: application/json');
        return json_encode($data);
    }

    public function loadGjsAction()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $this->layout = false;
        $model = new Page($id);
        $data = empty($model->content) ? '{}' : $model->content;
        header('Content-type: application/json');

        return $data;
    }

    public function uploadFileAction()
    {
        $this->layout = false;

        if (!isset($_FILES['file'])) {
            throw new \RuntimeException('No uploaded file found.');
        }

        if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
            $errorMsg = array(
                1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            );

            throw new \RuntimeException($errorMsg[$_FILES['file']['error']]);
        }

        $uploadDir = Piko::getAlias('@webroot/uploads');

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0750);
        }
        $filename = basename($_FILES['file']['name']);
        $uploadfile = $uploadDir . '/' . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

            $data = [];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $uploadfile);

            if (strpos($type, 'image') !== false) {
                $size = getimagesize($uploadDir . '/' . $uploadfile);
                list($width, $height) = $size;
                $data[] = [
                    'src' => Piko::getAlias('@web/uploads/' . $filename),
                    'type' => 'image',
                    'height' => $height,
                    'width' => $width,
                ];
            } else {
                $data[] = [
                    'src' => Piko::getAlias('@web/uploads/' . $filename),
                    'type' => 'other',
                ];
            }

            finfo_close($finfo);

            header('Content-type: application/json');
            return json_encode(['data' => $data]);
        } else {
            echo "Possible file upload attack!\n";
        }

    }

    public function deleteFileAction()
    {
        $this->layout = false;

        if (isset($_POST['file'])) {
            $uploadDir = Piko::getAlias('@webroot/uploads');
            $filename = basename($_POST['file']);

            if (file_exists($uploadDir . '/' . $filename)) {
                unlink($uploadDir . '/' . $filename);
            }
        }
    }

    protected function getUploadedFiles()
    {
        $uploadDir = Piko::getAlias('@webroot/uploads');
        $files = scandir($uploadDir);

        $list = [];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        foreach ($files as $file) {
            if (!in_array($file,['.','..'])) {

                $type = finfo_file($finfo, $file);

                if (strpos($type, 'image') !== false) {
                    $size = getimagesize($uploadDir . '/' . $file);
                    list($width, $height) = $size;
                    $list[] = [
                        'src' => Piko::getAlias('@web/uploads/' . $file),
                        'type' => 'image',
                        'height' => $height,
                        'width' => $width,
                    ];
                } else {
                    $list[] = [
                        'src' => Piko::getAlias('@web/uploads/' . $file),
                        'type' => 'other',
                    ];
                }
            }
        }

        finfo_close($finfo);

        return $list;
    }

    /**
     * Check AJAX Request
     * @return boolean
     */
    protected function isAJaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
               && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}