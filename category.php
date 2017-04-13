<?php get_header(); ?>
<?php if ( have_posts() ) : ?>
<div class="row">
	<div class="container no-padding  margin">
		<div class="col-md-8 col-lg-8 col-sm-8 col-xs-8 no-padding category_loop">
			<?php 
				$cat 			= get_query_var('cat');
				$category 		= get_category ($cat);
				echo do_shortcode(' [infinite-scroll category="'.$category->slug.'" numofpost="6" ]');
			?>
		</div>
	</div>
<?php endif; ?>	
</div>
<?php get_footer(); ?>
