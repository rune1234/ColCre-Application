<?php
class CommunityProjectsController extends CommunityBaseController
{
    function display()
    { 
        $model	=  $this->getModel ( 'projects' );
		$projects	=  $model->getProjects ();
		//$modMsg	= array ();
 
		$view	=  $this->getView ( 'projects' );
		$my		= CFactory::getUser ();

                if($my->id == 0)
		{
			return $this->blockUnregister();
		}
                $data = new stdClass ( );
		$data->matches = $projects;
                $data->user = $my;
		 $data->pagination =  $model->getPagination ();
		echo $view->get ( 'projects', $data );
    }
}

?>