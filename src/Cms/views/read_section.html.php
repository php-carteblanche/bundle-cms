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

if (empty($object)) $object=array();
if (empty($fields)) $fields=array();
if (empty($relations)) $relations=array();
if (empty($object_content_id)) $object_content_id = _getid('object_content',true,true);
//var_export($object);

?>

<?php echo @$breadcrumb; ?>

<?php echo @$toolbox; ?>

<div class="small_infos_left">
<?php if (!empty($relations)) : ?>
	<ul>
	<?php foreach($relations as $_rel=>$data) : ?>
		<?php if (!empty($object[ $data['related_table'] ])) :
			$rel_obj = $object[ $data['related_table'] ];
			$value = isset($rel_obj[ $data['slug_field'] ]) ? 
				$rel_obj[ $data['slug_field'] ] : $rel_obj['id'];
			$related_table_name = $data['related_table'];
		?>
		<li>Related <?php echo str_replace('_id', '', $_rel); ?> : <a href="<?php echo build_url(array(
			'controller'=>'cms','model'=>$related_table_name, 'id'=>$object[$_rel]['id'], 'altdb'=>$altdb
		)); ?>" title="See this entry"><?php echo $value; ?></a></li>
	<?php endif; ?>	
	<?php endforeach; ?>
	</ul>
<?php endif; ?>	
</div>

<br class="clear" />

<div class="content" id="<?php echo $object_content_id; ?>">
	<?php echo $object['content']; ?>
</div>

<?php if (!empty($object['children'])) : ?>
<h4>Sub-sections</h4>
<div class="menu">
	<ul>
	<?php foreach($object['children'] as $_childid=>$_child) : ?>
		<li><a href="<?php echo build_url(array(
			'controller'=>'cms','model'=>$relations['children']['related_table'], 'id'=>$_child['id'], 'altdb'=>$altdb
		)); ?>" title="See this page"><?php echo $_child['title']; ?></a></li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<br class="clear" />

<?php if (!empty($object['articles'])) : ?>
<h4>Contents</h4>
<div class="articles_list">
	<ul>
	<?php foreach($object['articles'] as $_artid=>$_art) : ?>
	<li class="content cms_article_item">
		<h4>
			<a href="<?php echo build_url(array(
				'controller'=>'cms','model'=>$relations['articles']['related_table'], 'id'=>$_art['id'], 'altdb'=>$altdb
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
</div>
<?php endif; ?>

<br class="clear" />

<div class="small_infos_right">
<?php if (!empty($object['created_at'])) : ?>
	Created on <?php echo strftime('%c', strtotime($object['created_at'])); ?>
<?php endif; ?>
<?php if (!empty($object['updated_at'])) : ?>
	&nbsp;|&nbsp;
	Last update on <?php echo strftime('%c', strtotime($object['updated_at'])); ?>
<?php endif; ?>
</div>

<br class="clear" />
