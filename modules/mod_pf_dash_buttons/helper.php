<?php
defined('_JEXEC') or die();
class tabPermits //redacron class
{
   var $user = '';
   var $db = ''; 
   var $userSession = array();
   var $projUser = array();
   private function _getUserMap($user, & $db) //redacron method
   {
       if (!is_numeric($user) || $user == 0) return false;
       if (isset($this->userSession[$user])) { return $this->userSession[$user]; } 
       $query = "SELECT group_id FROM #__user_usergroup_map WHERE user_id = $user LIMIT 1";
       $db->setQuery($query);
       $level = $db->loadResult();
       if ($level > 5 && $level != 9) { $this->userSession[$user] = true; return true;}
       else { $this->userSession[$user] = false; return false;}
   }
   private function _getCreator($id, & $db)//let's find out who created this project, redacron method
   {
       $query = "SELECT created_by FROM #__pf_projects WHERE id = $id LIMIT 1";
       $db->setQuery($query);
       $created = $db->loadResult();
       return (is_numeric($created)) ? $created : 0;
   }
   function checkPermissions($class) //redacron method
   {
       $case = str_replace(array('PF', 'HelperDashboard'), '', $class);
       if (!isset($this->user->id) || $this->user->id == 0) return false;
       $project_id = isset($_GET['id']) ? $_GET['id'] : '';
       if (!is_numeric($project_id)) return false;
       $db = JFactory::getDbo();
       $level = $this->_getUserMap($this->user->id, $db);
       $creator = $this->_getCreator($project_id, $db);    
       if ($creator == $this->user->id) $myProject = true;
       else $myProject = false;
           
       switch ($case):
           case 'projects':
                if ($this->user->id > 0) { return true; }
                else return false;
            break;
           case 'milestones':
               
               if ($this->user->id > 0 && ($myProject || $level)) { return true;}
                else { return false; }
           break;
           case 'tasks':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           case 'time':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           case 'repo':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           case 'forum':
                if ($this->user->id > 0 && ($myProject || $level)) return true;
                else return false;
           break;
           default:
               
       endswitch;
   }
}
/**
 * Module helper class
 *
 */
abstract class modPFdashButtonsHelper
{
    /**
     * Method to get a list of available buttons
     *
     * @return    array    $buttons    The available buttons
     */
    public static function getButtons($tabPermits)
    {
        $components = PFApplicationHelper::getComponents();
        $buttons    = array();
        
        foreach ($components AS $component)
        {
            if (!PFApplicationHelper::enabled($component->element)) {
                continue;
            }

            // Register component route helper if exists
            $router = JPATH_SITE . '/components/' . $component->element . '/helpers/route.php';
            $class  = str_replace('com_pf', 'PF', $component->element) . 'HelperRoute';

            if (JFile::exists($router)) {
                JLoader::register($class, $router);
            }

            // Register component dashboard helper if exists
            $helper = JPATH_ADMINISTRATOR . '/components/' . $component->element . '/helpers/dashboard.php';
            $class  = str_replace('com_pf', 'PF', $component->element) . 'HelperDashboard';

            if (!JFile::exists($helper)) {
                continue;
            }

            JLoader::register($class, $helper);

            // Get the dashboard button
            if (class_exists($class)) {
                if (in_array('getSiteButtons', get_class_methods($class))) {
                    
                    
                    $checkClass = $tabPermits->checkPermissions($class);
                    if (!$checkClass) continue;
                    $com_buttons = (array) call_user_func(array($class, 'getSiteButtons'));
           
                    $buttons[$component->element] = array();

                    foreach ($com_buttons AS $button)
                    {
                        $buttons[$component->element][] = $button;
                    }
                }
            }
        }

        return $buttons;
    }
}
