<?php

/**
 * Group.Split API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_group_split_spec(&$spec) {
  $spec['id']['title']            = ts('Base group ID');
  $spec['id']['type']             = CRM_Utils_Type::T_INT;
  $spec['id']['FKClassName']      = 'CRM_Contact_DAO_Group';
  $spec['id']['api.required']     = 1;
  $spec['id']['api.aliases']      = array('group_id');

  $spec['size']['title']          = ts('Size of split groups');
  $spec['size']['type']           = CRM_Utils_Type::T_INT;
  $spec['size']['api.required']   = 1;

  $spec['suffix']['title']        = ts('To be added to the base group name, followed by a number');
  $spec['suffix']['type']         = CRM_Utils_Type::T_STRING;
  $spec['suffix']['api.required'] = 1;
  $spec['suffix']['api.default']  = '_G';
}

/**
 * Group.Split API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_group_split($params) {
  // Validate params
  if ($params['id'] <= 0 || $params['size'] <= 0) {
    throw new API_Exception('Group.Split requires positive id and size parameters', 'mandatory_missing');
  }
  // This doesn't seem to work in the spec above
  if (empty($params['suffix'])) {
    $params['suffix'] = '_G';
  }

  // Get group details and members
  // This will throw an exception if the base group doesn't exist
  $group   = civicrm_api3('Group', 'getsingle', array('id' => $params['id']));
  $members = array_keys(CRM_Contact_BAO_Group::getMember($params['id']));
  $chunks  = array_chunk($members, $params['size']);
  // group_type is returned in a different format to how it is passed
  $group_type = array();
  if (isset($group['group_type']) && is_array($group['group_type'])) {
    foreach ($group['group_type'] as $gtid) {
      $group_type[$gtid] = 1;
    }   
  }

  $returnValues = array();
  foreach ($chunks as $key => $chunk) {
    // Create a split group
    // This will throw an exception if a (sub)group already exists with this title
    $subgroup = civicrm_api3('Group', 'create', array(
      'title'       => $group['title'] . $params['suffix'] . ($key + 1),
      'description' => $group['description'],
      'source'      => ts('Split from group ' . $params['id']),
      'group_type'  => $group_type,
    ));
    $subgroup = $subgroup['values'][$subgroup['id']];

    // Add a bunch of contacts to it
    list($total, $added, $notAdded) = CRM_Contact_BAO_GroupContact::addContactsToGroup($chunk, $subgroup['id']);
    $subgroup['contacts_total']     = $total;
    $subgroup['contacts_added']     = $added;
    $subgroup['contacts_not_added'] = $notAdded;

    $returnValues[$subgroup['id']] = $subgroup;
  }

  return civicrm_api3_create_success($returnValues, $params, 'Group', 'Split');
}
