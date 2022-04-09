<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Http\Requests\StudentRequest;
use App\Models\Subject;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('student', 'students');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'type' => 'checkbox',
            'name' => 'bulk_actions',
            'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px; padding-left:0px;" />',
            'priority' => 1,
            'searchLogic' => false,
            'orderable' => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();

        $this->crud->addFilter(
            [
                'name'  => 'name',
                'type'  => 'select2',
                'label' => 'Name'
            ],
            function () {
                return Student::pluck('name', 'name')->toArray();
            },

        );

        $this->crud->addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();

        $this->crud->addFilter(
            [
                'name'  => 'address',
                'type'  => 'select2_multiple',
                'label' => 'Address'
            ],
            function () {
                return Student::pluck('address', 'address')->toArray();
            },
            function ($values) {
                $this->crud->addClause('whereIn', 'address', json_decode($values));
            }
        );

        $this->crud->addColumn([
            'name'      => 'image', // The db column name
            'label'     => 'Profile', // Table column heading
            'type'      => 'image',
            'prefix' => '/storage/',
            'height' => '45px',
            'width'  => '45px',
        ]);

        $this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'labal' => 'Name'
        ]);

        $this->crud->addColumn([
            'name' => 'code',
            'type' => 'text',
            'labal' => 'Code'
        ]);

        $this->crud->addColumn([
            'name'         => 'Group', // name of relationship method in the model
            'type'         => 'relationship',
            'label'        => 'Group',
        ]);

        $this->crud->addColumn(
            [
                'label'     => "Subject",
                'type'      => 'select_multiple',
                'name'      => 'subject',
                'entity'    => 'subject',
                'model'     => "App\Models\Subject",
                'attribute' => 'subjectName',
                'pivot'     => true,
                'options'   => (function ($query) {
                    return $query->orderBy('subjectName', 'ASC')->get();
                }),
            ],
        );

        $this->crud->addColumn([
            'name' => 'address',
            'type' => 'text',
            'label' => 'Address'
        ]);


        $this->crud->addFilter(
            [
                'type'  => 'date',
                'name'  => 'created_at',
                'label' => 'CreateDate'
            ],
            false,
            function ($value) {
                $this->crud->addClause('whereDate', 'created_at', $value);
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

        $this->crud->addColumn([

            'name'     => 'status',
            'label'    => 'Status',
            'type'     => 'closure',
            'function' => function ($entry) {
                return $entry->status == 'Open' ? "<span class='btn btn-success btn-sm'>Open</span>" : "<span class='btn btn-danger btn-sm'>Close</span>";
            }
        ]);


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
        CRUD::setValidation(StudentRequest::class);

        $this->crud->addField([
            'label' => "Code",
            'name' => "code",
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        CRUD::addField([
            'label'       => "Subject",
            'type'        => "select2_from_ajax_multiple",
            'name'        => 'subject',
            'entity'      => 'subject',
            'attribute'   => "subjectName",
            'data_source' => url("admin/student/fetch/student-name"),
            'placeholder'             => "Select a subject",
            'minimum_input_length'    => 1,
            'model' => 'App\Models\Subject',
            // 'dependencies'            => ['subject'], 
            'method'                  => 'POST',
            'include_all_form_fields' => false,
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);


        $this->crud->addField([
            'label'     => "Group",
            'type'      => 'select2',
            'name'      => 'group_id',
            'entity'   => 'group',
            'attribute' => 'group_name',
            'model'     => "\Modules\Group\Entities\Group",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        // CRUD::field('name');
        $this->crud->addField([
            'label' => "Address",
            'name' => "address",
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label' => "Name",
            'name' => "name",
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name'        => 'status',
            'label'       => "Status",
            'type'        => 'select_from_array',
            'options'     => ['Open' => 'Open', 'Closed' => 'Closed'],
            'allows_null' => false,
            'default'     => 'one',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]

        ]);

        $this->crud->addField([
            'label' => "Profile Image",
            'name' => "image",
            'type' => 'image',
            'crop' => true,
            'aspect_ratio' => 1,
            // 'prefix'    => '/uploads/images',
        ]);


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    public function store()
    {

        // dd(request()->group);

        CRUD::addfield([
            'name' => 'created_by',
            'type' => 'hidden'
        ]);
        request()->merge([
            'created_by' => backpack_user()->id
        ]);

        $response = $this->traitStore();

        // $this->crud->entry->update([
        //     'code' => "001"
        // ]);

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

    public function fetchStudentName()
    {
        return $this->fetch([
            'model' => Subject::class,
            'query' => function ($model) {
                $search = request()->input('q') ?? false;

                if ($search) {
                    return $model->where('subjectName', 'LIKE', '%' . $search . '%');
                } else {
                    return $model;
                }
            },
            'searchable_attributes' => []
        ]);
    }
}
