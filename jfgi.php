<?php
/*
Plugin Name:  Bitcoin Address Shortener by jfGi.cc
Plugin URI:   https://www.jfgi.cc/
Description:  Convert your WordPress site into a short URL linked to your public Bitcoin Address. Use the BTC Address Shortener URL to receive payments quickly, easily and with one click.
Version:      1.0.1
Author:       Rafiq Phillips
Author URI:   https://www.webaddict.co.za
*/

function jfgi_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( 'admin.php?page=jfgi_setup' ) ) . '">' . __( 'Please enter your public Bitcoin address to finish installation', 'textdomain' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'jfgi_action_links' );


add_action('admin_menu', 'jfgi_menu');

function jfgi_menu()
{
	add_menu_page( 'Setup', 'jfGi Bitcoin', 'administrator', 'jfgi_setup', 'jfgi_setup_callback', esc_url( plugins_url( 'images/jfGi-logox25.png', __FILE__ ) ));
	add_submenu_page( 'jfgi_setup', 'Click Logs', 'Click Logs', 'administrator', 'jfgi_clickLogs', 'jfgi_clickLogs_callback' );
	add_submenu_page( 'jfgi_setup', 'Advanced', 'Advanced', 'administrator', 'jfgi_advanced', 'jfgi_advanced_callback' );
	add_submenu_page( 'jfgi_setup', 'Upgrade', 'Upgrade', 'administrator', 'jfgi_upgrade', 'jfgi_upgrade_callback' );
	add_submenu_page( 'jfgi_setup', 'Support', 'Support', 'administrator', 'jfgi_support', 'jfgi_support_callback' );
}

/**
 * Include CSS and JS file for MyPlugin.
 */
