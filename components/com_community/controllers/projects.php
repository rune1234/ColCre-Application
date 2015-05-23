<?php
class CommunityProjectsController extends CommunityBaseController
{
    function display()
    { 
        
        $model	=  $this->getModel ( 'projects' );
        
		$projects	=  $model->getProjects ();
		
		$view	=  $this->getView ( 'projects' );
		$user_id = JRequest::getVar('user_id', 0, 'get', 'int');
                 
                if ($user_id == 0)
                {
                    $user = $my		= CFactory::getUser ();
                }
                else $user =  JFactory::getUser($user_id);
                 
                if($user->id == 0)
		{
			return $this->blockUnregister();
		}
                
                $data = new stdClass ( );
		$data->matches = $projects;
                $data->user = $user;
                //$data->document = $document;
                //print_r($data->user);
		 $data->pagination =  $model->getPagination ();
		echo $view->get ( 'projects', $data );
                
    }
}

?>