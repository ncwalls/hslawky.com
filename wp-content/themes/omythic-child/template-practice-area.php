<?php
/*
Template Name: Practice Area
*/

get_header(); ?>

<?php while( have_posts() ): the_post(); ?>

<?php
	$hero        = get_field('hero');
	$body        = get_field('body');
	$clients_say = get_field('clients_say');
	$rights      = get_field('rights');
	$faq         = get_field('faq');
	$defaults    = get_field('practice_area_defaults', 'option') ?: array();
	$contact     = get_field('contact', 'option');

	// Per-page overrides of the defaults. Each field falls back to the option value when blank.
	// Scalars: page value wins when non-empty. Repeaters: page array wins when it has rows.
	if(!function_exists('pa_apply_overrides')){
		function pa_apply_overrides($override, $default){
			if(!is_array($default)) $default = array();
			if(!is_array($override) || empty($override)) return $default;
			$out = $default;
			foreach($override as $k => $v){
				$has_value = $v !== '' && $v !== null && $v !== false && $v !== array();
				if(!$has_value) continue;
				// Repeaters / numeric arrays: replace whole list when page has at least one row.
				if(is_array($v) && array_keys($v) === range(0, count($v) - 1)){
					$out[$k] = $v;
				}
				// Nested group (associative array): recurse so each subfield falls back individually.
				elseif(is_array($v) && (!isset($default[$k]) || is_array($default[$k]))){
					$out[$k] = pa_apply_overrides($v, $default[$k] ?? array());
				}
				else {
					$out[$k] = $v;
				}
			}
			return $out;
		}
	}

	$what_happens   = pa_apply_overrides(get_field('what_happens'),       $defaults['what_happens']   ?? array());
	$case_review    = pa_apply_overrides(get_field('case_review'),        $defaults['case_review']    ?? array());
	$cities         = pa_apply_overrides(get_field('cities'),             $defaults['cities']         ?? array());
	$referral       = pa_apply_overrides(get_field('referral'),           $defaults['referral']       ?? array());
	$further_heading = get_field('further_heading') ?: ($defaults['further_heading'] ?? 'Further reading');
	$further_button  = get_field('further_button')  ?: ($defaults['further_button']  ?? null);
	$related_heading = get_field('related_heading') ?: ($defaults['related_heading'] ?? 'Related Practice Areas');

	// Posts in Further Reading: per-page picks (max 2) override the 2 latest.
	$picked_posts = get_field('further_posts');
	if($picked_posts){
		$ids = array_map('intval', (array) $picked_posts);
		$further_reading = get_posts(array(
			'post_type'   => 'post',
			'post__in'    => $ids,
			'orderby'     => 'post__in',
			'numberposts' => 2,
		));
	} else {
		$further_reading = get_posts(array(
			'numberposts' => 2,
			'post_type'   => 'post',
			'post_status' => 'publish',
		));
	}

	$siblings  = get_pages(array(
		'child_of'    => wp_get_post_parent_id($post),
		'parent'      => wp_get_post_parent_id($post),
		'exclude'     => array($post->ID),
		'sort_column' => 'menu_order,post_title',
		'sort_order'  => 'ASC',
	));

	// H&S monogram for resource cards
	$monogram_svg = '<svg viewBox="0 0 50 70" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M43.16 22.44c-2.31-1.3-4.93-2.06-7.9-2.29V4.43c.04-1.05.37-1.95 1.02-2.7.64-.75 1.42-1.13 2.32-1.13h.69V0H24.83v.61h.69c.9 0 1.69.38 2.35 1.13.66.75 1 1.65 1.03 2.7v15.79H10.43V4.43c.04-1.05.37-1.95 1.02-2.7.64-.75 1.42-1.13 2.32-1.13h.69V0H0v.61h.68c.9 0 1.68.38 2.33 1.13.65.75.99 1.65 1.02 2.7v33.63c-.04 1.05-.37 1.95-1.02 2.7-.64.75-1.42 1.13-2.33 1.13H0v.61h14.46v-.61h-.69c-.9 0-1.68-.38-2.33-1.13-.65-.75-.99-1.65-1.02-2.7v-15.54h18.44v13.42c.41.26.84.51 1.31.77 1.51.8 3.16 1.6 4.92 2.4.06.02.11.04.16.07.13.05.26.12.4.17.47.22.92.43 1.4.63-.26-.17-.51-.39-.74-.66-.64-.75-.99-1.65-1.02-2.7V23.24c1.36.22 2.55.68 3.53 1.4 1.4 1.03 2.11 2.46 2.11 4.28s-.26 1.84-.78 2.44c-.51.58-1.11 1.03-1.77 1.33-.66.29-1 .46-1 .52 0 .44.3.92.89 1.44.59.52 1.46.8 2.59.84 1.93 0 3.49-.57 4.7-1.73 1.21-1.16 1.81-2.62 1.81-4.39 0-3.04-1.39-5.36-4.18-6.93h-.02v-.01zm4.65 26.9c-1.26-1.92-2.81-3.46-4.66-4.64-1.08-.69-2.39-1.43-3.93-2.2-.49-.25-.99-.49-1.51-.75-.22-.11-.46-.22-.69-.33-.44-.21-.9-.42-1.38-.63-.13-.05-4.4-1.93-7.19-3.67-2.41-1.51-3.62-2.58-4.35-3.48-.93-1.16-1.45-2.06-1.45-3.78s.24-4.01 2.6-6.1c-1.87.4-4.1 1.5-5.53 2.86-1.44 1.36-2.26 3.04-2.36 6.19-.07 2.65.65 4.87 1.93 6.67 1.28 1.8 2.83 3.23 4.66 4.31 1.82 1.08 4.22 2.29 7.18 3.61 2.56 1.13 4.59 2.12 6.07 2.99 1.48.86 2.72 1.93 3.74 3.21 1.01 1.28 1.51 2.8 1.51 4.57s-.41 2.79-1.22 4.09c-.81 1.3-1.98 2.35-3.51 3.13-1.53.79-3.62 1.18-5.63 1.18-2.96 0-5.6-1.16-7.01-2.41-1.4-1.26-1.95-2.86-1.95-5.17s.2-2.02.59-2.61c.39-.58.84-1.03 1.33-1.33.5-.29.74-.46.74-.52 0-.44-.3-.92-.89-1.44-.59-.52-1.46-.8-2.59-.84-1.93 0-3.49.57-4.7 1.73-1.21 1.16-1.66 2.79-1.66 4.56 0 3.49 1.52 6.49 4.26 8.29 2.74 1.8 6.69 2.69 11.87 2.69s6.36-.56 9-1.7c2.64-1.13 4.73-2.69 6.29-4.68 1.55-1.99 2.33-4.21 2.33-6.67s-.63-4.85-1.89-6.77v.02h-.01z" fill="currentColor"/></svg>';
