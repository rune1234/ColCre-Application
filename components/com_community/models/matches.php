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

		        $sql = "SELECT a.*, b.*,d.skill, c.title,c.alias,c.description, c.project_brief, c.created_by, c.created, c.access, c.state FROM #__pf_project_skills as a INNER JOIN #__pf_user_skills as b ON b.skill_id = a.skill_id INNER JOIN #__pf_projects as c ON c.id = a.project_id INNER JOIN #__pf_skills as d ON d.id = a.skill_id  WHERE b.user_id=$my->id";
			$db->setQuery($sql);
			$result = $db->loadObjectList();
                        $limit 		= $this->getState('limit');
		        $limitstart	= $this->getState('limitstart');
			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($result), $limitstart, $limit );
				$result = array_slice($result, $limitstart, $limit);
			}

			return $result;
		}

		return null;
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
