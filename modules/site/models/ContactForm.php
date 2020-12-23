<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */
namespace app\modules\site\models;

use piko\Piko;

/**
 * This is the model class for the contact form.
 *
 * @property string  $name;
 * @property string  $email;
 * @property string  $subject;
 * @property string  $message;
 */
class ContactForm extends \piko\Model
{
    protected $data = [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => ''
    ];

    protected $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        if (empty($this->data['name'])) {
            $this->errors['name'] = 'Name is required';
        }

        if (empty($this->data['email'])) {
            $this->errors['email'] = 'Email is required';
        }
        elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email is invalid';
        }

        if (empty($this->data['subject'])) {
            $this->errors['subject'] = 'Subject is required';
        }

        if (empty($this->data['message'])) {
            $this->errors['message'] = 'Subject is required';
        }

        return empty($this->errors) ? true : false;
    }

    public function sendMessage()
    {
        $message = 'Author: ' . $this->data['name'] . "\n"
                 . 'Email: ' . $this->data['email'] . "\n"
                 . 'Message: ' . $this->data['email'] . "\n";

        $headers = 'From: ' . $this->data['email'] . "\r\n" .
                   'Reply-To: ' . $this->data['email'] . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        $mailFrom = Piko::$app->config['contactEmail'];

        $ret = getenv('PIKO_ENV') === 'dev' ? true : mail($mailFrom, $this->data['subject'], $message, $headers);

        if ($ret === false) {
            $this->errors['send_message'] = 'Cannot send message';
        }

        return $ret;
    }
}