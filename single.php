<? get_header(); ?>
		<? if(have_posts()) : while(have_posts()) : the_post(); ?>
		<div id="main">
			<div class="post">
				<h1 class="postTitle"><a href="<? the_permalink(); ?>"><? the_title(); ?></a></h1>
				<div class="postThumbnails">
					<? $getAttachmentArgs = array('post_type' => 'attachment','numberposts' => -1,'post_status' => NULL,'post_parent'=>$post->ID); ?>
					<? if($attachements = get_posts($getAttachmentArgs)) : ?>
					<? foreach($attachements as $attachment) : ?>
					<? if(wp_attachment_is_image($attachment->ID)) : ?>
					<? $attachmentThumb = wp_get_attachment_image_src($attachment->ID,array(50,50)); ?>
					<? $attachmentImage = wp_get_attachment_image_src($attachment->ID,'full'); ?>
					<a href="<?=$attachmentImage[0]?>" class="backgroundThumbnail">
						<img src="<?=$attachmentThumb[0]?>" width="<?=$attachmentThumb[1]?>" height="<?=$attachmentThumb[2]?>" />
					</a>
					<? endif; ?>
					<? endforeach; ?>
					<? endif; ?>
				</div>
				<div class="postContent">
					<? the_content(); ?>
				</div>
			</div>
		</div>
		<? endwhile; endif; ?>
<? get_footer(); ?>
