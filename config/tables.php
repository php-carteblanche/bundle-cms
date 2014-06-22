<?php
/**
 * **Example tables user definitions.**
 *
 * @category  CarteBlanche
 * @author    Piero Wbmstr <me@e-piwi.fr>
 * @license   GPL v3
 * @copyright Les Ateliers Pierrot <http://www.ateliers-pierrot.fr>
 * @link      https://github.com/php-carteblanche/carteblanche
 * @package   Settings
 */

$tables = array(

array(
    'table'=>'rubrique',
    'editable'=>true,
    'structure'=>array(
        'id'=>array(
            'type'=>'integer',
            'null'=>false,
            'default'=>'',
            'index'=>'primary key asc',
        ),
        'parent_id'=>array(
            'type'=>'integer',
            'null'=>true,
            'default'=>'',
            'index'=>'',
            'related'=>'rubrique:id',
        ),
        'is_menu'=>array(
            'type'=>'bit',
            'null'=>true,
            'default'=>'',
            'index'=>'',
        ),
        'title'=>array(
            'type'=>'varchar(255)',
            'null'=>true,
            'default'=>'',
            'index'=>false,
            'slug'=>true
        ),
        'content'=>array(
            'type'=>'mediumtext',
            'null'=>true,
            'default'=>'',
            'index'=>false,
            'markdown'=>true
        ),
        'created_at'=>array(
            'type'=>'datetime',
            'null'=>true,
            'default'=>'',
            'index'=>false,
        ),
        'updated_at'=>array(
            'type'=>'datetime',
            'null'=>true,
            'default'=>'',
            'index'=>false,
        ),
    ),
    'has_one'=>array(
        'parent'=>'rubrique:id',
    ),
    'has_many'=>array(
        'children'=>'rubrique:parent_id',
        'articles'=>'article:rubrique_id',
    )
    ),

    array(
    'table'=>'article',
    'editable'=>true,
    'structure'=>array(
        'id'=>array(
            'type'=>'integer',
            'null'=>false,
            'default'=>'',
            'index'=>'primary key asc',
        ),
        'rubrique_id'=>array(
            'type'=>'integer',
            'null'=>true,
            'default'=>'',
//            'index'=>'key',
            'index'=>'',
            'related'=>'rubrique:id',
        ),
        'title'=>array(
            'type'=>'varchar(255)',
            'null'=>true,
            'default'=>'',
            'index'=>false,
            'slug'=>true
        ),
        'content'=>array(
            'type'=>'longtext',
            'null'=>true,
            'default'=>'',
            'index'=>false,
            'markdown'=>true
        ),
        'created_at'=>array(
            'type'=>'datetime',
            'null'=>true,
            'default'=>'',
            'index'=>false,
        ),
        'updated_at'=>array(
            'type'=>'datetime',
            'null'=>true,
            'default'=>'',
            'index'=>false,
        ),
    ),
),

array(
    'table'=>'test',
    'editable'=>true,
    'structure'=>array(
        'id'=>array(
            'type'=>'integer',
            'null'=>false,
            'default'=>'',
            'index'=>'primary key asc',
        ),
        'is_active'=>array(
            'type'=>'bit',
            'null'=>true,
            'default'=>'',
            'index'=>'',
            'toggler'=>true,
        ),
        'article_id'=>array(
            'type'=>'integer',
            'null'=>true,
            'default'=>'',
            'index'=>'',
            'related'=>'article:id',
        ),
        'email'=>array(
            'type'=>'varchar(12)',
            'null'=>false,
            'default'=>'mon titre',
            'index'=>false,
            'slug'=>true,
            'validation'=>'is_email'
        ),
        'url'=>array(
            'type'=>'varchar(12)',
            'null'=>false,
            'default'=>'mon titre',
            'index'=>false,
            'slug'=>true,
            'validation'=>'is_url'
        ),
        'content'=>array(
            'type'=>'mediumtext',
            'null'=>true,
            'default'=>'',
            'index'=>false,
            'markdown'=>true
        ),
        'value'=>array(
            'type'=>'integer',
            'null'=>false,
            'default'=>'1',
            'index'=>false,
            'comment' => 'Mon commentaire',
        ),
        'float_value'=>array(
            'type'=>'float',
            'null'=>false,
            'default'=>false,
            'index'=>false,
        ),
        'date_test'=>array(
            'type'=>'date',
            'null'=>true,
            'default'=>'0000-00-00',
            'index'=>false,
        ),
        'is_followed'=>array(
            'type'=>'bit',
            'null'=>true,
            'default'=>'',
            'index'=>'',
        ),
        'attached_file'=>array(
            'type'=>'blob',
            'null'=>true,
            'default'=>'',
            'index'=>'',
            'accept'=>array( 'image/*' )
        ),
        'datetime_test'=>array(
            'type'=>'datetime',
            'null'=>true,
            'default'=>'0000-00-00 00:00:00',
            'index'=>false,
        ),
        'time_test'=>array(
            'type'=>'time',
            'null'=>true,
            'default'=>'00:00:00',
            'index'=>false,
        ),
    ),
)

);

// Endfile