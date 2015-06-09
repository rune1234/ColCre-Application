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


jimport('joomla.application.component.controller');


/**
 * Projects main controller
 *
 * @see    JController
 */
class PFprojectsController extends JControllerLegacy
{
    /**
     * The default view
     *
     * @var    string
     */
    protected $default_view = 'projects';


    /**
     * Displays the current view
     *
     * @param     boolean    $cachable    If true, the view output will be cached  (Not Used!)
     * @param     array      $urlparams   An array of safe url parameters and their variable types (Not Used!)
     *
     * @return    JController             A JController object to support chaining.
     */
    public function projectMSG()
    {
        $user = JFactory::getUser();
        if (!isset($user->id) || $user->id == 0) 
        {
             $mainframe = JFactory::getApplication();
             $link = JRoute::_('index.php?option=com_users&view=login&return='.base64_encode(JRoute::_('index.php?option=com_pfprojects&task=projectMSG')));
             $mainframe->redirect($link, "You need to log in first");
        }
        $db =& JFactory::getDBO();
        $query = "SELECT * FROM #__community_msg_recepient WHERE to = $user->id";
    }
    public function getSKills()
    {
          $db =& JFactory::getDBO();
          $data = json_decode(file_get_contents("php://input"));
          $query = "SELECT * FROM #__pf_skills WHERE skill LIKE '".$db->escape($data->skill)."%'";
          //echo $query;
          $db->setQuery($query);
          $rows = $db->loadObjectList();
          if (!$rows)
          {
              $rows[0] = new stdClass();
              $rows[0]->id = 0;
              $rows[0]->skill = "There's no match for the skill you are seeking. Click on Add a Skill Tag to add the skill.";
          }
          $fr = new stdClass();
          $fr->skills = json_encode($rows);
             $fr->msg = '';
             echo json_encode($fr);
             exit;
          die();
    }
    public function delSKills()
    {
          $db =& JFactory::getDBO();
          $data = json_decode(file_get_contents("php://input"));
          $fr = new stdClass();
          if (!isset($data->id) || !is_numeric($data->id) || $data->id == 0)
          { $fr->msg = 'ERROR - non-numeric ID'; }
          elseif (!isset($data->catg) || !is_numeric($data->catg) || $data->catg == 0)
          { $fr->msg = 'ERROR - non-numeric Category'; }
          else
          {
             
             $fr->id = $data->id;//we will need this to delete the skill link
             $query = "SELECT skillCatg FROM #__pf_project_skills_added WHERE id=".$data->id." LIMIT 1";
             $skillCatg = $db->setQuery($query)->loadResult();
             if (! $skillCatg)
             {
                 $fr->msg = 'ERROR - data does not exist';
                 exit;
             }
             $query = "DELETE FROM #__pf_project_skills_added where userid =".$data->userid." AND skillCatg=".$data->catg." AND id=".$data->id." LIMIT 1";
             if (! $db->setQuery($query)->Query())
             {
                 $fr->msg = "There was an error deleting the project skills";
             }
             $query = "DELETE FROM #__pf_user_skills where user_id =".$data->userid." AND skillCatg=".$data->catg;
             if (! $db->setQuery($query)->Query())
             {
                 $fr->msg = "There was an error deleting the user skills";
             }
             else $fr->msg = '';
             
          }
          echo json_encode($fr);
          exit;
          die();
    }
    function getUserMainSkilAj()
    {
        
         $user = JFactory::getUser();
        if ((int)$user->id == 0)
        {
            echo 'false';
            exit;
        }
        else
        {
             $db = JFactory::getDbo();
             $catg = JRequest::getVar('catg');
             
        if (is_numeric($catg) && $catg > 0)
        {
            //$catg = " AND a.skillCatg = $catg";
        }
        else {
            echo "ERROR - no category";
            exit;
        }
            $query = "SELECT * FROM #__pf_project_skills_added WHERE userid = ".$user->id." AND skillCatg = '".$catg."' LIMIT 1";
            ///echo $query;
            $db->setQuery($query);
            $row = $db->loadObject();
            echo ($row) ? json_encode($row) : 0;
            exit;
        }
    }
    function getUserSkilAj()
    {
        $user = JFactory::getUser();
        if ((int)$user->id == 0)
        {
            echo 'false';
            exit;
        }
        else
        {
             $db = JFactory::getDbo();
             $catg = JRequest::getVar('catg');
             
        if (is_numeric($catg) && $catg > 0)
        {
            $catg = " AND a.skillCatg = $catg";
        }
        else {
            echo "ERROR - no category";
            exit;
        }
             $query = "SELECT b.* FROM #__pf_user_skills as a INNER JOIN #__pf_skills as b ON a.skill_id = b.id WHERE a.user_id = ".$user->id." $catg ORDER BY b.skill";
            // echo $query;
             $db->setQuery($query);
             $rows = $db->loadObjectList();
             echo json_encode($rows);
             exit;
        }
    }
    public function inviteUser()
    {
        $db =& JFactory::getDBO();
        $data = json_decode(file_get_contents("php://input"));
         if (!is_numeric($data->project_id) || !is_numeric($data->user_id)) exit;
        $user = JFactory::getUser();
        $user_2 =  JFactory::getUser($data->user_id);
        // print_r($user_2); exit;
        $query = "SELECT * FROM #__pf_projects WHERE id = ".$data->project_id." LIMIT 1";
        $db->setQuery($query);
        $row = $db->loadObject();
        $mailMSG = "<p>Hi ".ucwords($user_2->name).",</p>
<p>You've been invited to apply for a job! Sign in and select \"Invitations\" on your dashboard to respond.</p>
<p>Project: $row->title (ID: $row->id)
Description: <p><i>".nl2br( strip_tags($row->description) )."</i></p>
<p>
Sincerely,<br />
the Make Whatever staff</p>";
         
        //if (is_numeric($data->project_id) && is_numeric($data->user_id))
        {
            $query = "SELECT project_id FROM #__pf_projects_invites WHERE project_id = ".$data->project_id." AND invited=".$data->user_id." LIMIT 1";
            
            $db->setQuery($query);
            $thisID = $db->loadResult();
            if ($thisID && is_numeric($thisID)) 
            {
                echo "You already invited ".$user_2->name." to project ".$row->title; 
                exit;
            }     
            $mainframe = JFactory::getApplication();
            $mailfrom = $mainframe->getCfg('mailfrom');
            $fromname = $mainframe->getCfg('fromname');
            $mail = JFactory::getMailer();
            $mail->sendMail($mailfrom, $fromname, $user_2->email, "You have been invited!", $mailMSG, true);    
            $query = "INSERT INTO #__pf_projects_invites (project_id, invited, invited_by, accepted, date_added) VALUES (".$data->project_id.", ".$data->user_id.", ".$user->id.", 0, ".time().")";
            $db->setQuery($query);
            $db->Query();
        }
        
        exit;
    }
    private function editInstead($user_id, $skillCatg, & $db)
    {
        if (!is_numeric($skillCatg)) return false;
        $query = "SELECT id FROM #__pf_project_skills_added WHERE userid= $user_id AND skillCatg='$skillCatg' LIMIT 1";
        
        $db->setQuery($query);
        $id = $db->loadResult();
        return (is_numeric($id)) ? true : false;
    }   
    public function addUserKill()
    {
        
        $response = array();
        $response['status'] = 0;
        $db = JFactory::getDbo();
        $user_id = $_POST['userid'];
        if (!is_numeric($user_id) || $user_id == 0) return;
        
        $query = "DELETE FROM #__pf_user_skills WHERE user_id = $user_id AND skillCatg='".$db->escape($_POST['skillCatg'])."' LIMIT 50";
        $db->setQuery($query);
        $db->Query();
        if ($_POST['editInstead'] == 1)
        {
            $editInstead = $this->editInstead($user_id, $_POST['skillCatg'], $db);
        }
        else $editInstead = 0;
        if ($editInstead)
        { $query = "UPDATE #__pf_project_skills_added SET skillCatg='".$db->escape($_POST['skillCatg'])."', skillTags='".$db->escape($_POST['skillTags'])."', skillDesc='".$db->escape($_POST['skillDesc'])."', skill='".$db->escape($_POST['skilltoAdd'])."' WHERE userid= $user_id AND skillCatg='".$db->escape($_POST['skillCatg'])."' LIMIT 1"; }
        else { $query = "INSERT INTO #__pf_project_skills_added (userid, skillDesc, skill, skillTags, skillCatg) VALUES ($user_id, '".$db->escape($_POST['skillDesc'])."', '".$db->escape($_POST['skilltoAdd'])."', '".$db->escape($_POST['skillTags'])."', '".$db->escape($_POST['skillCatg'])."');"; }
        $taskIds = $_POST['taskIds'];
         
        $db->setQuery($query);
        $r = $db->Query();
        if ($_POST['editInstead'] == 1)
        {
            $query = "SELECT id FROM #__pf_project_skills_added WHERE userid= $user_id AND skillCatg='".$db->escape($_POST['skillCatg'])."' LIMIT 1";
            $db->setQuery($query);
            $profiID = $db->loadResult();
        }
        else $profiID = $db->insertid();
        $response['id'] = $profiID;
        if (!$r) {
                $response['status'] = 0;
                 $response['result'] = "There was an error adding data to the database";
        } 
        else
        {
            $response['status'] = 1;
            if ($editInstead)
            {
                $response['edited'] = 1;   
            }
            else $response['edited'] = 0;
              $response['result'] = "Data successfully added";
        }
        if ($taskIds)
        {
            $taskIds = json_decode($taskIds);
            if (is_array($taskIds))
            {
                foreach ($taskIds as $tsk)
                {
                    if (!is_numeric($tsk) || $tsk == 0) continue;
                    $query = "SELECT skill_id FROM #__pf_user_skills WHERE user_id = $user_id AND skill_id = $tsk AND skillCatg='".$db->escape($_POST['skillCatg'])."' LIMIT 1";
                    $db->setQuery($query);
                    $taskid = $db->loadResult();
                   if (is_numeric($taskid)) continue;
                    else {
                         $query = "INSERT INTO #__pf_user_skills (user_id, skill_id, skillCatg, date_added) VALUES ('$user_id', '$tsk', '".$db->escape($_POST['skillCatg'])."', CURRENT_TIMESTAMP)";
                         $db->setQuery($query);
                         $db->Query();
                    }
                }
            }
        }
         $JNewTagsCag = $_POST['JNewTagsCag'];
         $JNewTags = $_POST['JNewTags'];
         if ($JNewTags)
         {
             $JNewTags = json_decode($JNewTags);
             $JNewTagsCag = json_decode($JNewTagsCag);
             $this->_insertNewTags($JNewTags, $JNewTagsCag, $user_id, $profiID, $db);
         }
         echo  json_encode($response);
        exit;
    }
    private function _insertNewTags($tags, $tagCatg, $userid, $profiID, &$db)//new tags are added but not published
    {
        $a = 0;
        $id = '';
      //  echo "we do get here";
        if (is_numeric($profiID))
        {
            $query = "SELECT * FROM #__pf_project_skills_added WHERE userid = '$userid' LIMIT 1";
            //echo $query;
            $db->setQuery($query);
            $rowSkills = $db->loadObject();
            
        }
        $newTags = array();
        //print_r($newTags);
        foreach ($tags as $tag)
        {
             $query = "SELECT id FROM #__pf_skills WHERE skill = '$tag' AND category = '".$tagCatg[$a]."' LIMIT 1";
             $db->setQuery($query);
             $oldID = $db->loadResult();
             if (!is_numeric($oldID) || $oldID < 1)
             {
                 $query ="INSERT INTO #__pf_skills (id,skill,category,user_id,published) VALUES (NULL , '$tag', '".$tagCatg[$a]."', '$userid', '1')";
                 $db->setQuery($query);
                 $db->Query();
                 $id = $db->insertid();
             }
             if ($tag) $newTags[] = $tag;
             
             if (is_numeric($id) && $id > 0) 
             { 
                  $query = "INSERT INTO #__pf_user_skills (user_id, skill_id, date_added, skillCatg) VALUES ('$userid', '$id', CURRENT_TIMESTAMP, '".$tagCatg[$a]."')";
                  $db->setQuery($query);
                  $db->Query(); 
             }
             $a++;
        }
        if ($a > 0)
        {
            $tags = implode(',', $newTags);
        }
        $rowSkills->skillTags = $rowSkills->skillTags.",".$tags;
        $query = "UPDATE #__pf_project_skills_added SET skillTags='".$rowSkills->skillTags."' WHERE userid = $userid LIMIT 1";
        //echo $query;
        $db->setQuery($query);
        $db->Query();
    }
      
