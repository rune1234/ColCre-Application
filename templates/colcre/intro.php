<?php
// Check modules
//$showRightColumn	= ($this->countModules('position-3') or $this->countModules('position-6') or $this->countModules('position-8'));
//$showbottom			= ($this->countModules('position-9') or $this->countModules('position-10') or $this->countModules('position-11'));
//$showleft			= ($this->countModules('position-4') or $this->countModules('position-7') or $this->countModules('position-5'));
include_once('tempcolcre.php');
$lang = JFactory::getLanguage();
$lang->load( 'com_community.country'); 
if (!isset($showRightColumn)) $showRightColumn = 0;
if (!isset($showleft)) $showleft = 0;
if ($showRightColumn == 0 and $showleft == 0)
{
	$showno = 0;
}

JHtml::_('behavior.framework', true);

// Get params
$color				= $this->params->get('templatecolor');
$logo				= "whaticon.png";//$this->params->get('logo');
$navposition		= $this->params->get('navposition');
$headerImage		= $this->params->get('headerImage');
$app				= JFactory::getApplication();
$doc				= JFactory::getDocument();
$templateparams		= $app->getTemplate(true)->params;
$config = JFactory::getConfig();

$bootstrap = explode(',', $templateparams->get('bootstrap'));

if (in_array($option, $bootstrap))
{
	// Load optional rtl Bootstrap css and Bootstrap bugfixes
	JHtml::_('bootstrap.loadCss', true, $this->direction);
}

$doc->addStyleSheet(JUri::base() . 'templates/system/css/system.css');

$doc->addStyleSheet(JUri::base() . 'templates/' . $this->template . '/css/bootstrap.css');

$doc->addStyleSheet(JUri::base() . 'templates/' . $this->template . '/css/styles.css', $type = 'text/css', $media = 'screen,projection');

if ($this->direction == 'rtl')
{
	$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template_rtl.css');
	if (file_exists(JPATH_SITE . '/templates/' . $this->template . '/css/' . $color . '_rtl.css'))
	{
		$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/' . htmlspecialchars($color) . '_rtl.css');
	}
}

JHtml::_('bootstrap.framework');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/md_stylechanger.js', 'text/javascript');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/hide.js', 'text/javascript');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/respond.src.js', 'text/javascript');
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/javascript/template.js', 'text/javascript');

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		 
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56034120-1', 'auto');
  ga('send', 'pageview');

