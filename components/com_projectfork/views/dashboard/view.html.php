<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_projectfork
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.view');


class ProjectforkViewDashboard extends JViewLegacy
{
    protected $params;
    protected $state;
    protected $modules;
    protected $item;
    protected $pageclass_sfx;
    protected $toolbar;
    protected $commonQuery = "SELECT users.name DeveloperName, users.id user_id,
	projects.title ProjectTitle, 
	project_skills.task_id  MatchingTaskId, 
	skills.skill SkillName, 
	round(1 / (
		SELECT count(user_skills.user_id)
		FROM #__pf_user_skills user_skills
		WHERE user_skills.user_id = users.id
	) * 100, 0) AS TaskMatchPercentage,
	round(
		1 / (
			SELECT count(user_skills.user_id)
			FROM #__pf_user_skills user_skills
			WHERE user_skills.user_id = users.id
		) * (1 / (
			SELECT count(project_skills.task_id)
			FROM #__pf_project_skills project_skills
			WHERE project_skills.project_id = projects.id
		)) * 100, 0
	) AS ProjectMatchPercentage
FROM #__users users, 	
	#__pf_project_skills project_skills
JOIN #__pf_projects AS projects ON projects.id = project_skills.project_id
JOIN #__pf_skills AS skills ON skills.id = project_skills.skill_id
WHERE (project_skills.project_id, project_skills.task_id, project_skills.skill_id) IN (
	SELECT project_skills_2.project_id, project_skills_2.task_id, project_skills_2.skill_id
	FROM #__pf_project_skills project_skills_2
	WHERE project_skills_2.skill_id IN (
		SELECT user_skills_2.skill_id
		FROM #__pf_user_skills user_skills_2
		WHERE user_skills_2.user_id = users.id
	)
)";

private function getCandidates($userid)
{
    $query = "SELECT users.name DeveloperName, users.id user_id,
	projects.title ProjectTitle, 
	project_skills.task_id MatchingTaskId, 
	skills.skill SkillName, 
	round(1 / (
		SELECT count(user_skills.user_id)
		FROM #__pf_user_skills user_skills
		WHERE user_skills.user_id = users.id
	) * 100, 0) AS TaskMatchPercentage,
	round(
		1 / (
			SELECT count(user_skills.user_id)
			FROM #__pf_user_skills user_skills
			WHERE user_skills.user_id = users.id
		) * (1 / (
			SELECT count(project_skills.task_id)
			FROM #__pf_project_skills project_skills
			WHERE project_skills.project_id = projects.id
		)) * 100, 0
	) AS ProjectMatchPercentage
FROM #__users users, 	
	#__pf_project_skills project_skills
JOIN #__pf_projects AS projects ON projects.id = project_skills.project_id
JOIN #__pf_skills AS skills ON skills.id = project_skills.skill_id
WHERE (project_skills.project_id, project_skills.task_id, project_skills.skill_id) IN (
	SELECT project_skills_2.project_id, project_skills_2.task_id, project_skills_2.skill_id
	FROM #__pf_project_skills project_skills_2
	WHERE project_skills_2.skill_id IN (
		SELECT user_skills_2.skill_id
		FROM #__pf_user_skills user_skills_2
		WHERE user_skills_2.user_id = users.id
	)
)";//
//AND users.id != $userid";
      $db = JFactory::getDbo();
      $db->setQuery($query);
      $rows = $db->loadObjectList();
      return $rows;
}
private function getProjectCandidates($userid, $projectId)
{
    //$projectId = isset($_GET['id']) ? $_GET['id'] : '';
    if (!is_numeric($projectId)) return;
    
    $query = $this->commonQuery." AND projects.id = $projectId AND users.id != $userid";
   // echo $query
      $db = JFactory::getDbo();
      $db->setQuery($query);
      $rows = $db->loadObjectList();
      return $rows;
}
private function getPrjTskCandidates($userid, $taskid)
{
    $projectId = isset($_GET['id']) ? $_GET['id'] : '';
    if (!is_numeric($projectId)) return;
    
    $query = $this->commonQuery." AND projects.id = $projectId AND users.id != $userid";
      $db = JFactory::getDbo();
      $db->setQuery($query);
      $rows = $db->loadObjectList();
      return $rows;
}
private function getMatchDesc($userId)
{
    $db = JFactory::getDbo();
     if (!is_numeric($userId) ) return;
    $query = "SELECT * FROM #__pf_project_skills_added WHERE userid = $userId LIMIT 1";
    //echo $query;
     $db->setQuery($query);
     $rows = $db->loadObject();
     //print_r($rows);
     return ($rows) ? $rows : false;
}
public function specifyMatch($description, $taskId, $projectId, $userId, $matchid)
{
     $skill = $this->getMatchDesc($userId);
     $desc = $skill->skillDesc;
     if (!$desc) return;
     $query = "SELECT projects.title ProjectTitle,
MATCH (project_tasks.description) AGAINST ('$desc' IN NATURAL LANGUAGE MODE)
FROM #__pf_tasks AS project_tasks JOIN #__pf_projects AS projects ON projects.id = project_tasks.project_id
WHERE project_tasks.id = '$taskId' AND project_tasks.project_id = '$projectId'";
    /* $query = "SELECT users.name DeveloperName, projects.title ProjectTitle, MATCH (project_tasks.description) "
            . "AGAINST ('$desc' IN NATURAL LANGUAGE MODE) FROM #__users users, "
            . "#__pf_project_skills_added user_skills_added, #__pf_tasks AS project_tasks "
            . "JOIN #__pf_projects AS projects ON projects.id = project_tasks.project_id WHERE "
            . "project_tasks.id = '$taskId' AND project_tasks.project_id = '$projectId' AND "
            . "user_skills_added.userid = users.id AND users.id = '$userId'";*/
     //echo "<br /><p style='font-size: 10px;'>".$query."</p><br />";
     $db = JFactory::getDbo();
     $db->setQuery($query);
     $rows = $db->loadObjectList();
     return $rows;
}
   private function getProjectTasks($id)
   {
       if (!is_numeric($id) || $id == 0) return false;
       $query = "SELECT * FROM #__pf_tasks WHERE project_id = $id";
       $db = JFactory::getDbo();
       $db->setQuery($query);
       $rows = $db->loadObjectList();
       return $rows;
   }
	function display($tpl = null)
	{
	    $this->state   = $this->get('State');
        $this->item    = $this->get('Item');
        
        $this->tasks = $this->getProjectTasks($this->item->id);
         
        $this->params  = $this->state->params;
        $this->modules = JFactory::getDocument()->loadRenderer('modules');
        $this->toolbar = $this->getToolbar();
         
         
        $myUser = JFactory::getUser();
        $this->user = $myUser;
          
        if (1 ==1 || ($this->item->created_by && $this->user->id)) { $this->matches = $this->getProjectCandidates($this->user->id, $this->item->id);   }
        else $this->matches = false;
        $dispatcher	   = JDispatcher::getInstance();

        // Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

        // Process the content plugins.
        if (!empty($this->item)) {
            // Fake content item
            PFObjectHelper::toContentItem($this->item);

    		// Import plugins
    		JPluginHelper::importPlugin('content');
            $context = 'com_pfprojects.project';

            // Trigger events
    		$results = $dispatcher->trigger('onContentPrepare', array ($context, &$this->item, &$this->params, 0));

    		$this->item->event = new stdClass();
    		$results = $dispatcher->trigger('onContentAfterTitle', array($context, &$this->item, &$this->params, 0));
    		$this->item->event->afterDisplayTitle = trim(implode("\n", $results));

    		$results = $dispatcher->trigger('onContentBeforeDisplay', array($context, &$this->item, &$this->params, 0));
    		$this->item->event->beforeDisplayContent = trim(implode("\n", $results));

    		$results = $dispatcher->trigger('onContentAfterDisplay', array($context, &$this->item, &$this->params, 0));
    		$this->item->event->afterDisplayContent = trim(implode("\n", $results));
        }

        // Prepare the document
        $this->prepareDocument();

        // Display
		parent::display($tpl);
	}


