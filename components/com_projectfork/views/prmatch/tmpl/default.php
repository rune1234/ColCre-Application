<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_projectfork
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */
function getAvatarThumb_2($userid)
{
    if (!is_numeric($userid) || $userid == 0) return;
      $query = "SELECT thumb FROM #__community_users WHERE userid = $userid LIMIT 1";
      $db = JFactory::getDbo();
      $db->setQuery($query);
      $avatar = $db->loadResult();
      if (!$avatar) return false;
      return JURI::root()."/".$avatar;
}
function showMatches_2($matches, $bla)
{
    $userMatches = array();
    $shownMatches = false;
     if ($matches){
         foreach ($matches as $match)
         {
             if ($task != '') { if ($match->MatchingTaskId != $task->id) {   continue;} }
             else $shownMatches = true;
             $userMatches[$match->user_id][] = $match->SkillName;
         }
         $avoidRepeat = array();
         foreach ($matches as $match)
         {
             if ($task != '') { if ($match->MatchingTaskId != $task->id) continue; }
             //if (isset($avoidRepeat[$match->MatchingTaskId])) continue;
             $avoidRepeat[$match->MatchingTaskId] = true;
             if (isset($userMatches[$match->user_id]) && is_array($userMatches[$match->user_id]))
             {
                 $matchesImplode = implode(', ', $userMatches[$match->user_id]);
                 unset($userMatches[$match->user_id]);//let's get rid of the array, so we don't show the same users over and over again
             }
             else continue;
             echo "<div class='row-fluid matchBox' style='padding: 10px; margin: 10px; width: 96%;'>";
//print_r($match);
             $spec = $bla->specifyMatch('', $match->MatchingTaskId, $match->Project_Id, $match->user_id, $match->MatchingTaskId);
             //print_r($spec);
             if (is_array($spec))
             {
                 //print_r($spec);
             }
             $avatar = getAvatarThumb_2($match->user_id);
             
             if ($avatar)
             {
                 echo "<img src='$avatar' alt='user avatar' style='float: left; margin-right: 5px;' />";
             }
             echo "<p><b>Name:</b> <a href='".JRoute::_('index.php?option=com_community&view=profile&userid='.$match->user_id.'&Itemid=103')."'>".ucwords($match->DeveloperName)."</a>";
             echo "<br /><b>SkillName:</b> ".ucwords($matchesImplode);//ucwords($match->SkillName);
             echo "<br /><b>Task Percentage:</b> ".$match->TaskMatchPercentage."%";
             echo "<br /><b>Project Match Percentage:</b> ".$match->ProjectMatchPercentage."%";

             echo "</p>";

             echo "<div style='clear: both;'></div></div>";
         }
         echo "<div style='clear: both;'></div>";
         return $shownMatches;
     }
}
function lookupIcon_2($task)
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
                if ($task->category_alias && is_file(JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".jpg"))  
                $img_path = JUri::base()."/templates/colcre/images/".$task->category_alias.".jpg";   
                elseif ($task->category_alias && is_file(JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".png"))  
                $img_path = JUri::base()."/templates/colcre/images/".$task->category_alias.".png";       
               else return false;
            }

            return $img_path;
 
        }
defined('_JEXEC') or die();
?>
<div id="projectfork" class="category-list view-dashboard projectFrame" style="padding: 10px; margin: 10px;">
<?php
$row = $this->pd->projectInfo();


echo "<h3>Matches for Project ".ucwords($row->title).":</h3>";
 $proj_logo = lookupIcon_2($row) ? lookupIcon_2($row) : JUri::base()."images/foldered.jpg";
                            echo "<img src='$proj_logo' style='min-height: 70px; max-height: 80px; float: left; margin: 5px 10px 5px 5px;' alt='project $row->title'></a>";
                                                        echo "<div style='clear: both'></div>";
if ($this->matches) showMatches_2($this->matches, $this->pm);
else echo "<p>There are no matches for this project. Please add more skill requirements.</p>";
?>
    <div style='clear: both'></div>
</div>
