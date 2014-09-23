<?php
class projectData
{
    var $db = '';
    function __construct()
    {
        $this->db = JFactory::getDbo();
    }
     function getDBO()
      {
          if ($this->db == '') $this->db = JFactory::getDbo();
          return $this->db;
      }
      function getCategory($catid)
      {
          if (!is_numeric($catid)) return;
          $query = "SELECT * FROM #__categories WHERE id=$catid LIMIT 1";
           $db = $this->getDBO();
          $db->setQuery($query);
          $row = $db->loadObject();
          return $row;
      }
      function lookupIcon($task) 
        {
            $key = (int) $task->id;
            //print_r($task);

            // Default - Projectfork avatar
            $base_path = JPATH_ROOT . '/media/com_projectfork/repo/0/logo';
            $base_url  = JURI::root(true) . '/media/com_projectfork/repo/0/logo';
            $img_path  = NULL;
//echo $base_path; echo $id; exit;
            if (JFile::exists($base_path . '/' . $key . '.jpg')) {
                $img_path = $base_url . '/' . $key . '.jpg';
            }
            elseif (JFile::exists($base_path . '/' . $key . '.jpeg')) {
                $img_path = $base_url . '/' . $key . '.jpeg';
            }
            elseif (JFile::exists($base_path . '/' . $key . '.png')) {
                $img_path = $base_url . '/' . $key . '.png';  
            }
            elseif (JFile::exists($base_path . '/' . $key . '.gif')) {
                $img_path = $base_url . '/' . $key . '.gif';
            }
            else {  //echo JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".png"; 
                if ($task->category_alias && is_file(JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".png"))  
                $img_path = JUri::base()."/templates/colcre/images/".$task->category_alias.".png";       
               else return false;
            }

            return $img_path;
 
        }
}
?>
