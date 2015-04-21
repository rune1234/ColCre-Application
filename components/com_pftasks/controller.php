<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_pftasks
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.controller');


/**
 * Component main controller
 *
 * @see    JController
 */
class PFtasksController extends JControllerLegacy
{
    /**
     * The default view
     *
     * @var    string
     */
    protected $default_view = 'tasks';
    private function _getUserMap($user, & $db)
    {
       if (!is_numeric($user) || $user == 0) return false;
       $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id = $user LIMIT 1";
       $db->setQuery($query);
       $level = $db->loadResult();
       if ($level > 5 && $level != 9) {  return true;}
       else {   return false;}
    }
    public function finishTask()
    {
        $id = $fintask = JRequest::getInt('taskid');
        if ($id === 0)
        {
            echo "No task ID. Exiting"; exit; 
        }
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = "SELECT * FROM #__pf_tasks WHERE id = $id LIMIT 1";
        $row = $db->setQuery($query)->loadObject();
        $fintask = JRequest::getInt('finish');
        
        if (! $row) { echo "Unavailable task"; exit; }
        if ($row->created_by == $user->id) {}
        elseif ($this->_getUserMap($user->id, $db)) {}
        else  { echo "User does not have permission"; exit; }
        if ($fintask)
        $userid = $user->id;
        else $userid = 0;
        $query = "UPDATE #__pf_tasks SET complete = $fintask, completed_by = $userid WHERE id = $id LIMIT 1";
        $change = $db->setQuery($query)->Query();
        if ($change) echo "success";
        else echo "There has been an error";
        exit;
    }
    /**
     * Displays the current view
     *
     * @param     boolean    $cachable    If true, the view output will be cached  (Not Used!)
     * @param     array      $urlparams   An array of safe url parameters and their variable types (Not Used!)
     *
     * @return    JController             A JController object to support chaining.
     */
    public function display($cachable = false, $urlparams = false)
    {
        // Load CSS and JS assets
        JHtml::_('pfhtml.style.bootstrap');
        JHtml::_('pfhtml.style.projectfork');

        JHtml::_('pfhtml.script.jQuery');
        JHtml::_('pfhtml.script.bootstrap');
        JHtml::_('pfhtml.script.projectfork');

        JHtml::_('behavior.tooltip');

        $view      = JRequest::getCmd('view');
        
        $id        = JRequest::getUInt('id');
        $urlparams = array(
            'id'               => 'INT',
            'cid'              => 'ARRAY',
            'limit'            => 'INT',
            'limitstart'       => 'INT',
            'showall'          => 'INT',
            'return'           => 'BASE64',
            'filter'           => 'STRING',
            'filter_order'     => 'CMD',
            'filter_order_Dir' => 'CMD',
            'filter_project'   => 'CMD',
            'filter_milestone' => 'CMD',
            'filter_tasklist'  => 'CMD',
            'filter_search'    => 'STRING',
            'filter_published' => 'CMD'
        );

        // Inject default view if not set
        if (empty($view)) {
            JRequest::setVar('view', $this->default_view);
        }

        // Check for task edit form.
		if ($view == 'taskform' && !$this->checkEditId('com_pftasks.edit.taskform', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			//return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

        // Check for task list edit form.
		if ($view == 'tasklistform' && !$this->checkEditId('com_pftasks.edit.tasklistform', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
          $document = JFactory::getDocument();
          $js = "var projectURL = '".JURI::root()."';";
        $document->addScriptDeclaration($js);
           $document->addScript(JURI::root() . 'libraries/projectfork/js/angular.min.js');
         $document->addScript(JURI::root() . 'libraries/projectfork/js/like.js');
        // Display the view
        parent::display($cachable, $urlparams);

        // Return own instance for chaining
        return $this;
    }
}