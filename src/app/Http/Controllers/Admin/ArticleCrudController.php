<?php

namespace Backpack\NewsCRUD\app\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\NewsCRUD\app\Http\Requests\ArticleRequest;

class ArticleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CloneOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkCloneOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel("Backpack\NewsCRUD\app\Models\Article");
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/article');
        $this->crud->setEntityNameStrings('article', 'articles');

        /*
        |--------------------------------------------------------------------------
        | LIST OPERATION
        |--------------------------------------------------------------------------
        */
        $this->crud->operation('list', function () {
            $this->crud->addColumn('title');
            $this->crud->addColumn([
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
            ]);
            $this->crud->addColumn('status');
            $this->crud->addColumn([
                'name' => 'featured',
                'label' => 'Featured',
                'type' => 'check',
            ]);
            $this->crud->addColumn([
                'label' => 'Category',
                'type' => 'select',
                'name' => 'category_id',
                'entity' => 'category',
                'attribute' => 'name',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | CREATE & UPDATE OPERATIONS
        |--------------------------------------------------------------------------
        */
        $this->crud->operation(['create', 'update'], function () {
            DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
            
            $this->crud->setValidation(ArticleRequest::class);

            $this->crud->addField([
                'name' => 'title',
                'label' => 'Title',
                'type' => 'text',
                'placeholder' => 'Your title here',
            ]);
            $this->crud->addField([
                'name' => 'slug',
                'label' => 'Slug (URL)',
                'type' => 'text',
                'hint' => 'Will be automatically generated from your title, if left empty.',
                // 'disabled' => 'disabled'
            ]);
            $this->crud->addField([
                'name' => 'meta_title',
                'label' => trans('backpack::newscrud.meta_title'),
                'fake' => true,
                'store_in' => 'extras',
            ]);
            $this->crud->addField([
                'name' => 'meta_description',
                'label' => trans('backpack::newscrud.meta_description'),
                'fake' => true,
                'store_in' => 'extras',
            ]);
            $this->crud->addField([
                'name' => 'meta_keywords',
                'type' => 'textarea',
                'label' => trans('backpack::newscrud.meta_keywords'),
                'fake' => true,
                'store_in' => 'extras',
            ]);
            $this->crud->addField([
                'name' => 'date',
                'label' => 'Date',
                'type' => 'date',
                'default' => date('Y-m-d'),
            ]);
            $this->crud->addField([
                'name'  => 'published_at',
                'label' => 'Published time',
                'type'  => 'datetime'
            ]);
            $this->crud->addField([
                'name'  => 'expired_at',
                'label' => 'Expiration time',
                'type'  => 'datetime'
            ]);
            $this->crud->addField([
                'name' => 'resume',
                'label' => 'Resume',
                'type' => 'ckeditor',
                'placeholder' => 'Make a resume of your article here',
            ]);
            $this->crud->addField([
                'name' => 'content',
                'label' => 'Content',
                'type' => 'ckeditor',
                'placeholder' => 'Your textarea text here',
            ]);
            $this->crud->addField([
                'name' => 'image',
                'label' => 'Image',
                'type' => 'browse',
            ]);
            $this->crud->addField([
                'name' => 'thumbnail',
                'label' => 'Thumbnail',
                'type' => 'browse',
            ]);
            $this->crud->addField([
                'label' => 'Author',
                'type'  => 'select2',
                'name' => 'author_id',
                'entity' => 'author',
                'attribute' => 'name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ]);
            $this->crud->addField([
                'label' => 'Category',
                'type' => 'select2',
                'name' => 'category_id',
                'entity' => 'category',
                'attribute' => 'name',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6',
                ],
            ]);
            $this->crud->addField([
                'label' => 'Tags',
                'type' => 'select2_multiple',
                'name' => 'tags', // the method that defines the relationship in your Model
                'entity' => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            ]);
            $this->crud->addField([
                'name' => 'status',
                'label' => 'Status',
                'type' => 'enum',
            ]);
            $this->crud->addField([
                'name' => 'featured',
                'label' => 'Featured item',
                'type' => 'checkbox',
            ]);
            $this->crud->addColumn([
                'name'  => 'top',
                'label' => 'On top',
                'type'  => 'check'
            ]);
            $this->crud->addColumn([
                'name'  => 'recommended',
                'label' => 'Recommended',
                'type'  => 'check'
            ]);
        });
    }
}
