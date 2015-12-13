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


jimport('joomla.application.component.controllerform');


/**
 * Projectfork Task Form Controller
 *
 */
class PFtasksControllerTaskForm extends JControllerForm
{
    /**
     * The default item view
     *
     * @var    string
     */
    protected $view_item = 'taskform';

    /**
     * The default list view
     *
     * @var    string
     */
    protected $view_list = 'tasks';


    /**
     * Constructor
     *
     */
    public function __construct($config = array())
	{
	    parent::__construct($config);

        // Register additional tasks
		$this->registerTask('save2milestone', 'save');
		$this->registerTask('save2tasklist', 'save');
    }


    /**
     * Method to get a model object, loading it if required.
     *
     * @param     string    $name      The model name. Optional.
     * @param     string    $prefix    The class prefix. Optional.
     * @param     array     $config    Configuration array for model. Optional.
     *
     * @return    object               The model.
     */
    public function &getModel($name = 'TaskForm', $prefix = 'PFtasksModel', $config = array('ignore_request' => true))
    {
            
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }


    /**
     * Method to add a new record.
     *
     * @return    boolean    True if the item can be added, false if not.
     */
    public function add()
    {  
        if (!parent::add()) {
            // Redirect to the return page.
            $this->setRedirect($this->getReturnPage());
        }
    }

    public function save()//redacron function. This is a save overrided for saving tasks
    {
        if (!JSession::checkToken()) {
            $data['success']    = "false";
            $data['messages'][] = JText::_('JINVALID_TOKEN');

            $this->sendResponse($data);
            return;
        }
        
           $data = $_POST['jform'];
           $data['category'] = is_numeric($_POST['taskform']['category']) ? $_POST['taskform']['category'] : '';
          // echo "Category is ".$data['category']; exit;
           //print_r($_POST['taskform'][1]['idedit']);
   //print_r($_POST['taskform'][1]['SkillInput']); exit;
           $data['skills'] = $_POST['taskform'][1]['SkillInput'];
           $task_id = JRequest::getInt('id');//$_GET['id'];
           $db = JFactory::getDbo();
           $data['alias'] = str_replace(' ', '_', $data['title']);
           if (!is_numeric($task_id) || (int)$task_id  == 0) 
           {
               if (is_numeric($data['project_id']) && $data['project_id'] > 0) 
               {  
                   if ($data['title'] == '') return;
                   $this->addTask($data, $db);
                   $this->setRedirect($this->getReturnPage());
               }
               return;
           }
            $user = JFactory::getUser();
        if (!is_numeric($user->id) || $user->id == 0) return;
       
           $query = "UPDATE #__pf_tasks ";
          $query .= "SET category_id = '". $data['category']."', project_id='".$data['project_id']."', title='".$db->escape($data['title'])."', alias='".$db->escape($data['alias'])."', description='".$db->escape($data['description'])."', modified_by ='".$user->id."',modified ='".date('Y-m-d H:i:s', time())."', start_date='".$db->escape($data['start_date'])."', end_date='".$db->escape($data['end_date'])."', rate='".$db->escape($data['rate'])."', estimate='".$db->escape($data['estimate'])."' WHERE id = $task_id LIMIT 1";
          $db->setQuery($query);
          $db->Query();
          $this->addSkills($data, $task_id, $db, true);
          $this->setRedirect($this->getReturnPage());
           //exit;
    }       
    private function addTask($data, & $db)
    {
        //print_r($_POST['taskform']['newSkillTag']); exit;    
        $user = JFactory::getUser();
        if (!is_numeric($user->id) || $user->id == 0) return;
        $query = "INSERT INTO #__pf_tasks (`id`,`asset_id`,`project_id`,`category_id`,`list_id`,`milestone_id`,`title`,`alias`,`description`,`created`,`created_by`,`modified`,`modified_by`,`checked_out`,`checked_out_time`,`attribs`,`access`,`state`,`priority`,`complete`,`completed`,`completed_by`,`ordering`,`start_date`,`end_date`,`rate`,`estimate`) ";
        $query .= "VALUES (NULL , '', '".$data['project_id']."', '".$data['category']."', '', '', '".$db->escape($data['title'])."', '".$db->escape($data['alias'])."', '".$db->escape($data['description'])."', '".date('Y-m-d H:i:s', time())."', '".$user->id."', '', '', '', '', '', '1', '1', '', '', '', '', '', '".$db->escape($data['start_date'])."', '".$db->escape($data['end_date'])."', '".$db->escape($data['rate'])."', '".$db->escape($data['estimate'])."');";
        $db->setQuery($query);
        $db->Query();
        $task_id = $db->insertid();
        $this->addSkills($data, $task_id, $db);
         if (isset($_POST['taskform']['newSkillTag']) && is_array($_POST['taskform']['newSkillTag'])) 
            {  $this->_insertNewTags($_POST['taskform']['newSkillTag'], 1, $data['project_id'], $task_id, $db); }
    }
     private function _insertNewTags($tags, $tagCatg, $profiID, $taskid, &$db)//new tags are added but not published, this function is also in the pfprojects controller
    {
        $a = 0;
        $userid = JFactory::getUser();
        $userid = $userid->id;
        if (is_numeric($profiID))
        {
            $query = "SELECT * FROM #__pf_project_skills_added WHERE userid = '$userid' LIMIT 1";
            echo $query;
            $db->setQuery($query);
            $rowSkills = $db->loadObject();
            $mySkillsMore = '';
            if ($rowSkills) $mySkillsMore = $rowSkills->skillTags;
            
        }
        $newTags = array();
        //print_r($newTags);
        foreach ($tags as $tag)
        {
             if (trim($tag) == '') continue;
             $query = "SELECT id FROM #__pf_skills WHERE skill = '$tag' AND category = '".$tagCatg[$a]."' LIMIT 1";
             $db->setQuery($query);
             $oldID = $db->loadResult();
             if (!is_numeric($oldID) || $oldID < 1)
             {
                 $query ="INSERT INTO #__pf_skills (id,skill,category,user_id,published) VALUES (NULL , '$tag', '".$tagCatg[$a]."', '$userid', '1')";
                // echo "<br />".$query;
                 $db->setQuery($query);
                 $db->Query();
             }
             if ($tag) $newTags[] = $tag;
             $id = $db->insertid();
             $query = "INSERT INTO #__pf_user_skills (user_id, skill_id, date_added, skillCatg) VALUES ('$userid', '$id', CURRENT_TIMESTAMP, '".$tagCatg[$a]."')";
             
             $db->setQuery($query);
             $db->Query();
             $query = "INSERT INTO #__pf_project_skills (project_id, skill_id, task_id) VALUES ($profiID, $id, $taskid)";
           //  echo "<br />****************<br />".$query;
             $db->setQuery($query);
             $db->Query();
             $a++;
        }
        if ($a > 0)
        {
            $tags = implode(',', $newTags);
        }
        $rowSkills->skillTags = $mySkillsMore.",".$tags;
        $query = "UPDATE #__pf_project_skills_added SET skillTags='".$rowSkills->skillTags."' WHERE userid = $userid LIMIT 1";
            
        $db->setQuery($query);
        $db->Query();
            
    }
    private function addSkills($data, $task_id, & $db, $edit = false)
    {
        if ($edit)
        {
            $query = "DELETE FROM #__pf_project_skills WHERE project_id = ".$data['project_id']." AND task_id = $task_id";
            $db->setQuery($query);
            $db->Query();
        }
        foreach ($data['skills'] as $skill)
        {
            $query = "INSERT INTO #__pf_project_skills (project_id,skill_id,task_id) VALUES (".$data['project_id'].",  $skill, $task_id)";
            $db->setQuery($query);
            $db->Query();
        }
    }
    /**
     * Method to cancel an edit.
     *
     * @param     string     $key    The name of the primary key of the URL variable.
     *
     * @return    boolean            True if access level checks pass, false otherwise.
     */
    public function cancel($key = 'id')//when user clicks on cancel
    {  
        $result = parent::cancel($key);

        // Redirect to the return page.
        $this->setRedirect($this->getReturnPage());

        return $result;
    }


