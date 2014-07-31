<?php
// Check modules
//$showRightColumn	= ($this->countModules('position-3') or $this->countModules('position-6') or $this->countModules('position-8'));
//$showbottom			= ($this->countModules('position-9') or $this->countModules('position-10') or $this->countModules('position-11'));
$showleft			= ($this->countModules('position-3') or $this->countModules('position-4') or $this->countModules('position-5'));

if ($showleft == 0)
{ 
	$showno = 0;
} else $showno = 1;

JHtml::_('behavior.framework', true);

// Get params
$color				= $this->params->get('templatecolor');
$logo				= $this->params->get('logo');
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
$doc->addStyleSheet(JUri::base() . 'templates/' . $this->template . '/css/styles_2.css', $type = 'text/css', $media = 'screen,projection');
$doc->addStyleSheet(JUri::base() . 'templates/' . $this->template . '/css/bootstrap.css', $type = 'text/css', $media = 'screen,projection');


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
		 

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"/>
		<meta name="HandheldFriendly" content="true" />
		<meta name="apple-mobile-web-app-capable" content="YES" />

		<jdoc:include type="head" />

		<!--[if IE 7]>
		<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ie7only.css" rel="stylesheet" type="text/css" />
		<![endif]-->
	</head>
	<body>
		

		<div id="all" class="media">
			<div id="back">
				<header id="header">
					<div class="logoheader">
						<h1 id="logo">
						<?php if ($logo) : ?>
							<img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>"  alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
						<?php endif;?>
						<?php if (!$logo AND $templateparams->get('sitetitle')) : ?>
							<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>
						<?php elseif (!$logo AND $config->get('sitename')) : ?>
							<?php echo htmlspecialchars($config->get('sitename'));?>
						<?php endif; ?>
						<span class="header1">
						<?php echo htmlspecialchars($templateparams->get('sitedescription'));?>
						</span></h1>
					</div><!-- end logoheader -->
					 <jdoc:include type="modules" name="position-1" />
					 
					 
				</header> 
                            <?php
                            if (isset($_GET['option']) && isset($_GET['view']))
                                {
                                     
                                     if ($_GET['view'] == 'projects' && $_GET['option'] == 'com_pfprojects')
                                     {
                                          $classsub = "subheader2";
                                     }
                                     else $classsub = "subheader1";
                                     $showSubheader = true;
                                }
                                else $showSubheader = false;
                                if ($showSubheader) {
                            ?>
                            <section class='subheader <?php echo $classsub;?>'><div><div style="height: 100px;"></div>
                                <?php
                                if (isset($_GET['option']))
                                {
                                     
                                     if ($_GET['view'] == 'projects' && $_GET['option'] == 'com_pfprojects')
                                     {
                                         ?>
                                <div style="width: 100%; color: #fff; text-align: center;">
                                <h1 class="colcreh">PROJECTS</h1>
                                <p>Wanna see what creative people have been doing? They have really amazing projects, take a look!</p><br />
                                <form method="post" action="index.php?option=com_pfprojects&view=projects&Itemid=126" name="adminForm">
                                <input type="text" value="" placeholder="Search..." name="filter_search" style="margin:auto; width: 500px;" />
                                </form>
                                </div>
                                <?php
                                     }
                               
                                elseif ($_GET['task'] == 'browse' && $_GET['option'] == 'com_community')
                                {
                                    ?><div style="width: 100%; color: #fff; text-align: center;">
                                            <h1 class="colcreh">PROFILES</h1>
                                <p>Here you can find profiles of users, categorized by the skills you are looking for.</p><br />
                                
                                        <form id="cFormSearch" action="index.php?option=com_community&view=search&Itemid=103" method="get" name="search">

    <input type="text" value="" placeholder="Search..." class="input-block-level" type="text" name="q" style="margin:auto; width: 500px;"></input>
     <input type="hidden" value="com_community" name="option"></input>
    <input type="hidden" value="search" name="view"></input>

                                        </form></div>
                                <?php
                                    
                                } }
                                ?>
                                
                                
                                <jdoc:include type="modules" name="position-2" /></div></section>
                            
                                <?php } ?>
                               <?php if ($showleft) {  echo "<div id='leftarea'>";?>  <jdoc:include type="modules" name="position-3" />  <?php echo "</div>"; } ?>  
				<div <?php echo ($showno) ? "id=\"contentarea\"" : "id=\"contentarea_2\""; ?>>
					 <jdoc:include type="modules" name="position-0" />
                                         <jdoc:include type="component" />
				</div> <!-- end contentarea -->
                                <?php if ($showleft) {echo "<div style='clear: both;'></div>"; } ?>
			</div><!-- back -->
		

		<div id="footer-outer">
               
			 

			 
				<footer id="footer">
					<jdoc:include type="modules" name="position-14" />
				</footer><!-- end footer -->
			 
		</div>
                        </div><!-- all -->
		<jdoc:include type="modules" name="debug" />
	</body>
</html>
