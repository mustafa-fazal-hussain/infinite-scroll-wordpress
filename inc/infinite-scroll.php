<?php
new Infinite_Scoll_Class();
class Infinite_Scoll_Class
{
   
    private $addScript = true;
    public function __construct()
    {	
		/* [infinite-scroll category="news"] */
        add_shortcode('infinite-scroll', array($this, 'shortcode_content'));
		add_action('wp_ajax_infinite_scroll', array($this, 'loading_scroll_func')); //allow on front-end
		add_action('wp_ajax_nopriv_infinite_scroll', array($this, 'loading_scroll_func'));		
		add_action('wp_footer', array($this, 'add_shortcode_scripts'));
		
    }
    public function shortcode_content( $attr, $content )
    {
		extract(shortcode_atts(array('category' => 'news' , 'numofpost' => '6'), $attr));
		
	
        $this->addScript 	= true;
		$url        		= admin_url( 'admin-ajax.php' );
		$nonce_files 		= wp_nonce_field( 'protect_content', 'scroll_nonce_field' );
?>
		
				<div id="tb-ajax-content">
					<?php
						
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						
						$args 	= array(
							'category_name' 	=> 		$category,
							'post_status' 		=> 		'publish',
							'posts_per_page'	=> 		$numofpost,
							'orderby'      		=> 		'post_date',
							'paged' 			=> 		$paged,
						);
						

						query_posts($args);
						
						$count = 1;
						
						if ( have_posts() ) : while ( have_posts() ) : the_post(); 
						
						$image 					= 		wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()),'thumbnail');
						$get_category 			= 		get_the_category(get_the_ID());
						$this_cat	 			= 		$get_category[0]->term_id;
						$catname	 			= 		$get_category[0]->slug;
						$category_link 			= 		get_category_link( $this_cat );
						$excerpt 				= 		get_the_excerpt();
	
					?>
						<article class="categoryData">
							<header>
								<a href="<?php echo  the_permalink(); ?>" title="<?php echo  the_title(); ?>">
								<?php echo the_title(); ?></a>
								<a href="<?php echo  the_permalink(); ?>" title="<?php echo  the_title(); ?>" >
									<img  src="<?php echo $image; ?>" class="img-responsive" alt="<?php  the_title() ?>">
								</a>
								<p class="date-class-small"><?php echo get_the_date('l, F j, Y'); ?> | <?php echo get_the_author() ;?></p>
							</header>
							<section>
								<p class="excerpt"><?php echo $excerpt; ?></p>
								<a href="<?php echo the_permalink(); ?>" class="small-readmore btn hvr-shutter-out-horizontal">
									Continue Reading <i class="fa fa-caret-right" aria-hidden="true"></i><
								/a>
							</section>
						</article>
						<div class="article-separator"></div>
						
					<?php
					
						$count = $count + 1; 
						endwhile; 
						endif; 
						wp_reset_query();
					?>
				</div>
				<div class="post-clear"></div>
				<div class="button-div" >
					<a class="button e" href="javascript:void(0);" id="infinitBtn"><span><i class="fa fa-spinner fa-pulse" aria-hidden="true"></i> Loading..</span></a>
				</div>
				<input type="hidden" value="<?php echo $category; ?>" id="category_name_val" />
				
<?php
    }
	
	public  function loading_scroll_func() 
	{
		 // Verify nonce
		 
		$nonce 	= $_POST['afp_nonce'];   
		if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'afp_nonce' ) )
			die ( 'Permission denied');
		$paged 			= $_POST['page_no'];
		$cat 			= $_POST['val'];
		
		$args 	= array(
			'category_name'		 	=> 		$cat,
			'post_status' 			=> 		'publish',
			'posts_per_page' 		=> 		$numofpost,
			'orderby'      			=> 		'post_date',
			'paged'					=> 		$paged,
		);
					
		query_posts($args);
		$count 	= 1;
		if (have_posts()) : while ( have_posts() ) : the_post(); 
		$image 					= 		wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()),'thumbnail');
		$get_category 			= 		get_the_category(get_the_ID());
		$this_cat	 			= 		$get_category[0]->term_id;
		$catname	 			= 		$get_category[0]->slug;
		$category_link 			= 		get_category_link( $this_cat );
		$excerpt 				= 		get_the_excerpt();

		
?>	

		<article class="categoryData">
			<header>
				<a href="<?php echo  the_permalink(); ?>" title="<?php echo  the_title(); ?>">
				<?php echo the_title(); ?></a>
				<a href="<?php echo  the_permalink(); ?>" title="<?php echo  the_title(); ?>" >
					<img  src="<?php echo $image; ?>" class="img-responsive" alt="<?php  the_title() ?>">
				</a>
				<p class="date-class-small"><?php echo get_the_date('l, F j, Y'); ?> | <?php echo get_the_author() ;?></p>
			</header>
			<section>
				<p class="excerpt"><?php echo $excerpt; ?></p>
				<a href="<?php echo the_permalink(); ?>" class="small-readmore btn hvr-shutter-out-horizontal">
					Continue Reading <i class="fa fa-caret-right" aria-hidden="true"></i><
				/a>
			</section>
		</article>
		<div class="article-separator"></div>
					
				
<?php 
		$count = $count + 1; 
		endwhile; 
		endif;
		wp_reset_query();
		exit; 
						
}
	
    public function add_shortcode_scripts()
    {
        if(!$this->addScript)
        {
            return false;
        }
		wp_register_script('afp_script', get_stylesheet_directory_uri() . '/js/infinite-scroll.js', false, null, false);
		wp_enqueue_script('afp_script');
		wp_localize_script( 'afp_script', 'afp_vars', array(
			'afp_nonce' 	=> wp_create_nonce( 'afp_nonce' ), 
			'afp_ajax_url' 	=> admin_url( 'admin-ajax.php' ),
		));	
    }
}
