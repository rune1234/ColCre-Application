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


jimport('joomla.application.component.view');


/**
 * Task Form View Class for Projectfork component
 *
 */
class PFtasksViewTaskForm extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $return_page;
    protected $state;
    protected $pageclass_sfx;
    protected $toolbar;


    public function display($tpl = null)
    {  
        // Initialise variables.
        $app    = JFactory::getApplication();
        $user   = JFactory::getUser();

        // Get model data.
        $this->state       = $this->get('State');
        $this->item        = $this->get('Item');
        $this->form        = $this->get('Form');
        $this->return_page = $this->get('ReturnPage');
        $this->toolbar     = $this->getToolbar();

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }
         //$access = PFtasksHelper::getActions();
         if (is_numeric($this->item->id) && $this->item->id > 0) {  $authorised = PFtasksHelper::taskPermission($this->item->id, $user->id); }//get('core.create');
         else
         {
              $project_id = $this->form->getValue('project_id');
              $authorised = PFtasksHelper::projectPerm($project_id, $user->id); 
         }
        // $authorised = true;
         //echo "reerer---->: ".$this->item->id;
        
       //print_r($user);
        
        // Permission check.
       /* if ($this->item->id <= 0) {
            $access = PFtasksHelper::getActions();
            $authorised = $access->get('core.create');
        }
        else {
            $authorised = $this->item->params->get('access-edit');
        }*/

        if ($authorised !== true) {
            //JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            echo "<h2>ERROR - YOU ARE NOT AUTHORIZED TO EDIT THIS TASK</h2>";
            return false;
        }
//echo "view is $ddview"; exit;
        //Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->state->params->get('pageclass_sfx'));

        $this->params = $this->state->params;
        $this->user   = $user;

        // Prepare the document
        $this->_prepareDocument();

        // Display the view
        parent::display($tpl);
    }


    /**
     * Prepares the document
     *
     */
    protected function _prepareDocument()
    {
        $app     = JFactory::getApplication();
        $menu    = $app->getMenu()->getActive();
        $pathway = $app->getPathway();
        $title   = null;

        $def_title = JText::_('COM_PROJECTFORK_PAGE_' . ($this->item->id > 0 ? 'EDIT' : 'ADD') . '_TASK');

        // Because the application sets a default page title, we need to get it from the menu item itself
        if ($menu) {
            if (strpos($menu->link, 'view=tasks') !== false) {
                $this->params->def('page_heading', $def_title);
            }
            else {
                $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
            }
        }
        else {
            $this->params->def('page_heading', $def_title);
        }

        $title = $this->params->def('page_title', $def_title);

        if ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }

        $this->document->setTitle($title);

        $pathway = $app->getPathWay();
        $pathway->addItem($title, '');

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }


    /**
     * Generates the toolbar for the top of the view
     *
     * @return    string    Toolbar with buttons
     */
    protected function getToolbar()
    {
        $options = array();
        $user    = JFactory::getUser();

        $create_list = true;
        $create_ms   = $user->authorise('core.create', 'com_pfmilestones');

        $options[] = array(
            'text' => 'JSAVE',
            'task' => $this->getName() . '.save');

        $options[] = array(
            'text' => 'COM_PROJECTFORK_ACTION_2NEW',
            'task' => $this->getName() . '.save2new');

        $options[] = array(
            'text' => 'COM_PROJECTFORK_ACTION_2COPY',
            'task' => $this->getName() . '.save2copy',
            'options' => array('access' => ($this->item->id > 0)));

        if ($create_list || $create_ms) {
            $options[] = array('text' => 'divider');
        }

        $options[] = array(
            'text' => 'COM_PROJECTFORK_ACTION_2MILESTONE',
            'task' => $this->getName() . '.save2milestone',
            'options' => array('access' => $create_ms));

        $options[] = array(
            'text' => 'COM_PROJECTFORK_ACTION_2TASKLIST',
            'task' => $this->getName() . '.save2tasklist',
            'options' => array('access' => $create_list));

        PFToolbar::dropdownButton($options, array('icon' => 'icon-white icon-ok'));

        PFToolbar::button(
            'JCANCEL',
            $this->getName() . '.cancel',
            false,
            array('class' => '', 'icon' => '')
        );

        return PFToolbar::render();
    }
}
