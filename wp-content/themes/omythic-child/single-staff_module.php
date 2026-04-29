<?php get_header(); ?>

<div class="single-staff">
	<div class="container">

		<?php while( have_posts() ): the_post(); ?>

			<?php
				$staff_photo      = get_field('primary_photo');
				$staff_title      = get_field('title');
				$staff_experience = get_field('experience');
				$staff_phone      = get_field('phone_number');
				$staff_email      = get_field('email_address');
			?>

			<article <?php post_class( 'staff-profile' ); ?> id="staff-<?php the_ID(); ?>">

				<div class="staff-profile-grid">

					<aside class="staff-profile-sidebar">
						<?php if( $staff_photo ): ?>
							<figure class="staff-profile-photo">
								<img src="<?php echo esc_url( $staff_photo['sizes']['large'] ); ?>" alt="<?php the_title_attribute(); ?>">
							</figure>
						<?php endif; ?>

						<?php if( $staff_phone || $staff_email || have_rows('social_media') ): ?>
							<div class="staff-contact-card">
								<h3 class="staff-contact-title">Get In Touch</h3>

								<?php if( $staff_phone ): ?>
									<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $staff_phone ) ); ?>" class="staff-contact-item">
										<span class="staff-contact-icon"><i class="far fa-phone-alt" aria-hidden="true"></i></span>
										<span class="staff-contact-text"><?php echo esc_html( $staff_phone ); ?></span>
									</a>
								<?php endif; ?>

								<?php if( $staff_email ): ?>
									<a href="mailto:<?php echo esc_attr( $staff_email ); ?>" class="staff-contact-item">
										<span class="staff-contact-icon"><i class="far fa-envelope" aria-hidden="true"></i></span>
										<span class="staff-contact-text"><?php echo esc_html( $staff_email ); ?></span>
									</a>
								<?php endif; ?>

								<?php if( have_rows('social_media') ): ?>
									<ul class="staff-contact-social">
										<?php while( have_rows('social_media') ): the_row(); ?>
											<?php
												$site_name  = get_sub_field('site')['label'];
												$site_class = get_sub_field('site')['value'];
												$site_url   = get_sub_field('url');
												if( ! $site_url ) continue;
											?>
											<li>
												<a title="<?php echo esc_attr( $site_name ); ?>" href="<?php echo esc_url( $site_url ); ?>" target="_blank" rel="noopener">
													<i class="fab fa-<?php echo esc_attr( $site_class ); ?>" aria-hidden="true"></i>
													<span class="screen-reader-text"><?php echo esc_html( $site_name ); ?></span>
												</a>
											</li>
										<?php endwhile; ?>
									</ul>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</aside>

					<div class="staff-profile-main">
						<header class="staff-profile-header">
							<p class="staff-profile-eyebrow"><?php echo esc_html( get_post_type_object('staff_module')->labels->singular_name ); ?></p>
							<h1><?php the_title(); ?></h1>

							<?php if( $staff_title || $staff_experience ): ?>
								<ul class="staff-profile-meta">
									<?php if( $staff_title ): ?>
										<li><?php echo esc_html( $staff_title ); ?></li>
									<?php endif; ?>
									<?php if( $staff_experience ): ?>
										<li><?php echo esc_html( $staff_experience ); ?></li>
									<?php endif; ?>
								</ul>
							<?php endif; ?>
						</header>

						<div class="staff-profile-body wysiwyg">
							<?php the_content(); ?>
						</div>
					</div>

				</div>

				<footer class="single-pagination">
					<ul>
						<li class="item prev">
							<?php if( get_previous_post() ): $prev = get_previous_post(); ?>
								<a title="<?php echo esc_attr( $prev->post_title ); ?>" href="<?php echo get_permalink( $prev->ID ); ?>">
									<i class="far fa-angle-left"></i> <span class="text">Previous</span>
								</a>
							<?php endif; ?>
						</li>
						<li class="item all">
							<a title="All Attorneys" href="<?php echo get_post_type_archive_link( 'staff_module' ); ?>">Back to All</a>
						</li>
						<li class="item next">
							<?php if( get_next_post() ): $next = get_next_post(); ?>
								<a title="<?php echo esc_attr( $next->post_title ); ?>" href="<?php echo get_permalink( $next->ID ); ?>">
									<span class="text">Next</span> <i class="far fa-angle-right"></i>
								</a>
							<?php endif; ?>
						</li>
					</ul>
				</footer>

			</article>

		<?php endwhile; ?>

	</div>
</div>

<?php get_footer();
