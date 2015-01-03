<?php
/**
 * This file is part of the CarteBlanche PHP framework.
 *
 * (c) Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * License Apache-2.0 <http://github.com/php-carteblanche/carteblanche/blob/master/LICENSE>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (empty($articles)) $articles=array();
if (empty($articles_fields)) $articles_fields=array();
if (empty($articles_relations)) $articles_relations=array();

if (empty($sections)) $sections=array();
if (empty($sections_fields)) $sections_fields=array();
if (empty($sections_relations)) $sections_relations=array();

?>

<?php if (!empty($search_box)) : ?>
	<?php echo @$search_box; ?>
<?php endif; ?>

<div class="menu">
	<ul>
	<?php foreach($sections as $_rubrique) : ?>
		<li><a href="<?php echo build_url(array(
			'controller'=>'cms','model'=>'rubrique', 'id'=>$_rubrique['id'], 'altdb'=>$altdb
		)); ?>" title="See this page"><?php echo $_rubrique['title']; ?></a></li>
	<?php endforeach; ?>
	</ul>
</div>

<br class="clear" />

<h2>Last articles</h2>
<ul class="cms_home_list_items">
<?php foreach($articles as $_art) : ?>
	<li class="cms_content cms_home_item">
		<h4>
			<a href="<?php echo build_url(array(
				'controller'=>'cms','model'=>'article', 'id'=>$_art['id'], 'altdb'=>$altdb
			)); ?>" title="Read more"><?php echo $_art['title']; ?></a>
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

<?php if (!empty($pager)) echo $pager; ?>
<br class="clear" />

			<a href="<?php echo build_url(array(
				'controller'=>'cms','action'=>'sitemap', 'altdb'=>$altdb
			)); ?>" title="See website full map">sitemap</a>