function jfgi_plugins_scripts() {
  //wp_enqueue_style( 'style', get_stylesheet_uri() );
 
  wp_enqueue_style( 'jfgi_font_awesome_css', esc_url( plugins_url( 'css/font-awesome.min.css', __FILE__ ) ));

  wp_enqueue_style( 'jfgi_bootstrap_min_css', esc_url( plugins_url( 'css/bootstrap.min.css', __FILE__ ) ));

  

  wp_enqueue_style( 'jfgi_style_css', esc_url( plugins_url( 'css/style.css', __FILE__ ) ));
 
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
    }

  wp_enqueue_script( 'jfgi_bootstrap_min_js', esc_url( plugins_url( 'js/bootstrap.min.js', __FILE__ ) ), '3.3.7', true);
  wp_enqueue_script( 'jfgiScript', esc_url( plugins_url( 'js/jfgiScript.js', __FILE__ ) ), '1.0', true);

  wp_enqueue_script( 'jfgiMapsGoogle', 'https://maps.googleapis.com/maps/api/js?v=3.exp', '1.6.4', true);

  wp_enqueue_script( 'jfgiScriptColorbox', esc_url( plugins_url( 'js/jquery.colorbox.js', __FILE__ ) ), '1.0', true);
  wp_register_script( 'jfGi_ajaxHandle', esc_url( plugins_url( 'js/jfGi_ajax.js', __FILE__ ) ), array(), false, true );
  wp_enqueue_script( 'jfGi_ajaxHandle' );
  wp_localize_script( 'jfGi_ajaxHandle', 'jfGi_ajax_object', array( 'jfGi_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    
}
add_action( 'admin_enqueue_scripts', 'jfgi_plugins_scripts' );


add_action('plugins_loaded', 'jfgiConstant', 0);

function jfgiConstant(){
    if ( !defined('JFGI_COIN') ) {
        define ( 'JFGI_COIN', 'jfgi_address' );
    }
    if ( !defined('JFGI_LOG') ) {
        define ( 'JFGI_LOG', 'jfgi_log' );
    }
    if ( !defined('JFGIBASE') ) {
        define ( 'JFGIBASE', jfgiBaseurl() );
    }
    if ( !defined('JFGI_TOTALLOG') ) {
        define ( 'JFGI_TOTALLOG', '25' );
    }
    
}

function jfgi_setup_callback(){ 
	 
	$jfgiPage   = sanitize_text_field($_REQUEST['page']);
	if(!$jfgiPage){
        $jfgiPage = '';
    }

	$jfgiAction = sanitize_text_field($_POST['jfgiAction']);
	if(!$jfgiAction){
        $jfgiAction = '';
    }

	$jfgiEdit = sanitize_text_field($_POST['jfgiEdit']);
    if(!$jfgiEdit){
        $jfgiEdit = '';
    }

	$logData = jfgi_logData();
	if(!$logData){
        $logData = '';
    }

	$logData = json_decode($logData, true);
	if(!$logData){
        $logData = '';
    }

	$data = jfgi_data();
	if(!$data){
        $data = '';
    }

	$data = json_decode($data, true);
	
    $btcAdd = sanitize_text_field($data['jfgi']);
    if(!$btcAdd){
        $btcAdd = '';
    }

	$totalLogs = count($logData);
	if(!$totalLogs){
        $totalLogs = '';
    }
     
     
    $jfgi_setupadd = $_POST['jfgi_setupadd'];
    if(isset($jfgi_setupadd)){ 
        wp_nonce_field( "ecpt_BTCAddress_action", "ecpt_BTCAddress_nonce_field" );
		if ( ! isset( $_POST['ecpt_BTCAddress_nonce_field'] ) 
		    || ! wp_verify_nonce( $_POST['ecpt_BTCAddress_nonce_field'], 'ecpt_BTCAddress_action' ) 
		) {
		   print esc_html('Sorry, your nonce did not verify.');
		   exit;
		} else {

    	$jfgi_address = sanitize_text_field($_POST[JFGI_COIN]);
    	if(!$jfgi_address){
	        $jfgi_address = '';
	    }

    	$msg = jfgi_writeToLog( $jfgi_address );
    	if(!$msg){
	        $msg = '';
	    }

    	}

    }
    $form = jfgi_createform(); 
    if(!$form){
	        $form = '';
	    }

	$jfgiCurrent = jfgiCurrent($jfgiPage);
	if(!$jfgiCurrent){
	        $jfgiCurrent = '';
	    }
	
?> 
		    <div class="wrap jfgi_main">
		        <?= jfgiHeading(); ?>
		        <?php $ctotal = count($data);
                  if($ctotal !== 0){
                  	?>
                    <div class="updated notice">
		                <p><?php echo esc_html("You've reach the maximum links allowed with the current plugin version. Please upgrade to create more jfGi URLs"); ?></p>
		            </div>
                  	<?php
                  }
                  ?>
                
                
		        
		        <hr class="wp-header-end">
		        <?= jfgiMenu($jfgiCurrent); ?>
		        <div class="jfgiSetupTable">
				 <div class="tablenav top">
				     <div class="tablenav-pages">
				     <?php if($jfgiAction != 'delete' && $jfgiEdit != 'jfgiDelete'){?>
				     <span class="displaying-num" <?= $show; ?>><?= count($data); ?> item</span>
				     <?php } ?>
				     </div>
				 	
				 </div>

	
				<?php 
				    
				     $msg = json_decode($msg, true);
				     if(!$msg){
					    $msg = '';
					 }

				    if($jfgiAction == 'delete' && $jfgiEdit == 'jfgiDelete'){
				    	
	                       deletejfGiAddress();
	                   
					 }
				     if($jfgiAction == 'edit' && $jfgiEdit == 'jfgiEdit'){ 

	                    echo jfgi_createform($jfgiAction);
					 }elseif(!empty($msg['msg'])){
                        echo $msg['msg'].$form.jfgi_coinTable();
				     }elseif(!empty($msg['data'])){				     	
					    echo $msg['data'];
					 }else{
				     	echo $form.jfgi_coinTable();
				     }
				 ?>
				</div>
			</div>
	   
<?php    
}

function jfgiCurrent($jfgiPage){
	$current = array();
	if($jfgiPage == "jfgi_setup"){ 

       $current['jfgi_setup'] = 'current';

	}elseif ($jfgiPage == "jfgi_clickLogs") {

	   $current['jfgi_clickLogs'] = 'current';

	}elseif ($jfgiPage == "jfgi_upgrade") {

	   $current['jfgi_upgrade'] = 'current';

	}elseif ($jfgiPage == "jfgi_advanced") {

	   $current['jfgi_advanced'] = 'current';

	}elseif ($jfgiPage == "jfgi_support") {

	   $current['jfgi_support'] = 'current';

	}

	return json_encode($current);
}

function jfgiHeading(){
	return '<h1 class="wp-heading-inline">
                <img class="jfGi_logo" src="'. esc_url( plugins_url( 'images/jfGi-logo.png', __FILE__ ) ).'">
		        '.esc_html('jfGi Plugin').'</h1>';
}
function jfgiMenu($jfgiCurrent){
	$jfgiCurrent = json_decode($jfgiCurrent, true);
	if(!$jfgiCurrent){
       $jfgiCurrent = '';
	}
	$setup = !empty($jfgiCurrent['jfgi_setup']) ? $jfgiCurrent['jfgi_setup'] : '';
	$log = !empty($jfgiCurrent['jfgi_clickLogs']) ? $jfgiCurrent['jfgi_clickLogs'] : '';
	$advanced = !empty($jfgiCurrent['jfgi_advanced']) ? $jfgiCurrent['jfgi_advanced'] : '';
	$upgrade = !empty($jfgiCurrent['jfgi_upgrade']) ? $jfgiCurrent['jfgi_upgrade'] : '';
	$support = !empty($jfgiCurrent['jfgi_support']) ? $jfgiCurrent['jfgi_support'] : '';
    
	return '<ul class="subsubsub">
					<li class="all"><a href="'.esc_url('admin.php?page=jfgi_setup').'" class="'. esc_attr($setup) .'" >'. esc_html('Setup') .'</a> |</li>
					<li class="publish"><a href="'.esc_url('admin.php?page=jfgi_clickLogs').'" class="'.esc_attr($log).'" >'. esc_html('Click Logs') .'</a> |</li>
					<li class="publish"><a href="'.esc_url('admin.php?page=jfgi_advanced').'" class="'.esc_attr($advanced).'" >'. esc_html('Advanced') .'</a> |</li>
					<li class="publish"><a href="'.esc_url('admin.php?page=jfgi_upgrade').'" class="'.esc_attr($upgrade).'" >'. esc_html('Upgrade') .'</a> |</li>
					<li class="publish"><a href="'.esc_url('admin.php?page=jfgi_support').'" class="'.esc_attr($support).'" >'. esc_html('Support') .'</a> </li>
				</ul>';
}

function jfgi_writeToLog( $u ) {
        
        if(!$u){
          return;
        }

        $rdata = jfgi_data();
        if (!$rdata) {
        	$rdata = '';
        }

        $resultData = array();
        if (!$resultData) {
        	$resultData = '';
        }

    	$udec = json_decode($rdata, true);
    	if (!$udec) {
        	$udec = '';
        }

    	$add1 = $udec["jfgi"];
    	if (!$add1) {
        	$add1 = '';
        }

    	if($add1 == $u){
           $resultData['msg'] = "<p>You have already entered coin address <strong>$u</strong>.</p>";
    	}else{
    	
    	if(array_key_exists("jfgi",$udec))
		  {
		  	$udec['jfgi'] = $u;
            $data = $udec;
		  }else{
            $data = array('jfgi' => $u );
		  }
    $path = JFGIBASE.JFGI_COIN.'.txt'; 
  
    $agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
    
	    $h = fopen($path, "w+");
	    if ( $h != FALSE ) {
	    	
	    	$arr = base64_encode(urlencode(json_encode($data)));

	        $mystring = $arr;
	       
	        fwrite( $h, $mystring );
	        fclose($h);
	        chmod($h, 0777);
	        $resultData['data'] = jfgi_coinAddressList($u);
	        
	    }
	    else
	        die('WHAT IS GOING ON?');
    

    }
    return json_encode($resultData);
}

/* add_action( 'wp_enqueue_scripts', 'jfGi_enqueue' );
function jfGi_enqueue(){
  wp_register_script( 'jfGi_ajaxHandle', esc_url( plugins_url( 'js/jfGi_ajax.js', __FILE__ ) ), array(), false, true );
  wp_enqueue_script( 'jfGi_ajaxHandle' );
  wp_localize_script( 'jfGi_ajaxHandle', 'jfGi_ajax_object', array( 'jfGi_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}*/

function deletejfGiAddress() {   
    $nonce = sanitize_text_field($_POST['nonce']);    
    wp_nonce_field( "ecpt_BTCAddressDelete_action", "ecpt_BTCAddressDelete_nonce_field" );
	if ( ! isset( $nonce ) 
	    || ! wp_verify_nonce( $nonce, 'ecpt_BTCAddressDelete_action' ) 
	) {
	   print esc_html('Sorry, your nonce did not verify.');
	   exit;
	} else {    	
    $path = JFGIBASE.JFGI_COIN.'.txt'; 
    if ( file_exists($path)){
    if (!unlink($path))
	  {
	  echo esc_html("Error deleting $file");
	  }
	else
	  {
  	 
  	 	echo esc_html('BTC address has beed deleted succesfully. Please click <a href="'.esc_url(get_site_url().'/wp-admin/admin.php?page=jfgi_setup').'" >here </a> for add new.');
  	 }
  	}else{
  	  echo esc_html('$path file not exist.');	
  	}
  }
}

add_action( 'wp_ajax_nopriv_deletejfGiAddress', 'deletejfGiAddress' );
add_action( 'wp_ajax_deletejfGiAddress', 'deletejfGiAddress' );

function jfgi_data(){

	
    $path = JFGIBASE.JFGI_COIN.'.txt';
    if ( file_exists($path)){
	    $h = fopen($path, "r");
	    if ( $h != FALSE ) {
	    	$b64 = fgets($h);
	    	$input = base64_decode($b64);
	    	return utf8_decode(urldecode($input));
	    	
	    }
    }else{
    	return '';
    }
}

function jfgi_createform($jfgiAction=''){

	if(!is_dir(JFGIBASE))
	  {
	  mkdir(JFGIBASE);
	  chmod(JFGIBASE, 0777);
	  }
	
    $path = JFGIBASE.JFGI_COIN.'.txt';
    $h = fopen($path, "r");
    $b64 = fgets($h);
	$input = base64_decode($b64);
	$udec = json_decode(utf8_decode(urldecode($input)), true);
    if ( $h != FALSE && $jfgiAction == null ) {
    	
    	if(array_key_exists("jfgi",$udec))
		  {
		  	jfgi_coinTable();	
		  }	 
    	
    }else if($jfgiAction == 'edit'){
           return '<form class="jfgiCenter" action="" method="post">
                    '.wp_nonce_field( "ecpt_BTCAddress_action", "ecpt_BTCAddress_nonce_field" ).'
				    <input type="text" name="'.esc_attr(JFGI_COIN).'" class="form-control '.esc_attr(JFGI_COIN).'" value="'.esc_attr($udec['jfgi']).'">
				    
				    <input type="submit" class="jfgi_setupadd" name="jfgi_setupadd" value="Set my JFGI Bitcoin Receive URL">
				</form>';
		  
    }else{
		  return '<form class="jfgiCenter" action="" method="post">
		         '.wp_nonce_field( "ecpt_BTCAddress_action", "ecpt_BTCAddress_nonce_field" ).'
				    <input type="text" name="'.esc_attr(JFGI_COIN).'" class="form-control '.esc_attr(JFGI_COIN).'" value="">
				    <input type="submit" class="jfgi_setupadd" name="jfgi_setupadd" value="'.esc_html('Set my JFGI Bitcoin Receive URL').'">
				</form>';
		  }
}
function jfgi_coinTable(){
        $data = jfgi_data();
		$data = json_decode($data, true);

		$domain = sanitize_text_field($_SERVER['SERVER_NAME']);
		if(!$domain){
			$domain = '';
		}

		$requestURI = sanitize_text_field($_SERVER['REQUEST_URI']);
		if(!$requestURI){
			$requestURI = '';
		}

		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
		    $htp = "https://";
		} else { 
		    $htp = "http://";
		}
		$uri = $htp.$domain.$requestURI;
        $logData = jfgi_logData();
		$logData = json_decode($logData, true);
		
	    $btcAdd = $data['jfgi'];
	    $logData = $logData;
		$totalLogs = count($logData);

		$table = '';
		if(count($data) >= 1){
		
		$table .= '<table class="wp-list-table widefat btcAddressTable">
		         <thead>
                 <tr>
                 <th class="manage-column url">'.esc_html("URL").'</th>
                 <th class="manage-column btcAddress">'.esc_html("BTC Address").'</th>                
                 <th class="manage-column action">'.esc_html("Action").'</th>
                 <th class="manage-column clicks">'.esc_html("Clicks").'</th>
                 </tr></thead><tbody id="the-list">';
        foreach ($data as $key => $value) {
        	
        	$link = get_site_url().'/btc';
        	$linkBtc = get_site_url().'/jfgi';
        $table .= '<tr>
                 <td><a target="_blank" href="'.esc_url($link).'">/btc</a> | <a target="_blank" href="'.esc_url($linkBtc).'">'.esc_html('/jfgi').'</a></td>
                 <td>
	                 <form id="jfgiEditForm" method="post" action="">
		                 <input type="hidden" name="jfgiEdit" class="form-control jfgiEdit" value="jfgiEdit">
		                 <input type="hidden" name="jfgiAction" class="form-control jfgiEdit" value="edit">
		                 <a href="javascript:{}" onclick=document.getElementById("jfgiEditForm").submit(); return false;>'.esc_html($value).'</a>
	                 </form>
                 </td>
                 
                 <td class="jfgiFlex"><form id="jfgiEditForm" class="jfgiMR" method="post" action="">
		                 <input type="hidden" name="jfgiEdit" class="form-control jfgiEdit" value="jfgiEdit">
		                 <input type="hidden" name="jfgiAction" class="form-control jfgiEdit" value="edit">
		                 <a href="javascript:{}" onclick=document.getElementById("jfgiEditForm").submit(); return false;>'.esc_html('Edit').'</a>
	                 </form> | 
	                 <form id="jfgideleteForm" class="jfgiML" method="post" action="">
	                     '.wp_nonce_field( "ecpt_BTCAddressDelete_action", "ecpt_BTCAddressDelete_nonce_field" ).'
		                 <input type="hidden" name="jfgiEdit" class="form-control jfgiEdit" value="jfgiDelete">
		                 <input type="hidden" name="jfgiAction" class="form-control jfgiEdit" value="delete">
		                 <a href="javascript:{}" id="jfgideleteForm" onclick=jfgideleteForm()>Delete</a>
		                 <div id="confirmBox">
						    <div class="message"></div>
						    <span class="button yes">Yes</span>
						    <span class="button no">No</span>
						</div>
	                 </form>
                     		    <div class="jfgiSocialShare" style="padding-left: 20px;">

		             <div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, "script", "facebook-jssdk"));</script>
						<div class="fb-share-button" data-href="'.get_site_url().'/btc?title=Send me Bitcoin here '.get_site_url().'/btc&summary=Send me Bitcoin here '.get_site_url().'/btc" data-layout="button" data-desc="Send me Bitcoin here '.get_site_url().'/btc" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=Send me Bitcoin here '.get_site_url().'/btc" class="fb-xfbml-parse-ignore"></a></div>

						<a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Send me Bitcoin here '.get_site_url().'/btc @jfGi" data-size="large"><i class="fa fa-twitter f32"></i></span></a>
						<!—- ShareThis BEGIN -—>
						<a href="https://wa.me/?text=Send me Bitcoin here '.get_site_url().'/btc"><span><i class="fa fa-whatsapp f32"></i></span></a>
						<!—- ShareThis END -—>
						<a href="https://t.me/share/url?url='.get_site_url().'/btc&text=Send me Bitcoin here '.get_site_url().'/btc" style="text-decoration:none;" title="Share on Telegram"><span><i class="fa fa-telegram f32"></i></span></a>

		           
		    </div>
	                 </td>
                 <td><a href="'.esc_url('admin.php?page=jfgi_clickLogs').'">'.$totalLogs.'</a></td>
                 </tr>';
                  }    
        $remaning = 2-count($data);    
        $remaning = $remaning < 0 ? 0 : $remaning;      
                        
		$table .= '</tbody>
                 <tfoot>
                 <tr>
                 <th class="manage-column">'.esc_html('URL').'</th>
                 <th class="manage-column">'.esc_html('BTC Address').'</th>
                 
                 <th class="manage-column">'.esc_html('Action').'</th>
                 <th class="manage-column">'.esc_html('Clicks').'</th>
                 </tr></tfoot></table>';
		
		
		 
	    }
		return $table;
		
}

