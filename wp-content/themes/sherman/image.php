<?php 
$parent_id = $post->post_parent;
if($parent_id == 0) {
  header("HTTP/1.1 301 Moved Permanently");
  header("Location: ". get_bloginfo('url'));
  exit();
} else {
  wp_redirect(get_permalink($post->post_parent));
}
