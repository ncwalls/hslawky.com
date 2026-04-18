<?php get_header(); ?>

<?php while( have_posts() ): the_post(); ?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">


	<?php // ====== HERO ====== ?>
	<?php if($hero = get_field('hero')): ?>
	<div class="hero">
		<?php $hero_type = $hero['type']; ?>

		<?php if($hero_type == 'image' && $hero['image']): ?>
			<div class="hero-bg" style="background-image:url(<?php echo $hero['image']['url']; ?>)"></div>

		<?php elseif($hero_type == 'video_file' && $hero['video_file']): ?>
			<div class="hero-video">
				<video src="<?php echo $hero['video_file']['url']; ?>" autoplay muted loop playsinline></video>
			</div>

		<?php elseif($hero_type == 'video_embed' && $hero['video_embed']): ?>
			<?php
				$video = $hero['video_embed'];
				if( preg_match('/src="(.+?)"/', $video, $matches) ) {
					$src = $matches[1];
					preg_match('/embed\/(.*?)\?/', $src, $vid_id_arr);
					$playlist_id = (is_array($vid_id_arr) && isset($vid_id_arr[1])) ? $vid_id_arr[1] : '';
					$params = array(
						'controls'   => 0,
						'muted'      => 1,
						'mute'       => 1,
						'playsinline'=> 1,
						'hd'         => 1,
						'background' => 1,
						'loop'       => 1,
						'title'      => 0,
						'byline'     => 0,
						'autoplay'   => 1,
						'playlist'   => $playlist_id,
					);
					$new_src = add_query_arg($params, $src);
					$video   = str_replace($src, $new_src, $video);
					$attributes = 'frameborder="0" autoplay muted loop playsinline webkit-playsinline allow="autoplay; fullscreen"';
					$video = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $video);
				}
			?>
			<div class="hero-video"><?php echo $video; ?></div>

		<?php elseif($hero_type == 'slider' && isset($hero['slider']) && $hero['slider']): ?>
			<div class="hero-slider">
				<?php foreach($hero['slider'] as $slide): ?>
					<div class="slide">
						<img src="" alt="" data-lazy="<?php echo $slide['url']; ?>">
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="hero-content">
			<div class="container">
				<?php if($hero['title']): ?>
					<h1 class="hero-title"><?php echo $hero['title']; ?></h1>
				<?php endif; ?>
				<?php if($hero['subtitle']): ?>
					<p class="hero-subtitle"><?php echo $hero['subtitle']; ?></p>
				<?php endif; ?>
				<?php if($hero['subsubtitle']): ?>
					<p class="hero-subsubtitle"><?php echo $hero['subsubtitle']; ?></p>
				<?php endif; ?>
				<?php if($hero['button']): $btn = $hero['button']; ?>
					<a href="<?php echo $btn['url']; ?>" target="<?php echo esc_attr($btn['target']); ?>" class="button"><?php echo $btn['title']; ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>


	<?php // ====== INTRO ====== ?>
	<?php $intro = get_field('intro'); $intro_side = get_field('intro_side'); ?>
	<?php if( ($intro && ($intro['title'] || $intro['content'])) || ($intro_side && ($intro_side['title'] || $intro_side['content'])) ): ?>
	<section class="home-section home-intro">
		<div class="container">

			<?php if($intro): ?>
			<div class="intro-left">
				<?php if($intro['title']): ?>
					<h2 class="section-title"><?php echo $intro['title']; ?></h2>
				<?php endif; ?>
				<?php if($intro['content']): ?>
					<div class="wysiwyg"><?php echo $intro['content']; ?></div>
				<?php endif; ?>
				<?php if($intro['slider']): ?>
					<div class="intro-logos">
						<?php if($intro['slider_title']): ?>
							<p class="intro-logos-title"><?php echo $intro['slider_title']; ?></p>
						<?php endif; ?>
						<div class="logos-marquee">
							<ul class="logos-list" aria-hidden="false">
								<?php foreach($intro['slider'] as $logo_img): ?>
									<li><img src="<?php echo $logo_img['sizes']['medium']; ?>" alt="<?php echo esc_attr($logo_img['alt']); ?>" loading="lazy"></li>
								<?php endforeach; ?>
							</ul>
							<ul class="logos-list" aria-hidden="true">
								<?php foreach($intro['slider'] as $logo_img): ?>
									<li><img src="<?php echo $logo_img['sizes']['medium']; ?>" alt="" loading="lazy"></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>
				<?php if($intro['buttons']): ?>
					<div class="section-buttons">
						<?php foreach($intro['buttons'] as $btn_row): if($btn_row['button']): $btn = $btn_row['button']; ?>
							<a href="<?php echo $btn['url']; ?>" target="<?php echo esc_attr($btn['target']); ?>" class="button"><?php echo $btn['title']; ?></a>
						<?php endif; endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if($intro_side && ($intro_side['title'] || $intro_side['content'])): ?>
			<div class="intro-right">
				<?php if($intro_side['title']): ?>
					<h3 class="section-title"><?php echo $intro_side['title']; ?></h3>
				<?php endif; ?>
				<?php if($intro_side['content']): ?>
					<div class="wysiwyg"><?php echo $intro_side['content']; ?></div>
				<?php endif; ?>
				<?php if($intro_side['signature']): ?>
					<p class="signature"><?php echo $intro_side['signature']; ?></p>
				<?php endif; ?>
			</div>
			<?php endif; ?>

		</div>
	</section>
	<?php endif; ?>


	<?php // ====== PRACTICE AREAS ====== ?>
	<?php if($pa_section = get_field('practice_areas')): if($pa_section['title'] || $pa_section['practice_areas']): ?>
	<section class="home-section home-practice-areas">
		<div class="container">
			<?php if($pa_section['title'] || $pa_section['content']): ?>
			<div class="section-header">
				<?php if($pa_section['title']): ?>
					<h2 class="section-title"><?php echo $pa_section['title']; ?></h2>
				<?php endif; ?>
				<?php if($pa_section['content']): ?>
					<div class="wysiwyg section-intro"><?php echo $pa_section['content']; ?></div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if($pa_section['practice_areas']): ?>
			<ul class="practice-areas-grid">
				<?php foreach($pa_section['practice_areas'] as $pa_id): ?>
					<?php
						$pa_icon    = get_field('icon', $pa_id);
						$pa_types   = get_field('key_case_types', $pa_id);
						$pa_excerpt = get_the_excerpt($pa_id);
						$pa_link    = get_permalink($pa_id);
						$pa_title   = get_the_title($pa_id);
					?>
					<li class="practice-area-card">
						<a href="<?php echo $pa_link; ?>" class="practice-area-card-inner">
							<?php if($pa_icon): ?>
								<div class="card-icon"><?php echo $pa_icon; ?></div>
							<?php endif; ?>
							<h3 class="card-title"><?php echo $pa_title; ?></h3>
							<?php if($pa_excerpt): ?>
								<p class="card-excerpt"><?php echo $pa_excerpt; ?></p>
							<?php endif; ?>
							<?php if($pa_types): ?>
								<ul class="card-types">
									<?php foreach($pa_types as $t): if(!empty($t['case_type'])): ?>
										<li><?php echo esc_html($t['case_type']); ?></li>
									<?php endif; endforeach; ?>
								</ul>
							<?php endif; ?>
							<span class="card-more">Show More <i class="fas fa-chevron-down"></i></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<?php if($pa_section['cta_title'] || $pa_section['cta_button']): ?>
		<div class="section-cta">
			<div class="container">
				<?php if($pa_section['cta_title']): ?>
					<p class="section-cta-text"><?php echo $pa_section['cta_title']; ?></p>
				<?php endif; ?>
				<?php if($pa_section['cta_button']): $cta_btn = $pa_section['cta_button']; ?>
					<a href="<?php echo $cta_btn['url']; ?>" target="<?php echo esc_attr($cta_btn['target']); ?>" class="button"><?php echo $cta_btn['title']; ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
	</section>
	<?php endif; endif; ?>


	<?php // ====== ADVANTAGES ====== ?>
	<?php if($adv_section = get_field('advantages')): if($adv_section['title'] || $adv_section['advantages']): ?>
	<section class="home-section home-advantages">
		<div class="container">
			<?php if($adv_section['title'] || $adv_section['content']): ?>
			<div class="section-header">
				<svg class="icon" xmlns="http://www.w3.org/2000/svg" width="50" height="70" viewBox="0 0 50 70" fill="none">
				  <path d="M43.1589 22.4354C40.8505 21.136 38.2243 20.3781 35.2617 20.1507V4.4286C35.2991 3.3783 35.6355 2.47958 36.2804 1.73246C36.9252 0.985336 37.7009 0.606361 38.6075 0.606361H39.2897V0H24.8318V0.606361H25.514C26.4206 0.606361 27.2056 0.985336 27.8692 1.73246C28.5327 2.47958 28.8692 3.3783 28.8692 4.4286V20.2156H10.4299V4.4286C10.4673 3.3783 10.8037 2.47958 11.4486 1.73246C12.0935 0.985336 12.8692 0.606361 13.7757 0.606361H14.4579V0H0V0.606361H0.682242C1.58878 0.606361 2.36448 0.985336 3.00934 1.73246C3.6542 2.47958 4 3.3783 4.02804 4.4286V38.06C3.99065 39.1103 3.6542 40.009 3.00934 40.7561C2.36448 41.5032 1.58878 41.8822 0.682242 41.8822H0V42.4886H14.4579V41.8822H13.7757C12.8692 41.8822 12.0935 41.5032 11.4486 40.7561C10.8037 40.009 10.4579 39.1103 10.4299 38.06V22.522H28.8692V35.9377C29.2804 36.1976 29.7103 36.4466 30.1776 36.7065C31.6916 37.5078 33.3365 38.309 35.1028 39.1103C35.1589 39.1319 35.2056 39.1536 35.2617 39.1861C35.3925 39.2402 35.5234 39.3052 35.6542 39.3593C36.1308 39.5759 36.5794 39.7816 37.028 39.9873C36.7664 39.8141 36.514 39.5975 36.2897 39.3268C35.6449 38.5797 35.2991 37.681 35.271 36.6307V23.2366C36.6355 23.4532 37.8224 23.9188 38.8037 24.6442C40.2056 25.6729 40.9159 27.1022 40.9159 28.9212C40.9159 30.7403 40.6542 30.762 40.1402 31.3575C39.6262 31.9422 39.028 32.3862 38.3645 32.6893C37.7009 32.9817 37.3645 33.1549 37.3645 33.2091C37.3645 33.653 37.6636 34.1295 38.2523 34.6492C38.8411 35.1689 39.7103 35.4505 40.8411 35.4938C42.7664 35.4938 44.3271 34.9199 45.5421 33.7613C46.7477 32.6027 47.3551 31.141 47.3551 29.376C47.3551 26.3334 45.9626 24.0162 43.1776 22.4462H43.1589V22.4354Z" fill="#262626"/>
				  <path d="M47.8131 49.3426C46.5514 47.426 45 45.8776 43.1495 44.6974C42.0654 44.0044 40.757 43.2681 39.2243 42.4993C38.7383 42.2503 38.2336 42.0121 37.7103 41.7522C37.486 41.6439 37.2523 41.5357 37.0187 41.4274C36.5794 41.2217 36.1215 41.0051 35.6448 40.7994C35.514 40.7452 31.243 38.8612 28.4579 37.1287C26.0467 35.6236 24.8411 34.5517 24.1121 33.653C23.1775 32.4944 22.6635 31.5957 22.6635 29.874C22.6635 28.1524 22.9065 25.8677 25.2617 23.778C23.3925 24.1786 21.1682 25.283 19.7383 26.6365C18.299 28.0008 17.4766 29.6791 17.3832 32.8301C17.3084 35.4829 18.028 37.7026 19.3084 39.5C20.5888 41.2975 22.1402 42.7267 23.9719 43.8095C25.7944 44.8923 28.1869 46.0942 31.1495 47.4152C33.7103 48.5413 35.7383 49.5375 37.2149 50.4037C38.6916 51.2591 39.9346 52.3311 40.9533 53.6087C41.9626 54.8864 42.4673 56.4132 42.4673 58.1781C42.4673 59.943 42.0561 60.9717 41.243 62.271C40.4299 63.5704 39.2617 64.6207 37.729 65.4003C36.1962 66.1907 34.1121 66.5805 32.0934 66.5805C29.1308 66.5805 26.4953 65.422 25.0841 64.1659C23.6822 62.9099 23.1308 61.3074 23.1308 59.001C23.1308 56.6947 23.3271 56.9762 23.7196 56.3915C24.1121 55.8068 24.5607 55.3629 25.0467 55.0597C25.542 54.7673 25.785 54.5941 25.785 54.5399C25.785 54.096 25.486 53.6196 24.8972 53.0998C24.3084 52.5801 23.4392 52.2986 22.3084 52.2553C20.3832 52.2553 18.8224 52.8291 17.6075 53.9877C16.4018 55.1463 15.9532 56.7813 15.9532 58.5462C15.9532 62.0328 17.4766 65.0321 20.2149 66.8296C22.9532 68.627 26.9065 69.5149 32.0841 69.5149C37.2617 69.5149 38.4486 68.9518 41.0841 67.8149C43.7196 66.6888 45.8131 65.1296 47.3738 63.1373C48.9252 61.1449 49.7009 58.9252 49.7009 56.4673C49.7009 54.0094 49.0747 51.2374 47.8131 49.3209V49.3426Z" fill="#262626"/>
				</svg>
				<?php if($adv_section['title']): ?>
					<h2 class="section-title"><?php echo $adv_section['title']; ?></h2>
				<?php endif; ?>
				<?php if($adv_section['content']): ?>
					<div class="wysiwyg section-intro"><?php echo $adv_section['content']; ?></div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if($adv_section['advantages']): ?>
			<ul class="advantages-grid">
				<?php foreach($adv_section['advantages'] as $adv): ?>
					<li class="advantage-card">
						<?php if($adv['icon']): ?>
							<div class="card-icon"><?php echo $adv['icon']; ?></div>
						<?php endif; ?>
						<?php if($adv['title']): ?>
							<h3 class="card-title"><?php echo $adv['title']; ?></h3>
						<?php endif; ?>
						<?php if($adv['content']): ?>
							<div class="wysiwyg card-content"><?php echo $adv['content']; ?></div>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


	<?php // ====== STAFF ====== ?>
	<?php if($staff_section = get_field('staff')): if($staff_section['title'] || $staff_section['staff']): ?>
	<section class="home-section home-staff">
		<div class="container">
			<?php if($staff_section['title'] || $staff_section['content']): ?>
			<div class="section-header">
				<?php if($staff_section['title']): ?>
					<h2 class="section-title"><?php echo $staff_section['title']; ?></h2>
				<?php endif; ?>
				<?php if($staff_section['content']): ?>
					<div class="wysiwyg section-intro"><?php echo $staff_section['content']; ?></div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if($staff_section['staff']): ?>
			<div class="staff-grid">
				<div class="shadow"></div>
				<div class="staff-grid-inner">
					<?php foreach($staff_section['staff'] as $member_row): ?>
						<?php
							$member_id = $member_row['staff_member'];
							if(!$member_id) continue;
							$member_photo       = get_field('primary_photo', $member_id);
							$member_title       = get_field('title', $member_id);
							$member_experience  = get_field('experience', $member_id);
							$member_description = $member_row['description'];
							$member_name        = get_the_title($member_id);
							$member_link        = get_permalink($member_id);
						?>
						<div class="staff-card">
							<?php if($member_photo): ?>
								<figure class="staff-photo">
									<img src="<?php echo $member_photo['sizes']['large']; ?>" alt="<?php echo esc_attr($member_name); ?>" loading="lazy">
								</figure>
							<?php endif; ?>
							<div class="staff-info">
								<h3 class="staff-name"><?php echo $member_name; ?></h3>
								<?php if($member_title || $member_experience): ?>
									<p class="staff-role">
										<?php if($member_title): ?><span class="staff-title"><?php echo $member_title; ?></span><?php endif; ?>
										<?php if($member_title && $member_experience): ?><span class="staff-sep">|</span><?php endif; ?>
										<?php if($member_experience): ?><span class="staff-experience"><?php echo $member_experience; ?></span><?php endif; ?>
									</p>
								<?php endif; ?>
								<?php if($member_description): ?>
									<p class="staff-description"><?php echo $member_description; ?></p>
								<?php endif; ?>
								<a href="<?php echo $member_link; ?>" class="button">View Full Profile</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


	<?php // ====== CTA 1 ====== ?>
	<?php if($cta1 = get_field('cta_1')): if($cta1['title'] || $cta1['content']): ?>
	<section class="home-section home-cta home-cta-1">
		<div class="container">
			<?php if($cta1['title']): ?>
				<h2 class="section-title"><?php echo $cta1['title']; ?></h2>
			<?php endif; ?>
			<?php if($cta1['content']): ?>
				<div class="wysiwyg section-intro"><?php echo $cta1['content']; ?></div>
			<?php endif; ?>
			<?php if($cta1['button']): $btn = $cta1['button']; ?>
				<a href="<?php echo $btn['url']; ?>" target="<?php echo esc_attr($btn['target']); ?>" class="button"><?php echo $btn['title']; ?></a>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


	<?php // ====== RESULTS ====== ?>
	<?php if($results_section = get_field('results')): if($results_section['title'] || $results_section['results']): ?>
	<section class="home-section home-results">
		<div class="container">
			<?php if($results_section['title'] || $results_section['content']): ?>
			<div class="section-header">
				<?php if($results_section['title']): ?>
					<h2 class="section-title"><?php echo $results_section['title']; ?></h2>
				<?php endif; ?>
				<?php if($results_section['content']): ?>
					<div class="wysiwyg section-intro"><?php echo $results_section['content']; ?></div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if($results_section['results']): ?>
			<ul class="results-grid">
				<?php foreach($results_section['results'] as $result_row): ?>
					<?php
						$case_id = $result_row['case_result'];
						if(!$case_id) continue;

						$amount    = get_field('amount', $case_id);
						$case_link = get_permalink($case_id);

						// Primary (parent=0) and child case_category terms
						$case_cats   = wp_get_post_terms($case_id, 'case_category');
						$primary_cat = null;
						$child_cat   = null;
						if(!is_wp_error($case_cats)) {
							foreach($case_cats as $cat) {
								if($cat->parent == 0) $primary_cat = $cat;
								else                  $child_cat   = $cat;
							}
						}

						// Icon from primary case_category ACF field
						$cat_icon = '';
						if($primary_cat) {
							$cat_icon = get_field('icon', 'case_category_' . $primary_cat->term_id);
						}

						// Case outcome label
						$case_outcomes = wp_get_post_terms($case_id, 'case_outcome');
						$outcome = (!is_wp_error($case_outcomes) && $case_outcomes) ? $case_outcomes[0] : null;
					?>
					<li class="result-card<?php echo $outcome ? ' outcome-' . sanitize_html_class($outcome->slug) : ''; ?>">
						<a href="<?php echo $case_link; ?>" class="result-card-inner">
							<div class="result-card-strip">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 325 14.02"><path d="M325,14.02H57.9l-.27-.58c-2.58-5.63-5.38-10.62-6.38-11.4-4.12.34-42.5.05-49.26,0v10.98H0V.02h1.01c16.6.16,48.43.3,50.11.03.65-.17,2.25-.58,8.07,11.97h265.82v2ZM51.57,2s-.06.01-.11.02c.03,0,.07,0,.11-.02Z" /></svg>
								<?php if($outcome): ?>
									<span class="result-label"><?php echo $outcome->name; ?></span>
								<?php endif; ?>
							</div>
							<?php if($cat_icon): ?>
								<div class="result-icon"><?php echo $cat_icon; ?></div>
							<?php endif; ?>
							<?php if($primary_cat): ?>
								<h3 class="result-title"><?php echo $primary_cat->name; ?></h3>
							<?php endif; ?>
							<?php if($child_cat): ?>
								<p class="result-subtitle"><?php echo $child_cat->name; ?></p>
							<?php endif; ?>
							<div class="hover">
								<?php if($amount): ?>
									<p class="result-amount">$<?php echo number_format($amount); ?></p>
								<?php endif; ?>
								<p class="result-more"><span>Learn More</span> <i class="far fa-arrow-right"></i></p>
							</div>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			<?php if($results_section['note']): ?>
				<p class="results-note"><?php echo $results_section['note']; ?></p>
			<?php endif; ?>
			<?php if($results_section['buttons']): ?>
				<div class="section-buttons">
					<?php foreach($results_section['buttons'] as $btn_row): if($btn_row['button']): $btn = $btn_row['button']; ?>
						<a href="<?php echo $btn['url']; ?>" target="<?php echo esc_attr($btn['target']); ?>" class="button"><?php echo $btn['title']; ?></a>
					<?php endif; endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


	<?php // ====== REVIEWS ====== ?>
	<?php if($reviews_section = get_field('reviews')): if($reviews_section['title'] || $reviews_section['reviews']): ?>
	<section class="home-section home-reviews">
		<div class="container">
			<?php if($reviews_section['title'] || $reviews_section['content']): ?>
			<div class="section-header">
				<?php if($reviews_section['title']): ?>
					<h2 class="section-title"><?php echo $reviews_section['title']; ?></h2>
				<?php endif; ?>
				<?php if($reviews_section['content']): ?>
					<div class="wysiwyg section-intro"><?php echo $reviews_section['content']; ?></div>
				<?php endif; ?>
				<?php
					$review_count = wp_count_posts('review');
					$review_total = ($review_count && isset($review_count->publish)) ? (int) $review_count->publish : 0;
					$review_display = $review_total >= 100 ? '100+' : ($review_total >= 10 ? floor($review_total / 10) * 10 . '+' : $review_total);
				?>
				<div class="reviews-summary">
					<span class="reviews-stars">
						<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
					</span>
					<span class="reviews-rating">5.0</span>
					<?php if($review_total): ?>
						<span class="reviews-count"><?php echo esc_html($review_display); ?> Reviews</span>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<?php if($reviews_section['reviews']): ?>
			<div class="reviews-slider-wrap">
				<div class="reviews-slider">
					<?php foreach($reviews_section['reviews'] as $review_row): ?>
						<?php
							$review_id = is_array($review_row) ? $review_row['review'] : $review_row;
							if(!$review_id) continue;
							$review_name     = get_the_title($review_id);
							$review_post     = get_post($review_id);
							$review_content  = $review_post ? apply_filters('the_content', $review_post->post_content) : '';
							$review_location = get_field('location', $review_id);

							$review_cats = wp_get_post_terms($review_id, 'review_category');
							$review_cat = (!is_wp_error($review_cats) && !empty($review_cats)) ? $review_cats[0] : null;
						?>
						<div class="review-slide">
							<div class="review-card">
								<span class="review-quote-icon"><i class="fas fa-quote-right"></i></span>
								<div class="review-stars">
									<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
								</div>
								<?php if($review_content): ?>
									<blockquote class="review-content"><?php echo $review_content; ?></blockquote>
								<?php endif; ?>
								<cite class="review-attribution">
									<?php if($review_name): ?>
										<span class="review-name"><?php echo esc_html($review_name); ?></span>
									<?php endif; ?>
									<?php if($review_location): ?>
										<span class="review-sep">|</span>
										<span class="review-location"><?php echo esc_html($review_location); ?></span>
									<?php endif; ?>
								</cite>
								<?php if($review_cat): ?>
									<span class="review-cat-pill"><?php echo esc_html($review_cat->name); ?></span>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


	<?php // ====== FAQ ====== ?>
	<?php if($faq_section = get_field('faq')): if($faq_section['title'] || $faq_section['faqs']): ?>
	<section class="home-section home-faq">
		<div class="container">
			<?php if($faq_section['title'] || $faq_section['content']): ?>
			<div class="section-header">
				<?php if($faq_section['title']): ?>
					<h2 class="section-title"><?php echo $faq_section['title']; ?></h2>
				<?php endif; ?>
				<?php if($faq_section['content']): ?>
					<div class="wysiwyg section-intro"><?php echo $faq_section['content']; ?></div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if($faq_section['faqs']): ?>
			<ul class="faq-list">
				<?php foreach($faq_section['faqs'] as $faq_id): ?>
					<?php
						$faq_question = get_the_title($faq_id);
						$faq_answer   = get_the_content(null, false, $faq_id);
						$faq_cats     = wp_get_post_terms($faq_id, 'faq_category');
						$faq_cat      = (!is_wp_error($faq_cats) && $faq_cats) ? $faq_cats[0] : null;
						$faq_cat_icon = $faq_cat ? get_field('icon', 'faq_category_' . $faq_cat->term_id) : '';
					?>
					<li data-action="faq">
						<div class="question">
							<?php if($faq_cat_icon): ?>
								<span class="faq-cat-icon"><?php echo $faq_cat_icon; ?></span>
							<?php endif; ?>
							<div class="question-body">
								<?php if($faq_cat): ?>
									<span class="faq-cat-tag"><?php echo esc_html($faq_cat->name); ?></span>
								<?php endif; ?>
								<span class="question-text"><?php echo $faq_question; ?></span>
							</div>
						</div>
						<div class="answer">
							<?php if($faq_answer): ?>
								<div class="wysiwyg"><?php echo $faq_answer; ?></div>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


	<?php // ====== CTA 2 ====== ?>
	<?php if($cta2 = get_field('cta_2')): if($cta2['title'] || $cta2['content']): ?>
	<section class="home-section home-cta home-cta-2">
		<div class="container">
			<?php if($cta2['title']): ?>
				<h2 class="section-title"><?php echo $cta2['title']; ?></h2>
			<?php endif; ?>
			<?php if($cta2['content']): ?>
				<div class="wysiwyg section-intro"><?php echo $cta2['content']; ?></div>
			<?php endif; ?>
			<?php if($cta2['button']): $btn = $cta2['button']; ?>
				<a href="<?php echo $btn['url']; ?>" target="<?php echo esc_attr($btn['target']); ?>" class="button outline black"><?php echo $btn['title']; ?></a>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


	<?php // ====== CTA 3 — Phone Bar ====== ?>
	<?php if($cta3 = get_field('cta_3')): if($cta3['title']): ?>
	<?php $contact_options = get_field('contact', 'option'); ?>
	<section class="home-section home-cta-3">
		<svg class="icon-bg" xmlns="http://www.w3.org/2000/svg" width="50" height="70" viewBox="0 0 50 70" fill="none">
		  <path d="M43.1589 22.4354C40.8505 21.136 38.2243 20.3781 35.2617 20.1507V4.4286C35.2991 3.3783 35.6355 2.47958 36.2804 1.73246C36.9252 0.985336 37.7009 0.606361 38.6075 0.606361H39.2897V0H24.8318V0.606361H25.514C26.4206 0.606361 27.2056 0.985336 27.8692 1.73246C28.5327 2.47958 28.8692 3.3783 28.8692 4.4286V20.2156H10.4299V4.4286C10.4673 3.3783 10.8037 2.47958 11.4486 1.73246C12.0935 0.985336 12.8692 0.606361 13.7757 0.606361H14.4579V0H0V0.606361H0.682242C1.58878 0.606361 2.36448 0.985336 3.00934 1.73246C3.6542 2.47958 4 3.3783 4.02804 4.4286V38.06C3.99065 39.1103 3.6542 40.009 3.00934 40.7561C2.36448 41.5032 1.58878 41.8822 0.682242 41.8822H0V42.4886H14.4579V41.8822H13.7757C12.8692 41.8822 12.0935 41.5032 11.4486 40.7561C10.8037 40.009 10.4579 39.1103 10.4299 38.06V22.522H28.8692V35.9377C29.2804 36.1976 29.7103 36.4466 30.1776 36.7065C31.6916 37.5078 33.3365 38.309 35.1028 39.1103C35.1589 39.1319 35.2056 39.1536 35.2617 39.1861C35.3925 39.2402 35.5234 39.3052 35.6542 39.3593C36.1308 39.5759 36.5794 39.7816 37.028 39.9873C36.7664 39.8141 36.514 39.5975 36.2897 39.3268C35.6449 38.5797 35.2991 37.681 35.271 36.6307V23.2366C36.6355 23.4532 37.8224 23.9188 38.8037 24.6442C40.2056 25.6729 40.9159 27.1022 40.9159 28.9212C40.9159 30.7403 40.6542 30.762 40.1402 31.3575C39.6262 31.9422 39.028 32.3862 38.3645 32.6893C37.7009 32.9817 37.3645 33.1549 37.3645 33.2091C37.3645 33.653 37.6636 34.1295 38.2523 34.6492C38.8411 35.1689 39.7103 35.4505 40.8411 35.4938C42.7664 35.4938 44.3271 34.9199 45.5421 33.7613C46.7477 32.6027 47.3551 31.141 47.3551 29.376C47.3551 26.3334 45.9626 24.0162 43.1776 22.4462H43.1589V22.4354Z" fill="#9B7839"/>
		  <path d="M47.8131 49.3426C46.5514 47.426 45 45.8776 43.1495 44.6974C42.0654 44.0044 40.757 43.2681 39.2243 42.4993C38.7383 42.2503 38.2336 42.0121 37.7103 41.7522C37.486 41.6439 37.2523 41.5357 37.0187 41.4274C36.5794 41.2217 36.1215 41.0051 35.6448 40.7994C35.514 40.7452 31.243 38.8612 28.4579 37.1287C26.0467 35.6236 24.8411 34.5517 24.1121 33.653C23.1775 32.4944 22.6635 31.5957 22.6635 29.874C22.6635 28.1524 22.9065 25.8677 25.2617 23.778C23.3925 24.1786 21.1682 25.283 19.7383 26.6365C18.299 28.0008 17.4766 29.6791 17.3832 32.8301C17.3084 35.4829 18.028 37.7026 19.3084 39.5C20.5888 41.2975 22.1402 42.7267 23.9719 43.8095C25.7944 44.8923 28.1869 46.0942 31.1495 47.4152C33.7103 48.5413 35.7383 49.5375 37.2149 50.4037C38.6916 51.2591 39.9346 52.3311 40.9533 53.6087C41.9626 54.8864 42.4673 56.4132 42.4673 58.1781C42.4673 59.943 42.0561 60.9717 41.243 62.271C40.4299 63.5704 39.2617 64.6207 37.729 65.4003C36.1962 66.1907 34.1121 66.5805 32.0934 66.5805C29.1308 66.5805 26.4953 65.422 25.0841 64.1659C23.6822 62.9099 23.1308 61.3074 23.1308 59.001C23.1308 56.6947 23.3271 56.9762 23.7196 56.3915C24.1121 55.8068 24.5607 55.3629 25.0467 55.0597C25.542 54.7673 25.785 54.5941 25.785 54.5399C25.785 54.096 25.486 53.6196 24.8972 53.0998C24.3084 52.5801 23.4392 52.2986 22.3084 52.2553C20.3832 52.2553 18.8224 52.8291 17.6075 53.9877C16.4018 55.1463 15.9532 56.7813 15.9532 58.5462C15.9532 62.0328 17.4766 65.0321 20.2149 66.8296C22.9532 68.627 26.9065 69.5149 32.0841 69.5149C37.2617 69.5149 38.4486 68.9518 41.0841 67.8149C43.7196 66.6888 45.8131 65.1296 47.3738 63.1373C48.9252 61.1449 49.7009 58.9252 49.7009 56.4673C49.7009 54.0094 49.0747 51.2374 47.8131 49.3209V49.3426Z" fill="#9B7839"/>
		</svg>
		<div class="container">
			<div class="cta-3-left">
				<?php if($cta3['icon']): ?>
					<div class="cta-3-icon"><?php echo $cta3['icon']; ?></div>
				<?php endif; ?>
				<div class="cta-3-text">
					<?php if($cta3['label']): ?>
						<p class="cta-3-label"><?php echo $cta3['label']; ?></p>
					<?php endif; ?>
					<?php if($cta3['title']): ?>
						<h2 class="cta-3-title"><?php echo $cta3['title']; ?></h2>
					<?php endif; ?>
					<?php if($cta3['subtitle']): ?>
						<p class="cta-3-subtitle"><?php echo $cta3['subtitle']; ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php if($contact_options && $contact_options['phone']): ?>
			<a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $contact_options['phone']); ?>" class="cta-3-phone">
				<i class="fas fa-phone"></i>
				<?php if($cta3['phone_button_text']): ?>
					<span class="cta-3-phone-label"><?php echo $cta3['phone_button_text']; ?></span>
				<?php endif; ?>
				<span class="cta-3-phone-number"><?php echo $contact_options['phone']; ?></span>
			</a>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>


</article>
<?php endwhile; ?>
<?php get_footer();
