<?php get_header(); ?>

		<div id="primary">
			<div id="content">
				
				<?php
					if(isset($wp_taxonomies)) {
						$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
						if($term) {
							echo '<div class="intro">BLAH'.$term->name.'</div>';
						}
					}
					
					echo term_description( $term_id, 'hotel_category' );
				?>
				
				<div id="clientsGrid">
					<ul id="currentClients">
						<?php 
						
					// Term loop
					$terms = get_the_terms( $post->ID, 'hotel_category' );
						
					if ( $terms && ! is_wp_error( $terms ) ) : 

						$hotel_cats = array();

					foreach ( $terms as $term ) {
						$hotel_cats[] = $term->name;
					} 
					
					endif;
					//var_dump(array_shift($terms));
						
					//$hotel_cat = join( ", ", $hotel_cats );
												
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
						    
						    // echo '<pre>'.print_r( $hotels_logos, true ).'</pre>';
						    
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