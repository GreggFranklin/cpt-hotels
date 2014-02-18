<?php get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
				
				<?php if (have_posts()) : while (have_posts()) : the_post(); 
				
				$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'hotel_feature');
				$website 		= get_field('website_link');
				$logo 			= get_field('logo');
				
				// Term loop
					$terms = get_the_terms( $post->ID, 'hotel_category' );
						
					if ( $terms && ! is_wp_error( $terms ) ) : 

						$hotel_cats = array();

					foreach ( $terms as $term ) {
						$hotel_cats[] = $term->name;
					}
					//var_dump(array_shift($terms));
						
					$hotel_cat = join( ", ", $hotel_cats );
				?>

				<div class="intro"><?php echo $hotel_cat; ?></div>
				
				<?php endif; ?>
								
				<div class="clientDetail">
					<div class="clientImage">
						<a href="<?php the_permalink();?>"><img src="<?php echo $featured_image[0]; ?>" alt="<?php the_title(); ?>" width="<?php echo $featured_image[1]; ?>" height="<?php echo $featured_image[2]; ?>" /></a>
					</div>
		
					<div class="clientInfo rightInfo">
						<h2><?php the_title(); ?></h2>
						<div id="post-<?php echo $post->ID ?>" class="post">
							<div class="entry indented">
        						<?php the_content(); ?>
        						<?php if( !$website == '' ) { ?>
									<p><a class="btnMore" target="_blank" rel="nofollow" href="<?php echo $website; ?>">visit website</a></p>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				
				<?php endwhile; endif; ?>
				
				<div id="clientsGrid">
					<ul id="currentClients">
						<?php 
						// Query
						if (is_array($hotel_cats)) {
						    $args = array( 'tax_query' => array( 
						    		array(
						    			'taxonomy'=> 'hotel_category', 
						    			'terms'=> array(array_shift($terms)->term_id), 
						    			'field'=>'term_id' 
						    		) 
						    	),
						    	'posts_per_page' => -1,
						    	'post_type' => 'hotel',
						    );
						    
						    $hotels_logos = get_posts( $args );
						    
						    //echo '<pre>'.print_r( $hotels_logos, true ).'</pre>';
						    
						    foreach( $hotels_logos as $hotels_logo ) {
						    	//$logo 	= get_field('logo',$hotels_logo->ID);
						    	
						    	$attachment_id = get_field('logo',$hotels_logo->ID);
								$size = "logo";
								$image = wp_get_attachment_image_src( $attachment_id, $size );
						    	
						    	$link 	= get_permalink($hotels_logo->ID);
						    	$title 	= get_the_title($hotels_logo->ID);
						    	echo '<li data-type="Current-Client"><a class="clientLink" href="'.$link.'"><img class="clientLogo" title="'.$title.'" alt="'.$title.'" src="'.$image[0] .'" width="'.$image[1].'" height="'.$image[2].'" /><div class="pinkOverlay" style="opacity: 0;"></div></a></li>';
						    }
						}
						
						?>
					</ul>
				</div>
				
				

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>