    public function display($cachable = false, $urlparams = false)
    {
        // Load CSS and JS assets
        JHtml::_('pfhtml.style.bootstrap');
        JHtml::_('pfhtml.style.projectfork');

        JHtml::_('pfhtml.script.jQuery');
        JHtml::_('pfhtml.script.bootstrap');
        JHtml::_('pfhtml.script.projectfork');
 
        JHtml::_('behavior.tooltip');
        $document = JFactory::getDocument();
        $uribase = JURI::base(true). "/components/com_pfprojects/css/style.css";
        $js = "var tasksURL = '".JURI::root()."';";
    $document->addScriptDeclaration($js);
        $document->addStyleSheet($uribase);
        $document->addScript(JURI::root() . 'libraries/projectfork/js/angular.min.js');
        $document->addScript(JURI::root() . 'components/com_pfprojects/js/angpfp.js');
        
        
         
         
        
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
            'filter_search'    => 'STRING',
            'filter_published' => 'CMD'
        );
 
        // Inject default view if not set
        if (empty($view)) {
            JRequest::setVar('view', $this->default_view);
        }
         
        // Check for edit form.
		if ($view == 'form' && !$this->checkEditId('com_pfprojects.edit.form', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
 
        // Display the view
        parent::display($cachable, $urlparams);

        // Return own instance for chaining
        return $this;
    }
    public function acceptProposal()
    {
        $project_id = $_POST['project'];
        $propos_id = $_POST['proposal'];
        if (!is_numeric($propos_id) || $propos_id == 0) return;
        if (!is_numeric($project_id) || $project_id == 0) return;
        $query = "UPDATE #__pf_projects_msg SET accepted = 1 WHERE project_id = $project_id AND id = $propos_id LIMIT 1";
        $db = JFactory::getDbo();
        $r = $db->setQuery($query)->Query();
        if ($r)
        {
            $query = "SELECT user_id FROM #__pf_projects_msg WHERE project_id = $project_id AND id = $propos_id LIMIT 1";
            $user_id = $db->setQuery($query)->loadResult();
            if (is_numeric($user_id) && $user_id > 0)
            {
                $query = "INSERT INTO #__pf_project_members (project_id, user_id, status, member_since) VALUES($project_id, $user_id, 1, ".time().")";
                $db->setQuery($query)->Query();
            }
        }
        exit;
    }
    public function rejecroposal()
    {
        $project_id = $_POST['project'];
        $propos_id = $_POST['proposal'];
        if (!is_numeric($propos_id) || $propos_id == 0) return;
        if (!is_numeric($project_id) || $project_id == 0) return;
        $query = "UPDATE #__pf_projects_msg SET declined = 1 WHERE project_id = $project_id AND id = $propos_id LIMIT 1";
        $db = JFactory::getDbo();
        $r = $db->setQuery($query)->Query();
        $this->proposalCleanUp($db);
        exit;
    }
    private function proposalCleanUp(& $db)
    {
        $threeMonths = 3600 * 24 * 90;
        $threeMonths = time() - $threeMonths;
        $query = "DELETE FROM #__pf_projects_msg WHERE declined = 1 AND posted_on < $threeMonths";
        $db->setQuery($query)->Query();
    }
}