function jfgi_coinAddressList($address){
	if(!empty($address)){
        	$data = $address;
			
			$domain = sanitize_text_field($_SERVER['SERVER_NAME']);
			if(!$domain){
				$domain = '';
			}

			$requestURI = sanitize_text_field($_SERVER['REQUEST_URI']);
			if(!$requestURI){
				$requestURI = '';
			}

			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
			    $htp = "https://";
			} else { 
			    $htp = "http://";
			}
			$uri = $htp.$domain;

			$i = 0; 
			
			$list = "<div class='jfgi_linkList'><h2 class='info1'>".esc_html("Congratulations! You are ready to receive Bitcoin payments with 1 click on the following URLs :
		You can change your BTC address whenever you like and your URLs will remain the same.")."</h2>";
		             
         	

         	$list .= "<div class='jfgiLinkContainer'><input type='text' name='jfgiLink' id='jfgiLink".$i."' class='jfgiLink' value='".get_site_url()."/btc'/>";
            $list .= "<button id='jfgiCopy".$i."' class='jfgiCopy jfgiMenu jfgiMenuShadow'>".esc_html("Copy")."</button>
         	</div>";

         	$list .= "<div class='jfgiLinkContainer'><input type='text' name='jfgiLink' id='jfgiLink1' class='jfgiLink' value='".get_site_url()."/jfgi'/>";
            $list .= "<button id='jfgiCopy1' class='jfgiCopy jfgiMenu jfgiMenuShadow'>".esc_html("Copy")."</button>
         	</div>";
		    $msg = urlencode('Message for Social sharing:
                    "Please find the btc and jfgi links of my BTC address:"

                    i.  '.get_site_url().'/jfgi
                    ii. '.get_site_url().'/'.$data);

		    $list .= '<div class="jfgiSocialShare">

		             <div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, "script", "facebook-jssdk"));</script>
						<div class="fb-share-button" data-href="'.get_site_url().'/btc?title=Send me Bitcoin here '.get_site_url().'/btc&summary=Send me Bitcoin here '.get_site_url().'/btc" data-layout="button" data-desc="Send me Bitcoin here '.get_site_url().'/btc" data-size="large" data-mobile-iframe="true"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=Send me Bitcoin here '.get_site_url().'/btc" class="fb-xfbml-parse-ignore"></a></div>

						<a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Send me Bitcoin here '.get_site_url().'/btc @jfGi" data-size="large"><i class="fa fa-twitter f32"></i></span></a>
						<!—- ShareThis BEGIN -—>
						<a href="https://wa.me/?text=Send me Bitcoin here '.get_site_url().'/btc"><span><i class="fa fa-whatsapp f32"></i></span></a>
						<!—- ShareThis END -—>
						<a href="https://t.me/share/url?url='.get_site_url().'/btc&text=Send me Bitcoin here '.get_site_url().'/btc" style="text-decoration:none;" title="Share on Telegram"><span><i class="fa fa-telegram f32"></i></span></a>

		           
		    </div>';

		    $list .= "<h2 class='info2'>".esc_html("Add it to your site or share it on social media wherever a URL can be clicked to start receiving bitcoin payments from your jfGi-enabled Wordpress URLs. See Click Logs for where and when users have clicked on them or Advanced to create multiple customised Bitcoin URLs.")."</h2>";
			$list .= "</div>";

			return $list;

	}

}

