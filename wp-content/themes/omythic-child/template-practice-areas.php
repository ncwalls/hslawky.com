<?php
/*
Template Name: Practice Areas Overview
*/

get_header(); ?>

<?php while( have_posts() ): the_post(); ?>

	<?php
		// Top-level practice areas are simply the child pages of this overview page,
		// matching the hand-picked grid used in the home page's Practice Areas section.
		$areas = get_pages(array(
			'child_of'    => get_the_ID(),
			'parent'      => get_the_ID(),
			'sort_column' => 'menu_order,post_title',
			'sort_order'  => 'ASC',
		));
	?>

	<section class="home-section home-practice-areas practice-areas-overview">
		<div class="container">
			<div class="section-header">
				<h1 class="section-title"><?php the_title(); ?></h1>
				<?php if( get_the_content() ): ?>
					<div class="wysiwyg section-intro"><?php the_content(); ?></div>
				<?php endif; ?>
			</div>
			<?php if( $areas ): ?>
			<ul class="practice-areas-grid">
				<?php foreach( $areas as $area ): ?>
					<?php
						$pa_id       = $area->ID;
						$pa_icon     = get_field('icon', $pa_id);
						$pa_excerpt  = get_the_excerpt($pa_id);
						$pa_title    = get_the_title($pa_id);
						$pa_children = get_pages(array(
							'child_of'    => $pa_id,
							'parent'      => $pa_id,
							'sort_column' => 'menu_order,post_title',
							'sort_order'  => 'ASC',
						));
						$pa_limit    = 3;
					?>
					<li class="practice-area-card">
						<div class="practice-area-card-inner">
							<?php if($pa_icon): ?>
								<div class="card-icon"><?php echo $pa_icon; ?></div>
							<?php endif; ?>
							<h3 class="card-title"><?php echo $pa_title; ?></h3>
							<?php if($pa_excerpt): ?>
								<p class="card-excerpt"><?php echo $pa_excerpt; ?></p>
							<?php endif; ?>
							<?php if($pa_children): ?>
								<ul class="card-types">
									<?php foreach($pa_children as $i => $child): ?>
										<li class="card-type<?php echo $i >= $pa_limit ? ' is-overflow' : ''; ?>">
											<a href="<?php echo get_permalink($child->ID); ?>"><?php echo get_the_title($child->ID); ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if(count($pa_children) > $pa_limit): ?>
								<button type="button" class="card-more" aria-expanded="false">
									<span class="card-more-show">Show More</span>
									<span class="card-more-hide">Show Less</span>
									<i class="fas fa-chevron-down"></i>
								</button>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</section>

<?php endwhile; ?>

<?php get_footer();
