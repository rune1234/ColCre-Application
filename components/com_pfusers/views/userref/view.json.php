<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_pfusers
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.view');
jimport('projectfork.library');

/**
 * User Reference view class.
 *
 */
class PFusersViewUserRef extends JViewLegacy
{
    protected $items;


    /**
     * Generates a list of JSON items.
     *
     * @return    void
     */
    public function display($tpl = null)
    {
        $user   = JFactory::getUser();
        $access = JRequest::getUInt('filter_access');

        // No access if not logged in
        if ($user->id == 0) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }

        // Check Access for non-admins
        if (!$user->authorise('core.admin')) {
            $allowed = PFAccessHelper::getGroupsByAccessLevel($access, true);
            
            $groups  = $user->getAuthorisedGroups();

            $can_access = false;

            foreach ($groups AS $group)
            {
                if (in_array($group, $allowed)) {
                    $can_access = true;
                    break;
                }
            }

            if (!$can_access) {
                JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
                return false;
            }
        }
        $task_id = JRequest::getInt('task_id');
        $option = JRequest::getVar('option');
        if ($task_id > 0) $this->items = $this->getProjectUsers($task_id);//
        else  $this->items = $this->get('Items');
        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

        parent::display($tpl);
    }
    public function getProjectUsers($id)//redacron function.It is used to assign tasks only to project mebmers
    {
        /*{"total":20,"items":[{"id":893,"text":"[carborrtek] Pedro RP"},{"id":902,"text":"[Cashin] Cashin"},{"id":886,"text":"[Colcre adm] Colcre adm"},{"id":883,"text":"[pcyahoo] Pedro Carbonell"},{"id":918,"text":"[Pedrohot] Pedrohot"},{"id":864,"text":"[rhoegh] Rune"},{"id":860,"text":"[rpcarnell] Pedro Carbonell"},{"id":884,"text":"[rpgmail] rpc pedo"},{"id":887,"text":"[runelf] Rune Fritzen"},{"id":859,"text":"[rune_colcre] Super User"}]}*/
         
        $db    = JFactory::getDbo();
        //$query = $db->getQuery(true);
        $query = "SELECT project_id FROM #__pf_tasks WHERE id = $id LIMIT 1";
        $db->setQuery($query);
        $project_id = $db->loadResult();
       if (!is_numeric($project_id) || $project_id < 1) return false;
        $items = array();
        $query = $db->getQuery(true);
        $query->select('u.id, a.user_id, u.username, u.name')
              ->from('#__pf_project_members AS a')
              ->join('INNER', '#__users AS u ON u.id = a.user_id');
             // ->where('a.project_id = ' . (int)$project_id);
             //->where('a.item_id = ' . (int) $item_id);*/
 
        $db->setQuery((string) $query);
        $items = (array) $db->loadObjectList();
        
        return $items;
    }
}
