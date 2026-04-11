<?php get_header(); ?>

	<?php while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			
			<?php if($hero = get_field('hero')): ?>
				<div class="hero">
					<?php
						$hero_type = $hero['type'];
						
						$popup_video_type = $hero['popup_type'];
						$popup_video_embed_url = $hero['popup_video_embed'];
						$popup_video_file = $hero['popup_video_file'];
						$popup_video_url = false;
						$popup_video_action = '';
					?>
					
					<?php if($hero_type == 'image' && $hero['image']): ?>
						<div class="hero-bg" style="background-image:url(<?php echo $hero['image']['url']; ?>)"></div>

					<?php elseif($hero_type == 'slider' && $hero['slider']): ?>
						<div class="hero-slider" style="background-image:url(<?php echo $hero['slider'][0]['image']['sizes']['medium']; ?>);">
							<?php foreach($hero['slider'] as $slide): ?>
								<div class="slide">
									<img src="" alt="" data-lazy="<?php echo $slide['image']['url']; ?>">
								</div>
							<?php endforeach; ?>
						</div>

					<?php elseif($hero_type == 'video_file' && $hero['video_file']): ?>
						<div class="hero-video">
							<?php $video_url = $hero['video_file']['url']; ?>
							<video id="videoembed" src="<?php echo $video_url; ?>" poster="<?php //echo $hero_bg; ?>" autoplay muted loop playsinline ></video>
						</div>

					<?php elseif($hero_type == 'video_embed' && $hero['video_embed']): ?>
						<?php 
							$video = $hero['video_embed'];

							// Add autoplay functionality to the video code
							if ( preg_match('/src="(.+?)"/', $video, $matches) ) {
								// Video source URL
								$src = $matches[1];

								// get youtube video id
								preg_match('/embed\/(.*?)\?/', $src, $vid_id_arr);
								
								if(is_array($vid_id_arr) && count($vid_id_arr) > 0){
									if(isset($vid_id_arr[1])){
										$playlist_id = $vid_id_arr[1];
									}
									else{
										$playlist_id = $vid_id_arr[0];
									}
								}
								else{
									$playlist_id = '';
								}

								// Add option to hide controls, enable HD, and do autoplay -- depending on provider
								$params = array(
									'controls'    => 0,
					                'muted' => 1,
					                'mute' => 1,
					                'playsinline' => 1,
									'hd'  => 1,
									'background' => 1,
									'loop' => 1,
									'title' => 0,
									'byline' => 0,
									'autoplay' => 1,
					                'playlist' => $playlist_id // required to loop youtube
								);

								
								$new_src = add_query_arg($params, $src);
								
								$video = str_replace($src, $new_src, $video);
								
								// add extra attributes to iframe html
								$attributes = ' id="videoframe" frameborder="0" autoplay muted loop playsinline webkit-playsinline allow="autoplay; fullscreen"';
								 
								$video = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $video);
							}
						?>
						<div class="hero-video iframe"><?php echo $video ?></div>

					<?php endif; ?>

					<div class="hero-content">
						<?php if($hero['title']): ?>
							<div class="hero-title"><?php echo $hero['title']; ?></div>
						<?php endif; ?>
						<?php if($popup_video_type == 'file' && $popup_video_file): ?>
							<a href="#hero-video-modal-container" class="hero-play no-scroll" data-action="hero-popup-play">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50.8 50.8" style="enable-background:new 0 0 50.8 50.8;" xml:space="preserve">
									<path d="M35.9,26.5l-8,4.6l-8,4.6c-1.1,0.6-1.9,0.1-1.9-1.1v-9.2v-9.2c0-1.2,0.9-1.7,1.9-1.1l8,4.6l8,4.6C37,24.9,37,25.9,35.9,26.5
											 M25.4,0C11.4,0,0,11.4,0,25.4c0,14,11.4,25.4,25.4,25.4c14,0,25.4-11.4,25.4-25.4C50.8,11.4,39.5,0,25.4,0"/>
								</svg>
								<span>Play Full Video</span>
							</a>
						<?php elseif($popup_video_type == 'embed' && $popup_video_embed_url): ?>
							<a href="<?php echo $popup_video_embed_url; ?>" class="hero-play no-scroll" data-action="hero-popup-embed">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50.8 50.8" style="enable-background:new 0 0 50.8 50.8;" xml:space="preserve">
									<path d="M35.9,26.5l-8,4.6l-8,4.6c-1.1,0.6-1.9,0.1-1.9-1.1v-9.2v-9.2c0-1.2,0.9-1.7,1.9-1.1l8,4.6l8,4.6C37,24.9,37,25.9,35.9,26.5
									 M25.4,0C11.4,0,0,11.4,0,25.4c0,14,11.4,25.4,25.4,25.4c14,0,25.4-11.4,25.4-25.4C50.8,11.4,39.5,0,25.4,0"/>
								</svg>
								<span>Play Full Video</span>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<?php if($popup_video_type == 'file' && $popup_video_file): ?>
					<div class="video-outer-wrap" id="hero-video-modal-container">
						<div class="video-container">
							<?php if($popup_video_type == 'file' && $popup_video_file): ?>
								<video id="hero-video-modal" src="<?php echo $popup_video_file['url']; ?>" poster="<?php ?>" playsinline controls ></video>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			
			<?php if($section_1 = get_field('section_1')): ?>
				<section class="home-section home-section-1" id="home-section-1">
					<?php if($section_1['background_image']): ?>
						<img src="<?php echo $section_1['background_image']['url']; ?>" alt="" loading="lazy" class="bg">
					<?php endif; ?>
					<div class="container">
						<div class="content">
							<?php if($section_1['label']): ?>
								<p class="section-label"><?php echo $section_1['label']; ?></p>
							<?php endif; ?>
							<?php if($section_1['title']): ?>
								<h2 class="section-title"><?php echo $section_1['title']; ?></h2>
							<?php endif; ?>
							<?php if($section_1['subtitle']): ?>
								<h3 class="section-subtitle"><?php echo $section_1['subtitle']; ?></h3>
							<?php endif; ?>
							<?php if($section_1['content']): ?>
								<div class="wysiwyg"><?php echo $section_1['content']; ?></div>
							<?php endif; ?>
							<?php if($section_1['repeater']): ?>
								<ul class="repeater">
									<?php foreach($section_1['repeater'] as $item): ?>
										<li>
											<?php // item fields ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							<?php if($section_1_button = $section_1['button']): ?>
								<a href="<?php echo $section_1_button['url']; ?>" target="<?php echo $section_1_button['target']; ?>" class="button"><?php echo $section_1_button['title']; ?></a>
							<?php endif; ?>

							<?php if($section_1_buttons['buttons']): ?>
								<div class="section-buttons">
									<?php foreach($section_1_buttons['buttons'] as $btn): ?>
										<a href="<?php echo $btn['button']['url']; ?>" target="<?php echo $btn['button']['target']; ?>" class="button <?php echo $btn['style']; ?>"><?php echo $btn['button']['title']; ?></a>
		    						<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if($section_1['image']): ?>
							<figure class="image">
								<img src="<?php echo $section_1['image']['sizes']['medium']; ?>" alt="" loading="lazy">
							</figure>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>


			<ul class="faq-list">
				<?php while(have_rows('faqs')): the_row(); ?>
					<li data-action="faq">
						<div class="question">
							<p><?php the_sub_field('question'); ?></p>
						</div>
						<div class="answer">
							<div class="wysiwyg"><?php the_sub_field('answer'); ?></div>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>

		</article>
	<?php endwhile; ?>
<?php get_footer();