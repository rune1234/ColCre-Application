<?php
defined('_JEXEC') or die();
class projectData 
{
    var $db = '';
    var $user = '';
    var $userSession = array();
    var $projUser = array();
    public function invitedORmember($project_id)
    {
           echo "project is $project_id<br />";
        if ($this->userInvited($project_id)) return true;
        $member = $this->userMember($project_id);
        if ($member && $member == 1) return true;
        else return false;
    }
    public function userInvited($project_id)
    {
        $user = JFactory::getUser();
        if (!isset($user) || $user->id == 0) return false;
        if (!is_numeric($project_id)) return false;
        $db = $this->db;
        $query = "SELECT project_id FROM #__pf_projects_invites WHERE project_id = $project_id AND invited = $user->id LIMIT 1";
        $id = $db->setQuery($query)->loadResult();
        return ($id) ? true : false;
    }
    public function userMember($project_id)
    {
        $user = JFactory::getUser();
        if (!isset($user) || $user->id == 0) return false;
        if (!is_numeric($project_id)) return false;
        $db = $this->db;
        $query = "SELECT status FROM #__pf_project_members WHERE project_id = $project_id AND user_id = $user->id LIMIT 1";
        $status = $db->setQuery($query)->loadResult();
        return (is_numeric($status)) ? $status : false;
    }
    private function _getUserMap($user, & $db)
    {
       if (!is_numeric($user) || $user == 0) return false;
       if (isset($this->userSession[$user])) { return $this->userSession[$user]; } 
       $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id = $user LIMIT 1";
       $db->setQuery($query);
       $level = $db->loadResult();
       if ($level > 5 && $level != 9) { $this->userSession[$user] = true; return true;}
       else { $this->userSession[$user] = false; return false;}
    }
    private function _getCreator($id, & $db)//let's find out who created this project
    {
       $query = "SELECT created_by FROM #__pf_projects WHERE id = $id LIMIT 1";
       $db->setQuery($query);
       $created = $db->loadResult();
      // echo "created by is $created";
       return (is_numeric($created)) ? $created : 0;
    }
    public function projectInfo()
    {
        $project_id = JRequest::getInt('id');
       if (!is_numeric($project_id) || $project_id == 0) return false;
       $db = JFactory::getDbo();
       $query = "SELECT * FROM #__pf_projects WHERE id = $project_id LIMIT 1";
       $db->setQuery($query);
       return $db->loadObject();
       
    }
    public function projectOwner()
    {
       if (!isset($this->user->id) || $this->user->id == 0) return false;
       $project_id = JRequest::getInt('id', 0);
           
       if (!is_numeric($project_id) || $project_id == 0) return false;
       $db = JFactory::getDbo();
       $level = $this->_getUserMap($this->user->id, $db);
       if ($level) return true;
       $creator = $this->_getCreator($project_id, $db);    
       if ($creator == $this->user->id) $myProject = true;
       else $myProject = false;
       return $myProject;
    }
    function checkPermissions($class)
    {
       $case = str_replace(array('PF', 'HelperDashboard'), '', $class);
       if (!isset($this->user->id) || $this->user->id == 0) return false;
       $project_id = JRequest::getInt('id');
       if (!is_numeric($project_id) || $project_id == 0) return false;
       $db = JFactory::getDbo();
       $level = $this->_getUserMap($this->user->id, $db);
       $creator = $this->_getCreator($project_id, $db);    
       if ($creator == $this->user->id) $myProject = true;
       else $myProject = false;
           
       switch ($case):
           case 'projects':
                if ($this->user->id > 0) { return true; }
                else return false;
            break;
           case 'milestones':
               
               if ($this->user->id > 0 && ($myProject || $level)) { return true;}
                else { return false; }
           break;
           case 'tasks':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           case 'time':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           case 'repo':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           case 'forum':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           default:
               
       endswitch;
    }
    function __construct()
    {
        $this->db = JFactory::getDbo();
        $this->user = JFactory::getUser();
    }
     function getDBO()
      {
          if ($this->db == '') $this->db = JFactory::getDbo();
          return $this->db;
      }
      function getCategory($catid)
      {
          if (!is_numeric($catid)) return;
          $query = "SELECT * FROM #__categories WHERE id=$catid LIMIT 1";
           $db = $this->getDBO();
          $db->setQuery($query);
          $row = $db->loadObject();
          return $row;
      }
      function lookupIcon($task) 
        {  
            $key = (int) $task->id;
 
            // Default - Projectfork avatar
            $base_path = JPATH_ROOT . '/media/com_projectfork/repo/0/logo';
            $base_url  = JURI::root(true) . '/media/com_projectfork/repo/0/logo';
            $img_path  = NULL;
//echo $base_path; echo $id; exit;
            if (JFile::exists($base_path . '/' . $key . '.jpg')) {
                $img_path = $base_url . '/' . $key . '.jpg';
            }
            elseif (JFile::exists($base_path . '/' . $key . '.jpeg')) {
                $img_path = $base_url . '/' . $key . '.jpeg';
            }
            elseif (JFile::exists($base_path . '/' . $key . '.png')) {
                $img_path = $base_url . '/' . $key . '.png';  
            }
            elseif (JFile::exists($base_path . '/' . $key . '.gif')) {
                $img_path = $base_url . '/' . $key . '.gif';
            }
            else {  //echo JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".png"; 
                if ($task->category_alias && is_file(JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".png"))  
                $img_path = JUri::base()."/templates/colcre/images/".$task->category_alias.".png";       
               else return JUri::base()."/images/foldered.jpg";
                  
            }

            return $img_path;
 
        }
        function commentData($id)
        {
            $query = "SELECT * FROM #__pf_comments WHERE id = $id LIMIT 1";
            $db = JFactory::getDbo();
            $db->setQuery($query);
            $row = $db->loadObject();
            return ($row) ? $row : false;
        }
}
?>
