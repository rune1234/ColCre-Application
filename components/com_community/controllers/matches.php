<?php
class CommunityMatchesController extends CommunityBaseController
{
    function display()
    { 
        $model	=  $this->getModel ( 'matches' );
		$matches	=  $model->getMatches ();
		$modMsg	= array ();
 
		$view	=  $this->getView ( 'matches' );
		$my		= CFactory::getUser ();

                if($my->id == 0)
		{
			return $this->blockUnregister();
		}
                $data = new stdClass ( );
		$data->matches = $matches;
                $data->user = $my;
		 $data->pagination =  $model->getPagination ();
		echo $view->get ( 'matches', $data );
    }
}

?>