function jfgiBaseurl(){
	$upload_dir = wp_upload_dir();
    $url = $upload_dir['basedir'].'/jfgi';
	if (!file_exists($url)) {
        mkdir($url, 0777, true);
    }
    return $url."/";
}

function jfgi_clickLogs_callback(){
	$logData = jfgi_logData();
	$logData = json_decode($logData, true);
	//print_r($logData);
	$data = jfgi_data();
	$data = json_decode($data, true);
    $btcAdd = $data['jfgi'];
    $logData = $logData;
    
	$totalLogs = count($logData);
     

    $jfgiPage = sanitize_text_field($_REQUEST['page']);
    if(!$jfgiPage){
		$jfgiPage = '';
	}
    $jfgiCurrent = jfgiCurrent($jfgiPage);
	
?> 
		    <div class="wrap jfgi_main">
		        <?= jfgiHeading(); ?>
		        <hr class="wp-header-end">

		        <div class="jfgiSetupTable">
		        <?= jfgiMenu($jfgiCurrent); ?>
				 <div class="tablenav top">
				     <div class="tablenav-pages"><span class="displaying-num"><?= esc_html($totalLogs." item"); ?></span></div>
				 	
				 </div>
                
                <?php if(count($logData) >= 1){ 
                    
                    foreach($logData as $key=>$val){
                      if($key > JFGI_TOTALLOG){
                       $msg = "<div class='updated notice' style='display: block;'><p>". esc_html("Please upgrade to Pro to view unlimited <a href='https://www.jfgi.cc/upgrade'>click logs</a>.")."</p></div>";
                      }

                    }  

                	?>
                	
					    <?php echo $msg; ?>
					
				<table class="wp-list-table widefat jfgiLogTable">
			        <thead>
		                <tr>
		                    <th class="manage-column date"><?php echo esc_html('Date'); ?></th>
		                    <th class="manage-column sourceURL"><?php echo esc_html('Source URL'); ?></th>
		                    <th class="manage-column useragent"><?php echo esc_html('Referred/User Agent'); ?></th>
		                    <th class="manage-column ipaddress"><?php echo esc_html('IP Address'); ?></th>
		                </tr>
		                
	                </thead>
	                <tbody id="the-list">
                 <?php
                   foreach($logData as $key=>$val){
                      if($key <= JFGI_TOTALLOG){
                      	$ip = $val['ip'];
                 ?>
		                <tr>
			                <td><?= $val['date']; ?></td>
			                <td><?= $val['source_url']; ?></td>
			                <td><?= $val['userAgent']; ?></td>
			                <td>
			                <?php
			                $details = json_decode(file_get_contents("http://ipinfo.io/$ip/json"), true);
			                $loc = $details['loc'];
			                $city = $details['city'];
			                $region = $details['region'];
			                $country = $details['country'];
			                $postal = $details['postal'];
			                
			                ?>
			                 <a class="googleMapPopUp<?php echo $key; ?>" rel="nofollow" href="https://maps.google.com.au/maps?q=<?php echo $city,$region,$country,$postal;; ?>" target="_blank"><?php echo $ip; ?> </a>
			                <script type="text/javascript">
								jQuery('.googleMapPopUp<?php echo $key; ?>').each(function() {
								    var thisPopup = jQuery(this);
								    thisPopup.colorbox({
								        iframe: true,
								        innerWidth: 600,
								        innerHeight: 400,
								        opacity: 0.7,
								        href: thisPopup.attr('href') + '&ie=UTF8&t=h&output=embed'
								    });
								});
							</script>
							
			                 </td>                 
		                </tr>
                 <?php 
                      }
                  } 

                 ?>
                 
                    </tbody>
	                <tfoot>
		                <tr>
		                    <th class="manage-column"><?php echo esc_html('Date'); ?></th>
		                    <th class="manage-column"><?php echo esc_html('Source URL'); ?></th>
		                    <th class="manage-column"><?php echo esc_html('Referred/User Agent'); ?></th>
		                    <th class="manage-column"><?php echo esc_html('IP Address'); ?></th>
		                </tr>
		            </tfoot>
                </table>
                 <?php }else{
                 	echo esc_html('No log found!');
                 	} ?>

                 	</div>
			</div>
	  
<?php
}

