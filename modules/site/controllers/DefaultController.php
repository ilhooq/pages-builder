<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\site\controllers;

use piko\Piko;
use app\modules\site\models\ContactForm;
use app\modules\site\models\User;

class DefaultController extends \piko\Controller
{
    public function indexAction()
    {
        return $this->render('index');
    }

    public function contactAction()
    {
        $form = new ContactForm();
        $message = [];

        if (!empty($_POST)) {
            $form->bind($_POST);

            if ($form->validate() && $form->sendMessage()) {
                $message['type'] = 'success';
                $message['content'] = Piko::t('site', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                $message['type'] = 'danger';
                $message['content'] = Piko::t('site', 'An error occured. Please try again later.');
            }
        }

        return $this->render('contact', [
            'form' => $form,
            'message' => $message,
        ]);
    }

    public function loginAction()
    {
        $message = [];

        if (!empty($_POST)) {
            $userIdentity = User::findByUsername($_POST['username']);

            if ($userIdentity instanceof User) {
                if ($userIdentity->validatePassword($_POST['password'])) {
                    $user = Piko::get('user');
                    $router = Piko::get('router');
                    $user->login($userIdentity);
                    return Piko::$app->redirect($router->getUrl('pages/admin/pages'));
                }
            }
            $message = ['type' => 'danger', 'content' => Piko::t('site', 'Auhtentication failed')];
        }

        return $this->render('login', ['message' => $message]);
    }

    public function logoutAction()
    {
        $user = Piko::get('user');
        $user->logout();
        Piko::$app->redirect('/');
    }

    public function errorAction()
    {
        return $this->render('error', [
            'exception' => Piko::get('exception')
        ]);
    }
}