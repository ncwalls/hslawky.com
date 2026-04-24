<?php get_header(); ?>

<div class="blog-archive">
	<div class="container">

		<div class="archive-header">
			<?php if( is_search() ): ?>
				<p class="archive-eyebrow">Search Results</p>
				<h1>Results for &ldquo;<?php echo htmlentities( get_search_query() ); ?>&rdquo;</h1>
			<?php elseif( single_term_title( '', false ) ): ?>
				<p class="archive-eyebrow">Category</p>
				<h1><?php single_term_title(); ?></h1>
			<?php else: ?>
				<h1><?php echo get_the_title( get_option( 'page_for_posts' ) ); ?></h1>
				<div class="archive-intro wysiwyg">
					<?php echo apply_filters( 'the_content', get_post_field( 'post_content', get_option( 'page_for_posts' ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="filter-container">
			<div class="filter-label">Filter By</div>
			<div class="filter-dropdown">
				<div class="filter-display">
					<?php
						if( single_term_title( '', false ) ){
							single_term_title();
						} else {
							echo 'All Categories';
						}
					?>
				</div>
				<nav class="dropdown-list">
					<ul>
						<?php $post_type_name = get_post_type_object( get_post_type( get_the_ID() ) )->labels->name; ?>
						<li><a title="View All <?php echo $post_type_name; ?>" href="<?php echo get_post_type_archive_link( get_post_type( get_the_ID() ) ); ?>">All Articles</a></li>
						<?php
							$categories = get_categories( array(
								'orderby' => 'name',
								'order'   => 'ASC'
							) );
							foreach( $categories as $category ){
								$caturl  = get_category_link( $category->term_id );
								$catname = $category->name;
								$active  = is_category( $category->term_id ) ? ' class="active"' : '';
								echo '<li' . $active . '><a title="' . esc_attr( $catname ) . '" href="' . esc_url( $caturl ) . '">' . esc_html( $catname ) . '</a></li>';
							}
						?>
					</ul>
				</nav>
			</div>
		</div>

		<div class="archive-list">
			<?php while( have_posts() ): the_post(); ?>
				<article <?php post_class( 'post-card' ); ?> id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/BlogPosting">
					<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" class="post-card-link">

						<?php
							$thumb_image = '';
							if( get_the_post_thumbnail_url() ){
								$thumb_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
							} else {
								$placeholder = get_field( 'default_placeholder_image', 'option' );
								if( $placeholder ) $thumb_image = $placeholder['sizes']['medium'];
							}
							$cats = get_the_category();
						?>

						<figure class="post-thumbnail">
							<div class="image">
								<?php if( $thumb_image ): ?>
									<img src="<?php echo esc_url( $thumb_image ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
								<?php endif; ?>
							</div>
							<?php if( $cats && ! is_search() ): ?>
								<div class="post-category"><?php echo esc_html( $cats[0]->name ); ?></div>
							<?php endif; ?>
						</figure>

						<div class="content">
							<h2 class="post-title" itemprop="name"><?php the_title(); ?></h2>

							<?php if( is_search() ): ?>
								<?php
									$post_type_obj   = get_post_type_object( get_post_type() );
									$post_type_label = false;
									if( $post_type_obj->name === 'post' ){
										$post_type_label = 'Blog Post';
									} elseif( $post_type_obj && $post_type_obj->name !== 'page' ){
										$post_type_label = $post_type_obj->labels->singular_name;
									}
									if( $post_type_label ):
								?>
									<ul class="post-meta"><li><?php echo esc_html( $post_type_label ); ?></li></ul>
								<?php endif; ?>
							<?php else: ?>
								<ul class="post-meta">
									<li><i class="far fa-calendar" aria-hidden="true"></i><?php the_time( 'F j, Y' ); ?></li>
									<li><i class="far fa-clock" aria-hidden="true"></i><?php read_time(); ?></li>
								</ul>
							<?php endif; ?>

							<div class="post-excerpt">
								<?php the_excerpt(); ?>
							</div>

							<span class="button outline gold">Read Article</span>
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
