<?php

require_once 'splitgroup.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function splitgroup_civicrm_config(&$config) {
  _splitgroup_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function splitgroup_civicrm_install() {
  _splitgroup_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function splitgroup_civicrm_enable() {
  _splitgroup_civix_civicrm_enable();
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function splitgroup_civicrm_navigationMenu(&$menu) {
  _splitgroup_civix_insert_navigation_menu($menu, 'Contacts', array(
    'name'       => ts('Split Group'),
    'url'        => 'civicrm/group/split',
    'permission' => 'access CiviCRM,edit groups',
  ));
  _splitgroup_civix_navigationMenu($menu);
}
