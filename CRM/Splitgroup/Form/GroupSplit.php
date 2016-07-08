<?php

require_once 'CRM/Core/Form.php';

/**
 * Interface to the Group.Split API
 */
class CRM_Splitgroup_Form_GroupSplit extends CRM_Core_Form {
  /**
   * Assemble form widgets and notes
   */
  public function buildQuickForm() {
    // Get list of groups
    $groupsData = CRM_Contact_BAO_Group::getGroups(
      array('is_active' => 1, 'is_hidden' => 0),
      array('id', 'title'),
      'title ASC'
    );
    $groups = array();
    foreach ($groupsData as $group) {
      $groups[$group->id] = $group->title;
    }

    $this->addElement('select', 'group_id', ts('Group'), $groups);
    $this->addElement('text',   'size',     ts('Max size'));
    $this->addElement('text',   'suffix',   ts('Suffix'));
    $this->addElement('static', 'notes',    ts('Notes'),
<<<EOF
      <p>
        This function takes a group of contacts, and splits it into several new smaller groups,
        each with some maximum number of contacts. The original group is left untouched.
      </p>
      <ul>
        <li>
          Select the base group you want to split. It can be a smart or normal group.
          If smart, the split will be based on the most recent state of that group;
          the new groups themselves will not be smart.
        </li>
        <li>
          Choose the maximum number of contacts per subgroup.
          The last subgroup may receive less than this number of contacts.
        </li>
        <li>
          Choose a suffix to be added to the base group's title when creating the subgroups;
          this will be further followed by a number.
        </li>
      </ul>
EOF
    );

    // add form elements
    $this->addButtons(array(
      array(
        'type'      => 'submit',
        'name'      => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    // Set defaults
    $this->setDefaults(array(
      'size'   => 3000,
      'suffix' => '_G',
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * If your form requires special validation, add one or more callbacks here
   */
  function addRules() {
    $this->addRule('group_id', ts('This field is required.'),      'required');
    $this->addRule('size',     ts('This field is required.'),      'required');
    $this->addRule('suffix',   ts('This field is required.'),      'required');
    $this->addRule('size',     ts('This field must be a number.'), 'numeric');
    parent::addRules();
  }

  /**
   * Assign data for display
   */
  public function postProcess() {
    $values = $this->exportValues();
    $result = civicrm_api3('Group', 'split', array(
      'id'     => CRM_Utils_Type::validate($values['group_id'], 'Positive'),
      'size'   => CRM_Utils_Type::validate($values['size'],     'Positive'),
      'suffix' => CRM_Utils_Type::validate($values['suffix'],   'String'),
    ));
    $this->assign('subgroups', $result['values']);
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
