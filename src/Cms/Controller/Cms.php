<?php
/**
 * CarteBlanche - PHP framework package - CMS bundle
 * Copyleft (c) 2013 Pierre Cassat and contributors
 * <www.ateliers-pierrot.fr> - <contact@ateliers-pierrot.fr>
 * License Apache-2.0 <http://www.apache.org/licenses/LICENSE-2.0.html>
 * Sources <http://github.com/php-carteblanche/carteblanche>
 */

namespace Cms\Controller;

use \CarteBlanche\CarteBlanche,
    \CarteBlanche\App\Container,
    \CarteBlanche\Abstracts\AbstractController,
    \CarteBlanche\Exception\NotFoundException;

use \Crud\Controller\CrudControllerAbstract;

/**
 * The default CMS controller
 *
 * CMS controller extending the abstract \CarteBlanche\Abstracts\AbstractController class
 *
 * @author 		Piero Wbmstr <piero.wbmstr@gmail.com>
 */
class Cms extends CrudControllerAbstract
{

	/**
	 * The sections table name
	 */
	var $section_table;

	/**
	 * The articles table name
	 */
	var $article_table;

	/**
	 * The directory where to search the views files
	 */
	static $views_dir = 'Cms/views/';

	/**
	 * Constructor : sets the table names
	 *
	 * @param object $_container The global `App\Container` application container instance
	 * @see App\Container
	 */
	public function __construct(Container $_container)
	{
		parent::__construct($_container);
		$cms = CarteBlanche::getConfig('cms');
		if (!empty($cms['section_table']))
			$this->section_table = $cms['section_table'];
		else
			trigger_error( "Table for CMS sections is not defined!", E_USER_ERROR );
		if (!empty($cms['article_table']))
			$this->article_table = $cms['article_table'];		
		else
			trigger_error( "Table for CMS articles is not defined!", E_USER_ERROR );
	}

	/**
	 */
	public function indexAction($id = null, $offset = 0)
	{
		$_mod = $this->getContainer()->get('request')->getUrlArg('model');
		$search_str = $this->getContainer()->get('request')->getArgument('search', '', true, ENT_NOQUOTES);
		if ($_mod && $_mod == $this->section_table)
			return self::sectionAction( $id );
		elseif ($_mod && $_mod == $this->article_table)
			return self::articleAction( $id );
		elseif (!empty($search_str))
			return self::searchAction( $search_str );
		else
			return self::homeAction( $offset );
	}

	public function emptyAction($_altdb = null)
	{
		$this->getContainer()->get('router')->redirect();
	}

	public function sectionAction($id = null)
	{
		$_mod = $this->getContainer()->get('request')->getUrlArg('model');
		$_altdb = $this->getContainer()->get('request')->getUrlArg('altdb');
		if ($_mod!=$this->section_table)
			return self::indexAction( $id );

		$model = self::getCmsModel( $this->section_table );
		$art_model = self::getCmsModel( $this->article_table );
		if (isset($model)) 
		{
			$object = $model->read( $id, true );
			if ($model->exists()) 
			{
				$slug = $model->getSlugField();
				foreach($model->getTableStructure() as $fieldname=>$field) {
					if (isset($field['markdown']) && $field['markdown']===true) {
						if (isset($object[$fieldname])) {
							$_txt = new \Tool\Text(array(
								'original_str'=>$object[$fieldname],
								'markdown'=>true,
							));
							$object[$fieldname] = $_txt;
						}
					}
				}

				if ($slug && isset($object[$slug])) 
				{
					$title = $object[$slug];
					unset($object[$slug]);
				} else
					$title = 'Reading '.$_mod.' '.$id;

				$breadcrumb = new \Tool\Breadcrumb(array(
					'home'=>$this->getContainer()->get('router')->buildUrl(array('controller'=>'cms', 'altdb'=>$_altdb)),
					'current'=>$title,
					'links'=>self::followSectionBreadcrumb( $object )
				));

				$object_content_id = \Library\Helper\Html::getId('object_content',true);
				$toolbox = new \Tool\TextToolBox(array(
					'content_id'=>$object_content_id,
					'font_size_tools'=>true,
				));

				if (!empty($object['articles']))
				foreach($object['articles'] as $i=>$artobject)
				{
					foreach($art_model->getTableStructure() as $fieldname=>$field) 
					{
						if (isset($field['markdown']) && $field['markdown']===true) 
						{
							if (isset($artobject[$fieldname])) 
							{
								$_txt = new \Tool\Text(array(
									'original_str'=>$artobject[$fieldname],
									'markdown'=>true,
									'max_length'=>280,
									'strip_tags'=>true
								));
								$object['articles'][$i][$fieldname] = $_txt;
							}
						}
					}
				} 

        		return array(self::$views_dir.'read_section', array(
					'title'=>$title,
                    'altdb'=>$_altdb,
                    'table_name'=>$_mod,
                    'object'=>$object,
                    'fields'=>$model->getFieldsList(),
                    'relations'=>$model->getObjectRelations(),
                    'breadcrumb'=>$breadcrumb,
                    'toolbox'=>$toolbox
        		));
			} 
			else {
				throw new NotFoundException("Section '$id' not found!");
			}
		}
		else {
			throw new NotFoundException("Page not found!");
		}
	}

