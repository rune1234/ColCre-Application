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
        //print_r($_POST);
        $response = array();
        $response['status'] = 0;
        $response['error'] = "There was an error adding data to the database";
        echo  json_encode($response);
        exit;/*
         * Array
(
    [option] => com_pfprojects
    [task] => addUserKill
    [skilltoAdd] => ariel
    [skillDesc] => European, Asian, this woman had the whole world in her. 
    [skillTags] => divination, murder
         * skillCatg => skillCatg
)
         */
        $db = JFactory::getDbo();
        $query = "INSERT INTO #__";
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