function jfgi_advanced_callback(){
	 echo headerJfgi();
?> 	  

	<div class="jfgi_advance">
	<p><?php echo esc_html('1) Use your eXtended Public (XPUB) Key instead of your Public BTC Address for increased safety and security. Every time your jfGi enabled links are accessed a new public BTC address will be created linked to your Bitcoin Wallet.'); ?></p>

    <p><?php echo esc_html('2) Create memorable jfGi links on your domain for yourself, authors, readers and the multiple BTC wallets you frequently use to send BTC to like exchanges and hardware wallets.'); ?></p>

    <p><?php echo esc_html('3) Create multiple QR codes with shortcodes enabling you to easily embed them in posts, pages and sidebars.'); ?></p>

    <p><?php echo esc_html('4) Non supported mobile & desktop wallets are redirected to a jfgi.cc URL personalised with your own domain and address. Upgrading to Pro jfGi will allow you to control these URLs . enabling you to direct these users anywhere and keep them on your site.'); ?></p>

    <p><a href="../wp-admin/admin.php?page=jfgi_upgrade"><?php echo esc_html('Upgrade to Pro'); ?></a> <?php echo esc_html('now to capitalise on all of these fetures.'); ?></p>
    </div>
</div>
	   
<?php
}

function jfgi_upgrade_callback(){
    echo headerJfgi();
?> 
    <div class="jfgi-upgrade">
        <form action="" method="post">

        <div class="url_generations_main">
	        <div class="url_generations">
	         <div class="url_generations-box"><p><?php echo esc_html('10 jfGi URLs'); ?></p></div>
	            <!-- <input type="radio" name="generateUpgrade" value="10" id="select_1"> 
	            <label for="select_1">Select</label> -->
	        </div>

            <div class="url_generations">
	           <div class="url_generations-box"><p><?php echo esc_html('25 jfGi URLs'); ?></p></div>
	            <!-- <input type="radio" name="generateUpgrade" value="25" id="select_2" >
	            <label for="select_2">Select</label> -->
	        </div>
	        <div class="url_generations">
	            <div class="url_generations-box"><p><?php echo esc_html('50 jfGi URLs'); ?></p></div>
	            <!-- <input type="radio" name="generateUpgrade" value="50" id="select_3">
	            <label for="select_3">Select</label> -->
	        </div>	    	    
	        <div class="url_generations">
	           <div class="url_generations-box"><p><?php echo esc_html('99 jfGi URLs'); ?></p></div>
	            <!-- <input type="radio" name="generateUpgrade" value="99" id="select_4">
	            <label for="select_4">Select</label> -->
	        </div>
	        <div class="url_generations">
	       	 <div class="url_generations-box"><p><?php echo esc_html('999 jfGi URLs'); ?></p></div>
	            <!-- <input type="radio" name="generateUpgrade" value="999" id="select_5">
	            <label for="select_5">Select</label> -->
	        </div>

	    </div>    
        <p> <?php echo esc_html("The pro plugins will be released soon that will allow you to create multiple Short Bitcoin Address URLs on your WordPress site. Find out more and pre-order the Pro Bitcoin Address Shortener"); ?> <a href="<?php echo esc_html('https://www.jfgi.cc/upgrade'); ?>"><?php echo esc_html("here and save 50% or more before it's released"); ?></a></p> 	
        </form>
    </div>    
</div>
	    
<?php
}

