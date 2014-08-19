<?php
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT .'/components/com_community/models/models.php' );

class CommunityModelMatches extends JCCModel
implements CNotificationsInterface
{
    var $_pagination = null;
    
    public function getMatches($_isread=true)
	{
	    jimport('joomla.html.pagination');
		$my = CFactory::getUser();
		 

		if (empty($this->_data))
		{
			$this->_data = array();

			$db = $this->getDBO();

		        $sql = "SELECT a.*, b.*,d.skill, c.title,c.alias,c.description, c.created_by, c.created, c.access, c.state FROM #__pf_project_skills as a INNER JOIN #__pf_user_skills as b ON b.skill_id = a.skill_id INNER JOIN #__pf_projects as c ON c.id = a.project_id INNER JOIN #__pf_skills as d ON d.id = a.skill_id  WHERE b.user_id=$my->id";
			$db->setQuery($sql);
			$result = $db->loadObjectList();
                        $limit 		= $this->getState('limit');
		        $limitstart	= $this->getState('limitstart');
			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($result), $limitstart, $limit );
				$result = array_slice($result, $limitstart, $limit);
			}
                        $i = 0;
                        foreach($result as $rs)
                        {
                           $spec = $this->specifyMatch($rs->description, $rs->task_id, $rs->project_id, $my->id);
                           //print_r($spec);
                          $result[$i]->MatchAgainst = ($spec) ? $spec->MatchAgainst : 0;
                          $i++;
                        }
			return $result;
		}

		return null;
                
   }
   private function getMatchDesc($userId)
{
    $db = JFactory::getDbo();
     if (!is_numeric($userId) ) return;
    $query = "SELECT * FROM #__pf_project_skills_added WHERE userid = $userId LIMIT 1";
     
     $db->setQuery($query);
     $rows = $db->loadObject();
     //print_r($rows);
     return ($rows) ? $rows : false;
}
   private function specifyMatch($description, $taskId, $projectId, $userId)
{
     $skill = $this->getMatchDesc($userId);
     $desc = $skill->skillDesc;
     if (!$desc) return;
     $query = "SELECT projects.title ProjectTitle,
MATCH (project_tasks.description) AGAINST ('$desc' IN NATURAL LANGUAGE MODE) as MatchAgainst
FROM #__pf_tasks AS project_tasks JOIN #__pf_projects AS projects ON projects.id = project_tasks.project_id
WHERE project_tasks.id = '$taskId' AND project_tasks.project_id = '$projectId' LIMIT 1";
      
     $db = JFactory::getDbo();
     $db->setQuery($query);
     $rows = $db->loadObject();
     return $rows;
}
    public function getTotalNotifications( $userId )
    {
         $db = $this->getDBO();
         $sql = "SELECT count(*) FROM #__pf_project_skills as a INNER JOIN #__pf_user_skills as b ON b.skill_id = a.skill_id WHERE b.user_id=$userId";
	 $db->setQuery($sql);
	 $result = $db->loadResult();
         return $result;
    }
    public function getPagination()
    {
        return $this->_pagination;
    }
}
?>
