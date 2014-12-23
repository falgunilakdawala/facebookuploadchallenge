<!DOCTYPE html>
<html>
	<head>
		<title>Facebook Album</title>

		<link rel="shortcut icon" type="image/jpg" href="libs/resources/img/favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="libs/resources/css/jquery.fancybox.css" />
		<link rel="stylesheet" type="text/css" href="libs/resources/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="libs/resources/css/style.css" />

		<script src="libs/resources/js/jquery-2.1.1.min.js"></script>
		<script src="libs/resources/js/spin.min.js"></script>
		<script src="libs/resources/js/jquery.fancybox.js" type="text/javascript" charset="utf-8"></script>
		<script src="libs/resources/js/bootstrap.min.js"></script>
	</head>
	<body>

	<?php
	
		require_once( 'includes.php' );

		use Facebook\GraphObject;
		use Facebook\GraphSessionInfo;
		use Facebook\Entities\AccessToken;
		use Facebook\HttpClients\FacebookHttpable;
		use Facebook\HttpClients\FacebookCurl;
		use Facebook\HttpClients\FacebookCurlHttpClient;
		use Facebook\FacebookSession;
		use Facebook\FacebookRedirectLoginHelper;
		use Facebook\FacebookRequest;
		use Facebook\FacebookResponse;
		use Facebook\FacebookSDKException;
		use Facebook\FacebookRequestException;
		use Facebook\FacebookAuthorizationException;


		FacebookSession::setDefaultApplication( $fb_app_id, $fb_secret_id );

		// login helper with redirect_uri
		$helper = new FacebookRedirectLoginHelper( $fb_login_url );
		
		// see if a existing session exists
		if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
			// create new session from saved access_token
			$session = new FacebookSession( $_SESSION['fb_token'] );

			try {
				if ( !$session->validate() ) {
				  $session = null;
				}
			} catch ( Exception $e ) {
				// catch any exceptions
				$session = null;
			}
		}  
		 
		if ( !isset( $session ) || $session === null ) {
			try {
				$session = $helper->getSessionFromRedirect();
			} catch( FacebookRequestException $ex ) {
				print_r( $ex );
			} catch( Exception $ex ) {
				print_r( $ex );
			}
		}

		$google_session_token = "";

		// see if we have a session
		if ( isset( $session ) ) {

			//require_once( 'libs/resize_image.php' );

			$_SESSION['fb_login_session'] = $session;
			$_SESSION['fb_token'] = $session->getToken();

			// create a session using saved token or the new one we generated at login
			$session = new FacebookSession( $session->getToken() );
			
			$request_user_details = new FacebookRequest( $session, 'GET', '/me?fields=id,name' );
			$response_user_details = $request_user_details->execute();
			$user_details = $response_user_details->getGraphObject()->asArray();
			
			$user_id = $user_details['id'];
			$user_name = $user_details['name'];
			
			
			/*if ( isset( $_SESSION['google_session_token'] ) ) {
				$google_session_token = $_SESSION['google_session_token'];
			}*/
?>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
					<div class="container">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div id="nav-menu" class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-menu">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="#" id="username">
								<img src="<?php echo 'https://graph.facebook.com/'.$user_id.'/picture';?>" id="user_photo" class="img-thumbnail" />
								<span style="margin-left: 5px;"> <?php echo $user_name;?></span>
							</a>
						</div>

				<div id="navbar-collapse-menu" class="collapse navbar-collapse menu-links">
							<ul class="nav navbar-nav pull-right">
								<li>
									<a href="#" id="download-all-albums" class="center">
										<span class="btn btn-info col-md-12">
											Download All
										</span>
									</a>
								</li>
								<li>
									<a href="#" id="download-selected-albums" class="center">
										<span class="btn btn-info col-md-12">
											Download Selected
										</span>
									</a>
								</li>
								<li>
									<a href="#" id="move_all" class="center">
										<span class="btn btn-info col-md-12">
											Move All
										</span>
									</a>
								</li>
								<li>
									<a href="#" id="move-selected-albums" class="center">
										<span class="btn btn-info col-md-12">
											Move Selected
										</span>
									</a>
								</li>
								<li>
									<a href="<?php echo $helper->getLogoutUrl( $session, $fb_logout_url );?>" class="center">
										<span class="btn btn-info col-md-12">
											Logout
										</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</nav>

<?php
		} else {
			//$perm = array( "scope" => "email,user_photos" );
?>
			<nav class="navbar navbar-inverse" role="navigation">
				<div class="container-fluid">
					<div class="navbar-header" align="center">
						<span class="center-block" style="font-size:36px;padding-left:80%;" href="<?php echo $helper->getLoginUrl( $perm );?>">
							Facebook Album
						</span>
					</div>
				</div>
			</nav>

			<div id="login-div" align="center" class="row">
				<a id="login-link" class="btn btn-primary col-md-12" href="<?php echo $helper->getLoginUrl( $perm );?>">
					Facebook Login
				</a>
			</div>            

<?php   } ?>
</body>
</html>