function jfgi_support_callback(){
	echo headerJfgi();
?>
    <div class="jfgi-support">
        <a class="twitter-mention-button" href="https://twitter.com/intent/tweet?screen_name=jfGi&amp;ref_src=twsrc%5Etfw" data-size="large" data-text="Ask your question:" data-show-count="false"><?php echo esc_html("Tweet to @jfGi"); ?></a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

        <h2 class="jfgiAlignLeft"><?php echo esc_html("Contact us on Facebook"); ?> <a href="<?php echo esc_url('https://www.facebook.com/jfgi.cc/'); ?>"><?php echo esc_html("https://www.facebook.com/jfgi.cc/"); ?></a></h2>
        <h2 class="jfgiAlignLeft"><?php echo esc_html("Email support mail to:"); ?> <a href="mailto:contact@jfgi.cc"><?php echo esc_html("contact@jfgi.cc");?></a></h2>
        <h2 class="jfgiAlignLeft">WordPress Forum: <a href="https://wordpress.org/support/plugin/bitcoin-address-shortener-by-jfgi-cc/">wordpress.org/forum</a></h2>
    </div>    
</div>
<?php
}

function headerJfgi(){
	$logData = jfgi_logData();
	if($logData){
        $logData = json_decode($logData, true);
        $totalLogs = count($logData);
	}
	
	$data = jfgi_data();
	if($data){
        $data = json_decode($data, true);
        $btcAdd = $data['jfgi'];
	}

    $jfgiPage = sanitize_text_field($_REQUEST['page']);
    $jfgiCurrent = jfgiCurrent($jfgiPage);
	

		$heder = '<div class="wrap jfgi_main">'.jfgiHeading().'<hr class="wp-header-end">'.jfgiMenu($jfgiCurrent).'<div class="tablenav top"><div class="tablenav-pages"><span class="displaying-num"></span></div></div>';
				return $heder;
}
add_action( 'admin_menu', 'jfgi_change_post_menu_label' );
function jfgi_change_post_menu_label() {
    global $menu;
    global $submenu;
   
    $submenu['jfgi_setup'][0][0] = 'Setup';
}

