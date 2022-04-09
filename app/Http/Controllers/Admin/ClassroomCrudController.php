<?php

namespace App\Http\Controllers\Admin;

use App\Models\Classroom;
use App\Libraries\dashboardLib;
use App\Http\Requests\ClassroomRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ClassroomCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ClassroomCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Classroom::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/classroom');
        CRUD::setEntityNameStrings('schedule', 'schedule');
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
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ]);

        $this->crud->addColumn([
            'type' => 'checkbox',
            'name' => 'bulk_actions',
            'label' => ' <input type="checkbox" class="crud_bulk_actions_main_checkbox" style="width: 16px; height: 16px; padding-left:0px;" />',
            'priority' => 1,
            'searchLogic' => false,
            'orderable' => false,
            'visibleInModal' => false,
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'name'         => 'group',
            'type'         => 'relationship',
            'label'        => 'Group',
        ]);

        $this->crud->addColumn([
            'name' => 'class_name',
            'type' => 'text',
            'label' => 'ClassName'
        ]);

        $this->crud->addColumn([
            'label'     => "Subject",
            'type'      => 'relationship',
            'name'      => 'subSubject',
            'attribute' => 'subjectName',
        ]);


        $this->crud->addColumn([
            'label'     => "Teacher",
            'type'      => 'relationship',
            'name'      => 'subteacher',
        ]);

        $this->crud->addColumn([
            'label'     => "Start Date",
            'type'      => 'time',
            'name'      => 'start_date',
        ]);

        $this->crud->addColumn([
            'label'     => "End Date",
            'type'      => 'time',
            'name'      => 'end_date',
            'format' => 'DD/MM/YYYY',
            
        ]);


        

        // $this->crud->addColumn([
        //     'name' => 'created_at',
        //     'lable' => 'Date',
        //     'type'  => 'date',
        //     'format' => 'Y-MM-DD'
        // ]);

        // $this->crud->addColumn([
        //     'name' => 'updated_at',
        //     'lable' => 'Date',
        //     'type'  => 'date',
        //     'format' => 'Y-MM-DD'
        // ]);

        // $this->crud->addColumn([
        //     'type' => 'relationship',
        //     'name' => 'createdBy',
        //     'label' => 'Created By',
        //     'attribute' => 'name',
        //     'entity'    => 'createdBy',

        //     'searchLogic' => function ($query, $column, $searchTerm) {
        //         $query->orWhereHas('createdBy', function ($q) use ($column, $searchTerm) {
        //             $q->where($column['attribute'], 'like', '%' . $searchTerm . '%')
        //                 ->orWhereDate('created_at', '=', date($searchTerm));
        //         });
        //     },

        //     'function'  => function ($entry) {
        //         return optional($entry->createBy)->name;
        //     }

        // ]);

        // $this->crud->addColumn([
        //     'type' => 'relationship',
        //     'name' => 'updatedBy',
        //     'label' => 'updated By',
        //     'attribute' => 'name',
        //     'entity'    => 'updatedBy',
        //     'function'  => function ($entry) {
        //         return optional($entry->createBy)->name;
        //     }
        // ]);

        $this->crud->addFilter(
            [
                'type'  => 'select2',
                'name'  => 'class_name',
                'label' => 'ClassName'
            ],
            function () {
                return Classroom::pluck('class_name', 'class_name')->toArray();
            }
        );
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
        CRUD::setValidation(ClassroomRequest::class);

        $this->crud->addField([
            'name' => 'class_name',
            'type' => 'text',
            'label' => 'ClassName',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([

            'name'  => ['start_date','end_date'],
            'label' => 'Event Date Range',
            'type'  => 'date_range',
            'default' => ['2022-04-9 01:01', '2022-04-10 02:00'],
            'date_range_options' =>
            [
                'drops' => 'auto',
                'timePicker' => true,
                'locale' => ['format' => 'DD/MM/YYYY HH:mm ']
            ],
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

        $this->crud->addField([
            'label'     => "Subject",
            'type'      => 'select2',
            'name'      => 'subSubject',
            'attribute' => 'subjectName',
            'model'     => "App\Models\Subject",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'label'     => "Teacher",
            'type'      => 'select2',
            'name'      => 'subteacher',
            'attribute' => 'name',
            'model'     => "App\Models\Teacher",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);



        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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


    public function store()
    {

        // dd(request()->start_date);

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
}
