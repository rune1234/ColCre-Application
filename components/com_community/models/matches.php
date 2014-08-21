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

		        $sql = "SELECT a.*, e.title as task_title, b.*,d.skill, c.title,c.alias,c.description, c.created_by, c.created, c.access, c.state FROM #__pf_project_skills as a INNER JOIN #__pf_user_skills as b ON b.skill_id = a.skill_id INNER JOIN #__pf_projects as c ON c.id = a.project_id INNER JOIN #__pf_skills as d ON d.id = a.skill_id INNER JOIN #__pf_tasks as e ON e.id = a.task_id WHERE b.user_id=$my->id ORDER BY created DESC";
			/*$sql = "SELECT projects.alias,
   project_skills.*, 
   projects.description, projects.title, projects.created,
	skills.skill FROM kba07_users users, 	
	kba07_pf_project_skills project_skills
JOIN kba07_pf_projects AS projects ON projects.id = project_skills.project_id
JOIN kba07_pf_skills AS skills ON skills.id = project_skills.skill_id
WHERE (project_skills.project_id, project_skills.task_id, project_skills.skill_id) IN (
	SELECT project_skills_2.project_id, project_skills_2.task_id, project_skills_2.skill_id
	FROM kba07_pf_project_skills project_skills_2
	WHERE project_skills_2.skill_id IN (
		SELECT user_skills_2.skill_id
		FROM kba07_pf_user_skills user_skills_2
		WHERE user_skills_2.user_id = users.id
	) AND users.id =$my->id".// AND users.id != projects.created_by
") ORDER BY projects.created DESC";*/
                       
                        $db->setQuery($sql);
			$result = $db->loadObjectList();
                        //print_r($result);
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
                           $result[$i]->ProjectMatchPercentage = $this->getProjPercen($my->id, $rs->project_id);
                           $result[$i]->TaskMatchPercentage = $this->getTaskPercen($my->id, $rs->task_id);
                           //print_r($matchPercent);
                          /* if ($matchPercent)
                           {
                               $result[$i]->TaskMatchPercentage = $matchPercent->TaskMatchPercentage;
                               $result[$i]->ProjectMatchPercentage = $matchPercent->ProjectMatchPercentage;
                           }*/
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

private function getProjPercen($userid, $projectid)
{
    if (!is_numeric($userid) || !is_numeric($projectid)) return;
    $query = "select round((select count(*) FROM kba07_pf_user_skills a join kba07_pf_project_skills b ON a.skill_id = b.skill_id where a.user_id = $userid and b.project_id = $projectid) / (SELECT count(task_id) FROM `kba07_pf_project_skills` WHERE project_id = $projectid) * 100) as projectPercentage
FROM kba07_pf_project_skills` WHERE project_id = $projectid";
    
     $db = $this->getDBO();
    $db->setQuery($query);
	 $result = $db->loadResult();
         return $result;
}
private function getTaskPercen($userid, $taskid)
{
     if (!is_numeric($userid) || !is_numeric($taskid)) return;
    $query = "select round((select count(*) FROM kba07_pf_user_skills a join kba07_pf_project_skills b ON a.skill_id = b.skill_id where a.user_id = $userid and b.task_id = $taskid) / (SELECT count(task_id) FROM `kba07_pf_project_skills` WHERE task_id = $taskid) * 100) as TaskMatchPercentage
FROM kba07_pf_project_skills` WHERE task_id = $taskid";
    echo $query;
     $db = $this->getDBO();
    $db->setQuery($query);
	 $result = $db->loadResult();
         return $result;
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

