<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_pfprojects
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


jimport('joomla.application.component.controller');


/**
 * Projects main controller
 *
 * @see    JController
 */
class PFprojectsController extends JControllerLegacy
{
    /**
     * The default view
     *
     * @var    string
     */
    protected $default_view = 'projects';


    /**
     * Displays the current view
     *
     * @param     boolean    $cachable    If true, the view output will be cached  (Not Used!)
     * @param     array      $urlparams   An array of safe url parameters and their variable types (Not Used!)
     *
     * @return    JController             A JController object to support chaining.
     */
    public function getSKills()
    {
          $db =& JFactory::getDBO();
          $data = json_decode(file_get_contents("php://input"));
          $query = "SELECT * FROM #__pf_skills WHERE skill LIKE '".$db->escape($data->skill)."%'";
          //echo $query;
          $db->setQuery($query);
          $rows = $db->loadObjectList();
          $fr = new stdClass();
          $fr->skills = json_encode($rows);
             $fr->msg = '';
             echo json_encode($fr);
             exit;
          die();
    }
    public function addUserKill()
    {
        
        $response = array();
        $response['status'] = 0;
         
       
        
        $db = JFactory::getDbo();
        $user_id = $_POST['userid'];
        if (!is_numeric($user_id) || $user_id == 0) return;
        $query = "INSERT INTO #__pf_project_skills_added (userid, skillDesc, skill, skillTags, skillCatg) VALUES ($user_id, '".$db->escape($_POST['skillDesc'])."', '".$db->escape($_POST['skilltoAdd'])."', '".$db->escape($_POST['skillTags'])."', '".$db->escape($_POST['skillCatg'])."');";
        $taskIds = $_POST['taskIds'];
        
        $db->setQuery($query);
        $r = $db->Query();
        if (!$r) {
                $response['status'] = 0;
                 $response['result'] = "There was an error adding data to the database";
        } 
        else
        {
            $response['status'] = 1;
              $response['result'] = "Data successfully added";
        }
        if ($taskIds)
        {
            $taskIds = json_decode($taskIds);
            if (is_array($taskIds))
            {
                foreach ($taskIds as $tsk)
                {
                    if (!is_numeric($tsk) || $tsk == 0) continue;
                    $query = "SELECT skill_id FROM #__pf_user_skills WHERE user_id = $user_id AND skill_id = $tsk LIMIT 1";
                    $db->setQuery($query);
                    $taskid = $db->loadResult();
                   if (is_numeric($taskid)) continue;
                    else {
                         $query = "INSERT INTO #__pf_user_skills (user_id, skill_id, date_added) VALUES ('$user_id', '$tsk', CURRENT_TIMESTAMP)";
                         $db->setQuery($query);
                         $db->Query();
                    }
                }
            }
        }
         echo  json_encode($response);
        exit;
    }
    
    public function display($cachable = false, $urlparams = false)
    {
        // Load CSS and JS assets
        JHtml::_('pfhtml.style.bootstrap');
        JHtml::_('pfhtml.style.projectfork');

        JHtml::_('pfhtml.script.jQuery');
        JHtml::_('pfhtml.script.bootstrap');
        JHtml::_('pfhtml.script.projectfork');
 
        JHtml::_('behavior.tooltip');
        $document = JFactory::getDocument();
        $uribase = JURI::base(true). "/components/com_pfprojects/css/style.css";
        
        $document->addStyleSheet($uribase);
        $document->addScript(JURI::root() . 'libraries/projectfork/js/angular.min.js');
        //$document->addScript(JURI::root() . 'components/com_pfprojects/js/pfp.js');
        $document->addScript(JURI::root() . 'components/com_pfprojects/js/angpfp.js');
        $js = "var tasksURL = '".JURI::root()."';";
        $document->addScriptDeclaration($js);
        
        
        
        $view      = JRequest::getCmd('view');
        
        $id        = JRequest::getUInt('id');
        $urlparams = array(
            'id'               => 'INT',
            'cid'              => 'ARRAY',
            'limit'            => 'INT',
            'limitstart'       => 'INT',
            'showall'          => 'INT',
            'return'           => 'BASE64',
            'filter'           => 'STRING',
            'filter_order'     => 'CMD',
            'filter_order_Dir' => 'CMD',
            'filter_search'    => 'STRING',
            'filter_published' => 'CMD'
        );
 
        // Inject default view if not set
        if (empty($view)) {
            JRequest::setVar('view', $this->default_view);
        }
         
        // Check for edit form.
		if ($view == 'form' && !$this->checkEditId('com_pfprojects.edit.form', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
 
        // Display the view
        parent::display($cachable, $urlparams);

        // Return own instance for chaining
        return $this;
    }
}