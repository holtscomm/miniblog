<?php
define('IN_BLOG', true);
define('PATH', '');
include('includes/miniblog.php');
require_once('../header.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Field Goal Campaign News</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:title" content="Let's Turfit - The Bowl Field Goal Campaign" />
<meta property="og:image" content="images/fieldgoalcolor.png" /> 
<meta property="og:description" content="Home page for The Bowl Field Goal Campaign!" />
<meta property="og:url" content="http://<?= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>" />

<!-- Bootstrap -->
<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="../css/custom.css" rel="stylesheet" media="screen">

<link rel="shortcut icon" type="image/png" href="../images/favicon.ico">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <!--<script src="js/html5shiv.js"></script>-->
  <!--<script src="js/respond.min.js"></script>-->
<![endif]-->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-44023331-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5342337c6f476df6"></script>
<script type="text/javascript">
  addthis.layers({
    'theme' : 'transparent',
    'share' : {
      'position' : 'left',
      'numPreferredServices' : 5
    }, 
    'follow' : {
      'services' : [
        {'service': 'facebook', 'id': 'thebowlcampaign'},
        {'service': 'twitter', 'id': 'thebowlcampaign'}
      ]
    }
  });
</script>
<!-- AddThis Smart Layers END -->
</head>

<body >
<div class="container">
   <?=print_header('','','', " class='active'")?>

	<div class="page-header">
	    <h1>Campaign Updates</h1>
	</div>
	<div class="row">
    	<div class="col-md-8">
    	<?=
    	    $miniblog_posts;
    	?>
    	</div>
    	<div class="col-md-4">
    	    <br /><br /><br />
    	    <a href="https://secure.e2rm.com/registrant/startup.aspx?eid=127859" target="_blank">
    	        <img src="../images/footballdonate.png" width="290px" height="165px" style="border-radius: 10px">
    	    </a>
    	</div>
	</div>
	
	<div class="navigation">
		<?php if(!$single) { ?>
			<?php if($miniblog_previous) {	?> <p class="previous-link"><?=$miniblog_previous?></p>	<?php } ?>
			<?php if($miniblog_next) {	?>	<p class="next-link"><?=$miniblog_next?></p> <?php } ?>
		<?php } ?>
		<?php if($single) { ?>
			<p class="previous-link"><a href="<?=$config['miniblog-filename']?>">&laquo; return to posts</a></p>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<div class="footer">
		<!-- a link back is REQUIRED under the terms of the license. To remove the link back see http://www.spyka.net/licensing -->
        <!-- Admin note: I tried, I swear I did. "License purchases are currently disabled." 
                Maybe some day we will get a license! -->
	</div>
</div>
</body>
</html>