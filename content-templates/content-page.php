<?php 
if(have_posts())
{
while ( have_posts() ) : the_post();
echo '<div class="panel panel-default" >';
echo '<div class="panel-body page-body  " >';
echo '<h2 class="page-title" >';
the_title();
echo '</h2>';
the_content();
// if ( comments_open() || get_comments_number() ) : comments_template(); endif;
echo '</div>';
echo '</div>';
endwhile;
}
?>