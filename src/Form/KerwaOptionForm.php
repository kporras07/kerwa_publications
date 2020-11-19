<?php

namespace Drupal\kerwa_publications\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * kerwa_option form.
 *
 * @property \Drupal\kerwa_publications\KerwaOptionInterface $entity
 */
class KerwaOptionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the Kerwa Option.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\kerwa_publications\Entity\KerwaOption::load',
      ],
      '#disabled' => !$this->entity->isNew(),
      '#required' => TRUE,
    ];

    $form['key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Key'),
      '#default_value' => $this->entity->get('key'),
      '#description' => $this->t('Key of the Kerwa Option.'),
      '#required' => TRUE,
    ];

    $form['value'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Value'),
      '#default_value' => $this->entity->get('value'),
      '#description' => $this->t('Value of the Kerwa Option.'),
      '#required' => TRUE,
    ];

    $form['language'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Language'),
      '#default_value' => $this->entity->get('language'),
      '#description' => $this->t('Language of the Kerwa Option.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new Kerwa Option %label.', $message_args)
      : $this->t('Updated Kerwa Option %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
