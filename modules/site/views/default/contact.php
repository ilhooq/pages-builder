<?php
/**
 * This file is part of Piko page builder
 *
 * @copyright 2020 Sylvain PHILIP.
 * @license LGPL-3.0; see LICENSE.txt
 * @link https://github.com/piko-framework/page-builder
 */

/* @var $this piko\View */
/* @var $message array */
/* @var $form app\modules\site\models\ContactForm */

if (!empty($message)) {
    $this->params['message'] = $message;
}
?>

<?php if (empty($message) || isset($message['danger'])): ?>
  <form action="" method="post">
    <div class="form-row mb-4">
      <div class="col-md">
        <input type="text" name="name" value="<?= $form->name ?>" class="form-control" placeholder="Name" required>
      </div>
      <div class="col-md">
        <input type="email" name="email" value="<?= $form->email ?>" class="form-control" placeholder="Email" required>
      </div>
    </div>

    <div class="form-group">
      <input type="text" name="subject" value="<?= $form->subject ?>" class="form-control" id="inputSubject" placeholder="Subject" required>
    </div>
    <div class="form-group">
      <label for="inputMessage">Message</label>
      <textarea  name="message" class="form-control" id="inputMessage" required rows="5"><?= $form->message ?></textarea>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
<?php endif ?>
