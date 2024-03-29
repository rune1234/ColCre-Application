<?php
/**
 * @package      Projectfork
 * @subpackage   Comments
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


class PFcommentsHelper
{
    /**
     * The component name
     *
     * @var    string
     */
    public static $extension = 'com_pfcomments';

    /**
     * Indicates whether this component uses a project asset or not
     *
     * @var    boolean
     */
    public static $project_asset = true;


    /**
     * Configure the Linkbar.
     *
     * @param     string    $view    The name of the active view.
     *
     * @return    void
     */
    public static function addSubmenu($view)
    {
        $is_j3 = version_compare(JVERSION, '3.0.0', 'ge');

        if ($view == 'comment' && $is_j3) return;

        $components = PFApplicationHelper::getComponents();
        $option     = JFactory::getApplication()->input->get('option');
        $class      = ($is_j3 ? 'JHtmlSidebar' : 'JSubMenuHelper');

        foreach ($components AS $component)
        {
            if ($component->enabled == '0') continue;

            $title = JText::_($component->element);
            $parts = explode('-', $title, 2);

            if (count($parts) == 2) $title = trim($parts[1]);

            call_user_func(
                array($class, 'addEntry'),
                $title,
                'index.php?option=' . $component->element,
                ($option == $component->element)
            );
        }
    }


    /**
     * Gets a list of actions that can be performed.
     *
     * @param     integer    $id         The item id
     *
     * @return    jobject
     */
    public static function getActions($id = 0)
    {
        $user   = JFactory::getUser();
        $result = new JObject;

        if ((empty($id) || $id == 0)) {
            $pid   = PFApplicationHelper::getActiveProjectId();
            $asset = (empty($pid) ? self::$extension : 'com_pfcomments.project.' . $pid);
        }
        else {
            $asset = 'com_pfcomments.comment.' . (int) $id;
        }

        $actions = array(
            'core.admin', 'core.manage',
            'core.create', 'core.edit',
            'core.edit.own', 'core.edit.state',
            'core.delete'
        );

       foreach ($actions as $action)
        {   $fc = $user->authorise($action, $asset);
            if ($action == 'core.create') $fc = true;//redacron alteration
            $result->set($action, $fc);
        }
       // if ($user->id > 0) $result = true;//redacron alteration. Everyone can reply right now
        return $result;
    }
}
