<?php
defined('_JEXEC') or die();
class projectMatches
{
      function getDBO()
      {
          return JFactory::getDbo();
      }
      
      public function specifyMatch($description, $taskId, $projectId, $userId)
       {
             $db = $this->getDBO();
             if (!is_numeric($taskId)) return false;
             if (!is_numeric($projectId)) return false;
             if (!is_numeric($userId)) return false;
             $skill = $this->_getMatchDesc($userId, $db);
             $desc = $skill->skillDesc;
             if (!$desc) return;
             $query = "SELECT projects.title ProjectTitle,
        MATCH (project_tasks.description) AGAINST ('$desc' IN NATURAL LANGUAGE MODE) as MatchAgainst
        FROM #__pf_tasks AS project_tasks JOIN #__pf_projects AS projects ON projects.id = project_tasks.project_id
        WHERE project_tasks.id = '$taskId' AND project_tasks.project_id = '$projectId' LIMIT 1";

             $db->setQuery($query);
             $rows = $db->loadObjectList();
             return $rows;
     }
     public function getUserMatches($userid)
     {
         if (!is_numeric($userid)) return;
         $db = $this->getDBO();
         $query = "SELECT a.*, e.title as task_title, b.*,d.skill, c.catid, c.title,c.alias,c.description, c.created_by, c.created, c.access, c.state, round((  (select count(*) "
            . "FROM #__pf_user_skills ab join #__pf_project_skills bc ON ab.skill_id = bc.skill_id where ab.user_id = $userid and bc.task_id = e.id) / "
            . "(SELECT count(cd.task_id) FROM `#__pf_project_skills` as cd WHERE cd.task_id = e.id) * 100)) as TaskMatchPercentage, round((select count(*) "
            . "FROM #__pf_user_skills de join #__pf_project_skills ef ON de.skill_id = ef.skill_id where de.user_id = $userid and ef.project_id = c.id)  / "
            . "(SELECT count(ef.task_id) FROM `#__pf_project_skills` as ef WHERE project_id = c.id) * 100) as ProjectMatchPercentage "
            . "FROM #__pf_project_skills as a INNER JOIN #__pf_user_skills as b ON b.skill_id = a.skill_id INNER JOIN "
            . "#__pf_projects as c ON c.id = a.project_id INNER JOIN #__pf_skills as d ON d.id = a.skill_id INNER "
            . "JOIN #__pf_tasks as e ON e.id = a.task_id WHERE b.user_id= $userid ORDER BY created DESC LIMIT 50";
         $db->setQuery($query);
         $rows = $db->loadObjectList();
         
         return $rows;
     }
        private function _getMatchDesc($userId, & $db)
    {
        //$db = JFactory::getDbo();
         if (!is_numeric($userId) ) return;
        $query = "SELECT * FROM #__pf_project_skills_added WHERE userid = $userId LIMIT 1";

         $db->setQuery($query);
         $rows = $db->loadObject();
         return ($rows) ? $rows : false;
     }
}
?>