    /**
     * Method to check if you can add a new record.
     *
     * @param     array      $data    An array of input data.
     *
     * @return    boolean
     */
    protected function allowAdd($data = array())
    {
        // Get form input
        $project = isset($data['project_id'])   ? (int) $data['project_id']   : PFApplicationHelper::getActiveProjectId();
        $ms      = isset($data['milestone_id']) ? (int) $data['milestone_id'] : 0;
        $list    = isset($data['list_id'])      ? (int) $data['list_id']      : 0;
            
        $user   = JFactory::getUser();
        $db     = JFactory::getDbo();
        $is_sa  = $user->authorise('core.admin');
        $levels = $user->getAuthorisedViewLevels();
        $query  = $db->getQuery(true);
        $asset  = 'com_pftasks';
        $access = true;

        // Check if the user has access to the project
        if ($project) {
            // Check if in allowed projects when not a super admin
            if (!$is_sa) {
                $access = in_array($project, PFUserHelper::getAuthorisedProjects());
            }

            // Change the asset name
            $asset  .= '.project.' . $project;
        }

        // Check if the user can access the selected milestone when not a super admin
        if (!$is_sa && $ms && $access) {
            $query->select('access')
                  ->from('#__pf_milestones')
                  ->where('id = ' . $db->quote((int) $ms));

            $db->setQuery($query);
            $lvl = $db->loadResult();

            $access = in_array($lvl, $levels);
        }

        // Check if the user can access the selected task list when not a super admin
        if (!$is_sa && $list && $access) {
            $query->clear()
                  ->select('access')
                  ->from('#__pf_task_lists')
                  ->where('id = ' . $list);

            $db->setQuery($query);
            $lvl = $db->loadResult();

            $access = in_array($lvl, $levels);

            // Change asset to list
            $asset = 'com_pftasks.tasklist.' . $list;
        }

        return ($user->authorise('core.create', $asset) && $access);
    }


