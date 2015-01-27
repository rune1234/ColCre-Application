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
   /* protected $commonQuery = "SELECT users.name DeveloperName, users.id user_id,
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
/*
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
}*/
/*
private function getProjectCandidates($userid, $projectId)
{
    //$projectId = isset($_GET['id']) ? $_GET['id'] : '';
    if (!is_numeric($projectId)) return;
    
    $query = $this->commonQuery." AND projects.id = $projectId AND users.id != projects.created_by";
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
}*/
   public function specifyMatch($description, $taskId, $projectId, $userId, $matchId)
   {
         require_once( JPATH_ROOT .'/libraries/projectfork/colcre/matches.php' );
         $pm = new projectMatches();
         $rows = $pm->specifyMatch($description, $taskId, $projectId, $userId, $matchId);//why are we using matchID yet?
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
        //require_once( JPATH_ROOT .'/libraries/projectfork/colcre/matches.php' );
        jimport('projectfork.colcre.project');
        jimport('projectfork.colcre.matches');
        $pd = new projectData();
        $owner = $pd->projectOwner();
        $this->owner = $owner;
        $this->tasks = $this->getProjectTasks($this->item->id);
         
        $this->params  = $this->state->params;
        $this->modules = JFactory::getDocument()->loadRenderer('modules');
        $this->toolbar = $this->getToolbar();
        
         
        $myUser = JFactory::getUser();
        $this->user = $myUser;
          
        if (1 ==1 || ($this->item->created_by && $this->user->id)) 
        { 
            $pm = new projectMatches();
            $this->matches = $pm->getProjectCandidates($this->user->id, $this->item->id);
        }
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
//print_r($this->item);
        // Prepare the document
        $this->prepareDocument($this->item);

        // Display
		parent::display($tpl);
	}


    /**
	 * Prepares the document
     *
	 */
	protected function prepareDocument($item)
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

               
                $extraMeta = '';
        // Set the page title
		//$title = $this->params->get('page_title', '');
                 $title = ($item->title) ? "Colcre Project: ".$item->title : false;
                 if ($item->category_alias)
                 {
                     $title .= " - Category: ".$item->category_alias;
                     $extraMeta .= " - Category: ".$item->category_alias;
                 }
                 $desc = ($item->text) ? substr(strip_tags($item->text), 0, 155)."..." : "Colcre Project";
                 
                 $keywords = preg_split("/[\s,\,\.,\',\;\!,\?]/", strip_tags(str_replace(array('&amp;', '&quot;'), '', $item->text)));
                 $stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount", "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as", "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");
                 
                 $keywords = array_diff($keywords, $stopwords);
                 $keywords_2 = array();
                 $k = 0;
                 foreach ($keywords as $keys) 
                 { 
                      if ($k > 50) continue;
                      if ($keys) { if (!in_array($keys, $keywords_2) && strlen($keys) > 2) { $keywords_2[] = $keys;  } } 
                      $k++;
                 }
                 $keywords = implode(',', $keywords_2);
                 if ($item->category_alias)
                 
                 {
                     $keywords .= ",".$item->category_alias;
                 }
                     
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

         
$this->document->setDescription(str_replace('"', '\'', $desc));
        // Set page keywords
            $this->document->setMetadata('keywords', $keywords);

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
