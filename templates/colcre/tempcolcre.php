<?php
define('DS','/');
include_once(JPATH_BASE.DS.'components'.DS.'com_community'.DS.'defines.community.php');	
require_once( JPATH_BASE . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php');
	
class tempColcre
{
    var $_db;
    public function __construct(& $db)
    {
        $this->_db = $db;
    }
    public function getAggregatedProgress($pks, $field = 'project_id')
    {
        $fields = array('project_id', 'milestone_id', 'list_id');

        if (!is_array($pks) || count($pks) == 0 || !in_array($field, $fields)) {
            return array();
        }

        $completed = $this->getAggregatedTotal($pks, $field, 1, true);
        $total     = $this->getAggregatedTotal($pks, $field, null, true);
        $items     = array();

        foreach ($pks AS $pk)
        {
            $count_complete = (int) (isset($completed[$pk]) ? $completed[$pk] : 0);
            $count_total    = (int) (isset($total[$pk])     ? $total[$pk]   : 0);

            if (!$count_total || !$count_complete) {
                $progress = 0;
            }
            elseif ($count_complete == $count_total) {
                $progress = 100;
            }
            else {
                $progress = round($count_complete * (100 / $count_total));
            }

            $items[$pk] = $progress;
        }

        return $items;
    }
     public function getAggregatedTotal($pks, $field = 'project_id', $complete = null)
    {
        static $cache = array();

        $fields = array('project_id', 'milestone_id', 'list_id');

        if (!is_array($pks) || count($pks) == 0 || !in_array($field, $fields)) {
            return array();
        }

        // Check the cache
        $key = md5($field . $complete . implode('.', $pks));
        if (isset($cache[$key])) return $cache[$key];

        $query = $this->_db->getQuery(true);
        $query->select($field . ', COUNT(id) AS total')
              ->from('#__pf_tasks')
              ->where($field . ' IN(' . implode(',', $pks) . ') ')
              ->where('state != -2');

        if ($complete === 0) {
            $query->where('complete = 0');
        }
        elseif ($complete === 1) {
            $query->where('complete = 1');
        }

        $query->group($field)
              ->order('id ASC');
 
        $this->_db->setQuery($query);
        $cache[$key] = $this->_db->loadAssocList($field, 'total');

        if (!is_array($cache[$key])) $cache[$key] = array();

        return $cache[$key];
    }
    public function popularProjects()
    {
         $query = "SELECT * FROM #__pf_projects WHERE state=1 ORDER BY id DESC LIMIT 4";
                                    $this->_db->setQuery($query);
                                    $rows = $this->_db->loadObjectList();
                                    return $rows;
    }
    public function getCatgInfo($id)
    {
        if (!is_numeric($id)) return;
        $query = "SELECT title, alias FROM #__categories WHERE id = $id LIMIT 1";
                                    $this->_db->setQuery($query);
                                    $row = $this->_db->loadObject();
                                    return $row;
    }        
            
    public function popularUsers()
    {
        $db = $this->_db;
        $query = 'SELECT distinct(a.' . $db->quoteName('id') . ') '
                . ' FROM ' . $db->quoteName('#__users') . ' AS a '
                . ' LEFT JOIN ' . $db->quoteName('#__session') . ' AS b '
                . ' ON a.' . $db->quoteName('id') . '=b.' . $db->quoteName('userid')
                . ' WHERE a.' . $db->quoteName('block') . '=' . $db->Quote(0)." AND a.id != 859 LIMIT 4";
           $tmpAdmins = $this->_getSuperAdmins();
           /* $query .= ' AND a.' . $db->quoteName('id') . ' NOT IN(';
            for ($i = 0; $i < count($tmpAdmins); $i++) {
                $admin = $tmpAdmins[$i];
                $query .= $db->Quote($admin->id);
                $query .= $i < count($tmpAdmins) - 1 ? ',' : '';
            }
            $query .= ')';
            echo $query; exit;*/
        $db->setQuery($query);
        $users = $db->loadObjectList();
        //print_r($users);
        $results = array();
        foreach ($users as $us)
        {
                $obj = clone($us);
                $user = CFactory::getUser($us->id);
                $obj->user = $user;
                $obj->profile = $this->_getInfo('FIELD_ABOUTME',$us->id);
                $obj->avatar = $user->getAvatar();
                $obj->country = $this->_getInfo('FIELD_COUNTRY',$us->id);
                $obj->state = $this->_getInfo('FIELD_STATE',$us->id);
                $obj->city = $this->_getInfo('FIELD_CITY',$us->id);
                $obj->skill_category = $this->getCategoryTitle($this->_getInfo('SKILL_CATEGORY',$us->id));
                $results[] = $obj;
        }
        //print_r($results);
        return $results;
        
    }
    function getCategoryTitle($id)
    {
          $db =& JFactory::getDBO();
          if (!is_numeric($id)) return;
          $query = "SELECT title FROM #__categories WHERE extension='com_pfprojects' AND id = $id AND published = 1 ORDER BY id ASC";
          $db->setQuery($query);
          $rows = $db->loadResult();
          return $rows;
    }
    private function _getSuperAdmins() {
        $db = $this->_db;

        $query = 'SELECT a.*'
                . ' FROM ' . $db->quoteName('#__users') . ' as a, '
                . $db->quoteName('#__user_usergroup_map') . ' as b'
                . ' WHERE a.' . $db->quoteName('id') . '= b.' . $db->quoteName('user_id')
                . ' AND b.' . $db->quoteName('group_id') . '=' . $db->Quote(8);
        $db->setQuery($query);
        $users = $db->loadObjectList();
        
        return $users;
    }
    private function _getInfo($fieldCode, $userid) {
        // Run Query to return 1 value
        $db = $this->_db;
        $query = 'SELECT b.* FROM ' . $db->quoteName('#__community_fields') . ' AS a '
                . 'INNER JOIN ' . $db->quoteName('#__community_fields_values') . ' AS b '
                . 'ON b.' . $db->quoteName('field_id') . '=a.' . $db->quoteName('id') . ' '
                . 'AND b.' . $db->quoteName('user_id') . '=' . $db->Quote($userid) . ' '
                . 'INNER JOIN ' . $db->quoteName('#__community_users') . ' AS c '
                . 'ON c.' . $db->quoteName('userid') . '= b.' . $db->quoteName('user_id') . ' '
                . 'LEFT JOIN ' . $db->quoteName('#__community_profiles_fields') . ' AS d '
                . 'ON c.' . $db->quoteName('profile_id') . ' = d.' . $db->quoteName('parent') . ' '
                . 'AND d.' . $db->quoteName('field_id') . ' = b.' . $db->quoteName('field_id') . ' '
                . 'WHERE a.' . $db->quoteName('fieldcode') . ' =' . $db->Quote($fieldCode);

        $db->setQuery($query);
        $result = $db->loadObject();
        if ($db->getErrorNum()) { JError::raiseError(500, $db->stderr()); }
       return $result->value;
    }
}
?>