</script> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"/>
		<meta name="HandheldFriendly" content="true" />
		<meta name="apple-mobile-web-app-capable" content="YES" />

		<jdoc:include type="head" />

		<!--[if IE 7]>
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ie7only.css" rel="stylesheet" type="text/css" />
		<![endif]-->
	</head>
	<body>
		

		<div id="all">
			<div id="back">
				<header id="header">
					<div class="logoheader">
						<h1 id="logo">
						<?php if ($logo) : ?>
							<img style="height: 45px;" src="<?php echo JUri::base(); ?>images/<?php echo htmlspecialchars($logo); ?>"  alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
						<?php endif;?>
						<?php /*if (!$logo AND $templateparams->get('sitetitle')) : ?>
							<?php echo htmlspecialchars($templateparams->get('sitetitle'));*/?>
						<?php /*elseif (!$logo AND $config->get('sitename')) : */?>
							<?php echo htmlspecialchars($config->get('sitename'));?>
						<?php /*endif;*/ ?>
						<span class="header1">
						<?php echo htmlspecialchars($templateparams->get('sitedescription'));?>
						</span></h1>
					</div><!-- end logoheader -->
					 <jdoc:include type="modules" name="position-1" />
					
					 
				</header> 
                            <section id="subheader"><div style="height: 100px;"></div><jdoc:include type="modules" name="position-2" /></section>
                                 
				<div id="contentarea">
					 <jdoc:include type="modules" name="position-0" />
				</div> <!-- end contentarea -->
                                <div id="popro">
                                    <h1>POPULAR PROJECTS</h1>
                                    <br /><br /><div style="width: 80%; margin: auto;">
                                    <?php
                                    $db = JFactory::getDbo();
                                    $tempColcre = new tempColcre($db);
                                    $rows = $tempColcre->popularProjects();
                                    //***************************** we have the popular projects now: 
                                    $pks = array();
                                    foreach ($rows as $rw)
                                    {
                                        $pks[] = $rw->id;
                                    }
                                       
                                    $rows_prog = $tempColcre->getAggregatedProgress($pks);//let's get the progress
                                     
                                    foreach ($rows as $rw)
                                    {
                                        $my = JFactory::getUser($rw->created_by);
                                        $rw->progress = $rows_prog[$rw->id];
                                        if ($rw->progress >= 67)  $progress_class = 'info';
                                        if ($rw->progress == 100) $progress_class = 'success';
                                        if ($rw->progress < 67)   $progress_class = 'warning';
                                        if ($rw->progress < 34)   $progress_class = 'danger label-important';
                                        echo "<div class='colcrproj'>";
                                        $catg = $tempColcre->getCatgInfo($rw->catid);
                                        //index.php?option=com_projectfork&view=dashboard&id=18&Itemid=124
                                         echo "<a href='".JRoute::_('index.php?option=com_projectfork&view=dashboard&id='.$rw->id.'&Itemid=124')."'>";
                                        $proj_logo = $tempColcre->lookup($rw) ? $tempColcre->lookup($rw) : "images/foldered.jpg";
                                        echo "<img src='$proj_logo' alt='project $rw->title'></a>";
                                        echo "<p><b style='margin-top: 15px;'><a class='colcrep' href='".JRoute::_('index.php?option=com_projectfork&view=dashboard&id='.$rw->id.'&Itemid=124')."'>$rw->title</a></b><br />";
                                        echo "<span style='font-size: 11px;'>by ".ucwords($my->name)."</span></p>";
                                        echo "<p>".strip_tags ( substr($rw->description, 0, 150) )."...</p>";
                                        echo "<br /><p>TBA NY, USA</p>";?>
                                        <hr class="visible-phone" />
    	    	    		<div class="progress progress-<?php echo $progress_class;?> progress-striped progress-project">
    	    	    		    <div class="bar" style="width: <?php echo ($rw->progress > 0) ? $rw->progress . "%" : "24px"; ?>">
    	    	    		        <span class="label label-<?php echo $progress_class;?> pull-right"><?php echo $rw->progress; ?>%</span>
    	    	    		    </div>
    	    	    		</div><div style="clear: both;"></div>
                                        <?php
                                        echo "</div>";
                                   }
                                   ?></div>
                                    <div style="clear: both;"></div><br /><br /><div style="text-align:center;"><a class='popro' href='<?php echo JRoute::_('index.php?option=com_pfprojects&view=projects&Itemid=141');?>'>SEE MORE</a></div><br /><br />
                                </div>
                                <div id="pousers">
                                    <h1>POPULAR USERS</h1><br /><br /><div style="width: 80%; margin: auto;">
                                    <?php
                                    $rows = $tempColcre->popularUsers();//***************************let's get the popular users
                                    foreach ($rows as $row)
                                    { 
                                        ?>
                                    
                                    <div class='colcrproj'>
			<a href="<?php echo JRoute::_("index.php?option=com_community&view=profile&userid=".$row->id."&Itemid=104");?>" class="cIndex-Avatar">
				<img class="cAvatar" src="<?php echo $row->user->getAvatar(); ?>" alt="<?php echo $row->user->getDisplayName(); ?>" />
				<?php /*if($row->user->isOnline()): ?>
				<b class="cStatus-Online">
					<?php echo JText::_('COM_COMMUNITY_ONLINE'); ?>
				</b>
				<?php endif; */ ?>
			</a> 
			<div>
				<p class="cIndex-Name cResetH">
		<a class="colcrea" href="<?php echo JRoute::_("index.php?option=com_community&view=profile&userid=".$row->id."&Itemid=104");?>"><?php echo ucwords($row->user->getDisplayName()); ?></a>
				<br />
                               <?php 
                               
                               echo $row->skill_category; 
                                ?> </p>
                                <p class="cIndex-Name cResetH"><?php echo substr($row->profile, 0, 100)."..."; /* Redacron alteration*/ ?>
				</p><br />
                                <?php if (isset($row->userskills)) {?>
                                <p class="cIndex-Name cResetH"><?php 
                                if ($row->userskills) {
                                    $theskills = array();
                                     foreach ($row->userskills as $skills){
                                         $theskills[] = $skills->skill;
                                         /* Redacron alteration*/ 
                                     }
                                     echo "<b>Skills:</b> ".implode(', ', $theskills);
                                }
                                 ?>
                                </p><br /><?php } ?>
                                <p class="colcrep">
                                    <?php
                                    echo ($row->city) ? $row->city.", " : '';
                                    echo ($row->state) ? $row->state.", " : '';
                                    echo ($row->country) ? JText::_($row->country) : '';
                                    ?>
                                    
                                    
				</p>
				<!--<div class="cIndex-Status"><?php //echo $row->user->getStatus() ;?></div>-->
                        </div></div>
                                    <?php
                                        
                                    }
                                    ?></div>
                                    <div style="clear: both;"></div><br /><br /><div style="text-align:center; color: #fff;"><a href='<?php echo JRoute::_("index.php?option=com_community&view=search&task=browse&Itemid=140");?>' class='pousers'>SEE MORE</a></div><br /><br />
                                </div>
			</div><!-- back -->
		

		<div id="footer-outer">
                    <!--<div id="footer-inner" ><h1 class="introponsors">OUR PARTNERS</h1>
                        <table class="introponsors">
                            <tr><td id="dropbox">&nbsp;</td><td id="uber">&nbsp;</td><td id="virginia">&nbsp;</td><td id="pinterest">&nbsp;</td><td id="syntheticgen">&nbsp;</td></tr>
                            
                        </table>
					<div id="bottom">
						<div class="box box1"> <jdoc:include type="modules" name="position-9" style="beezDivision" headerlevel="3" /></div>
						<div class="box box2"> <jdoc:include type="modules" name="position-10" style="beezDivision" headerlevel="3" /></div>
						<div class="box box3"> <jdoc:include type="modules" name="position-11" style="beezDivision" headerlevel="3" /></div>
					</div>

				</div>-->
			 

			 
				<?php
                                include_once('footer.php');
                                ?>
			 
		</div>
                        </div><!-- all -->
		<jdoc:include type="modules" name="debug" />
	</body>
</html>
