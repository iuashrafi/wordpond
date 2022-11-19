<?php wp_footer(); ?>
<div class="overlay"></div>
<script>
jQuery(document).ready(function() {
//Preloader
jQuery(window).load(function() {
preloaderFadeOutTime = 1000;
function hidePreloader() {
var preloader = jQuery('.spinner-wrapper');
preloader.fadeOut(preloaderFadeOutTime);
}
hidePreloader();
});
});
</script>
</body>
</html>