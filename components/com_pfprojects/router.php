<?php
/**
 * @package      Projectfork
 * @subpackage   Projects
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


/**
 * Build the route for the com_pfprojects component
 *
 * @param     array    $query    An array of URL arguments
 *
 * @return    array              The URL arguments to use to assemble the subsequent URL.
 */
function PFprojectsBuildRoute(&$query)
{
    // We need to have a view in the query or it is an invalid URL
    if (!isset($query['view'])) {
        return array();
    }

    // Setup vars
    $segments = array();
    $view     = $query['view'];

    // We need a menu item.  Either the one specified in the query, or the current active one if none specified
    if (empty($query['Itemid'])) {
        $menu_item_given = false;
    }
    else {
        $menu_item_given = true;
    }

    // Handle projects query
    if($view == 'projects') {
        if (!$menu_item_given) $segments[] = $view;
        unset($query['view']);
    }

    // Handle the layout
    if (isset($query['layout'])) {
        if ($menu_item_given && isset($menuItem->query['layout'])) {
            if ($query['layout'] == $menuItem->query['layout']) {
                unset($query['layout']);
            }
        }
        else {
            if ($query['layout'] == 'default') {
                unset($query['layout']);
            }
        }
    }

    return $segments;
}



/**
 * Parse the segments of a URL.
 *
 * @param     array    The segments of the URL to parse.
 *
 * @return    array    The URL attributes to be used by the application.
 */
function PFprojectsParseRoute($segments)
{
    // Setup vars
    $vars  = array();
    $count = count($segments);
    $menu  = JFactory::getApplication()->getMenu();
    $item  = $menu->getActive();


    // Standard routing.  If we don't pick up an Itemid then we get the view from the segments
    // the first segment is the view and the last segment is the id of the item.
    if (!isset($item)) {
        $vars['view'] = $segments[0];
        $vars['id']   = $segments[$count - 1];

        return $vars;
    }

    // Set the view var
    $vars['view'] = $item->query['view'];

    return $vars;
}
