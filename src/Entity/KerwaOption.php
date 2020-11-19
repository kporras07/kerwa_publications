<?php

namespace Drupal\kerwa_publications\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\kerwa_publications\KerwaOptionInterface;

/**
 * Defines the kerwa_option entity type.
 *
 * @ConfigEntityType(
 *   id = "kerwa_option",
 *   label = @Translation("Kerwa Option"),
 *   label_collection = @Translation("Kerwa Options"),
 *   label_singular = @Translation("Kerwa Option"),
 *   label_plural = @Translation("Kerwa Options"),
 *   label_count = @PluralTranslation(
 *     singular = "@count Kerwa Option",
 *     plural = "@count Kerwa Options",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\kerwa_publications\KerwaOptionListBuilder",
 *     "form" = {
 *       "add" = "Drupal\kerwa_publications\Form\KerwaOptionForm",
 *       "edit" = "Drupal\kerwa_publications\Form\KerwaOptionForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "kerwa_option",
 *   admin_permission = "administer kerwa_option",
 *   links = {
 *     "collection" = "/admin/structure/kerwa-option",
 *     "add-form" = "/admin/structure/kerwa-option/add",
 *     "edit-form" = "/admin/structure/kerwa-option/{kerwa_option}",
 *     "delete-form" = "/admin/structure/kerwa-option/{kerwa_option}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "key",
 *     "value",
 *     "language",
 *   }
 * )
 */
class KerwaOption extends ConfigEntityBase implements KerwaOptionInterface {

  /**
   * The kerwa_option ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The kerwa_option label.
   *
   * @var string
   */
  protected $label;

  /**
   * The kerwa_option key.
   *
   * @var string
   */
  protected $key;

    /**
   * The kerwa_option value.
   *
   * @var string
   */
  protected $value;

    /**
   * The kerwa_option language.
   *
   * @var string
   */
  protected $language;

  /**
   * Get the kerwa_option key.
   *
   * @return  string
   */ 
  public function getKey() {
    return $this->key;
  }

  /**
   * Set the kerwa_option key.
   *
   * @param  string  $key  The kerwa_option key.
   */ 
  public function setKey(string $key) {
    $this->key = $key;
  }

  /**
   * Get the kerwa_option value.
   *
   * @return  string
   */ 
  public function getValue() {
    return $this->value;
  }

  /**
   * Set the kerwa_option value.
   *
   * @param  string  $value  The kerwa_option value.
   */ 
  public function setValue(string $value) {
    $this->value = $value;
  }

  /**
   * Get the kerwa_option language.
   *
   * @return  string
   */ 
  public function getLanguage(){
    return $this->language;
  }

  /**
   * Set the kerwa_option language.
   *
   * @param  string  $language  The kerwa_option language.
   */ 
  public function setLanguage(string $language) {
    $this->language = $language;
  }
  
}
