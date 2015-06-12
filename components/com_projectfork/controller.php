<?php
/**
 * @package      Projectfork
 * @subpackage   Dashboard
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.controller');


/**
 * Projectfork main controller
 *
 * @see    JController
 */
class ProjectforkController extends JControllerLegacy
{
    /**
     * Constructor
     *
     * @param    array    $config    Optional config options
     */
    function __construct($config = array())
    {
        parent::__construct($config);
    }
    public function like()
    {
        $data = json_decode(file_get_contents("php://input"));
        jimport('projectfork.colcre.likes');
        $prl = new projectLikes();
        $prl->likeProject($data->user_id, $data->type_id, $data->type);
        exit;
    }
    public function userlikes()
    {
        jimport('projectfork.colcre.likes');
        $prl = new projectLikes();
        $pid = JRequest::getInt('id');
        $view = $this->getView('userlikes', 'html');
        list($likes, $pagination) = $prl->projectLikes($pid);
        $view->set('userlikes', $likes);
        $pd = new projectData();
        $info = $pd->projectInfo();
         
        $view->set('pagination', $pagination);
        $view->set('profile_data', $pd);
        $view->set('info', $info);
        $view->display();
         
    }
    public function getlikes()
    {
        $data = json_decode(file_get_contents("php://input"));
        jimport('projectfork.colcre.likes');
        $prl = new projectLikes();
        $prl->getLikes($data->type_id, $data->type);
        exit;
    }
    public function getUserLike()
    {
        jimport('projectfork.colcre.likes');
        $data = json_decode(file_get_contents("php://input"));
        $prl = new projectLikes();
        echo ($prl->alreadyLiked($data->user_id, $data->type_id, $data->type)) ? 1 : 0;
        exit;
    }
    public function proposals()
    {
         $db = JFactory::getDbo();
         $user = JFactory::getUser();
         $pid = JRequest::getInt('id'); 
         if ($pid == 0) return;
         if (!$user || !isset($user->id) || $user->id == 0) return;
         $limitstart = JRequest::getInt('limitstart');
         $limit = JRequest::getVar( "viewlistlimit", '10', 'get', 'int');
         $total = $db->setQuery("SELECT count(id) FROM #__pf_projects_msg WHERE owner_id = ".$user->id." AND project_id=".$pid)->loadResult();
         $pagination = new JPagination($total, $limitstart, $limit);
         $rows = $db->loadObjectList();
         $query = "SELECT * FROM #__pf_projects_msg WHERE ";//owner_id = ".$user->id." AND ";
         $query .= "project_id=".$pid." ORDER BY id DESC LIMIT $limitstart, $limit";
         //echo $query;
         $rows = $db->setQuery($query)->loadObjectList();
         // $pagination = $pagination->getPagesLinks();
         $query = "SELECT title, id, created_by FROM #__pf_projects WHERE id= $pid LIMIT 1";
         $db->setQuery($query);
         $project = $db->loadObject();
         if ($project->created_by != $user->id)
         {
             ?>
<div class="projectFrame" style="color: #a00; padding: 0px; padding-left: 10px; margin: 10px;">
    <h3>ERROR - you are not authorized to be here</h3>
</div> 
<?php 
            return;
         }
         $view = $this->getView('proposals', 'html');
         $view->set('project', $project);
         $view->set('proposals', $rows);
         $view->set('db', $db);
         $view->set('pagination', $pagination);
         $view->display();
    }
    public function prmatch()
    {
        jimport('projectfork.colcre.project');
        jimport('projectfork.colcre.matches');
        $pd = new projectData();
        $pd->projectInfo();
        $owner = $pd->projectOwner();
        if (!$owner) { echo "<p style='color: #a00;'>ERROR - you are not authorized to see the matches. Please log in.</p>"; return; }
        $pm = new projectMatches();
        $user = JFactory::getUser();
        $userid = $user->id;
        //$projectId = isset($_GET['id']) ? $_GET['id'] : '';
        $projectId = JRequest::getInt('id');
        if (is_numeric($projectId) && $projectId > 0) $matches = $pm->getProjectCandidates($userid, $projectId, $pagination);
        $view      = $this->getView('prmatch', 'html');
        $view->set('matches', $matches);
        $view->set('pm', $pm);
         $view->set('pd', $pd);
        $view->display();
        //<div class="span3 pull-left projectBox"
    }
    public function deleteTask()
    {
        $post = $_POST; 
        $id = $post['id'];
        $created_by = $post['created_by'];
        $token = $post['token'];
        if (!is_numeric($id) || !is_numeric($created_by)) exit;
        $ourToken = md5($id."taskjk".$created_by);
        $msg = array();
        $db = JFactory::getDbo();
        if ($ourToken != $token) 
        {
            $msg['error'] = 1;
            $msg['msg'] = "Invalid Data";
            echo json_encode($msg);
            exit;
        }
    $query = "DELETE FROM #__pf_ref_tasks WHERE task_id = $id"; 
        $this->delProjDb($query, $db);
        $query = "DELETE FROM #__pf_tasks WHERE id = $id";  
        $this->delProjDb($query, $db);
        $query = "DELETE FROM #__pf_project_skills WHERE task_id = $id";
        $this->delProjDb($query, $db);
        $msg['error'] = 0;
        $msg['msg'] = "Successfully delete project";
        echo json_encode($msg);
        exit;
    }
    public function deleteProject()
    {
        $post = $_POST; 
        $id = $post['project_id'];
        $created_by = $post['created_by'];
        $token = $post['token'];
        if (!is_numeric($id) || !is_numeric($created_by)) exit;
        $ourToken = md5($id."projk".$created_by);
        $msg = array();
        $db = JFactory::getDbo();
        if ($ourToken != $token) 
        {
            $msg['error'] = 1;
            $msg['msg'] = "Invalid Data";
            echo json_encode($msg);
            exit;
        }
            $query = "DELETE FROM #__pf_projects WHERE id = $id LIMIT 1";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_projects_invites WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_projects_msg WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_project_skills WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_ref_tasks WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_ref_attachments WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_ref_labels WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_ref_observer WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_replies WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_repo_dirs WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_repo_files WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_repo_file_revs WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_repo_notes WHERE project_id = $id";
        $this->delProjDb($query, $db);
            $query = "DELETE FROM #__pf_task_lists WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_tasks WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_topics  WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_milestones  WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_labels  WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_design_revisions  WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_design_albums  WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_designs  WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_comments  WHERE project_id = $id";
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_likes WHERE type = 1 AND type_id = $id"; 
        $this->delProjDb($query, $db);
             $query = "DELETE FROM #__pf_likescount WHERE type = 1 AND type_id = $id";
        $this->delProjDb($query, $db);
        $msg['error'] = 0;
        $msg['msg'] = "Successfully delete project";
        echo json_encode($msg);
        exit;
    }
    private function delProjDb($query,& $db)
    {
        $r = $db->setQuery($query)->Query();
        if (!$r)
        {
            $msg['error'] = 1;
            $msg['msg'] = $db->getErrorMsg();
            echo json_encode($msg);
            exit;
        }
    }
    public function msgOwner()//redacron function, message a project Owner
    {
        $post = $_POST;
        $db = JFactory::getDbo();
        $proposal = $db->escape($post['proposal']);
        $howwould = $db->escape($post['howwould']);
        $project_id = $post['project_id'];
        $created_by = $post['created_by'];
        $user_id = $post['user_id'];
        if (!is_numeric($user_id) || !is_numeric($created_by) || !is_numeric($project_id)) return;
        $name = JFactory::getUser($user_id);
        $exists = $this->_proposalExists($user_id, $project_id);
        if (!$exists) 
        { 
            $query = "INSERT INTO #__pf_projects_msg (project_id,owner_id, user_id,from_name,posted_on,howwould,proposal) VALUES ($project_id, $created_by, $user_id, '".$name->name."', ".time().", '$howwould', '$proposal')";
        }
        else
        {
            $query = "UPDATE #__pf_projects_msg SET howwould ='$howwould', proposal='$proposal' WHERE user_id = $user_id AND owner_id = $created_by AND project_id = $project_id LIMIT 1";
        }
        $db->setQuery($query);
        $db->Query();
        if (!$exists) {$this->_messageUser($post, $name->name, $db);}
        $post['proposal'] = nl2br($post['proposal']);
        $post['howwould'] = nl2br($post['howwould']);
        echo json_encode($post);
        exit;
    }
    private function _proposalExists($userid, $projectid)
    {
        if (!is_numeric($userid) || !is_numeric($projectid)) return false;
        $query = "SELECT id FROM #__pf_projects_msg WHERE user_id = $userid AND project_id = $projectid LIMIT 1";
        $db = JFactory::getDbo();
        $id = $db->setQuery($query)->loadResult();
        return ($id) ? true : false;
    }
    private function _messageUser($post, $name, & $db)
    {
        $db =& JFactory::getDBO();
        $subject = "Project ".$this->_projectTitle($post['project_id'],  $db)." Proposal from ".ucwords($name);
        $query = "INSERT INTO `#__community_msg` (`id`, `from`, `parent`, `deleted`, `from_name`, `posted_on`, `subject`, `body`) VALUES (NULL, ".$post['user_id'].", 1, 0, '".$name."', '".date('Y-m-d H:i:s', time())."', '".$subject."', '".$db->escape($post['proposal'])."')";
        $db->setQuery($query);
        $db->Query();
        $insertId = $db->insertid();
        if (is_numeric($insertId))
        {
            $query = "INSERT INTO #__community_msg_recepient (`msg_id`,`msg_parent`,`msg_from`,`to`,`bcc`,`is_read`,`deleted`) VALUES ($insertId, $insertId, ".$post['user_id'].",".$post['created_by'].", 0, 0, 0)";
            $db->setQuery($query);
            $db->Query();
            $query = "UPDATE `#__community_msg` SET parent = $insertId WHERE id = $insertId LIMIT 1";
            $db->setQuery($query);
            $db->Query();
        }
        return;
    }
    private function _projectTitle($id, & $db)
    {
        $query = "SELECT title FROM #__pf_projects WHERE id = $id LIMIT 1";
        $db->setQuery($query);
        return $db->loadResult();
    }
    public function display($cachable = false, $urlparams = false)
    {
        // Load CSS and JS assets
        JHtml::_('pfhtml.style.bootstrap');
        JHtml::_('pfhtml.style.projectfork');
        jimport('projectfork.colcre.project');
 
        JHtml::_('pfhtml.script.jquery');
        JHtml::_('pfhtml.script.bootstrap');
        JHtml::_('pfhtml.script.projectfork');

        JHtml::_('behavior.tooltip');
        
        // Override method arguments
        $urlparams = array('id'               => 'INT',
                           'filter_project'   => 'CMD'
                           );
        $document = JFactory::getDocument();
        $document->addScript(JURI::root() . 'libraries/projectfork/js/angular.min.js');
          $document->addCustomTag('<script src="'.JURI::root().'libraries/projectfork/js/jquery-ui.dialog.js" type="text/javascript"></script>');
        $document->addScript(JURI::root() . 'components/com_pfprojects/js/pfp.js');
        $document->addScript(JURI::root() . 'libraries/projectfork/js/like.js');
        $js = "var projectURL = '".JURI::root()."';";
        $document->addScriptDeclaration($js);
        // Display the view
        parent::display($cachable, $urlparams);

        // Return own instance for chaining
        return $this;
    }
}