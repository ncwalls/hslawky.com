<?php
/*
 * Template Name: Contact
 */
get_header(); ?>

<?php
	$contact_options = get_field('contact', 'option');
?>

<div class="contact-page">
	<div class="container">

		<?php while( have_posts() ): the_post(); ?>

			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

				<header class="contact-header">
					<p class="contact-eyebrow">Hance &amp; Srinivasan</p>
					<h1><?php the_title(); ?></h1>
					<?php if( get_the_content() ): ?>
						<div class="contact-intro wysiwyg">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
				</header>

				<div class="contact-grid">

					<aside class="contact-info">

						<?php if( $contact_options['address'] ): ?>
							<?php
								$directions_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode( wp_strip_all_tags( $contact_options['address'] ) );
							?>
							<div class="contact-info-block">
								<span class="contact-info-label"><?php echo esc_html( $contact_options['address_title'] ?: 'Office' ); ?></span>
								<address class="contact-info-address"><?php echo $contact_options['address']; ?></address>
								<a class="contact-info-directions" href="<?php echo esc_url( $directions_url ); ?>" target="_blank" rel="noopener noreferrer">
									<i class="far fa-map-marker-alt" aria-hidden="true"></i> Get Directions
								</a>
							</div>
						<?php endif; ?>

						<?php if( $contact_options['phone'] ): ?>
							<div class="contact-info-block">
								<span class="contact-info-label">Call Us</span>
								<a class="contact-info-link" href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $contact_options['phone']) ); ?>">
									<span class="contact-info-icon"><i class="far fa-phone-alt" aria-hidden="true"></i></span>
									<span><?php echo esc_html( $contact_options['phone'] ); ?></span>
								</a>
								<?php if( $contact_options['phone_note'] ): ?>
									<p class="contact-info-note"><?php echo $contact_options['phone_note']; ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if( $contact_options['email'] ): ?>
							<div class="contact-info-block">
								<span class="contact-info-label">Email Us</span>
								<a class="contact-info-link" href="mailto:<?php echo antispambot( $contact_options['email'] ); ?>">
									<span class="contact-info-icon"><i class="far fa-envelope" aria-hidden="true"></i></span>
									<span><?php echo antispambot( $contact_options['email'] ); ?></span>
								</a>
							</div>
						<?php endif; ?>

						<?php if( $contact_options['hours'] ): ?>
							<div class="contact-info-block">
								<span class="contact-info-label">Office Hours</span>
								<div class="contact-info-link contact-info-hours">
									<span class="contact-info-icon"><i class="far fa-clock" aria-hidden="true"></i></span>
									<span><?php echo $contact_options['hours']; ?></span>
								</div>
								<?php if( $contact_options['hours_note'] ): ?>
									<p class="contact-info-note"><?php echo $contact_options['hours_note']; ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if( $contact_options['social_media_links'] ): ?>
							<div class="contact-info-social">
								<span class="contact-info-label">Follow Us</span>
								<ul>
									<?php foreach( $contact_options['social_media_links'] as $social ): ?>
										<?php if( $social['site'] && $social['url'] ): ?>
											<li>
												<a href="<?php echo esc_url( $social['url'] ); ?>" title="<?php echo esc_attr( $social['site']['label'] ); ?>" target="_blank" rel="noopener noreferrer">
													<i class="fab fa-<?php echo esc_attr( $social['site']['value'] ); ?>" aria-hidden="true"></i>
													<span class="screen-reader-text"><?php echo esc_html( $social['site']['label'] ); ?></span>
												</a>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

					</aside>

					<section class="contact-form">
						<h2 class="contact-form-title">Send Us a Message</h2>
						<?php echo do_shortcode('[gravityform id="1" title="false" description="false" ajax="true"]'); ?>
					</section>

				</div>

			</article>

		<?php endwhile; ?>

	</div>
</div>

<?php get_footer();
