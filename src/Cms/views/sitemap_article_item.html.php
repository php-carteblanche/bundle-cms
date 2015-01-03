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

if (empty($current_article)) return '';
if (!isset($altdb)) $altdb='';

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
			'controller'=>'cms','model'=>'article', 'id'=>$current_article['id'], 'altdb'=>$altdb
		)); ?>" title="Read more"><?php echo $current_article['title']; ?></a>
		<span class="posted_info"><small>posted on <?php 
		if (!empty($current_article['updated_at'])) :
			echo strftime('%c', strtotime($current_article['updated_at']));
		elseif (!empty($current_article['created_at'])) :
			echo strftime('%c', strtotime($current_article['created_at']));
		endif;
		?></small></span>
	</h4>
</li>
