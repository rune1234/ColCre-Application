<?php
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT .'/components/com_community/models/models.php' );

class CommunityModelMatches extends JCCModel
implements CNotificationsInterface
{
    
    public function getMatches($_isread=true)
	{
	    jimport('joomla.html.pagination');
		$my = CFactory::getUser();
		 

		if (empty($this->_data))
		{
			$this->_data = array();

			$db = $this->getDBO();

			// Select all recent message to the user
// 			$sql = "SELECT b.* "
// 				." FROM #__community_msg_recepient as a, "
// 				." #__community_msg as b "
// 				." WHERE "
// 				." a.`to` = {$to} AND "
// 				." b.`id` = a.`msg_id` AND"
// 				." a.`deleted`=0 "
// 				." ORDER BY b.`id` ASC, b.`parent`";

			$sql = 'SELECT MAX(b.'.$db->quoteName('id').') AS '.$db->quoteName('bid');
			$sql .= ' FROM '.$db->quoteName('#__community_msg_recepient').' as a, '.$db->quoteName('#__community_msg').' as b';
//			$sql .= ' WHERE (a.'.$db->quoteName('to').' = '.$db->Quote($to) . ' OR a.'.$db->quoteName('msg_from').' = '.$db->Quote($to) . ')';
			$sql .= ' WHERE (a.'.$db->quoteName('to').' = '.$db->Quote($to) . ')';
			$sql .= ' AND b.'.$db->quoteName('id').' = a.'.$db->quoteName('msg_id');
			$sql .= ' AND (a.'.$db->quoteName('deleted').'='.$db->Quote(0) . ' || (a.' . $db->quoteName('deleted') . '=' . $db->Quote(1) . ' && b.from =' . $to . '))';
			$sql .= ' AND (b.'.$db->quoteName('deleted').'='.$db->Quote(0) . ' || (b.'.$db->quoteName('deleted').'='.$db->Quote(1) . ' && b.from !=' . $to . '))';
			$sql .= ' GROUP BY b.'.$db->quoteName('parent');
			$db->setQuery($sql);
			$tmpResult = $db->loadObjectList();
			$strId = '';
			foreach ($tmpResult as $tmp)
			{
				if (empty($strId)) $strId = $tmp->bid;
				else $strId = $strId . ',' . $tmp->bid;
			}

			$result	= null;

			if( ! empty($strId) )
			{
				$sql = 'SELECT b.'.$db->quoteName('id').', b.'.$db->quoteName('from').', b.'.$db->quoteName('parent').', b.'.$db->quoteName('from_name').', b.'.$db->quoteName('posted_on').', b.'.$db->quoteName('subject').', a.'.$db->quoteName('to');
				$sql .= ' FROM '.$db->quoteName('#__community_msg').' as b, '.$db->quoteName('#__community_msg_recepient').' as a ';
				$sql .= ' WHERE b.'.$db->quoteName('id').' in ('.$strId.')';
				$sql .= ' AND b.'.$db->quoteName('id').' = a.'.$db->quoteName('msg_id');
                                if(!$_isread)
                                {
                                    $sql .= ' AND a.'.$db->quoteName('is_read').' = '.$db->Quote('0');
                                }
				$sql .= ' AND (a.'.$db->quoteName('deleted').'='.$db->Quote(0) . ' || (a.' . $db->quoteName('deleted') . '=' . $db->Quote(1) . ' && b.from =' . $to . '))';
				$sql .= ' AND (b.'.$db->quoteName('deleted').'='.$db->Quote(0) . ' || (b.'.$db->quoteName('deleted').'='.$db->Quote(1) . ' && b.from !=' . $to . '))';



				$sql .= ' ORDER BY b.'.$db->quoteName('posted_on').' DESC';
				$db->setQuery($sql);
				$result = $db->loadObjectList();
				if($db->getErrorNum()) {
					JError::raiseError( 500, $db->stderr());
			    }
		    }

			// For each message, find the parent+from, group them together
			$inboxResult =  array();
			if(!empty($result)){
				foreach($result as $row) {
					$inboxResult[$row->parent] = $row;
				}
			}

		    $limit 		= $this->getState('limit');
		    $limitstart	= $this->getState('limitstart');
			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($inboxResult), $limitstart, $limit );
				$inboxResult = array_slice($inboxResult, $limitstart, $limit);
			}

			return $inboxResult;
		}

		return null;
	}
    public function getTotalNotifications( $userId )
    {
        
    }
}
?>