    /**
     * Method override to check if you can edit an existing record.
     *
     * @param     array      $data    An array of input data.
     * @param     string     $key     The name of the key for the primary key.
     *
     * @return    boolean
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Get form input
        $id = (int) isset($data[$key]) ? $data[$key] : 0;

        $user  = JFactory::getUser();
        $uid   = $user->get('id');
        $asset = 'com_pftasks.task.' . $id;

        // Check if the user has viewing access when not a super admin
        if (!$user->authorise('core.admin')) {
            $db    = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('access')
                  ->from('#__pf_tasks')
                  ->where('id = ' . $id);

            $db->setQuery($query);
            $lvl = $db->loadResult();

            if (!in_array($lvl, $user->getAuthorisedViewLevels())) {
                return false;
            }
        }

        // Check edit permission first
        if ($user->authorise('core.edit', $asset)) {
            return true;
        }

        // Fallback on edit.own.
        // First test if the permission is available.
        if (!$user->authorise('core.edit.own', $asset)) {
            return false;
        }

        // Load the item
        $record = $this->getModel()->getItem($id);

        // Abort if not found
        if (empty($record)) return false;

        // Now test the owner is the user.
        $owner = (int) isset($data['created_by']) ? (int) $data['created_by'] : $record->created_by;

        // If the owner matches 'me' then do the test.
        return ($owner == $uid && $uid > 0);
    }


    /**
     * Gets the URL arguments to append to an item redirect.
     *
     * @param     int       $id         The primary key id for the item.
     * @param     string    $url_var    The name of the URL variable for the id.
     *
     * @return    string                The arguments to append to the redirect URL.
     */
    protected function getRedirectToItemAppend($id = null, $url_var = 'id')
    {
        // Need to override the parent method completely.
        $tmpl    = JRequest::getCmd('tmpl');
        $layout  = JRequest::getCmd('layout', 'edit');
        $item_id = JRequest::getUInt('Itemid');
        $ms_id   = JRequest::getUInt('milestone_id');
        $list_id = JRequest::getUInt('list_id');
        $return  = $this->getReturnPage();
        $append  = '';

        // Setup redirect info.
        if ($tmpl) $append .= '&tmpl=' . $tmpl;

        $append .= '&layout=edit';
        if ($id)      $append .= '&' . $url_var . '=' . $id;
        if ($ms_id)   $append .= '&milestone_id=' . $ms_id;
        if ($list_id) $append .= '&list_id=' . $list_id;
        if ($item_id) $append .= '&Itemid=' . $item_id;
        if ($return)  $append .= '&return=' . base64_encode($return);

        return $append;
    }


    /**
     * Get the return URL.
     * If a "return" variable has been passed in the request
     *
     * @return    string    The return URL.
     */
    protected function getReturnPage()
    {
        $return = JRequest::getVar('return', null, 'default', 'base64');

        if (empty($return) || !JUri::isInternal(base64_decode($return))) {
            $app       = JFactory::getApplication();
            $project   = PFApplicationHelper::getActiveProjectId();   
            $milestone = (int) $app->getUserStateFromRequest('com_pftasks.tasks.filter.milestone', 'milestone_id', '');
            $list      = (int) $app->getUserStateFromRequest('com_pftasks.tasks.filter.tasklist', 'list_id', '');
            return JRoute::_('index.php?option=com_projectfork&view=dashboard&id=34&Itemid=124');//redacron alteration
            //--> we have neutralized this one: return JRoute::_(PFtasksHelperRoute::getTasksRoute($project, $milestone, $list), false);
        }
        else {
            return base64_decode($return);
        }
    }


    /**
     * Function that allows child controller access to model data after the data has been saved.
     *
     * @param     jmodel    $model        The data model object.
     * @param     array     $validData    The validated data.
     *
     * @return    void
     */
    protected function postSaveHook(&$model, $validData)
    {
        $task = $this->getTask();

        switch($task)
        {
            case 'save2copy':
            case 'save2new':
                // No redirect because its already set
                break;

            case 'save2milestone':
                $link = JRoute::_(PFmilestonesHelperRoute::getMilestonesRoute() . '&task=form.add');
                $this->setRedirect($link);
                break;

            case 'save2tasklist':
                $link = JRoute::_(PFtasksHelperRoute::getTasksRoute() . '&task=tasklistform.add');
                $this->setRedirect($link);
                break;

            default:
                $this->setRedirect($this->getReturnPage());
                break;
        }
    }
}
