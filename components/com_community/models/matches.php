<?php
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT .'/components/com_community/models/models.php' );
require_once( JPATH_ROOT .'/libraries/projectfork/colcre/matches.php' );//libraries/projectfork/colcre/matches.php


class CommunityModelMatches extends JCCModel
implements CNotificationsInterface
{
    var $_pagination = null;
    var $user;
    public function getMatches($_isread=true)
	{
	    jimport('joomla.html.pagination');
		$my = CFactory::getUser();
		$this->user = $my;

		if (empty($this->_data))
		{
			$this->_data = array();
                        $pm = new projectMatches();
                        $result = $pm->getUserMatches($my->id);
                  /*  
		 $sql = "SELECT a.*, e.title as task_title, b.*,d.skill, c.title,c.alias,c.description, c.created_by, c.created, c.access, c.state, round((  (select count(*) "
            . "FROM #__pf_user_skills ab join #__pf_project_skills bc ON ab.skill_id = bc.skill_id where ab.user_id = $my->id and bc.task_id = e.id) / "
            . "(SELECT count(cd.task_id) FROM `#__pf_project_skills` as cd WHERE cd.task_id = e.id) * 100)) as TaskMatchPercentage, round((select count(*) "
            . "FROM #__pf_user_skills de join #__pf_project_skills ef ON de.skill_id = ef.skill_id where de.user_id = $my->id and ef.project_id = c.id)  / "
            . "(SELECT count(ef.task_id) FROM `#__pf_project_skills` as ef WHERE project_id = c.id) * 100) as ProjectMatchPercentage "
            . "FROM #__pf_project_skills as a INNER JOIN #__pf_user_skills as b ON b.skill_id = a.skill_id INNER JOIN "
            . "#__pf_projects as c ON c.id = a.project_id INNER JOIN #__pf_skills as d ON d.id = a.skill_id INNER "
            . "JOIN #__pf_tasks as e ON e.id = a.task_id WHERE b.user_id= $my->id ORDER BY created DESC LIMIT 50";	
                
                        $sql = "SELECT projects.alias,
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
                        
                        /*$db->setQuery($sql);
			$result = $db->loadObjectList();*/
                        //print_r($result);
                         $limit 		= $this->getState('limit');
		         $limitstart	= $this->getState('limitstart');
                         $limitstart = JRequest::getInt('limitstart', '0');
                         $limit = JRequest::getVar( "limit", '10', 'get', 'int');
                         if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($result), $limitstart, $limit );
				$result = array_slice($result, $limitstart, $limit);
			}
                        $i = 0;
                        foreach($result as $rs)
                        {
                           $spec = $pm->specifyMatch($rs->description, $rs->task_id, $rs->project_id, $my->id);
                           //$result[$i]->ProjectMatchPercentage = $this->getProjPercen($my->id, $rs->project_id);
                           //$result[$i]->TaskMatchPercentage = $this->getTaskPercen($my->id, $rs->task_id);
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
   /*
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
/*
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
*/
private function getProjPercen($userid, $projectid)
{
    if (!is_numeric($userid) || !is_numeric($projectid)) return;
    $query = "SELECT a.*, e.title as task_title, b.*,d.skill, c.title,c.alias,c.description, c.created_by, c.created, c.access, c.state, ((  (select count(*) FROM #__pf_user_skills ab join #__pf_project_skills bc ON ab.skill_id = bc.skill_id where ab.user_id = 860 and bc.task_id = e.id) / (SELECT count(cd.task_id) FROM `#__pf_project_skills` as cd WHERE cd.task_id = e.id) * 100)) as TaskMatchPercentage, round((select count(*) FROM #__pf_user_skills de join #__pf_project_skills ef ON de.skill_id = ef.skill_id where de.user_id =860 and ef.project_id = c.id)  / (SELECT count(ef.task_id) FROM `#__pf_project_skills` as ef WHERE project_id = c.id) * 100) as projectPercentage FROM #__pf_project_skills as a INNER JOIN #__pf_user_skills as b ON b.skill_id = a.skill_id INNER JOIN #__pf_projects as c ON c.id = a.project_id INNER JOIN #__pf_skills as d ON d.id = a.skill_id INNER JOIN #__pf_tasks as e ON e.id = a.task_id WHERE b.user_id=860 ORDER BY created DESC ";
    
     $db = $this->getDBO();
    $db->setQuery($query);
	 $result = $db->loadResult();
         return $result;
}/*
private function getTaskPercen($userid, $taskid)
{
     if (!is_numeric($userid) || !is_numeric($taskid)) return;
    $query = "select round((select count(*) FROM kba07_pf_user_skills a join kba07_pf_project_skills b ON a.skill_id = b.skill_id where a.user_id = $userid and b.task_id = $taskid) / (SELECT count(task_id) FROM `kba07_pf_project_skills` WHERE task_id = $taskid) * 100) as TaskMatchPercentage
FROM kba07_pf_project_skills` WHERE task_id = $taskid";
    //echo $query;
     $db = $this->getDBO();
    $db->setQuery($query);
	 $result = $db->loadResult();
         return $result;
}*/
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

