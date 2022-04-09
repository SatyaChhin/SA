<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ContactCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Contact::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact');
        CRUD::setEntityNameStrings('contact', 'contacts');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::addFilter(
            [
                'labal' => 'Name',
                'name' => 'name_teacher',
                'type' => 'select2',
               
            ],
            function () {
                return Contact::pluck('name_teacher', 'name_teacher')->toArray();
            }
        );

        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Teacher',
            'name' => 'name_teacher',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Phone',
            'name' => 'phone',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Email',
            'name' => 'email',
        ]);

        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Facebook',
            'name' => 'facebook',
        ]);

        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Website',
            'name' => 'website',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Facebook',
            'name' => 'facebook',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Messenger',
            'name' => 'messenger',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'whatApp',
            'name' => 'whatApp',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Twitter',
            'name' => 'twitter',
        ]);
        $this->crud->addColumn([
            'type' => 'text',
            'label' => 'Telegram',
            'name' => 'telegram',
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
        CRUD::setValidation(ContactRequest::class);

        $this->crud->addField([
            'type' => 'email',
            'name' => 'email',
            'label' => 'Email',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'type' => 'text',
            'name' => 'facebook',
            'label' => 'Facebook',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'type' => 'text',
            'name' => 'whatApp',
            'label' => 'WhatApp',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        
        $this->crud->addField([
            'type' => 'text',
            'name' => 'messenger',
            'label' => 'Messenger',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'type' => 'text',
            'name' => 'website',
            'label' => 'Website',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'type' => 'text',
            'name' => 'twitter',
            'label' => 'Twitter',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ]
        ]);

        $this->crud->addField([
            'type' => 'text',
            'name' => 'telegram',
            'label' => 'Telegram',
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
}