    /**
	 * Prepares the document
     *
	 */
	protected function prepareDocument()
	{
		$app	 = JFactory::getApplication();
		$menu    = $app->getMenu()->getActive();
		$pathway = $app->getPathway();
		$title	 = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else {
			$this->params->def('page_heading', JText::_('COM_PROJECTFORK_DASHBOARD_TITLE'));
		}


        // Set the page title
		$title = $this->params->get('page_title', '');

		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		$this->document->setTitle($title);

        // Set crawler behavior info
		if ($this->params->get('robots')) {
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

        // Set page description
        if($this->params->get('menu-meta_description')) {
            $this->document->setDescription($desc);
        }

        // Set page keywords
        if($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $keywords);
        }

		// Add feed links
		if ($this->params->get('show_feed_link', 1)) {
			// Add RSS link
            $link    = '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');

			$this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);

            // Add atom link
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');

			$this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
		}
	}


    /**
     * Generates the toolbar for the top of the view
     *
     * @return    string    Toolbar with buttons
     */
    protected function getToolbar()
    {
        $id = (empty($this->item) || empty($this->item->id) ? null : $this->item->id);

        $access = PFprojectsHelper::getActions($id);
        $uid    = JFactory::getUser()->get('id');

        if (!empty($id)) {
            $slug = $this->item->id . ':' . $this->item->alias;

            PFToolbar::button(
                'COM_PROJECTFORK_ACTION_EDIT',
                '',
                false,
                array(
                    'access' => ($access->get('core.edit') || $access->get('core.edit.own') && $uid == $this->item->created_by),
                    'href' => JRoute::_(PFprojectsHelperRoute::getProjectsRoute() . '&task=form.edit&id=' . $slug)
                )
            );
        }

        return PFToolbar::render();
    }
}
