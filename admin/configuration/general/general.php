<?php
add_action('wp_ajax_ml_configuration_general', 'ml_configuration_general_callback');
add_action('wp_ajax_ml_configuration_connection_test', 'ml_configuration_connection_test_callback');


function ml_configuration_general_callback()
{
    global $ml_pb_use_ssl;
	global $ml_automatic_image_resize;
	global $ml_html_banners_enable;

	global $ml_article_list_enable_dates;
	global $ml_article_list_enable_featured_images;
	
	global $ml_hierarchical_pages_enabled;
	
	global $ml_include_pages_in_search;
	
    if(isset($_POST['ml_pb_use_ssl']))
	{
		$ml_pb_use_ssl = $_POST['ml_pb_use_ssl'] == "true";
		ml_set_generic_option("ml_pb_use_ssl",
							   $ml_pb_use_ssl);
	}
    
	if(isset($_POST['ml_automatic_image_resize']))
	{
		$ml_automatic_image_resize = $_POST['ml_automatic_image_resize'] == "true";
		ml_set_generic_option("ml_automatic_image_resize",
							   $ml_automatic_image_resize);
	}

	//html banners
	if(isset($_POST['ml_html_banners_enable']))
	{
		$ml_html_banners_enable = $_POST['ml_html_banners_enable'] == "true";
		ml_set_generic_option("ml_html_banners_enable",
							   $ml_html_banners_enable);
	}
	
	if(isset($_POST['ml_article_list_enable_dates']))
	{
		$ml_article_list_enable_dates = $_POST['ml_article_list_enable_dates'] == "true";
		ml_set_generic_option("ml_article_list_enable_dates",
								$ml_article_list_enable_dates);
	}
	
	if(isset($_POST['ml_article_list_enable_featured_images']))
	{
		$ml_article_list_enable_featured_images = $_POST['ml_article_list_enable_featured_images'] == "true";
		ml_set_generic_option("ml_article_list_enable_featured_images",
								$ml_article_list_enable_featured_images);
	}

	if(isset($_POST['ml_hierarchical_pages_enabled']))
	{
		$ml_hierarchical_pages_enabled = $_POST['ml_hierarchical_pages_enabled'] == "true";
		ml_set_generic_option("ml_hierarchical_pages_enabled", $ml_hierarchical_pages_enabled);
	}
	
	if(isset($_POST['ml_include_pages_in_search']))
	{
		$ml_include_pages_in_search = $_POST['ml_include_pages_in_search'] == "true";
		ml_set_generic_option("ml_include_pages_in_search", $ml_include_pages_in_search);
	}

	//right to left text
	if(isset($_POST['ml_rtl_text_enable']))
	{
		if($_POST['ml_rtl_text_enable'] == 'true') {
			ml_set_generic_option("ml_rtl_text_enable","true");			
		}
		else {
			delete_option("ml_rtl_text_enable");
		}
	}

	ml_configuration_general();
	die();
}

function ml_configuration_connection_test_callback(){
	global $ml_server_host;


	$request = new WP_Http;
	$url = "$ml_server_host";
	
	$result = $request->request($url,
		array('method' => 'GET', 'timeout' => 10) );

	if($result)
	{
		print_r($result);		
	}
	else
	{
		echo "Request returned NULL";
	}
	die();
}


function ml_configuration_general_ajax_load()
{
	?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		var data = {
			action: 'ml_configuration_general'
		};
		jQuery("#ml_configuration_general").css("display","none");
			
		$.post(ajaxurl, data, function(response) {
			//saving the result and reloading the div
			jQuery("#ml_configuration_general").html(response).fadeIn().slideDown();
		});			
			
	});
	</script>
	<?php
}

function ml_configuration_general()
{

	ml_configuration_general_div();

	?>

	
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		jQuery("#ml_configuration_general_submit").click(function(){
			
			jQuery("#ml_configuration_general_submit").val("<?php _e('Saving...'); ?>");
			jQuery("#ml_configuration_general_submit").attr("disabled", true);
			jQuery("#ml_configuration_general").css("opacity","0.5");
			
			var data = {
				action: 'ml_configuration_general',
                ml_pb_use_ssl:  jQuery("#ml_pb_use_ssl_active").is(":checked"),
				ml_automatic_image_resize:  jQuery("#ml_automatic_image_resize_active").is(":checked"),
				ml_html_banners_enable: jQuery("#ml_html_banners_enable").is(":checked"),
				ml_rtl_text_enable: jQuery("#ml_rtl_text_enable").is(":checked"),
				ml_article_list_enable_dates: jQuery("#ml_article_list_enable_dates").is(":checked"),
				ml_article_list_enable_featured_images: jQuery("#ml_article_list_enable_featured_images").is(":checked"),
				ml_hierarchical_pages_enabled: jQuery("#ml_hierarchical_pages_enabled").is(":checked"),
				ml_include_pages_in_search: jQuery("#ml_include_pages_in_search").is(":checked"),
			};
			
			
			$.post(ajaxurl, data, function(response) {
				//saving the result and reloading the div
				jQuery("#ml_configuration_general").html(response).fadeIn();
				jQuery("#ml_configuration_general_submit").val("<?php _e('Apply'); ?>");
				jQuery("#ml_configuration_general_submit").attr("disabled", false);
				jQuery("#ml_configuration_general").css("opacity","1.0");

			});			
			
		});


		//connection test
		jQuery("#ml_configuration_connection_test_submit").click(function(){
			var submit = jQuery(this);
			submit.val("<?php _e('Connecting to Mobiloud...'); ?>");
			submit.attr("disabled", true);
			submit.attr("opacity", 0.5);


			var data = {
				action: 'ml_configuration_connection_test'
			};
			
			$.post(ajaxurl, data, function(response) {
				//saving the result and reloading the div
				jQuery("#ml_configuration_connection_test_response").html(response).fadeIn();
				submit.val("<?php _e('Test connection to Mobiloud'); ?>");
				submit.attr("disabled", false);
				submit.attr("opacity", 1.0);
			});			
	
		});
	});
	</script>
	
	
	<?php
}



