<?php get_header(); ?>

<div class="staff-archive">
	<div class="container">

		<div class="archive-header">
			<p class="archive-eyebrow">Hance &amp; Srinivasan</p>
			<h1>
				<?php
					if( get_field('staff_module_archive_title', 'option') ){
						echo get_field('staff_module_archive_title', 'option');
					} else {
						echo get_post_type_object('staff_module')->labels->name;
					}
				?>
			</h1>
			<?php if( get_field('staff_module_intro_copy', 'option') ): ?>
				<div class="archive-intro wysiwyg">
					<?php echo get_field('staff_module_intro_copy', 'option'); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="staff-archive-list">
			<?php while( have_posts() ): the_post(); ?>
				<?php
					$staff_photo      = get_field('primary_photo');
					$staff_title      = get_field('title');
					$staff_experience = get_field('experience');
				?>
				<article <?php post_class( 'staff-card' ); ?> id="staff-<?php the_ID(); ?>">
					<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" class="staff-card-link">

						<figure class="staff-card-photo">
							<div class="image">
								<?php if( $staff_photo ): ?>
									<img src="<?php echo esc_url( $staff_photo['sizes']['medium_large'] ?? $staff_photo['sizes']['medium'] ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
								<?php endif; ?>
							</div>
						</figure>

						<div class="staff-card-content">
							<h2 class="staff-card-name"><?php the_title(); ?></h2>

							<?php if( $staff_title || $staff_experience ): ?>
								<ul class="staff-card-meta">
									<?php if( $staff_title ): ?>
										<li><?php echo esc_html( $staff_title ); ?></li>
									<?php endif; ?>
									<?php if( $staff_experience ): ?>
										<li><?php echo esc_html( $staff_experience ); ?></li>
									<?php endif; ?>
								</ul>
							<?php endif; ?>

							<?php if( get_the_excerpt() ): ?>
								<div class="staff-card-excerpt">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>

							<span class="button outline gold">View Profile</span>
						</div>

					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<?php if( paginate_links() ): ?>
			<footer class="archive-pagination">
				<div class="pagination-links">
					<?php
						echo paginate_links( array(
							'prev_text'          => '<i class="fal fa-chevron-left"></i><span class="screen-reader-text">Previous Page</span>',
							'next_text'          => '<i class="fal fa-chevron-right"></i><span class="screen-reader-text">Next Page</span>',
							'type'               => 'plain',
							'before_page_number' => '<span class="screen-reader-text">Page </span>'
						) );
					?>
				</div>
			</footer>
		<?php endif; ?>

	</div>
</div>

<?php get_footer();