	public function articleAction($id = null)
	{
		$_mod = $this->getContainer()->get('request')->getUrlArg('model');
		$_altdb = $this->getContainer()->get('request')->getUrlArg('altdb');
		if ($_mod!=$this->article_table)
			return self::indexAction( $id );

		$model = self::getCmsModel( $this->article_table );
		if (isset($model)) 
		{
			$object = $model->read( $id, true );
			if ($model->exists()) 
			{
				$slug = $model->getSlugField();
				foreach($model->getTableStructure() as $fieldname=>$field) 
				{
					if (isset($field['markdown']) && $field['markdown']===true) 
					{
						if (isset($object[$fieldname])) 
						{
							$_txt = new \Tool\Text(array(
								'original_str'=>$object[$fieldname],
								'markdown'=>true,
							));
							$object[$fieldname] = $_txt;
						}
					}
				}

				if ($slug && isset($object[$slug])) 
				{
					$title = $object[$slug];
					unset($object[$slug]);
				} else
					$title = 'Reading '.$_mod.' '.$id;

				$breadcrumb = new \Tool\Breadcrumb(array(
					'home'=>$this->getContainer()->get('router')->buildUrl(array('controller'=>'cms', 'altdb'=>$_altdb)),
					'current'=>$title,
					'links'=>self::followArticleBreadcrumb( $object )
				));

				$object_content_id = \Library\Helper\Html::getId('object_content',true);
				$toolbox = new \Tool\TextToolBox(array(
					'content_id'=>$object_content_id,
					'font_size_tools'=>true,
				));

        		return array(self::$views_dir.'read_section', array(
					'title'=>$title,
                    'altdb'=>$_altdb,
                    'table_name'=>$_mod,
                    'object'=>$object,
                    'fields'=>$model->getFieldsList(),
                    'relations'=>$model->getObjectRelations(),
                    'breadcrumb'=>$breadcrumb,
                    'toolbox'=>$toolbox
        		));
			} 
			else {
				throw new NotFoundException("Article '$id' not found!");
			}
		}
		else {
			throw new NotFoundException("Page not found!");
		}
	}

