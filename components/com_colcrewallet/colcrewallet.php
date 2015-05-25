<?php
date_default_timezone_set('UTC');
defined('_JEXEC') or die('Restricted access');
//print_r($_GET); exit;
require_once (JPATH_COMPONENT.'/controllers/controller.php');
$view  = JRequest::getVar('view', 'mainwallet', 'cmd');
if ($view == 'walletview') $view = 'mainwallet';
$task = JRequest::getVar('task', $view, 'default', 'cmd');
$mainframe = JFactory::getApplication();
$user = JFactory::getUser();
$_GET['user_id'] = $user->id;
if ($user->id == 0)
{
      $redirectUrl = base64_encode(JRoute::_("index.php?option=com_colcrewallet"));  
      $redirectUrl = '&return='.$redirectUrl;
      $joomlaLoginUrl = 'index.php?option=com_users&view=login';
      $finalUrl = $joomlaLoginUrl . $redirectUrl;
      $mainframe->redirect($finalUrl);
}
if( $view != '') {  

	$path = JPATH_COMPONENT.'/controllers/'.$view.'.php';
        
	if (file_exists($path)) {
		require_once $path;
                $controller = $view;
	}  
}
//echo "controller is $controller";
//Create the controller
$classname  = 'WalletController'.$controller;
//echo "view is $classname ** $task";exit;
$controller = new $classname( );
$controller->execute($task);
// Redirect if set by the controller
$controller->redirect();
?>