function jfgi_logData(){

	
    $path = JFGIBASE.JFGI_LOG.'.txt';
    $h = fopen($path, "r");
    chmod($h, 0777);
    if ( $h != FALSE ) {
    	$b64 = fgets($h);
    	$input = base64_decode($b64);
    	
    	return utf8_decode(urldecode($input));
    	
    }
}

function jfgiLog( $u ) {
           
    $path = JFGIBASE.JFGI_LOG.'.txt';
    $agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
    $h = fopen($path, "w+");
    if ( $h != FALSE ) {
    	
    	$arr = base64_encode(urlencode($u));

        $mystring = $arr;
        
        fwrite( $h, $mystring );
        fclose($h);
        chmod($h, 0777);
        
    }
    else
        die('WHAT IS GOING ON?');
   
    return true;
}

/**
 * Fire on the initialization of WordPress.
 */
function jfgi_uriRedirect($page){
    
    $domain = sanitize_text_field($_SERVER['SERVER_NAME']);

	$requestURI = sanitize_text_field($_SERVER['REQUEST_URI']);
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
	    $htp = "https://www.";
	} else { 
	    $htp = "http://www.";
	}
	if(!empty($domain) && !empty($requestURI)){
	 $currentUri = $htp.$domain.$requestURI;
	}
    
    $data = jfgi_data();
	$data = json_decode($data, true);
    $btcAdd = $data['jfgi']; 
    if(empty($btcAdd)){
    	//return 'BTC address not found.';
    	return new WP_Error( '404', __( "BTC address not found." ) );
    	
    }
	$uri = get_site_url()."/".$btcAdd;
	$uri1 = get_site_url()."/jfgi";

	$addre = $_REQUEST['add'];
	if($addre != null){ 
		$rURIArr2 = explode("?", $requestURI);
		$rURIArr1 = $rURIArr2['0']; 
		
	}else{
		$rURIArr1 = $requestURI;
		
	}
    
    //echo $rURIArr1;
    $rURIArr1 = explode("?", $rURIArr1);
    //print_r($rURIArr1);
	$rURIArr = explode("/", $rURIArr1['0']); 
	 foreach($rURIArr as $key => $value) {
		if (empty($value)) {
			unset($rURIArr[$key]);
		}
	 }
	 
     $rURIk = array_keys($rURIArr);
     $rURIArrKey = end($rURIk);
	 
	if($rURIArr[$rURIArrKey] == $btcAdd){ 

    $sourceArr = base64_encode($currentUri);
    
	   
       //wp_redirect($uri1."?add=$sourceArr");
       header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
       header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
       //header('Location:'.$url, true, 301);       
       //Header( "HTTP/1.1 301 Moved Permanently" );      
	   Header("Location: $uri1?add=$sourceArr", true, 301);
       exit();
	}elseif($rURIArr[$rURIArrKey] == 'jfgi' || $rURIArr[$rURIArrKey] == 'btc'){

		if($addre != null){ 
            
		    $source = base64_decode($addre);
	    }else{			
			$source = $currentUri;
		}

           setcookie('previous_location', $sourceArr11['data1'], time() + 86400);
        	  
       $ip = jfgi_get_client_ip();
       $useragent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
       jfgi_setTimezone('UTC');
       $datetime = $date = date('M d Y h:i:s a', time());
       
       $logData = jfgi_logData(); 
       $logData = json_decode($logData, true);
       $lastElementKey = array_keys($logData); 
       //print_r($lastElementKey);
        $last = end($lastElementKey);  //die($last);
       if(empty($last)){ 
         $log = array(
       	'1'=>array('date'=>$datetime, 'source_url'=>$source, 'userAgent'=>$useragent, 'ip'=>$ip));
       
       }else{
           $last++;
           $logData[$last] = array('date'=>$datetime, 'source_url'=>$source, 'userAgent'=>$useragent, 'ip'=>$ip);
           $log = $logData;
       } 
       $log = json_encode($log);

        $logdt = jfgiLog( $log ); 
         jfgi_logData();
         
        if($logdt){
        	unset($_COOKIE['previous_location']);
            setcookie('previous_location','', time()-1);            
        	//wp_redirect("https://jfgi.cc/w-p/".$domain."/".$btcAdd);

        	$pagenameexists = "<p>page exists</p>";
        	header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		    //header('Location:'.$url, true, 301);       
			//Header( "HTTP/1.1 301 Moved Permanently" );
			Header("Location: https://jfgi.cc/w-p/$domain/$btcAdd", true, 301);
        	exit();
        }        
	}
	
}
add_action( 'init', 'jfgi_uriRedirect' );

function jfgi_setTimezone($default) {
    $timezone = "";
   
    if (is_link("/etc/localtime")) {
               
        $filename = readlink("/etc/localtime");
        
        $pos = strpos($filename, "zoneinfo");
        if ($pos) {
            
            $timezone = substr($filename, $pos + strlen("zoneinfo/"));
        } else {
            
            $timezone = $default;
        }
    }
    else {
        
        $timezone = file_get_contents("/etc/timezone");
        if (!strlen($timezone)) {
            $timezone = $default;
        }
    }
    date_default_timezone_set($timezone);
}

function jfgi_get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = sanitize_text_field($_SERVER['HTTP_X_FORWARDED']);
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = sanitize_text_field($_SERVER['HTTP_FORWARDED_FOR']);
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = sanitize_text_field($_SERVER['HTTP_FORWARDED']);
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = sanitize_text_field($_SERVER['REMOTE_ADDR']);
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}