		</div><!-- /.wrapper -->

		<?php
			$footer_options  = get_field('footer', 'option');
			$contact_options = get_field('contact', 'option');
			$footer_logo     = get_field('footer_logo', 'option');

			$footer_pa = get_posts(array(
				'post_type'  => 'practice_area',
				'numberposts'=> -1,
				'orderby'    => 'menu_order title',
				'order'      => 'ASC',
			));
		?>

		<footer class="site-footer" role="contentinfo">

			<div class="footer-main">
				<div class="container">

					<!-- Brand column -->
					<div class="footer-col footer-col-brand">
						<?php if($footer_logo): ?>
							<div class="footer-logo">
								<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
									<img src="<?php echo $footer_logo; ?>" alt="<?php bloginfo('name'); ?>">
								</a>
							</div>
						<?php endif; ?>
						<?php if($footer_options && $footer_options['title']): ?>
							<h3 class="footer-brand-title"><?php echo $footer_options['title']; ?></h3>
						<?php endif; ?>
						<?php if($footer_options && $footer_options['content']): ?>
							<div class="wysiwyg footer-brand-content"><?php echo $footer_options['content']; ?></div>
						<?php endif; ?>
						<?php if($footer_options && $footer_options['credentials']): ?>
							<div class="footer-credentials">
								<?php if($footer_options['credentials_title']): ?>
									<p class="footer-credentials-title"><?php echo $footer_options['credentials_title']; ?></p>
								<?php endif; ?>
								<ul class="footer-credentials-list">
									<?php foreach($footer_options['credentials'] as $cred): ?>
										<li>
											<a href="<?php echo $cred['url']; ?>" target="_blank">
												<?php echo $cred['title']; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

						<?php if($contact_options && $contact_options['social_media_links']): ?>
							<div class="footer-social">
								<h3 class="footer-social-title">Follow us</h3>
								<ul class="social-social-list">
									<?php foreach($contact_options['social_media_links'] as $social_site): ?>
										<?php 
											$social_site_name = $social_site['site']['label'];
											$social_site_class = $social_site['site']['value'];
											$social_site_url = $social_site['url'];
										?>
										<li>
											<a title="<?php echo $social_site_name ?>" href="<?php echo $social_site_url; ?>" target="_blank">
												<?php if(str_contains($social_site_class, 'twitter')): ?>
													<svg width="1200" height="1227" viewBox="0 0 1200 1227" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M714.163 519.284L1160.89 0H1055.03L667.137 450.887L357.328 0H0L468.492 681.821L0 1226.37H105.866L515.491 750.218L842.672 1226.37H1200L714.137 519.284H714.163ZM569.165 687.828L521.697 619.934L144.011 79.6944H306.615L611.412 515.685L658.88 583.579L1055.08 1150.3H892.476L569.165 687.854V687.828Z" />
													</svg>
												<?php else: ?>
													<span class="fab fa-<?php echo $social_site_class; ?>"></span>
												<?php endif; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>

					<!-- Contact column -->
					<?php if($contact_options): ?>
					<div class="footer-col footer-col-contact">
						<h3 class="footer-col-title">Contact Info</h3>
						<?php if($contact_options['address']): ?>
							<span class="footer-contact-label"><?php echo $contact_options['address_title'] ? $contact_options['address_title'] : 'Office'; ?></span>
							<address class="footer-address"><?php echo $contact_options['address']; ?></address>
							<?php
								$directions_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode( wp_strip_all_tags( $contact_options['address'] ) );
							?>
							<a class="footer-directions" href="<?php echo esc_url( $directions_url ); ?>" target="_blank" rel="noopener noreferrer">Get Directions</a>
						<?php endif; ?>
						<?php if($contact_options['phone']): ?>
							<span class="footer-contact-label">Call Us</span>
							<p class="footer-phone">
								<a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $contact_options['phone']); ?>">
									<i class="far fa-phone"></i> <?php echo $contact_options['phone']; ?>
								</a>
							</p>
							<?php if($contact_options['phone_note']): ?>
								<p class="footer-phone-note"><?php echo $contact_options['phone_note']; ?></p>
							<?php endif; ?>
						<?php endif; ?>
						<?php if($contact_options['email']): ?>
							<span class="footer-contact-label">Email Us</span>
							<p class="footer-email">
								<a href="mailto:<?php echo antispambot($contact_options['email']); ?>">
									<i class="far fa-envelope"></i> <?php echo antispambot($contact_options['email']); ?>
								</a>
							</p>
						<?php endif; ?>
						<?php if($contact_options['hours']): ?>
							<span class="footer-contact-label">Office Hours</span>
							<p class="footer-hours">
								<i class="far fa-clock"></i> <?php echo $contact_options['hours']; ?>
							</p>
							<?php if($contact_options['hours_note']): ?>
								<p class="footer-hours-note"><?php echo $contact_options['hours_note']; ?></p>
							<?php endif; ?>
						<?php endif; ?>
						<?php if($contact_options['social_media_links']): ?>
							<ul class="footer-social">
								<?php foreach($contact_options['social_media_links'] as $social): ?>
									<?php if($social['site'] && $social['url']): ?>
										<li>
											<a href="<?php echo $social['url']; ?>" title="<?php echo esc_attr($social['site']['label']); ?>" target="_blank" rel="noopener noreferrer">
												<span class="fab fa-<?php echo $social['site']['value']; ?>"></span>
											</a>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<!-- Practice Areas column -->
					<?php if($footer_pa): ?>
					<div class="footer-col footer-col-practice-areas">
						<h3 class="footer-col-title">Practice Areas</h3>
						<ul class="footer-links">
							<?php foreach($footer_pa as $pa_post): ?>
								<li><a href="<?php echo get_permalink($pa_post->ID); ?>"><?php echo $pa_post->post_title; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>

					<!-- Quick Links column -->
					<div class="footer-col footer-col-quick-links">
						<h3 class="footer-col-title">Quick Links</h3>
						<?php
							wp_nav_menu(array(
								'container'  => false,
								'menu'       => 'Quick Links',
								'items_wrap' => '<ul class="footer-links">%3$s</ul>',
								'fallback_cb'=> false,
							));
						?>
					</div>

					<!-- Subscribe / Legal Updates (sits beneath the right 3 columns) -->
					<?php if($footer_options && ($footer_options['subscribe_title'] || $footer_options['subscribe_content'])): ?>
					<div class="footer-subscribe">
						<div class="footer-subscribe-text">
							<?php if($footer_options['subscribe_title']): ?>
								<h3 class="footer-subscribe-title"><?php echo $footer_options['subscribe_title']; ?></h3>
							<?php endif; ?>
							<?php if($footer_options['subscribe_content']): ?>
								<p class="footer-subscribe-content"><?php echo $footer_options['subscribe_content']; ?></p>
							<?php endif; ?>
						</div>
						<?php if($footer_options['subscribe_button_text']): ?>
							<a href="#newsletter" class="button footer-subscribe-button"><?php echo $footer_options['subscribe_button_text']; ?></a>
						<?php endif; ?>
					</div>
					<?php endif; ?>

				</div>
			</div><!-- /.footer-main -->

			<!-- Footer bottom bar -->
			<div class="footer-bottom">
				<div class="container">
					<div class="footer-bottom-left">
						<svg class="footer-bottom-mark" xmlns="http://www.w3.org/2000/svg" width="50" height="70" viewBox="0 0 50 70" fill="none">
						  <path d="M43.1589 22.4354C40.8505 21.136 38.2243 20.3781 35.2617 20.1507V4.4286C35.2991 3.3783 35.6355 2.47958 36.2804 1.73246C36.9252 0.985336 37.7009 0.606361 38.6075 0.606361H39.2897V0H24.8318V0.606361H25.514C26.4206 0.606361 27.2056 0.985336 27.8692 1.73246C28.5327 2.47958 28.8692 3.3783 28.8692 4.4286V20.2156H10.4299V4.4286C10.4673 3.3783 10.8037 2.47958 11.4486 1.73246C12.0935 0.985336 12.8692 0.606361 13.7757 0.606361H14.4579V0H0V0.606361H0.682242C1.58878 0.606361 2.36448 0.985336 3.00934 1.73246C3.6542 2.47958 4 3.3783 4.02804 4.4286V38.06C3.99065 39.1103 3.6542 40.009 3.00934 40.7561C2.36448 41.5032 1.58878 41.8822 0.682242 41.8822H0V42.4886H14.4579V41.8822H13.7757C12.8692 41.8822 12.0935 41.5032 11.4486 40.7561C10.8037 40.009 10.4579 39.1103 10.4299 38.06V22.522H28.8692V35.9377C29.2804 36.1976 29.7103 36.4466 30.1776 36.7065C31.6916 37.5078 33.3365 38.309 35.1028 39.1103C35.1589 39.1319 35.2056 39.1536 35.2617 39.1861C35.3925 39.2402 35.5234 39.3052 35.6542 39.3593C36.1308 39.5759 36.5794 39.7816 37.028 39.9873C36.7664 39.8141 36.514 39.5975 36.2897 39.3268C35.6449 38.5797 35.2991 37.681 35.271 36.6307V23.2366C36.6355 23.4532 37.8224 23.9188 38.8037 24.6442C40.2056 25.6729 40.9159 27.1022 40.9159 28.9212C40.9159 30.7403 40.6542 30.762 40.1402 31.3575C39.6262 31.9422 39.028 32.3862 38.3645 32.6893C37.7009 32.9817 37.3645 33.1549 37.3645 33.2091C37.3645 33.653 37.6636 34.1295 38.2523 34.6492C38.8411 35.1689 39.7103 35.4505 40.8411 35.4938C42.7664 35.4938 44.3271 34.9199 45.5421 33.7613C46.7477 32.6027 47.3551 31.141 47.3551 29.376C47.3551 26.3334 45.9626 24.0162 43.1776 22.4462H43.1589V22.4354Z" fill="#9B7839"/>
						  <path d="M47.8131 49.3426C46.5514 47.426 45 45.8776 43.1495 44.6974C42.0654 44.0044 40.757 43.2681 39.2243 42.4993C38.7383 42.2503 38.2336 42.0121 37.7103 41.7522C37.486 41.6439 37.2523 41.5357 37.0187 41.4274C36.5794 41.2217 36.1215 41.0051 35.6448 40.7994C35.514 40.7452 31.243 38.8612 28.4579 37.1287C26.0467 35.6236 24.8411 34.5517 24.1121 33.653C23.1775 32.4944 22.6635 31.5957 22.6635 29.874C22.6635 28.1524 22.9065 25.8677 25.2617 23.778C23.3925 24.1786 21.1682 25.283 19.7383 26.6365C18.299 28.0008 17.4766 29.6791 17.3832 32.8301C17.3084 35.4829 18.028 37.7026 19.3084 39.5C20.5888 41.2975 22.1402 42.7267 23.9719 43.8095C25.7944 44.8923 28.1869 46.0942 31.1495 47.4152C33.7103 48.5413 35.7383 49.5375 37.2149 50.4037C38.6916 51.2591 39.9346 52.3311 40.9533 53.6087C41.9626 54.8864 42.4673 56.4132 42.4673 58.1781C42.4673 59.943 42.0561 60.9717 41.243 62.271C40.4299 63.5704 39.2617 64.6207 37.729 65.4003C36.1962 66.1907 34.1121 66.5805 32.0934 66.5805C29.1308 66.5805 26.4953 65.422 25.0841 64.1659C23.6822 62.9099 23.1308 61.3074 23.1308 59.001C23.1308 56.6947 23.3271 56.9762 23.7196 56.3915C24.1121 55.8068 24.5607 55.3629 25.0467 55.0597C25.542 54.7673 25.785 54.5941 25.785 54.5399C25.785 54.096 25.486 53.6196 24.8972 53.0998C24.3084 52.5801 23.4392 52.2986 22.3084 52.2553C20.3832 52.2553 18.8224 52.8291 17.6075 53.9877C16.4018 55.1463 15.9532 56.7813 15.9532 58.5462C15.9532 62.0328 17.4766 65.0321 20.2149 66.8296C22.9532 68.627 26.9065 69.5149 32.0841 69.5149C37.2617 69.5149 38.4486 68.9518 41.0841 67.8149C43.7196 66.6888 45.8131 65.1296 47.3738 63.1373C48.9252 61.1449 49.7009 58.9252 49.7009 56.4673C49.7009 54.0094 49.0747 51.2374 47.8131 49.3209V49.3426Z" fill="#9B7839"/>
						</svg>
						<div class="footer-bottom-meta">
							<p class="copyright" role="contentinfo">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
							<?php
								wp_nav_menu(array(
									'container'   => 'nav',
									'container_id'=> 'footer-nav',
									'theme_location' => 'footer',
									'fallback_cb' => false,
								));
							?>
						</div>
					</div>

					<?php if($footer_options && $footer_options['licenses']): ?>
					<div class="footer-bottom-right footer-licenses">
						<?php echo $footer_options['licenses']; ?>
					</div>
					<?php endif; ?>
					
					<?php /* ?><div class="site-footer__attribution">
						<a href="https://www.omythic.com/" title="Branding by Omythic" target="_blank" class="omythic-skull">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 viewBox="0 0 125 133.3" style="enable-background:new 0 0 125 133.3;" xml:space="preserve">
								<path class="st0" d="M124.4,38.1c-0.7-3.4-17.8-25-28.1-32.1c-1.3-0.5-2.5-1.2-3.8-1.5C56.8-4.3,54.4,2.2,30.1,4.8
								C28,5,26,6.5,24,7.4C17.8,10,7,24.2,6,24.7c-1,0.6-1.5,3.1-1.7,4c-0.1,0.9,4.4,0.8,6.3,0.8s2.7,1,2.7,1s-0.5,1.3-0.9,2.3
								c-0.4,1-1.3,8-1.4,12.1C11,49,11,54,10.9,60l0,0l0,0c0,2.2,0,4.5,0,6.9c-0.1,0-0.1,0-0.2,0c-0.2,3.2-0.4,7.1-0.6,11.1
								c-1.2-0.5-2.2-0.9-3.1-1.3c-3-1.4-2.3-1.8-3.7-2.3s-2.5,1.1-2.5,1.1c-0.9,1.6-1.1,3.7,0,4.6c1,0.8,3,4.6,8.6,8.1v0.1
								c0.7,0.4,1.4,0.8,2.1,1.1c0.9,0.6,1.9,1.2,3.1,1.8l0,0c0.1,0.1,0.3,0.1,0.5,0.2s0.5,0.2,0.7,0.4c0.5,0.2,0.9,0.5,1.4,0.7
								c0.3,0.1,0.5,0.2,0.8,0.4c0.6,0.3,1.2,0.5,1.7,0.7c0.2,0.1,0.4,0.2,0.5,0.2c0.7,0.3,1.4,0.6,2.1,0.8l0,0c0.2,0.1,0.3,0.1,0.5,0.2
								c-0.8,4.8-1,8.3-1.4,9.6c-0.5,1.5-3.2,7.8-3,10c0.2,2.3,0.7,6.4,0.7,6.6s1.4,0.5,1.9,0s2.8-0.1,2.8-2.1c0-1.9,0.2-5.2,0.5-7.1
								c0.4-1.9,0.5-4.9,2.1-7.2c1.6-2.2,1.3-2,1.4-3.7c0.1-0.9,0.7-2.6,1.3-4.1c0.1,0,0.3,0.1,0.5,0.1l-0.4-0.1c0-0.1,0-0.1,0.1-0.2
								c4.5,0.7,10.3,1.4,15.9,1.8c-0.2,4.5,1.4,5.1,0.8,6.7c-0.7,1.9-1.2,9.7-1.2,10.6s-2.8,12-2.6,13.6c0.1,1.6,0.7,1.2,2.6,1.2
								s5.6-5.2,5.8-7.1c0.2-1.9,1.9-9.1,4.6-12.7c0.4-0.5,0.7-1,1-1.5l3.5,20.4c1,1.2,2.3,2.8,2.6,3c0.4,0.4,3.6,0.7,3.6,0.7
								s3.8-0.7,14.3-7.7c3.1-2.5,5.5-5.1,6.2-7c2.2-5.8-0.7-5.5-0.7-5.5s-7.8,7.9-10.5,9.1s-4.2,1.6-5.6,1.3c-1.4-0.3-1-3.2-1.6-8.1
								c-0.2-1.8-1.7-7.2-1.5-8.6c0,0-1.3-4.6-1.2-9.1c2.5-0.6,5.6-1.3,7.2-1.7c1.2-0.3,2.4-0.5,3.5-0.8c6.7-0.4,11.2-1.4,15.9-3
								c1.5-0.5,18.3-7.2,20.7-8.5s10.5-6.9,11.7-10.7S125.1,41.5,124.4,38.1z M37.6,65.9c0.7-2.1,1.9-5.2,2.8-8c0.8-2.8,2.1-3.7,2.6-5.4
								c0.6-1.9,1.7-2.7,2.2-2.5c0.6,0.1,1.4,1.8,1.4,1.8s-0.1,11.8,0,14.9c0,0.4,0.1,0.8,0.1,1.4c0.1,2.1,0.4,5.6,0.7,9.1
								c0.2,1.9-0.2,3-0.1,4.6c0,0,0,0,0,0.1c0,1.1-0.4,2.4-0.7,3c-0.2,0.6-1.1,1.2-2.3,1.4c-3.2,0.1-6.6,0-9.3-0.4c-0.9-0.2-1.7-1-1.8-2.2
								c0-1,0.2-1.8,0.4-2.5c0.8-2.5,1.6-4.4,1.7-5C35.7,74.6,37,68,37.6,65.9z M23.1,81.8c-2.9-0.6-6-0.9-7.5-3.4c0,0,1.8-36.9,1.8-41.1
								s3.5-4.7,6,0s8.8,19.1,6.7,24.6C28,67.4,26,82.3,23.1,81.8z M93.7,67.6c-0.2,3.6-1.9,8.8-3.5,11.1c-1.7,2.5-17.5,8.6-18.3,8.9
								s-6.5,3.1-9.8,0c-3.2-3.1-4-30.5-4-34s0.3-13.1,0.6-14.4c0.3-1.3,1-4,2.1-5.8c1.2-1.8,4.5-2.1,8-2.4c0.4,0,0.7,0,1.1,0
								c7.9,0,13.8,2.9,17.6,8.6c4,7.6,6.1,16.3,6.1,26.3C93.8,66.4,93.7,67,93.7,67.6z"/>
							</svg>
						</a>
						<a href="https://www.omythic.com/" title="Branding by Omythic" target="_blank" class="omythic">
							<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1694.23px" height="296.77px" viewBox="0 0 1694.23 296.77" style="overflow:visible;enable-background:new 0 0 1694.23 296.77;" xml:space="preserve">
								<g>
									<g>
										<path d="M112.9,40.37c-79.1,0-112.9,65.6-112.9,112c0,51.6,32.7,112.6,111.2,112.6c69,0,114.3-53,114.3-117.2 C225.5,81.07,177.3,40.37,112.9,40.37z M198,156.07c-9.1,2.2-16.2,5.3-22.4,8.5c-1.8,0.9-2.5,3.3-1.3,5 c9.1,13,15.2,39.5-0.9,61.2c-0.2,0.3-0.4,0.6-0.5,1c-17.4,17.9-39.8,22.4-54,22.4c-51.6,0-91.7-43-91.7-109.7 c0-62.7,35.8-94.2,83.7-94.2c45.3,0,87.1,37,87.1,105.7C198,155.97,198,155.97,198,156.07z"></path>
										<path d="M763.5,44.37c-5.4,0-26.1,0.9-27.8,0.9c-2.3,0-11.5-0.9-18.9-0.9c-2.9,0-4.6,0.6-4.6,1.7c0,1.4,1.7,2,4.3,2.9 c3.4,0.9,4.6,4,4.6,8c0,3.7-1.2,8.6-4.9,14.6c-5.7,9.4-42.1,72.2-47.6,84.2c-5.2-8.3-48.1-82.2-52.7-90.5 c-2.3-4.3-3.7-7.7-3.7-11.2c0-2.3,1.4-4,3.7-5.2c2.6-1.2,4.3-1.4,4.3-2.6s-0.6-2-3.7-2c-11.2,0-22.1,0.9-24.1,0.9 c-1.4,0-22.9-0.9-28.4-0.9c-2.6,0-3.7,0.9-3.7,2c0,1.4,1.4,2,2.9,2c2.6,0,8,1.7,12.3,4.6c5.2,3.4,12.3,10.6,19.5,21.2 c10,14.9,48.4,77.9,50.4,82.5c3.7,8.3,6.6,14.6,6.6,30.1v24.6c0,4.6,0,16.6-0.9,28.6c-0.6,8.3-3.2,14.6-8.9,15.8 c-2.6,0.6-6,1.1-8.6,1.1c-1.7,0-2.3,0.9-2.3,1.7c0,1.7,1.4,2.3,4.6,2.3c8.6,0,26.6-0.9,28.1-0.9c1.4,0,19.5,0.9,35.2,0.9 c3.2,0,4.6-0.9,4.6-2.3c0-0.9-0.6-1.7-2.3-1.7c-2.6,0-8.9-0.6-12.9-1.1c-8.6-1.1-11.2-7.5-11.8-15.8c-0.9-12-0.9-24.1-0.9-28.6 v-24.6c0-9.4,0-18.3,4.3-28.6c5.2-12.3,45.3-79.6,54.7-91.1c6.3-7.7,9.5-11.2,15.2-14.6c4.6-2.9,10-4,13.2-4 c2.3,0,3.7-0.9,3.7-2.3C767.3,44.97,765.5,44.37,763.5,44.37z"></path>
										<path d="M972.5,42.37c-2.3,0-10,2.9-28.4,2.9H836.7c-3.4,0-14.3-0.6-22.6-1.4c-7.7-0.6-10.6-3.4-12.6-3.4 c-1.2,0-2.3,3.7-2.9,5.4c-0.6,2.3-6.3,26.9-6.3,29.8c0,1.7,0.6,2.6,1.4,2.6c1.2,0,2-0.6,2.9-2.9c0.9-2,1.7-4,4.9-8.9 c4.6-6.9,11.5-8.9,29.2-9.2l42.4-0.6v121.2c0,27.5,0,50.1-1.4,62.2c-1.2,8.3-2.6,14.6-8.3,15.8c-2.6,0.6-6,1.1-8.6,1.1 c-1.7,0-2.3,0.9-2.3,1.7c0,1.7,1.4,2.3,4.6,2.3c8.6,0,26.6-0.9,28.1-0.9c1.4,0,19.5,0.9,35.2,0.9c3.2,0,4.6-0.9,4.6-2.3 c0-0.9-0.6-1.7-2.3-1.7c-2.6,0-8.9-0.6-12.9-1.1c-8.6-1.1-10.3-7.5-11.2-15.8c-1.4-12-1.4-34.7-1.4-62.2V56.67l36.1,0.6 c27.5,0.6,34.1,7.2,35.2,15.8l0.3,3.2c0.3,4,0.9,4.9,2.3,4.9c1.2,0,2-1.2,2-3.7c0-3.2,0.9-22.9,0.9-31.5 C974,44.07,974,42.37,972.5,42.37z"></path>
										<path d="M1375.7,235.17c-2.6,0-3.7-0.6-7.7-1.1c-8.6-1.1-10.3-7.5-11.2-15.8c-1.4-12-1.4-34.7-1.4-62.2v-28.6 c0-44.7,0-52.7,0.6-61.9c0.6-10,2.9-14.9,10.6-16.3c3.4-0.6,5.2-0.9,7.2-0.9c1.2,0,2.3-0.6,2.3-1.7c0-1.7-1.4-2.3-4.6-2.3 c-8.6,0-26.6,0.9-28.1,0.9c-1.4,0-19.5-0.9-28.9-0.9c-3.2,0-4.6,0.6-4.6,2.3c0,1.2,1.2,1.7,2.3,1.7c2,0,5.7,0.3,8.9,1.2 c6.3,1.4,9.2,6.3,9.7,16c0.6,9.2,0.6,17.2,0.6,61.9v28.6c0,27.5,0,50.1-1.4,62.2c-1.2,8.3-2.6,14.6-8.3,15.8 c-2.6,0.6-6,1.1-8.6,1.1c-1.7,0-2.3,0.9-2.3,1.7c0,1.7,1.4,2.3,4.6,2.3c8.6,0,26.6-0.9,28.1-0.9c1.4,0,14.3,0.9,30,0.9 c3.2,0,4.6-0.9,4.6-2.3C1378,235.97,1377.4,235.17,1375.7,235.17z"></path>
										<path d="M1625.6,209.17c-1.7,0-2.3,1.1-2.9,4.3c-0.9,5.2-4.6,17.8-10.6,24.1c-11.5,11.8-28.4,14.3-49.8,14.3 c-61.3,0-103.4-50.1-103.4-104.9c0-0.1,0-0.2,0-0.3c9.8-2.2,17.2-5.5,23.7-8.8c1.8-0.9,2.5-3.3,1.3-5 c-9-12.8-15-38.5,0.1-59.9c0.2-0.2,0.5-0.5,0.7-0.8c10-9.7,27.8-20.9,65.9-20.9c25.2,0,47.3,7.7,57.3,16.3 c7.4,6.3,12,18.1,12,29.2c0,4,0.3,6,2.3,6c1.7,0,2.3-1.7,2.6-6c0.3-4.3,0.3-20,0.9-29.5c0.6-10.3,1.4-13.8,1.4-16 c0-1.7-0.6-2.9-3.7-3.2c-9.2-0.6-18.3-2.3-29.2-4.3c-14-2.6-30.1-3.4-41.5-3.4c-44.1,0-70.2,13.5-87.4,30.7 c-25.5,25.5-32.1,59-32.1,78.2c0,27.2,6.9,59.9,34.7,84.8c22.6,20.3,51.6,30.9,94.5,30.9c18.3,0,40.1-2,51.6-6.6 c5.2-2.3,5.7-2.9,7.4-8.3c2.9-9.7,6.3-34.7,6.3-36.7C1627.6,211.17,1627,209.17,1625.6,209.17z"></path>
									</g>
									<g>
										<path d="M551.8,256.97c-2.9,0-10.3,0-18.6-2.6c-12.3-4-14.3-20-15.5-30.4l-18-169.6c-0.2-1.7-2.5-2.1-3.2-0.6 c-6,12.6-11.5,25.4-16.6,38.4c-0.1,0.2-0.1,0.5-0.1,0.8l15.6,151c0.6,5.2,0.3,10-1.2,10.3c-1.4,0.3-2,0.9-2,2 c0,1.4,1.4,2.3,8,2.9c10.6,0.9,41.5,1.7,47.8,1.7c3.4,0,6-0.9,6-2.6C554.1,257.27,553.3,256.97,551.8,256.97z"></path>
										<path d="M466.2,0.57c-42.3,49-67.7,120.4-81.5,179.3l-65.8-135.2c-1.4-3.2-2.6-4.3-4.3-4.3s-2.6,2-3.1,5.2l-20.6,186.8 c-1.1,10.9-2,22.1-11.5,23.8c-4.3,0.9-6.3,0.9-8.6,0.9c-1.4,0-2.9,0.6-2.9,1.4c0,2,2,2.6,4.9,2.6c7.7,0,21.5-0.9,24.1-0.9 c2.3,0,16,0.9,26.6,0.9c3.4,0,5.2-0.6,5.2-2.6c0-0.9-1.1-1.4-2.3-1.4c-1.7,0-6-0.3-10.9-1.4c-4.3-0.9-7.5-4.6-7.5-8.9 c0-4.9,0-10,0.3-14.6l11.5-132.9h1.1c3.1,7.7,35.2,76.5,37.8,81.6c1.4,2.9,26.1,52.4,33.5,66.5c5,9.4,6.6,12.6,8.4,13.1 c0.7-13.6,1.9-27.1,3.5-40.5l0,0c9-77.4,31.3-150.6,64.7-217.5C469.6,0.57,467.4-0.83,466.2,0.57z"></path>
									</g>
									<path d="M1242.2,235.17c-2.6,0-8.9-0.6-12.9-1.1c-8.6-1.1-10.3-7.5-11.2-15.8c-1.4-12-1.4-42.9-1.4-70.4v-20.3 c0-44.7,0-52.7,0.6-61.9c0.6-10,2.9-14.9,10.6-16.3c3.4-0.6,5.2-0.9,7.2-0.9c1.2,0,2.3-0.6,2.3-1.7c0-1.7-1.4-2.3-4.6-2.3 c-8.6,0-26.6,0.9-28.1,0.9c-1.4,0-19.5-0.9-32.4-0.9c-3.2,0-4.6,0.6-4.6,2.3c0,1.2,1.2,1.7,2.3,1.7c2.6,0,7.4,0.3,9.7,0.9 c9.4,2,11.8,6.6,12.3,16.3c0.6,9.2,0.6,17.2,0.6,61.9v6c0,1.4-0.9,1.7-1.7,1.7h-121.5c-0.8,0-1.5-0.2-1.7-1.2 c0-1.9-0.1-3.8-0.1-5.7v-0.8c0-44.7,0-52.7,0.6-61.9c0.6-10,2.9-14.9,10.6-16.3c3.4-0.6,5.2-0.9,7.2-0.9c1.2,0,2.3-0.6,2.3-1.7 c0-1.7-1.4-2.3-4.6-2.3c-8.6,0-26.6,0.9-28.1,0.9s-19.5-0.9-32.4-0.9c-3.2,0-4.6,0.6-4.6,2.3c0,1.2,1.2,1.7,2.3,1.7 c2.6,0,7.4,0.3,9.7,0.9c9.4,2,11.8,6.6,12.3,16.3c0.6,9.2,0.6,17.2,0.6,61.9v13.3c0,124.6,86,155.9,172.1,155.9 c3.6,0,5.2-1,5.2-2.6c0-1-0.7-2.1-2.6-2.1c-0.8,0-1.7,0-2.6,0l0,0c-3.1,0-6.1,0-9-0.1l0,0l0,0 c-86.8-2.8-133.5-47-138.5-145.3c0.3-0.3,0.8-0.4,1.3-0.4h121.5c0.8,0,1.7,0.6,1.7,1.7c0,26.7-0.2,60.3-1.4,70.5 c-1.2,8.3-2.6,14.6-8.3,15.8c-2.6,0.6-6,1.1-8.6,1.1c-1.7,0-2.3,0.9-2.3,1.7c0,1.7,1.4,2.3,4.6,2.3c8.6,0,26.6-0.9,28.1-0.9 c1.4,0,19.5,0.9,35.2,0.9c3.2,0,4.6-0.9,4.6-2.3C1244.5,235.97,1244,235.17,1242.2,235.17z"></path>
								</g>
							</svg>
						</a>
					</div><?php */ ?>
				</div>
			</div><!-- /.footer-bottom -->

			<!-- Important Legal Notice -->
			<?php if($footer_options && $footer_options['notice']): ?>
			<div class="footer-notice">
				<div class="container">
					<div class="wysiwyg"><?php echo $footer_options['notice']; ?></div>
				</div>
			</div>
			<?php endif; ?>

		</footer>

		<?php wp_footer(); ?>
	</body>
</html>
