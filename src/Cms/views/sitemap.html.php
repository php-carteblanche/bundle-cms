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

<?php if (!empty($breadcrumb) || !empty($search_box)) : ?>
	<?php if (!empty($breadcrumb)) : ?>
		<?php echo @$breadcrumb; ?>
	<?php endif; ?>

	<?php if (!empty($search_box)) : ?>
		<?php echo @$search_box; ?>
	<?php endif; ?>
<br class="clear" />
<?php endif; ?>

<div class="menu">
	<ul>
	<?php foreach($sections as $_rubrique) : 
		if (!empty($_rubrique['is_menu']) || $_rubrique['is_menu']==1) :
	?>
		<li><a href="<?php echo build_url(array(
			'controller'=>'cms','model'=>'rubrique', 'id'=>$_rubrique['id'], 'altdb'=>$altdb
		)); ?>" title="See this page"><?php echo $_rubrique['title']; ?></a></li>
	<?php endif;
	endforeach; ?>
	</ul>
</div>

<br class="clear" />

<h2>Sitemap</h2>
<ul>
<?php 

foreach($articles as $_art) : 
	if (empty($_art['rubrique_id']) || $_art['rubrique_id']==0) :
		echo view(
			\Cms\Controller\Cms::$views_dir.'sitemap_article_item',
			array(
				'altdb'=>$altdb,
				'articles'=>$articles,
				'sections'=>$sections,
				'current_article'=>$_art,
			)
		);
	endif;
endforeach; 

foreach($sections as $_sect) : 
	if (empty($_sect['parent_id']) || $_sect['parent_id']==0) :
		echo view(
			\Cms\Controller\Cms::$views_dir.'sitemap_rubrique_item',
			array(
				'altdb'=>$altdb,
				'articles'=>$articles,
				'sections'=>$sections,
				'current_section'=>$_sect,
			)
		);
	endif;
endforeach; 

?>
</ul>

<br class="clear" />
