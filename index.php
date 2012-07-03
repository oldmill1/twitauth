<?php include 'app.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>twitauth <?php echo $username; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="public/css/bootstrap.css" rel="stylesheet">
    <link href="public/css/style.css" rel="stylesheet">
    <link href="public/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="public/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="public/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="public/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="public/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="public/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="container">

      <h1 class="page-header">twitauth</h1>
			<?php 
			if ( ! isset( $user ) ) : ?>  
					<p>Welcome to twitauth. To get started, <a href="?authenticate">begin here</a>.</p>
					<p>You may wish to <a href="?wipe">start over</a> if you're having trouble.</p>
			<?php else : ?> 	
				<p><img src="<?php echo $user->profile_image_url; ?>" /></p>
				<p>Welcome <strong><?php echo $user->screen_name; ?></strong>. <a href="?wipe">Sign Out</a></p>
				<br />
				<div class="row">
					<div class="span3">
						<div class="well">
							<ul id="users" class="nav nav-list">
								<?php 
								$ids = get_followers_ids( $user ); 
								$followers = get_userdata_for( $_SESSION['ids'] ); 
								echo "<li class='nav-header'>My Followers (".count($followers).")</li>"; 
								foreach ( $followers as $follower ) : 
								?> 
								<li id="<?php echo $follower['id']; ?>">
									<a href="http://www.twitter.com/<?php echo $follower['screen_name']; ?>"><?php echo $follower['screen_name']; ?></a>
								</li>
								<?php endforeach; ?> 
							</ul>
						</div>
					</div>
					<div class="span9" id="main">
						<p class='alert alert-info'>Please click a username on the left to get more info.</p>
						<ul>
							<li class="tweet-view">
								<a href=""></a>
							</li>
						</ul>
					</div>
				</div>
			<?php endif; ?>  
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="public/js/jquery.js"></script>
		<script src="public/js/app.js"></script>
  </body>
</html>


