	public function homeAction($offset = 0, $limit = 5)
	{
		$_altdb = $this->getContainer()->get('request')->getUrlArg('altdb');

		$art_model = self::getCmsModel( $this->article_table );
		$art_total = $art_model->count();
		$articles = $art_model->dump( $offset, $limit, false, 'updated_at,created_at' );

		if ($articles) {
			$art_slug = $art_model->getSlugField();

			$art_pager = new \Tool\Pager(
				array(
					'altdb'=>$_altdb,
					'table_name'=>null,
					'total'=>$art_total,
					'limit'=>$limit,
					'offset'=>$offset,
					'url_args'=>array('controller'=>'cms','altdb'=>$_altdb),
					'items_select'=>false,
					'pager_link_mask'=>$this->getContainer()->get('router')->buildUrl(array('controller'=>'cms','altdb'=>$_altdb,'offset'=>'%s')),
				)
			);

			foreach ($articles as $i=>$object) {
				foreach ($art_model->getTableStructure() as $fieldname=>$field) {
					if (isset($field['markdown']) && $field['markdown']===true) {
						if (isset($object[$fieldname])) {
							$_txt = new \Tool\Text(array(
								'original_str'=>$object[$fieldname],
								'markdown'=>true,
								'max_length'=>280,
								'strip_tags'=>true
							));
							$articles[$i][$fieldname] = $_txt;
						}
					}
				}
			} 
		}

		$sct_model = self::getCmsModel( $this->section_table );
		$sections = $sct_model->dump( 0, 10, false, 'updated_at,created_at', 'asc', null, 'is_menu=1' );
		$sct_slug = $sct_model->getSlugField();

		$search_str = $this->getContainer()->get('request')->getArgument('search', '', true, ENT_NOQUOTES);
		$_args=array(
			'controller'=>'cms','altdb'=>$_altdb
		);
		$url_args = CarteBlanche::getConfig('routing.arguments_mapping');
		foreach ($_args as $_arg_var=>$_arg_val) {
			if (!empty($_arg_val)) {
				if (in_array($_arg_var, $url_args))
					$args[ array_search($_arg_var, $url_args) ] = $_arg_val;
				else
					$args[ $_arg_var ] = $_arg_val;
			}
		}
		$searchbox = new \Tool\SearchBox(array(
			'hiddens'=>$args, 'search_str'=>$search_str
		));

        return array(self::$views_dir.'home', array(
            'title'=>null,
            'altdb'=>$_altdb,
            'articles'=>$articles,
            'sections'=>$sections,
            'slug_articles'=>$art_slug,
            'slug_sections'=>$sct_slug,
            'total'=>$art_total,
            'pager'=>$art_pager,
            'search_box'=>$searchbox,
        ));
	}

	/**
	 */
	public function sitemapAction()
	{
		$_altdb = $this->getContainer()->get('request')->getUrlArg('altdb');

		$art_model = self::getCmsModel( $this->article_table );
		$articles = $art_model->dump();

		$sct_model = self::getCmsModel( $this->section_table );
		$sections = $sct_model->dump();

		$_args=array(
			'controller'=>'cms','altdb'=>$_altdb
		);
		$url_args = CarteBlanche::getConfig('routing.arguments_mapping');
		foreach($_args as $_arg_var=>$_arg_val) 
		{
			if (!empty($_arg_val)) 
			{
				if (in_array($_arg_var, $url_args))
					$args[ array_search($_arg_var, $url_args) ] = $_arg_val;
				else
					$args[ $_arg_var ] = $_arg_val;
			}
		}
		$searchbox = new \Tool\SearchBox(array(
			'hiddens'=>$args
		));

		$breadcrumb = new \Tool\Breadcrumb(array(
			'home'=>$this->getContainer()->get('router')->buildUrl(array('controller'=>'cms', 'altdb'=>$_altdb)),
			'current'=>'Sitemap',
			'links'=>array()
		));

        return array(self::$views_dir.'sitemap', array(
			'title'=>'Sitemap',
            'altdb'=>$_altdb,
            'articles'=>$articles,
            'sections'=>$sections,
            'search_box'=>$searchbox,
            'breadcrumb'=>$breadcrumb,
        ));
	}

