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

if (empty($current_section)) return '';
if (!isset($altdb)) $altdb='';

if (!isset($loop_articles)) $loop_articles=true;
if (!isset($loop_subsections)) $loop_subsections=true;

if (empty($articles)) $articles=array();
if (empty($articles_fields)) $articles_fields=array();
if (empty($articles_relations)) $articles_relations=array();

if (empty($sections)) $sections=array();
if (empty($sections_fields)) $sections_fields=array();
if (empty($sections_relations)) $sections_relations=array();

?>

<li class="content cms_home_item">
	<h4>
		<a href="<?php echo build_url(array(
			'controller'=>'cms','model'=>'rubrique', 'id'=>$current_section['id'], 'altdb'=>$altdb
		)); ?>" title="Read more"><?php echo $current_section['title']; ?></a>
	</h4>

<?php if ($loop_articles===true || $loop_subsections===true) : ?>
	<ul>
<?php endif; ?>

<?php if ($loop_articles===true) :
	foreach($articles as $_art) : 
		if (!empty($_art['rubrique_id']) && $_art['rubrique_id']==$current_section['id']) :
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
endif; ?>

<?php if ($loop_subsections===true) :
	foreach($sections as $_subsect) : 
		if (!empty($_subsect['parent_id']) && $_subsect['parent_id']==$current_section['id']) :
			echo view(
				\Cms\Controller\Cms::$views_dir.'sitemap_rubrique_item',
				array(
					'altdb'=>$altdb,
					'articles'=>$articles,
					'sections'=>$sections,
					'current_section'=>$_subsect,
				)
			);
		endif;
	endforeach;
endif; ?>

<?php if ($loop_articles===true || $loop_subsections===true) : ?>
	</ul>
<?php endif; ?>

</li>
