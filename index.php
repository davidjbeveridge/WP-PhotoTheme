<? get_header(); ?>
		<div id="main">
			<? if(have_posts()) : while(have_posts()) : the_post(); ?>
			<div class="post">
				<h1 class="postTitle"><a href="<? the_permalink(); ?>" class="ajax"><? the_title(); ?></a></h1>
				<div class="postContent">
					<?
					$getAttachmentArgs = array('post_type' => 'attachment','numberposts' => 1,'post_status' => NULL,'post_parent'=>$post->ID);
					if($attachements = get_posts($getAttachmentArgs)) : foreach($attachements as $attachment) : ?>
					<? if(wp_attachment_is_image($attachment->ID)) : ?>
					<? $attachmentImage = wp_get_attachment_image_src($attachment->ID,array(100,100)) ?>
					<a href="<? the_permalink(); ?>">
						<img src="<?=$attachmentImage[0]?>" width="<?=$attachmentImage[1]?>" height="<?=$attachmentImage[2]?>" class="imageThumbnail" />
					</a>
					<? endif; ?>
					<? endforeach; endif; ?>
					<? the_excerpt(); ?>
				</div>
			</div>
			<? endwhile; endif; ?>
		</div>
<? get_footer(); ?>