?>

<article <?php post_class('practice-area'); ?> id="post-<?php the_ID(); ?>">

	<?php // ====== HERO ====== ?>
	<section class="pa-hero" <?php if($hero && !empty($hero['image']['url'])): ?>style="background-image: url('<?php echo esc_url($hero['image']['url']); ?>');"<?php endif; ?>>
		<div class="container">
			<div class="pa-hero-inner">
				<?php if($hero && $hero['subtitle']): ?>
					<p class="pa-hero-eyebrow"><?php echo $hero['subtitle']; ?></p>
				<?php endif; ?>
				<h1 class="pa-hero-tagline"><?php echo $hero && $hero['tagline'] ? $hero['tagline'] : get_the_title(); ?></h1>
				<?php if($hero && $hero['intro']): ?>
					<div class="wysiwyg pa-hero-intro"><?php echo $hero['intro']; ?></div>
				<?php endif; ?>
				<div class="pa-hero-actions">
					<?php if($hero && !empty($hero['button'])): $btn = $hero['button']; ?>
						<a href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target']); ?>" class="pa-hero-button-primary"><?php echo esc_html($btn['title']); ?></a>
					<?php endif; ?>
					<?php if($contact && $contact['phone']): ?>
						<span class="pa-hero-or">&ndash; &nbsp;or call&nbsp; &ndash;</span>
						<a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $contact['phone']); ?>" class="pa-hero-button-phone"><?php echo esc_html($contact['phone']); ?></a>
					<?php endif; ?>
				</div>
				<?php if(!empty($case_review['trust_badges'])): ?>
					<p class="pa-hero-trust">
						<?php
							$labels = array();
							foreach($case_review['trust_badges'] as $b){
								if(!empty($b['label'])) $labels[] = esc_html($b['label']);
							}
							echo implode('  &nbsp;·&nbsp;  ', $labels);
						?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<?php
			// Page-level hero.stats wins; otherwise show the site-wide hero credentials.
			$hero_credentials = !empty($hero['stats']) ? $hero['stats'] : ($defaults['hero_credentials'] ?? array());
		?>
		<?php if($hero_credentials): ?>
			<div class="pa-hero-credentials-wrap">
				<div class="container">
					<ul class="pa-hero-credentials">
						<?php foreach($hero_credentials as $stat): ?>
							<li>
								<?php if($stat['value']): ?><span class="pa-hero-cred-value"><?php echo esc_html($stat['value']); ?></span><?php endif; ?>
								<?php if($stat['label']): ?><span class="pa-hero-cred-label"><?php echo esc_html($stat['label']); ?></span><?php endif; ?>
								<?php if($stat['sublabel']): ?><span class="pa-hero-cred-sublabel"><?php echo esc_html($stat['sublabel']); ?></span><?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>
		<?php if($contact && $contact['phone']): ?>
			<a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $contact['phone']); ?>" class="pa-hero-phone-corner" aria-label="Call <?php echo esc_attr($contact['phone']); ?>">
				<i class="fas fa-phone"></i>
			</a>
		<?php endif; ?>
	</section>

	<?php // ====== BODY INTRO ("Up Against") ====== ?>
	<?php if($body && ($body['title'] || $body['content'])): ?>
	<section class="pa-section pa-intro">
		<div class="container">
			<div class="pa-intro-grid">
				<div class="pa-intro-main">
					<div class="pa-intro-head">
						<?php if($body['eyebrow']): ?>
							<p class="pa-intro-eyebrow"><?php echo $body['eyebrow']; ?></p>
						<?php endif; ?>
						<?php if($body['title']): ?>
							<h2 class="pa-intro-title"><?php echo $body['title']; ?></h2>
						<?php endif; ?>
						<?php if($body['content']): ?>
							<div class="wysiwyg pa-intro-content"><?php echo $body['content']; ?></div>
						<?php endif; ?>
					</div>
					<?php if(!empty($body['image']['url'])): ?>
						<figure class="pa-intro-image">
							<img src="<?php echo esc_url($body['image']['url']); ?>" alt="<?php echo esc_attr($body['image']['alt']); ?>">
						</figure>
					<?php endif; ?>
					<?php if($body['quote']): $q = $body['quote']; if($q['quote']): ?>
						<blockquote class="pa-intro-testimonial">
							<p class="pa-intro-testimonial-quote">&ldquo;<?php echo $q['quote']; ?>&rdquo;</p>
							<?php if($q['attribution']): ?>
								<cite class="pa-intro-testimonial-cite"><?php echo esc_html($q['attribution']); ?><?php if($q['attribution_meta']): ?>, <?php echo esc_html($q['attribution_meta']); ?><?php endif; ?></cite>
							<?php endif; ?>
						</blockquote>
					<?php endif; endif; ?>
				</div>
				<?php if($body['points']): ?>
					<aside class="pa-intro-side">
						<div class="pa-intro-points">
							<?php if($body['points_title']): ?>
								<h3 class="pa-intro-points-title"><?php echo $body['points_title']; ?></h3>
							<?php endif; ?>
							<ol class="pa-intro-points-list">
								<?php foreach($body['points'] as $i => $pt): ?>
									<li>
										<span class="pa-intro-points-number"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
										<div class="pa-intro-points-body">
											<?php if($pt['title']): ?><strong class="pa-intro-points-item-title"><?php echo esc_html($pt['title']); ?></strong><?php endif; ?>
											<?php if($pt['content']): ?><p><?php echo $pt['content']; ?></p><?php endif; ?>
										</div>
									</li>
								<?php endforeach; ?>
							</ol>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php // ====== WHAT CLIENTS SAY (featured testimonial bar) ====== ?>
	<?php if($clients_say && $clients_say['quote']): ?>
	<section class="pa-section pa-clients-say">
		<div class="container pa-clients-say-grid">
			<div class="pa-clients-say-main">
				<?php if($clients_say['eyebrow']): ?>
					<p class="pa-eyebrow pa-eyebrow-on-gold"><?php echo $clients_say['eyebrow']; ?></p>
				<?php endif; ?>
				<?php if($clients_say['title']): ?>
					<h2 class="pa-clients-say-title"><?php echo $clients_say['title']; ?></h2>
				<?php endif; ?>
				<div class="pa-clients-say-stars" aria-label="5 stars">
					<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
				</div>
				<blockquote class="pa-clients-say-quote">
					<p>&ldquo;<?php echo $clients_say['quote']; ?>&rdquo;</p>
				</blockquote>
				<?php if($clients_say['author_name']): ?>
					<p class="pa-clients-say-author">
						<span class="pa-clients-say-author-name"><?php echo esc_html($clients_say['author_name']); ?></span>
						<?php if($clients_say['author_city']): ?>
							<span class="pa-clients-say-author-sep">&nbsp; | &nbsp;</span>
							<span class="pa-clients-say-author-city"><?php echo esc_html($clients_say['author_city']); ?></span>
						<?php endif; ?>
					</p>
				<?php endif; ?>
				<?php if($clients_say['case_type']): ?>
					<p class="pa-clients-say-case-type"><?php echo esc_html($clients_say['case_type']); ?></p>
				<?php endif; ?>
			</div>
			<?php if(!empty($clients_say['stats'])): ?>
				<ul class="pa-clients-say-stats">
					<?php foreach($clients_say['stats'] as $stat): ?>
						<li>
							<span class="value<?php echo !empty($stat['strikethrough']) ? ' is-strike' : ''; ?>"><?php echo esc_html($stat['value']); ?></span>
							<span class="label"><?php echo esc_html($stat['label']); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php // ====== WHAT HAPPENS WHEN YOU CALL ====== ?>
	<?php if(!empty($what_happens) && !empty($what_happens['steps'])): $wh = $what_happens; ?>
	<section class="pa-section pa-what-happens">
		<div class="container">
			<?php if($wh['title']): ?>
				<h2 class="pa-what-happens-title"><?php echo $wh['title']; ?></h2>
			<?php endif; ?>
			<?php if($wh['intro']): ?>
				<p class="pa-what-happens-intro"><?php echo $wh['intro']; ?></p>
			<?php endif; ?>
			<ol class="pa-steps">
				<?php foreach($wh['steps'] as $i => $step): ?>
					<li class="pa-step">
						<span class="pa-step-number"><?php echo ($i + 1); ?></span>
						<?php if($step['title']): ?>
							<h3 class="pa-step-title"><?php echo esc_html($step['title']); ?></h3>
						<?php endif; ?>
						<?php if($step['content']): ?>
							<p class="pa-step-content"><?php echo $step['content']; ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>
	<?php endif; ?>

	<?php // ====== START HERE — CASE REVIEW CTA ====== ?>
	<?php if(!empty($case_review)): $cr = $case_review; if(!empty($cr['title'])): ?>
	<section class="pa-section pa-case-review">
		<div class="container pa-case-review-inner">
			<div class="pa-case-review-text">
				<?php if($cr['eyebrow']): ?>
					<p class="pa-eyebrow"><?php echo $cr['eyebrow']; ?></p>
				<?php endif; ?>
				<h2 class="pa-case-review-title"><?php echo $cr['title']; ?></h2>
				<?php if($cr['subtitle']): ?>
					<p class="pa-case-review-subtitle"><?php echo $cr['subtitle']; ?></p>
				<?php endif; ?>
				<div class="pa-case-review-actions">
					<?php if(!empty($cr['button'])): $btn = $cr['button']; ?>
						<a href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target']); ?>" class="button pa-case-review-button"><?php echo esc_html($btn['title']); ?></a>
					<?php endif; ?>
					<?php if($contact && $contact['phone']): ?>
						<span class="pa-case-review-or">— or call —</span>
						<a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $contact['phone']); ?>" class="pa-case-review-phone"><?php echo esc_html($contact['phone']); ?></a>
					<?php endif; ?>
				</div>
				<?php if(!empty($cr['trust_badges'])): ?>
					<ul class="pa-case-review-trust">
						<?php foreach($cr['trust_badges'] as $b): ?>
							<li><?php echo esc_html($b['label']); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<?php if(!empty($cr['stats'])): ?>
				<ul class="pa-case-review-stats">
					<?php foreach($cr['stats'] as $stat): ?>
						<li>
							<span class="value"><?php echo esc_html($stat['value']); ?></span>
							<span class="label"><?php echo esc_html($stat['label']); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; endif; ?>

	<?php // ====== KNOW YOUR RIGHTS + FAQs ====== ?>
	<?php if($rights && ($rights['title'] || $rights['facts'])): ?>
	<section class="pa-section pa-rights">
		<div class="container pa-rights-grid">
			<div class="pa-rights-main">
				<?php if($rights['eyebrow']): ?>
					<p class="pa-eyebrow"><?php echo $rights['eyebrow']; ?></p>
				<?php endif; ?>
				<?php if($rights['title']): ?>
					<h2 class="pa-rights-title"><?php echo $rights['title']; ?></h2>
				<?php endif; ?>
				<?php if($rights['intro']): ?>
					<div class="wysiwyg pa-rights-intro"><?php echo $rights['intro']; ?></div>
				<?php endif; ?>
				<?php if($rights['facts']): ?>
					<div class="pa-rights-facts">
						<?php foreach($rights['facts'] as $fact): ?>
							<div class="pa-rights-fact">
								<?php if($fact['title']): ?>
									<h3 class="pa-rights-fact-title"><?php echo esc_html($fact['title']); ?></h3>
								<?php endif; ?>
								<?php if($fact['content']): ?>
									<div class="wysiwyg pa-rights-fact-content"><?php echo $fact['content']; ?></div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<aside class="pa-rights-sidebar">
				<?php if($rights['questions_card_eyebrow'] || $rights['sidebar_title']): ?>
				<div class="pa-rights-card pa-rights-card-questions">
					<?php if($rights['questions_card_eyebrow']): ?>
						<p class="pa-rights-card-eyebrow"><?php echo $rights['questions_card_eyebrow']; ?></p>
					<?php endif; ?>
					<?php if($rights['sidebar_title']): ?>
						<p class="pa-rights-card-subtitle"><?php echo $rights['sidebar_title']; ?></p>
					<?php endif; ?>
					<?php
						// Prefer the per-page Questions Card Button; fall back to the global case-review CTA.
						$qbtn = !empty($rights['questions_card_button']) ? $rights['questions_card_button'] : (!empty($case_review['button']) ? $case_review['button'] : null);
					?>
					<?php if($qbtn): ?>
						<a href="<?php echo esc_url($qbtn['url']); ?>" target="<?php echo esc_attr($qbtn['target']); ?>" class="button"><?php echo esc_html($qbtn['title']); ?></a>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if($rights['statute_eyebrow'] || $rights['statute_value']): ?>
				<div class="pa-rights-card pa-rights-card-statute">
					<?php if($rights['statute_eyebrow']): ?>
						<p class="pa-rights-card-eyebrow"><?php echo $rights['statute_eyebrow']; ?></p>
					<?php endif; ?>
					<?php if($rights['statute_value']): ?>
						<p class="pa-rights-card-statute-value"><?php echo $rights['statute_value']; ?></p>
					<?php endif; ?>
					<?php if($rights['sidebar_subtitle']): ?>
						<p class="pa-rights-card-statute-desc"><?php echo $rights['sidebar_subtitle']; ?></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</aside>

			<?php if($faq && !empty($faq['items'])): ?>
			<div class="pa-faq">
				<?php if($faq['title']): ?>
					<p class="pa-eyebrow"><?php echo $faq['title']; ?></p>
				<?php endif; ?>
				<ul class="pa-faq-list">
					<?php foreach($faq['items'] as $i => $item): $open = $i === 0; ?>
						<li class="pa-faq-item<?php echo $open ? ' is-open' : ''; ?>">
							<button class="pa-faq-question" type="button" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
								<span><?php echo esc_html($item['question']); ?></span>
								<i class="far fa-chevron-down"></i>
							</button>
							<div class="pa-faq-answer wysiwyg"><?php echo $item['answer']; ?></div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php // ====== CHAOS: Further Reading + Related Areas + Cities + Referral ====== ?>
	<section class="pa-section pa-discover">
		<div class="container">

			<?php if($further_reading || $siblings): ?>
			<div class="pa-discover-grid">

				<?php if($further_reading): ?>
				<div class="pa-further-reading">
					<h2 class="pa-discover-title"><?php echo esc_html($further_heading); ?></h2>
					<ul class="pa-further-cards">
						<?php foreach($further_reading as $post_obj): setup_postdata($post_obj); ?>
							<li class="pa-further-card">
								<a href="<?php echo get_permalink($post_obj->ID); ?>">
									<span class="pa-further-card-mark"><?php echo $monogram_svg; ?></span>
									<h3 class="pa-further-card-title"><?php echo get_the_title($post_obj->ID); ?></h3>
									<?php $excerpt = get_the_excerpt($post_obj->ID); if($excerpt): ?>
										<p class="pa-further-card-excerpt"><?php echo wp_trim_words($excerpt, 22); ?></p>
									<?php endif; ?>
								</a>
							</li>
						<?php endforeach; wp_reset_postdata(); ?>
					</ul>
					<?php if(!empty($further_button) && !empty($further_button['url'])): ?>
						<a href="<?php echo esc_url($further_button['url']); ?>" target="<?php echo esc_attr($further_button['target']); ?>" class="pa-discover-button"><?php echo esc_html($further_button['title'] ?: 'Browse All Resources'); ?></a>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if($siblings): ?>
				<div class="pa-related-areas">
					<h2 class="pa-discover-title"><?php echo esc_html($related_heading); ?></h2>
					<ul class="pa-related-list">
						<?php foreach($siblings as $sib): ?>
							<li class="pa-related-card">
								<a href="<?php echo get_permalink($sib->ID); ?>">
									<h3><?php echo get_the_title($sib->ID); ?></h3>
									<?php $exc = get_the_excerpt($sib->ID); if($exc): ?>
										<p><?php echo wp_trim_words($exc, 14); ?></p>
									<?php endif; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>

			</div>
			<?php endif; ?>

			<?php if(!empty($cities) && !empty($cities['items'])): $c = $cities; ?>
			<div class="pa-discover-divider"></div>
			<div class="pa-cities">
				<?php if($c['title']): ?>
					<h2 class="pa-discover-title"><?php echo $c['title']; ?></h2>
				<?php endif; ?>
				<ul class="pa-cities-list">
					<?php foreach($c['items'] as $city): ?>
						<li>
							<?php if(!empty($city['link']['url'])): ?>
								<a href="<?php echo esc_url($city['link']['url']); ?>" class="pa-city-pill"><?php echo esc_html($city['name']); ?></a>
							<?php else: ?>
								<span class="pa-city-pill"><?php echo esc_html($city['name']); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
					<?php if(!empty($c['statewide_label'])): ?>
						<li>
							<?php if(!empty($c['statewide_link']['url'])): ?>
								<a href="<?php echo esc_url($c['statewide_link']['url']); ?>" class="pa-city-pill is-statewide"><?php echo esc_html($c['statewide_label']); ?></a>
							<?php else: ?>
								<span class="pa-city-pill is-statewide"><?php echo esc_html($c['statewide_label']); ?></span>
							<?php endif; ?>
						</li>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>

			<?php if(!empty($referral) && !empty($referral['title'])): $r = $referral; ?>
			<div class="pa-discover-divider"></div>
			<div class="pa-referral">
				<h2 class="pa-discover-title"><?php echo $r['title']; ?></h2>
				<?php if($r['content']): ?>
					<p class="pa-referral-content"><?php echo $r['content']; ?></p>
				<?php endif; ?>
				<?php if(!empty($r['button'])): $btn = $r['button']; ?>
					<a href="<?php echo esc_url($btn['url']); ?>" target="<?php echo esc_attr($btn['target']); ?>" class="pa-discover-button"><?php echo esc_html($btn['title']); ?></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>

		</div>
	</section>

</article>

<?php endwhile; ?>

<?php get_footer();
