<?php
/*
Plugin Name: Add Categories Post Footer
Plugin URI: http://assertionit.com/wordpress/
Description: This plugin will show Add Categories Post Footer
Author: Nikhil Vaghela
Version:2.2.2
Author URI: http://assertionit.com/
*/ 

// Admin Panel

class addnewcategoriespostfooter{
	
	private $name = 'Add New Categories Post Foooter';

	public function __construct() {	
	add_action('admin_menu',array($this, 'acpf_add_pages') );
	add_action('the_content', array($this, 'acpf_add_post_footer'));
	add_action('admin_enqueue_scripts',array($this,'acpf_loadcss'));
	register_deactivation_hook( __FILE__, array($this,'acpf_un_set_cat_post_options' ));
	register_activation_hook(__FILE__,array($this,'acpf_set_cat_post_options'));
	}
	
	public function acpf_loadcss()
	{	
	wp_enqueue_style('acpf_css', plugins_url(). '/add-categories-post-footer/css/style.css');
	}	
	public function acpf_add_pages() {		
	add_options_page('Post Footer Options', 'Categories Post Footer', 9, __FILE__, array($this,'acpf_options_page'));
	}	
	public function apf_show_info_msg($msg) {
	echo '<div id="message" class="updated fade"><p>' . $msg . '</p></div>';
	}		
	public function acpf_un_set_cat_post_options()
	{
	 delete_option('add_cat_post_footer_opts');
	}	
	public function acpf_set_cat_post_options()
	{
	add_option('add_cat_post_footer_opts','');
	$this->default_set_dat();	
	}
    public function default_set_dat(){
		$options['default'] = array(		
					"acpf_optional_txt" => 'this is default footer data',	
				);
		
		update_option("add_cat_post_footer_opts", $options);		
		}
	 public function acpf_options_page() {		
		if(isset($_REQUEST['insert']))
		{		
		$this->add_new_footer();	
			
		}
		 elseif(isset($_REQUEST['update']) )
		{
			$up_cat=$_REQUEST['update'];
			$this->update_cat_name($up_cat);
			
		}
		elseif(isset($_REQUEST['del']) )
		{	
		$del_cat_nam=$_REQUEST['del'];		
		$all_cat_nam=get_option("add_cat_post_footer_opts");		
		unset($all_cat_nam[$del_cat_nam]);
		update_option("add_cat_post_footer_opts", $all_cat_nam);
		
	$del_my_location= admin_url().'options-general.php?page=add-categories-post-footer/add_categories_post_footer.php';	
	?>
    <meta http-equiv="refresh" content="0; URL='<?php echo  $del_my_location; ?>'" />     
    <?php	
			
		}else{	
		
	?>
    
   <h2 >Add-Categories-Post-Footer :
      <a href="?page=add-categories-post-footer/add_categories_post_footer.php&insert=addnew" >Add New</a></h2>
   
    <div id="ln_admin">
    
      <table width="100%">
  	 <tr class="title">
     		<td width="20%">Categories Name</td>
  			 <td width="50%">Post Footer Description</td>
        	 <td width="15%">delete</td>
        	 <td width="15%">update</td>
     </tr>
    <?php
	 
     $get_cat_nm=get_option("add_cat_post_footer_opts");			
			foreach ($get_cat_nm as $cat_old_nam => $desc_info) {				
		?>		
     <tr class="title1">
     		<td><?php echo $cat_old_nam;?></td>
            <td style="padding:10px;">
			<?php   $desc1= $desc_info['acpf_optional_txt'];
			echo  $desc2=stripslashes($desc1);			
			?>            	
            </td>            
            <td>
            <?php if($cat_old_nam!='default' ) {?>
            <a href="?page=add-categories-post-footer/add_categories_post_footer.php&del=<?php echo $cat_old_nam;?>" >
            delete</a>
            <?php } else { echo "not delete";} ?>
            </td>  
            <td>            
            <a href="?page=add-categories-post-footer/add_categories_post_footer.php&update=<?php echo $cat_old_nam;?>
            ">Update</a>
            </td>   		
     </tr>
	<?php } ?>			
	</table>  
    
    </div>
    <?php	
		}

	}
	public function add_new_footer(){
		
		if (isset($_POST['info_add'])) {			
						
			$get_cat_nm=get_option("add_cat_post_footer_opts");			
			foreach ($get_cat_nm as $cat_old_nam => $desc_info) {
			$options[$cat_old_nam] = array(		
			"acpf_optional_txt" => $desc_info['acpf_optional_txt'],	
			);		
			}
	
			$cat_nam=$_POST["cat_nam"];			
			$options[$cat_nam] = array(		
					"acpf_optional_txt" => $_POST["acpf_optional_txt"],	
				);
						
		update_option("add_cat_post_footer_opts", $options);
		$this->apf_show_info_msg("Add-Categories-Post-Footer options saved.");
		
		$add_my_location= admin_url().'options-general.php?page=add-categories-post-footer/add_categories_post_footer.php';	
		?>
   		 <meta http-equiv="refresh" content="0; URL='<?php echo  $add_my_location; ?>'" />		
		<?php
		}
	?>

<form name="formapf" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
<p><label>Select Category :</label></p>
 <p>
    	<select name="cat_nam">    	
		<?php 
			$category_ids = get_all_category_ids();
			foreach($category_ids as $cat_id) {$cat_name = get_cat_name($cat_id);?>
        	<option><?php echo  $cat_name;?></option>
          <?php }?>  			
		</select>
      </p>
      <p><label>Footer Description</label></p>
      
	 <p>		
        <?php
			$args = array(
 		 					    'textarea_rows' => 10,
  							    'teeny' => false,
								'textarea_name'=>'acpf_optional_txt',
  							    'quicktags' => true,	
								'media_buttons' => true,	
							//	 'wpautop' => true,	
							//	 'tabindex' => '',
   							//	 'tabfocus_elements' => ':prev,:next', 
  							//	  'editor_css' => '', 
  							//	  'editor_class' => '', 							 
 						  //	    'dfw' => false,
  						  //     'tinymce' => false, 
							);
							
	
			wp_editor( ' ', 'editor1', $args );
		 ?>

	</p>

    <p class="submit"><input type="submit" name="info_add" value="Save Option" /></p>
</form>
<?php
	}
	public function update_cat_name($up_cat)
	{	
	if (isset($_POST['info_update'])) {		
		$cat_up_name=$_POST["update_cat_name"];	
		$cat_up_name1= trim($cat_up_name);
						
		 $update_cat_desc=get_option("add_cat_post_footer_opts");				  
		$update_cat_desc[$cat_up_name1]['acpf_optional_txt']=$_POST["up_acpf_optional_txt"];
		 update_option('add_cat_post_footer_opts',$update_cat_desc);
		 
		 $this->apf_show_info_msg("Add-Categories-Post-Footer options saved.");

		$up_my_location= admin_url().'options-general.php?page=add-categories-post-footer/add_categories_post_footer.php';
		
	?>
       <meta http-equiv="refresh" content="0; URL=<?php echo  $up_my_location; ?>" />       
    <?php		 
	}
	
	?>
<form name="formapf" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">

 	<p><label>Categorins Nama: <?php  echo $up_cat;?></label></p>     
    <p><input type="hidden" name="update_cat_name" value=" <?php echo $up_cat;?>" /></p>        
      <?php
      $all_cat_name=get_option("add_cat_post_footer_opts");
	  $old_cat_nam=$all_cat_name[$up_cat]['acpf_optional_txt'];
       		  $old_cat_nam1=  $old_cat_nam;
			  $old_cat_nam2=stripslashes($old_cat_nam1);			
		?>        
     <p>    
      <?php
			$args = array(
 		 					    'textarea_rows' => 10,
  							    'teeny' => false,
								'textarea_name'=>'up_acpf_optional_txt',
  							    'quicktags' => true,	
								'media_buttons' => true,	
							//	 'wpautop' => true,	
							//	 'tabindex' => '',
   							//	 'tabfocus_elements' => ':prev,:next', 
  							//	  'editor_css' => '', 
  							//	  'editor_class' => '', 							 
 						  //	    'dfw' => false,
  						  //     'tinymce' => false, 
							);
								
			wp_editor(  $old_cat_nam2, 'editor1', $args );
		 ?>
	</p>
     <p class="submit"><input type="submit" name="info_update" value="Update Footer" /></p>
</form>
<?php	
}
	public function acpf_add_post_footer($text) {
	global $post;
	
		$text .='<br><br>';
		// global $post;	
			$post_categories = wp_get_post_categories( $post->ID );
				$cont=0;
				foreach($post_categories as $c){
    			$cat = get_category( $c );
				$cat_name = $cat->name;
				$cont++;
    			//$text .=	$cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
				}
					
			if($cont==1){

				$cat_name;
				 $display_cat_desc=get_option("add_cat_post_footer_opts");
				$txt=$display_cat_desc[$cat_name]['acpf_optional_txt'];
				
					$text1=stripslashes($txt);
					$text2=stripslashes($text1);
					$text.=stripslashes($text2);
				
				}
				
			if($cont>=2){			
				$display_cat_desc=get_option("add_cat_post_footer_opts");
				$text .=$display_cat_desc['default']['acpf_optional_txt'];
				}	

	return $text;	
	}

}
$add_categories_post_footer = new addnewcategoriespostfooter();

?>
