<?php

namespace Drupal\sah_modifications\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class QuotePriceForm.
 */
class QuotePriceForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'sah_modifications.quote_price',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'quote_price_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('sah_modifications.quote_price');
    $form['price'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Price per Page(250 words) in USD'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('price'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('sah_modifications.quote_price')
      ->set('price', $form_state->getValue('price'))
      ->save();
  }

}
