<?php
//redacron script Colcre.php
('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT .'/components/com_community/models/models.php' );

class CommunityModelColcre extends JCCModel
{
    function getSkillsList()
    {
        $db = JFactory::getDbo();
       // $query = "SELECT * FROM #__pf_skill_category ORDER BY category";
        $query = "SELECT id, LOWER(title) as category FROM #__categories WHERE extension='com_pfprojects' ORDER BY LOWER(title)";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }
    function getUserSkills($userid, $catg = '')
    {
        if (!is_numeric($userid)) { return false; }
        else
        {
             $db = JFactory::getDbo();
        if (is_numeric($catg) && $catg > 0)
        {
            $catg = " AND a.skillCatg = $catg";
        }
        else {
            $catg = '';
            echo "Warning on colcre.php, catg is not a number";
        }
             $query = "SELECT b.* FROM #__pf_user_skills as a INNER JOIN #__pf_skills as b ON a.skill_id = b.id WHERE a.user_id = $userid $catg ORDER BY b.skill";
             $db->setQuery($query);
             $rows = $db->loadObjectList();
             return ($rows) ? $rows : false;
        }
    }
    function getSkillsAdded($userid)//this is a different database table than the one getUserSkills fetches
    {
        if (!is_numeric($userid)) { return false; }
        else
        {
             $db = JFactory::getDbo();
             $query = "SELECT * FROM #__pf_project_skills_added WHERE userid = $userid LIMIT 1";
             $db->setQuery($query);
             $row = $db->loadObject();
             return ($row) ? $row : false;
        }
    }
    
}
