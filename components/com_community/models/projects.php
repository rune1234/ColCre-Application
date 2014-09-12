<?php
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT .'/components/com_community/models/models.php' );

class CommunityModelProjects extends JCCModel implements CNotificationsInterface
{
    var $_pagination = null;
    var $user;
    function getProjects()
    {
        $db = $this->getDBO();
        $my = CFactory::getUser();
        $this->user = $my;
        $query = "SELECT count(*) as total FROM #__pf_projects WHERE created_by = $my->id ORDER BY id DESC";
         $db->setQuery($query);
			$total = $db->loadResult();
                        //print_r($result);
                      /*  $limit 		= $this->getState('limit');
		        $limitstart	= $this->getState('limitstart');*/
                         $limitstart = JRequest::getInt('limitstart');
                         $limit = JRequest::getVar( "limit", '5', 'get', 'int');
        $query = "SELECT * FROM #__pf_projects WHERE created_by = $my->id ORDER BY id DESC  LIMIT $limitstart, $limit"; 
        
        $db->setQuery($query);
			$result = $db->loadObjectList();
                        if (empty($this->_pagination)) {
				$this->_pagination = new JPagination($total, $limitstart, $limit );
				//$result = array_slice($result, $limitstart, $limit);
			}
                        //print_r($result); exit;
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