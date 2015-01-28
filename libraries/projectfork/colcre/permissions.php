<?php
defined('_JEXEC') or die();
//the idea here is overriding the defficient permission system used by ProjectFork
include_once('project.php');
class colcrePermissions extends projectData 
{
    var $db;
    var $user;
    var $userSession = array();
    public function __construct()
    {
        $this->db = JFactory::getDbo();
        $this->user = JFactory::getUser();
    }
    public function projectAccess($item_id, $type)
    {
        $db = $this->db;
        if (!is_numeric($item_id)) return false;
        if ($type == 'project')
        {
            $query = "SELECT commentsetting, created_by FROM #__pf_projects WHERE id = $item_id LIMIT 1";
            $row = $db->setQuery($query)->loadObject();
            $access = $row->commentsettings;
            $owner = $row->created_by;
            
            if ($this->user->id == $owner) return true;
            if ($access == 3) return false;
            if ($access == 0) return true;
            $prjDat = new projectData();
            if ($access == 1) return $prjDat->invitedORmember($item_id);
            if ($access == 2) return ( $prjDat->userMember($item_id) == 1) ? true : false;
        }
        else return true;//for now
    }
    public function comments($item, $access, $project_id = '')
    {
        if ($access != 'postnew' && (!isset($item->created_by) || !is_numeric($item->created_by)))
        { echo "ERROR - unable to get comment creator, exiting"; return false; }
        
        if ($access == 'postnew' && !is_numeric($project_id))
         {
           echo " ERROR - postnew co-existing with a non-numeric project-id"; 
        }
         switch ($access)
        {
            case 'create':
                if ($this->_getUserMap($this->user->id, $this->db)) return true;
                $proj = $this->_projectSettings($item->project_id, $this->db);
                if ($proj->comm_perm == 'registered' && $this->user->id > 0) return true;//invited will be more complex
                break;
            case 'postnew': //post when there's no comment data
            {    
                if ($this->_getUserMap($this->user->id, $this->db)) return true;
                $proj = $this->_projectSettings($project_id, $this->db);
                if ($proj->comm_perm == 'registered' && $this->user->id > 0) return true;//invited will be more complex
                break;
            }
            case 'edit':
            case 'delete':
                if ($this->user->id == $item->created_by) return true;
                if ($this->_getUserMap($this->user->id, $this->db)) return true;
                break;
            case 'trash':
                if ($this->user->id == $item->created_by) return true;
                if ($this->_getUserMap($this->user->id, $this->db)) return true;
        }
    }
    public function milesstones($access)
    {
        
    }
    public function projects($access)
    {
        
    }
    private function _getUserMap($user, & $db)//get user's rights to control tasks, comments, etc
    {
       if (!is_numeric($user) || $user == 0) return false;
       if (isset($this->userSession[$user])) { return $this->userSession[$user]; } 
       $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id = $user LIMIT 1";
       $db->setQuery($query);
       $level = $db->loadResult();
       if ($level > 5 && $level != 9) { $this->userSession[$user] = true; return true;}
       else { $this->userSession[$user] = false; return false;}
    }
    private function _projectSettings($id, & $db)//let's find out who created this project
    {
       if (!is_numeric($id) || $id == 0) { /*echo "ERROR - unable to get project ID, exiting"; */ return false; }
       $query = "SELECT * FROM #__pf_projects WHERE id = $id LIMIT 1";
       $db->setQuery($query);
       $project = $db->loadObject();
       return $project;
    }
}
?>

