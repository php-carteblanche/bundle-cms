<?php

if (empty($articles)) $articles=array();
if (empty($articles_fields)) $articles_fields=array();
if (empty($articles_relations)) $articles_relations=array();

if (empty($sections)) $sections=array();
if (empty($sections_fields)) $sections_fields=array();
if (empty($sections_relations)) $sections_relations=array();

?>

<?php if (!empty($breadcrumb) || !empty($search_box)) : ?>
	<?php if (!empty($breadcrumb)) : ?>
		<?php echo @$breadcrumb; ?>
	<?php endif; ?>

	<?php if (!empty($search_box)) : ?>
		<?php echo @$search_box; ?>
	<?php endif; ?>
<br class="clear" />
<?php endif; ?>

<div class="results">
<?php if (!empty($total) && $total!=0) : ?>
	Your search returns <?php echo $total; ?> results.
<?php else : ?>
	Your search returns no result.
<?php endif; ?>
</div>

<?php if (!empty($sections)) : ?>
<br class="clear" />
<h2>Found sections</h2>
<ul>
	<?php foreach($sections as $_rubrique) : ?>
		<li><a href="<?php echo build_url(array(
			'controller'=>'cms','model'=>'rubrique', 'id'=>$_rubrique['id'], 'altdb'=>$altdb
		)); ?>" title="See this page"><?php echo $_rubrique['title']; ?></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($articles)) : ?>
<br class="clear" />
<h2>Found articles</h2>
<ul>
<?php foreach($articles as $_art) : ?>
	<li class="content cms_home_item">
		<h4>
			<a href="<?php echo build_url(array(
				'controller'=>'cms','model'=>'article', 'id'=>$_art['id'], 'altdb'=>$altdb
			)); ?>" title="Read more"><?php echo $_art['title']; ?></a>
			<?php if (!empty($_art['points'])) : ?>
			<small> | <?php echo $_art['points']; ?> points</small>
			<?php endif; ?>
		</h4>
		<p><small>posted on <?php 
		if (!empty($_art['updated_at'])) :
			echo strftime('%c', strtotime($_art['updated_at']));
		elseif (!empty($_art['created_at'])) :
			echo strftime('%c', strtotime($_art['created_at']));
		endif;
		?></small></p>
		<?php echo $_art['content']; ?>
	</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($pager)) echo $pager; ?>
<br class="clear" />

			<a href="<?php echo build_url(array(
				'controller'=>'cms', 'altdb'=>$altdb
			)); ?>" title="Go back home">home</a>
&nbsp;|&nbsp;
			<a href="<?php echo build_url(array(
				'controller'=>'cms','action'=>'sitemap', 'altdb'=>$altdb
			)); ?>" title="See website full map">sitemap</a>