function ml_configuration_general_div()
{
    global $ml_pb_use_ssl;
	global $ml_automatic_image_resize;
	global $ml_html_banners_enable;
	global $ml_article_list_enable_dates;
	global $ml_article_list_enable_featured_images;
	global $ml_hierarchical_pages_enabled;
	global $ml_include_pages_in_search;
    $ml_pb_use_ssl = get_option('ml_pb_use_ssl');
	$ml_automatic_image_resize = get_option('ml_automatic_image_resize');
	$ml_html_banners_enable = get_option('ml_html_banners_enable');
	$ml_rtl_text_enable = get_option('ml_rtl_text_enable');
	$ml_article_list_enable_dates = get_option('ml_article_list_enable_dates',true);
	$ml_article_list_enable_featured_images = get_option('ml_article_list_enable_featured_images',true);
	$ml_hierarchical_pages_enabled = get_option('ml_hierarchical_pages_enabled',true);
	$ml_include_pages_in_search = get_option('ml_include_pages_in_search',false);
	?>
	<h3 style="font-family:arial;font-size:20px;font-weight:normal;padding:10px;">General app settings</h3>
	
	<h2 style="font-size:20px;font-weight:normal;padding:10px;">
		<input id="ml_automatic_image_resize_active" type="checkbox"
			<?php
				if($ml_automatic_image_resize)
				{
					echo " checked ";
				}
			?>
			/> Automatically resize image thumbnails
	</h2>

	<h2 style="font-size:20px;font-weight:normal;padding:10px;">
	<input id="ml_html_banners_enable" type="checkbox"
		<?php
			if($ml_html_banners_enable)
			{
				echo " checked ";
			}
		?>
		/> Enable support for HTML banners
	</h2>


	<h2 style="font-size:20px;font-weight:normal;padding:10px;">
	<input id="ml_rtl_text_enable" type="checkbox"
		<?php
			if($ml_rtl_text_enable)
			{
				echo " checked ";
			}
		?>
		/> Enable Right-To-Left for Arabic and Hebrew
	</h2>
   
	<h2 style="font-size:20px;font-weight:normal;padding:10px;">
	<input id="ml_article_list_enable_dates" type="checkbox"
		<?php
			if($ml_article_list_enable_dates)
			{
				echo " checked ";
			}
		?>
		/> Show dates on article list
	</h2>
    
    
	<h2 style="font-size:20px;font-weight:normal;padding:10px;">
	<input id="ml_article_list_enable_featured_images" type="checkbox"
		<?php
			if($ml_article_list_enable_featured_images)
			{
				echo " checked ";
			}
		?>
		/> Show featured images in article list
	</h2>

	<h2 style="font-size:20px;font-weight:normal;padding:10px;">
	<input id="ml_hierarchical_pages_enabled" type="checkbox"
		<?php
			if($ml_hierarchical_pages_enabled)
			{
				echo " checked ";
			}
		?>
		/> Enable users to navigate page hierarchy
	</h2>
    
    <h2 style="font-size:20px;font-weight:normal;padding:10px;">
	<input id="ml_include_pages_in_search" type="checkbox"
		<?php
			if($ml_include_pages_in_search)
			{
				echo " checked ";
			}
		?>
		/> Include pages in search results (in addition to posts)
	</h2>
 
    <h2 style="font-size:20px;font-weight:normal;padding:10px;">
	<input id="ml_pb_use_ssl_active" type="checkbox"
		<?php
			if($ml_pb_use_ssl)
			{
				echo " checked ";
			}
		?>
		/> Enable Push Notification Requests Over SSL
	</h2>
    
	<div style="margin-right:20px;">
		<p class="submit" align="right">
			<input type="submit" id="ml_configuration_general_submit" 
											   value="<?php _e('Apply'); ?>" class="button button-primary button-large"/>
		</p>
	</div>
	
	<pre id="ml_configuration_connection_test_response" style="display:none;">
	</pre>
	<?php
}
?>