<?php
/**
* @package      mod_pf_calendar
*
* @author       Tobias Kuhn (eaxs)
* @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
* @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
**/

defined('_JEXEC') or die();


if (!jimport('projectfork.framework')) {
    echo JText::_('MOD_PF_GANTT_PROJECTFORK_LIB_NOT_INSTALLED');
    return;
}

if (!PFApplicationHelper::exists('com_projectfork')) {
    echo JText::_('MOD_PF_GANTT_PROJECTFORK_NOT_INSTALLED');
    return;
}

// Get the helper class
require_once dirname(__FILE__) . '/helper.php';

modPFcalendarHelper::init($params, $module->id);
$items = modPFcalendarHelper::getItems();

$months = array(
    JText::_('JANUARY'), JText::_('FEBRUARY'), JText::_('MARCH'),
    JText::_('APRIL'), JText::_('MAY'), JText::_('JUNE'),
    JText::_('JULY'), JText::_('AUGUST'), JText::_('SEPTEMBER'),
    JText::_('OCTOBER'), JText::_('NOVEMBER'), JText::_('DECEMBER')
);

$days = array(
    JText::_('SUNDAY'), JText::_('MONDAY'), JText::_('TUESDAY'),
    JText::_('WEDNESDAY'), JText::_('THURSDAY'), JText::_('FRIDAY'),
    JText::_('SATURDAY')
);

$days_short = array(
    JText::_('SUN'), JText::_('MON'), JText::_('TUE'),
    JText::_('WED'), JText::_('THU'), JText::_('FRI'),
    JText::_('SAT')
);



// Include layout
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$option = JRequest::getVar('option');
if ($option == 'com_projectfork')//redacron alteration. Calendar should show up only on projects
require JModuleHelper::getLayoutPath('mod_pf_calendar', $params->get('layout', 'default'));
