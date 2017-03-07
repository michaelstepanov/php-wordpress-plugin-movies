<?php
get_header();
?>
<div id="primary">
    <div id="content" role="main">
        <?php while (have_posts()) : the_post(); ?>
            <div class="wrap">
                <article id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" <?php post_class(); ?>>
                    <!-- Display featured image in right-aligned floating div -->
                    <div style="float: right; margin: 10px">
                        <?php the_post_thumbnail(array(100, 100)); ?>
                    </div>

                    <!-- Display Title -->
                    <strong>Title: </strong><?php the_title(); ?><br />
                    <br />

                    <!-- Display movie content -->
                    <strong>Content: </strong>
                    <div class="entry-content"><?php the_content(); ?></div>

                    <!-- Display stars based on rating -->
                    <strong>Rating: </strong>
                    <ul class="stars-list">
                        <?php
                        $stars_count = intval(get_post_meta(get_the_ID(), 'rating', true));
                        for ($star_counter = 1; $star_counter <= 5; $star_counter++)
                        {
                            $class = ($star_counter <= $stars_count) ? 'gold' : 'grey';
                            echo '<li class="star ' . $class . '" data-rating="' . $star_counter . '"></li>';
                        }
                        ?>
                    </ul>
                </article>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>