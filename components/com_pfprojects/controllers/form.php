<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_pfprojects
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.controllerform');


/**
 * Projectfork Project Form Controller
 *
 */
class PFprojectsControllerForm extends JControllerForm
{
    /**
     * Default item view
     *
     * @var    string
     */
    protected $view_item = 'form';

    /**
     * Default list view
     *
     * @var    string
     */
    protected $view_list = 'projects';


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
	    $this->registerTask('save2task', 'save');
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
    public function &getModel($name = 'Form', $prefix = '', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }


    /**
     * Method to add a new record.
     *
     * @return    boolean    True if the article can be added, false if not.
     */
    public function add()
    {
        if (!parent::add()) {
            // Redirect to the return page.
            $this->setRedirect($this->getReturnPage());
        }
    }


    /**
     * Method to save a record.
     *
     * @param     string     $key       The name of the primary key of the URL variable.
     * @param     string     $urlVar    The name of the URL variable if different from the primary key.
     *
     * @return    boolean               True if successful, false otherwise.
     */
    public function save($key = null, $urlVar = null)
    {
        $data = JRequest::getVar('jform', array(), 'post', 'array');
        $task = $this->getTask();
            
        // Separate the different component rules before passing on the data
        if (isset($data['rules'])) {
            $rules = $data['rules'];

            if (isset($data['rules']['com_pfprojects'])) {
                $data['rules'] = $data['rules']['com_pfprojects'];

                unset($rules['com_pfprojects']);
            }

            $data['component_rules'] = $rules;
        }
            
        // Reset the repo dir when saving as copy
        if ($task == 'save2copy') {
            // Reset the repo dir when saving as copy
            if (isset($data['attribs']['repo_dir'])) {
                $dir = (int) $data['attribs']['repo_dir'];

                if ($dir) {
                    $data['attribs']['repo_dir'] = 0;
                }
            }

            // Reset label id's
            if (isset($data['labels']) && is_array($data['labels'])) {
                foreach($data['labels'] AS $a => $g)
                {
                    if (isset($g['id'])) {
                        foreach($g['id'] AS $k => $i)
                        {
                            $data['labels'][$a]['id'][$k] = 0;
                        }
                    }
                }
            }
//echo "taxxsk is $task"; exit;
            // Store the current project id in session
            $recordId = JRequest::getUInt('id');
            /*$projskills = isset($_POST['projskills']) ? $_POST['projskills'] : '';
            if (is_array($projskills))
            { 
                $db =& JFactory::getDBO();  
                foreach($projskills as $skillID)
                { $query = "INSERT INTO #__pf_project_skills (project_id,skill_id) VALUES ($recordId, $skillID)"; $db->setQuery($query); $db->Query(); }
            }
            echo $query; exit;*/
            if ($recordId) {
                // Store the current project id in session
                $context = "$this->option.copy.$this->context.id";
                $app     = JFactory::getApplication();

                $app->setUserState($context, intval($recordId));

                $cfg = JComponentHelper::getParams('com_pfprojects');
                $create_group   = (int) $cfg->get('create_group');

                if ($create_group) {
                    // Get the project attribs
                    $db = JFactory::getDbo();
                    $query = $db->getQuery(true);

                    $query->select('attribs')
                          ->from('#__pf_projects')
                          ->where('id = ' . (int) $recordId);

                    $db->setQuery($query, 0, 1);
                    $attribs = $db->loadResult();

                    // Turn to JRegistry object
                    $params = new JRegistry();
                    $params->loadString($attribs);

                    // Get custom user group
                    $group_id = (int) $params->get('usergroup');

                    // Replicate existing custom group settings
                    if ($group_id) {
                        // Copy component rules
                        if (isset($data['component_rules'])) {
                            $user     = JFactory::getUser();
                            $is_admin = $user->authorise('core.admin');

                            foreach ($data['component_rules'] AS $component => $rules)
                            {
                                foreach ($rules AS $action => $groups)
                                {
                                    if (!is_numeric($action) && is_array($groups)) {
                                        foreach ($groups AS $gid => $v)
                                        {
                                            if ($gid == $group_id) {
                                                if (!$is_admin && $action == 'core.admin') {
                                                    // Dont allow non-admins to inject core admin permission
                                                    unset($data['component_rules'][$component][$action]);
                                                }
                                                else {
                                                    unset($data['component_rules'][$component][$action][$gid]);
                                                    $data['component_rules'][$component][$action][0] = $v;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Copy item rules
                        if (isset($data['rules'])) {
                            foreach ($data['rules'] AS $action => $value)
                            {
                                if (is_numeric($action)) {
                                    if ($value == $group_id) {
                                        $data['rules'][$action] = 0;
                                    }
                                }
                                else {
                                    foreach ($value AS $k => $v)
                                    {
                                        if ($k == $group_id) {
                                            unset($data['rules'][$action][$k]);
                                            $data['rules'][$action][0] = $v;
                                        }
                                    }
                                }
                            }
                        }

                        // Copy group members
                        $query->clear();
                        $query->select('user_id')
                              ->from('#__user_usergroup_map')
                              ->where('group_id = ' . (int) $group_id);

                        $db->setQuery($query);
                        $add_users = (array) $db->loadColumn();

                        $add_append = "";

                        if (!isset($data['add_groupuser'])) {
                            $data['add_groupuser'] = array();
                        }

                        if (isset($data['add_groupuser'][$group_id])) {
                            $add_append = $data['add_groupuser'][$group_id];

                            unset($data['add_groupuser'][$group_id]);
                        }

                        $data['add_groupuser'][0] = implode(',', $add_users) . ($add_append == '' ? '' : ',' . $add_append);
                    }
                }
            }
        }
            
        if (version_compare(JVERSION, '3.0.0', 'ge')) {
            $this->input->post->set('jform', $data);
        }
        else {
            JRequest::setVar('jform', $data, 'post');
        }
            
        /*return*/ $newstate = parent::save($key, $urlVar);//redacron alteration: function postSaveHook( will activate after parent::save
            
        return $newstate;
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
        return JFactory::getUser()->authorise('core.create', 'com_pfprojects');
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
        $asset = 'com_pfprojects.project.' . $id;

        // Check if the user has viewing access when not a super admin
        if (!$user->authorise('core.admin')) {
            $db    = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('access')
                  ->from('#__pf_projects')
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

        // Fall back on edit.own.
        // First test if the permission is available.
        if (!$user->authorise('core.edit.own', $asset)) {
            return false;
        }

        // Now test the owner is the user.
        $owner = (int) isset($data['created_by']) ? (int) $data['created_by'] : 0;

        if (!$owner && $id) {
            // Need to do a lookup from the model.
            $record = $this->getModel()->getItem($id);

            if (empty($record)) return false;

            $owner = $record->created_by;
        }

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
        $return  = $this->getReturnPage();
        $append  = '';

        // Setup redirect info.
        if ($tmpl) $append .= '&tmpl=' . $tmpl;

        $append .= '&layout=edit';
        if ($id)      $append .= '&' . $url_var . '=' . $id;
        if ($item_id) $append .= '&Itemid=' . $item_id;
        if ($return)  $append .= '&return='.base64_encode($return);

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
            return JRoute::_(PFprojectsHelperRoute::getProjectsRoute(), false);
        }
        else {
            return base64_decode($return);
        }
    }


    /**
     * Function that allows child controller access to model data after the data has been saved.
     *
     * @param     jmodel    $model    The data model object.
     * @param     array     $data     The validated data.
     *
     * @return    void
     */
    private function projectSkills($id, $taskid, $skills, $check = true)
    {
            
        $db =& JFactory::getDBO();  
        if (is_array($skills)) {
            foreach($skills as $sk)
            {
                if (!is_numeric($sk)) continue;
                if ($check === true) { if ($this->projTaskAlrd($id, $taskid, $sk, $db)) continue; }
                $query = "INSERT INTO #__pf_project_skills (project_id,task_id, skill_id) VALUES ($id, $taskid, $sk)"; 
                echo $query;
                $db->setQuery($query); $db->Query();  
            }
        }
            
    }
    private function projTaskAlrd($id, $taskid, $skid, & $db)
    {
        $query = "SELECT * FROM #__pf_project_skills WHERE project_id = '$id' AND task_id = '$taskid' AND skill_id = '$skid' LIMIT 1";
        $db->setQuery($query);
        $row = $db->loadObject();
        return ($row) ? true : false;
    } 
    private function projectTasks($id)
    { 
        $db =& JFactory::getDBO(); 
        $tasks = $_POST['taskform'];
        $user = JFactory::getUser();
        $userid = $user->id;
        foreach($tasks as $tsk)
        {
            if (is_numeric($tsk['idedit']) && $tsk['idedit'] > 0) //user editing a task instead of adding it
             { 
                  $this->editTask($tsk, $id, $tsk['SkillInput'], $db); 
                  continue; 
             }
             $query = "INSERT INTO #__pf_tasks (id,asset_id,project_id,category_id, list_id,milestone_id,title,alias,description,created,created_by,modified,modified_by,checked_out,checked_out_time,attribs,access,state,priority,complete,completed,completed_by,ordering,start_date,end_date,rate,estimate)
VALUES (NULL , '0', '$id', '".$db->escape($tsk['category'])."', '0', '0', '".$db->escape($tsk['title'])."', '".str_replace(' ', '-', $db->escape($tsk['title']))."', '".$db->escape($tsk['description'])."', '".date('Y-m-d H:i:s', time())."', '".$userid."', '0000-00-00 00:00:00', '0', '0', '0000-00-00 00:00:00', '', '1', '1', '0', '0', '0000-00-00 00:00:00', '', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '')";
            $db->setQuery($query);
            $db->Query();
            $taskid = $db->insertid();
            $this->projectSkills($id, $taskid, $tsk['SkillInput']);
            
            if (isset($tsk[newSkillTag]) && is_array($tsk[newSkillTag])) 
            {  $this->_insertNewTags($tsk[newSkillTag], $tsk[newSkillTagCag], $id, $taskid, $db); }
        }
            
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
        $rowSkills->skillTags = $rowSkills->skillTags.",".$tags;
        $query = "UPDATE #__pf_project_skills_added SET skillTags='".$rowSkills->skillTags."' WHERE userid = $userid LIMIT 1";
       //echo "<br />".$query;
        $db->setQuery($query);
        $db->Query();
      //  exit;
    }
    
    private function editTask($task, $project_id, $skills, & $db)
    {
        $this->deleteSkills($task['idedit'], $project_id, $db);
        $this->projectSkills($project_id, $task['idedit'], $skills, false);//set to false, no need to check. We deleted the skills
        $query = "UPDATE #__pf_tasks SET title='".$db->escape($task['title'])."', description='".$db->escape($task['description'])."' WHERE id = ".$task['idedit']." LIMIT 1";
        $db->setQuery($query);
        $db->Query();
        //sometimes a user may add new tags to a task that already exists:
        if (isset($task[newSkillTag]) && is_array($task[newSkillTag])) 
        {  $this->_insertNewTags($task[newSkillTag], $task[newSkillTagCag], $project_id, $task['idedit'], $db); }
    }
    private function deleteSkills($task_id, $project_id, & $db)
    {
        if (!is_numeric($task_id) || !is_numeric($project_id)) return;
        $query = "DELETE FROM #__pf_project_skills WHERE task_id = $task_id AND project_id = $project_id LIMIT 50";
        $db->setQuery($query);
        $db->Query();
    }
    protected function postSaveHook($model, $data = array())//function override for a native Joomla function in JControllerForm
    {
        $task = $this->getTask();
        $item = $model->getItem();
        $id = $item->get('id');
            
        if (is_numeric($id)) 
        { 
            $this->commentSetting($id);
            $this->projectTasks($id); 
        }
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

            case 'save2task':
                $link = JRoute::_(PFtasksHelperRoute::getTasksRoute() . '&task=taskform.add');
                $this->setRedirect($link);
                break;

            default://redacron alteration. It is better to redirect back to the project:
                $this->setRedirect(JRoute::_('index.php?option=com_projectfork&view=dashboard&id='.$id.'&Itemid=124'));
              //  $this->setRedirect($this->getReturnPage());
                break;
        }
    }
    private function commentSetting($id)
    {
        if ($id <= 0) return;
        $commentsetting = JRequest::getInt('commentsetting');
            $query = "UPDATE #__pf_projects SET commentsetting = $commentsetting WHERE id = $id LIMIT 1";
            $db = JFactory::getDbo();
            $db->setQuery($query);
            $db->Query();
    }
}
