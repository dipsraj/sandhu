<?php

namespace Drupal\sah_modifications\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\webform\Entity\WebformSubmission;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {
  public function get_quote() {
    $name = \Drupal::request()->request->get('name');
    $email = \Drupal::request()->request->get('email');
    $subject = \Drupal::request()->request->get('subject');
    $pages = \Drupal::request()->request->get('pages');
    $description = \Drupal::request()->request->get('description');
    $deadline = \Drupal::request()->request->get('deadline');

    if($name == null || $email == null || $subject == null || $description == null || $deadline == null || $pages == null) {
      \Drupal::messenger()->addError('All Values are required to get a quotation.');
      $redirect = new RedirectResponse('/#get-quote');;
      $redirect->send();
    }
    else {
      $price = \Drupal::config('sah_modifications.quote_price')->get('price');
      $amt = $price * $pages;
      $values = [
        'webform_id' => 'webform_quote',
        'data' => [
          'name' => $name,
          'email' => $email,
          'subject' => $subject,
          'pages' => $pages,
          'amount_per_page' => $price,
          'total_amount' => $amt,
          'assignment_description' => $description,
          'deadline' => $deadline,
        ],
      ];
      WebformSubmission::create($values)->save();

      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'sah_modifications';
      $key = 'quote_notification_user';
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;

      $params['data']['subject'] = $subject;
      $params['data']['pages'] = $pages;
      $params['data']['price'] = $price;
      $params['data']['amt'] = $amt;

      // Sending Quotation Email to the User
      $to = $email;
      $params['subject'] = 'Quotation Details at Sandhu Assignment Help';
      $params['salutation'] = "Hello ".explode(' ',trim($name))[0].",";
      $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
      if ($result)
        \Drupal::messenger()->addMessage('Email Sent Successfully.');

      \Drupal::messenger()->addMessage('Thank You. Check your email for the Quotation.');
      return $this->redirect('<front>');
    }
  }
}
