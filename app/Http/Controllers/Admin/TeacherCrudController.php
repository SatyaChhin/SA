<?php

namespace App\Http\Controllers\Admin;

use App\Models\Teacher;
use App\Imports\UsersImport;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\TeacherRequest;
use App\Repositories\ContactRepositories;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TeacherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TeacherCrudController extends CrudController
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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    protected $contactRepo;
    public function setup()
    {
        CRUD::setModel(\App\Models\Teacher::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/teacher');
        CRUD::setEntityNameStrings('teacher', 'teachers');
        $this->crud->addButtonFromView('top', 'import', 'import', 'end');
        $this->contactRepo = resolve(ContactRepositories::class);
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


        $this->crud->addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();

        $this->crud->addColumn([
            'name' => 'code',
            'type' => 'text',
            'labal' => 'Code'
        ]);

        CRUD::addFilter(
            [
                'name' => 'name',
                'type' => 'select2',
                'labal' => 'Name',
            ],
            function () {
                return Teacher::pluck('name', 'name')->toArray();
            }
        );


        $this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'labal' => 'Name'
        ]);

        $this->crud->addColumn([
            'name' => 'gender',
            'type' => 'text',
            'labal' => 'Gender'
        ]);

        $this->crud->addColumn([
            'name' => 'address',
            'type' => 'text',
            'labal' => 'Address'
        ]);

        $this->crud->addFilter([
            'name'  => 'address',
            'type'  => 'select2_multiple',
            'label' => 'Address    '
        ], function () {
            return Teacher::pluck('address', 'address')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'address', json_decode($values));
        });

        CRUD::addColumn([
            'name' => 'PlaceOfBirth',
            'lable' => 'PlaceOfBirth',
            'type'  => 'text',

        ]);

        CRUD::addColumn([
            'name' => 'DateOfBirth',
            'lable' => 'DateOfBirth',
            'type'  => 'date',
            'format' => 'Y-MM-DD'
        ]);




        CRUD::addColumn([
            'name' => 'created_at',
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
                'label' => 'Delect_Date'
            ],
            false,
            function ($value) {
                $this->crud->addClause('whereDate', 'updated_at', $value);
            }
        );

        $this->crud->addColumn([
            'name' => 'updated_at',
            'lable' => 'Date',
            'type'  => 'date',
            'format' => 'Y-MM-DD'
        ]);

        $this->crud->addColumn([
            'type' => 'relationship',
            'name' => 'createdBy',
            'label' => 'Created By',
            'attribute' => 'name',
            'entity'    => 'createdBy',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('createdBy', function ($q) use ($column, $searchTerm) {
                    $q->where($column['attribute'], 'like', '%' . $searchTerm . '%')->orWhereDate('created_at', '=', date($searchTerm));
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


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    public function importTeacher()
    {
        // Excel::import(new UsersImport , response()->json(request()->test));

        Excel::import(new UsersImport, request()->file);

        return response()->json(['status' => True, 'message' => 'upolaod succesful']);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(TeacherRequest::class);


        $this->crud->addField([
            'name'     => 'name',
            'label'    => 'Name',
            'type'     => 'text',
            'tab'      => 'TeacherProfile',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);
        $this->crud->addField([
            'name'     => 'code',
            'label'    => 'Code',
            'type'     => 'text',
            'tab'      => 'TeacherProfile',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'facebook',
            'label' => 'Facebook',
            'type' => 'text',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'whatApp',
            'label' => 'What App',
            'type' => 'text',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'messenger',
            'label' => 'Messenger',
            'type' => 'text',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'Website',
            'label' => 'Website',
            'type' => 'text',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'TwitSter',
            'label' => 'Twitter',
            'type' => 'text',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'facebook',
            'label' => 'Facebook',
            'type' => 'text',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name' => 'telegram',
            'label' => 'Telegram',
            'type' => 'text',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name'        => 'gender',
            'label'       => "Gender",
            'type'        => 'select_from_array',
            'options'     => ['Male' => 'Male', 'Female' => 'Female'],
            'allows_null' => false,
            'default'     => 'one',
            'tab'      => 'TeacherProfile',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);


        $this->crud->addField([
            'name'     => 'address',
            'label'    => 'Address',
            'type'     => 'text',
            'tab'      => 'TeacherProfile',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name'        => 'PlaceOfBirth',
            'label'       => "place of birth",
            'type'        => 'select2_from_array',
            'options'     => [
                'Phnom Penh' => 'Phnom Penh',
                'Banteay Meanchey' => 'Banteay Meanchey',
                'Battambang' => 'Battambang',
                'Kampong Cham' => 'Kampong Cham',
                'Kampong Chhnang' => 'Kampong Chhnang',
                'Kampong Speu' => 'Kampong Speu',
                'Kampong Thom' => 'Kampong Thom',
                'Kampot' => 'Kampot',
                'Kandal' => 'Kandal',
                'Kep' => 'Kep',
                'Koh Kong' => 'Koh Kong',
                'Kratié' => 'Kratié',
                'Mondulkiri' => 'Mondulkiri',
                'Oddar Meanchey' => 'Oddar Meanchey',
                'Pailin' => 'Pailin',
                'Preah Sihanouk' => 'Preah Sihanouk',
                'Preah Vihear' => 'Preah Vihear',
                'Pursat' => 'Pursat',
                'Prey Veng' => 'Prey Veng',
                'Ratanakiri' => 'Ratanakiri',
                'Siem Reap' => 'Siem Reap',
                'Stung Treng' => 'Stung Treng',
                'Svay Rieng' => 'Svay Rieng',
                'Takéo' => 'Takéo',
                'Tboung Khmom' => 'Tboung Khmom',
            ],
            'allows_null' => false,
            'default'     => 'one',
            // 'allows_multiple' => true,
            'tab'      => 'TeacherProfile',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([

            'name'  => 'DateOfBirth',
            'label' => 'Birthday',
            'type'  => 'date',
            'tab'      => 'TeacherProfile',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'name'     => 'email',
            'label'    => 'Email',
            'type'     => 'email',
            'tab' => 'Contact Info',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
           'name'  => 'phone',
           'label' => 'phone',
           'type'  => 'number',
        //    'view'  => 'crud::Fields.form_phone',
           'tab' => 'Contact Info',
           'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        
        // CRUD::field('created_at');
        // CRUD::field('updated_at');

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
        CRUD::addfield([
            'name' => 'created_by',
            'type' => 'hidden'
        ]);
        request()->merge([
            'created_by' => backpack_user()->id
        ]);

        $response = $this->traitStore();

        $this->contactRepo->createRepo($this->crud->entry, $this->crud->getRequest());

        // dd($this->crud->entry);
        // \App\Models\Contact::create([
        //     'facebook' => request()->facebook,
        //     'whatApp' => request()->whatApp,
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

