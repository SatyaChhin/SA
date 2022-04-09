<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use App\Models\Teacher;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;

/**
 * Class SubjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubjectCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Subject::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subject');
        CRUD::setEntityNameStrings('subject', 'subjects');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addFilter(
            [
                'name' => 'subjectName',
                'type' => 'select2',
                'labal' => 'SubjectName',
            ],
            function () {
                return Subject::pluck('SubjectName', 'SubjectName')->toArray();
            }
        );

        $this->crud->addColumn([
            'type' => 'checkbox',
            'name' => 'bulk_actions',
            'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px;  height: 16px; padding-left:0px;" />',
            'priority' => 1,
            'searchLogic' => false,
            'orderable' => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();

      

        $this->crud->addFilter(
            [
                'name' => 'code',
                'type' => 'select2',
                'label' => 'Code',
            ],
            function () {
                return Subject::pluck('code', 'code')->toArray();
            }
        );

        $this->crud->addColumn([
            'name'         => 'teacher', // name of relationship method in the model
            'type'         => 'relationship',
            'label'        => 'Teacher',
        ]);

        $this->crud->addColumn([
            'name' => 'code',
            'type' => 'text',
            'labal' => 'Code'
        ]);

        $this->crud->addColumn([
            'name' => 'subjectName',
            'type' => 'text',
            'labal' => 'SubjectName'
        ]);



        $this->crud->addColumn([
            'name' => 'created_at',
            'lable' => 'Date',
            'type'  => 'date',
            'format' => 'Y-MM-DD'
        ]);

        $this->crud->addColumn([
            'name' => 'updated_at',
            'lable' => 'Date',
            'type'  => 'date',
            'format' => 'Y-MM-DD'
        ]);

        $this->crud->addFilter(
            [
                'type'  => 'date',
                'name'  => 'created_at',
                'label' => 'Create_Date'
            ],

            false,
            function ($value) {
                $this->crud->addClause('whereDate', 'created_at', $value);
            }
        );

        $this->crud->addFilter(
            [
                'type'  => 'date',
                'name'  => 'updated_at',
                'label' => 'Updata_Date'
            ],
            false,
            function ($value) {
                $this->crud->addClause('whereDate', 'updated_at', $value);
            }
        );
        $this->crud->addColumn([
            'type' => 'relationship',
            'name' => 'createdBy',
            'label' => 'Created By',
            'attribute' => 'name',
            'entity'    => 'createdBy',

            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('createdBy', function ($q) use ($column, $searchTerm) {
                    $q->where($column['attribute'], 'like', '%' . $searchTerm . '%')
                        ->orWhereDate('created_at', '=', date($searchTerm));
                });
            },

            'function'  => function ($entry) {
                return optional($entry->createBy)->name;
            }

        ]);

        $this->crud->addColumn([
            'type' => 'relationship',
            'name' => 'updatedBy',
            'label' => 'updated By',
            'attribute' => 'name',
            'entity'    => 'updatedBy',
            'function'  => function ($entry) {
                return optional($entry->createBy)->name;
            }
        ]);



        // CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubjectRequest::class);
        // CRUD::field('id');
        CRUD::field('code');
        // CRUD::field('teacher');
        CRUD::addField([
            'label'       => "Teacher",
            'type'        => "select2_from_ajax",
            'name'        => 'teacher',
            'entity'      => 'teacher',
            'attribute'   => "name",
            'data_source' => url("api/teacher"),
            'placeholder'             => "Select a teacher",
            'minimum_input_length'    => 1,
            'model' => 'App\Models\Teacher',
            'method'                  => 'GET',
            'include_all_form_fields' => false,
        ]);

        CRUD::field('subjectName');

        // CRUD::field('created_at');
        // CRUD::field('updated_at');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    public function store()
    {
        CRUD::addfield([
            'name' => 'created_by',
            'type' => 'hidden'
        ]);
        request()->merge([
            'created_by' => backpack_user()->id
        ]);
        $response = $this->traitStore();
        return $response;
    }

    public function update()
    {
        CRUD::addfield([
            'name' => 'updated_by',
            'type' => 'hidden'
        ]);
        request()->merge([
            'updated_by' => backpack_user()->id
        ]);
        $response = $this->traitUpdate();
        return $response;
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