	public function searchAction($search = null, $offset = 0, $limit = 10)
	{
		if (is_null($search)) return self::homeAction( $offset );
		$_altdb = $this->getContainer()->get('request')->getUrlArg('altdb');

		$art_model = self::getCmsModel( $this->article_table );
		$art_total = $art_model->count( $search );
		$articles_weights = array(
			'title'=>2,
			'content'=>1
		);
		$articles = $art_model->weightedDump( $articles_weights, $offset, $limit, false, $search );

		if ($articles) 
		{
			$art_slug = $art_model->getSlugField();

			$art_pager = new \Tool\Pager(
				array(
					'altdb'=>$_altdb,
					'table_name'=>null,
					'total'=>$art_total,
					'limit'=>$limit,
					'offset'=>$offset,
					'url_args'=>array('controller'=>'cms','altdb'=>$_altdb),
					'items_select'=>false,
					'pager_link_mask'=>$this->getContainer()->get('router')->buildUrl(array('controller'=>'cms','altdb'=>$_altdb,'offset'=>'%s')),
				)
			);

			foreach($articles as $i=>$object)
			{
				foreach($art_model->getTableStructure() as $fieldname=>$field) 
				{
					if (isset($field['markdown']) && $field['markdown']===true) 
					{
						if (isset($object[$fieldname])) 
						{
							$_txt = new \Tool\Text(array(
								'original_str'=>$object[$fieldname],
								'markdown'=>true,
								'max_length'=>280,
								'strip_tags'=>true
							));
							$articles[$i][$fieldname] = $_txt;
						}
					}
				}
			} 
		}

		$sct_model = self::getCmsModel( $this->section_table );
		$sct_total = $sct_model->count( $search );
		$sections = $sct_model->dump( $offset, $limit, false, 'updated_at,created_at', 'asc', $search );
		$sct_slug = $sct_model->getSlugField();

		$breadcrumb = new \Tool\Breadcrumb(array(
			'home'=>$this->getContainer()->get('router')->buildUrl(array('controller'=>'cms', 'altdb'=>$_altdb)),
			'current'=>'Searching \''.$search.'\'',
			'links'=>array()
		));

		$search_str = $this->getContainer()->get('request')->getArgument('search', '', true, ENT_NOQUOTES);
		$_args=array(
			'controller'=>'cms','altdb'=>$_altdb
		);
		$url_args = CarteBlanche::getConfig('routing.arguments_mapping');
		foreach($_args as $_arg_var=>$_arg_val) 
		{
			if (!empty($_arg_val)) 
			{
				if (in_array($_arg_var, $url_args))
					$args[ array_search($_arg_var, $url_args) ] = $_arg_val;
				else
					$args[ $_arg_var ] = $_arg_val;
			}
		}
		$searchbox = new \Tool\SearchBox(array(
			'hiddens'=>$args, 'search_str'=>$search_str
		));

        return array(self::$views_dir.'search_results', array(
			'title'=>'Search results for "'.$search.'"',
            'altdb'=>$_altdb,
            'articles'=>$articles,
            'sections'=>$sections,
            'slug_articles'=>$art_slug,
            'slug_sections'=>$sct_slug,
            'total'=>$art_total+$sct_total,
            'pager'=>$art_pager,
            'breadcrumb'=>$breadcrumb,
            'search_box'=>$searchbox,
        ));
	}

	public function followSectionBreadcrumb($object = null)
	{
		$_altdb = $this->getContainer()->get('request')->getUrlArg('altdb');
		$model = self::getCmsModel( $this->section_table );
		$bc_links=array();
		$viewed=array();
		$_obj = $object;
		while (!empty($_obj['parent'])) 
		{
			$bc_links[] = array(
				'url'=>$this->getContainer()->get('router')->buildUrl(array(
					'controller'=>'cms','model'=>$this->section_table,
					'id'=>$_obj['parent']['id'], 'altdb'=>$_altdb
				)),
				'title'=>$_obj['parent']['title']
			);
			if (!in_array($_obj['parent']['id'], $viewed)) 
			{
				$viewed[] = $_obj['parent']['id'];
				$model_obj = $model->getModelClone();
				$model_obj->setId( $_obj['parent']['id'] );
				$model_obj->setData( $_obj['parent'] );
				$model_obj->getRelations();
				$_obj = $model_obj->getData();
			} else break;
		}
		return $bc_links;
	}

	public function followArticleBreadcrumb($object = null)
	{
		$model = self::getCmsModel( $this->section_table );
		if (!empty($object[$this->section_table])) 
		{
			$object['parent'] = $object[$this->section_table];
			return self::followSectionBreadcrumb( $object );
		}
		return array();
	}

	public function getCmsModel($_mod = null)
	{
		$_altdb = $this->getContainer()->get('request')->getUrlArg('altdb');
		$_structure = \CarteBlanche\Library\AutoObject\AutoObjectMapper::getAutoObject( $_mod, $_altdb );
		if (isset($_structure))
			$model = $_structure->getModel();
		else
			throw new NotFoundException("Table structure for CMS model '$_mod' can not de found!");
		return $model;
	}

}

// Endfile