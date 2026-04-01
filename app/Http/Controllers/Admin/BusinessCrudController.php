<?php

namespace App\Http\Controllers\Admin;

use App\Models\Business;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class BusinessCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;

    public function setup()
    {
        $this->crud->setModel(Business::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/business');
        $this->crud->setEntityNameStrings('business', 'businesses');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Business Name',
        ]);

        $this->crud->addColumn([
            'name' => 'user',
            'type' => 'relationship',
            'label' => 'Owner',
            'attribute' => 'name',
        ]);

        $this->crud->addColumn([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Email',
        ]);

        $this->crud->addColumn([
            'name' => 'phone',
            'type' => 'text',
            'label' => 'Phone',
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'type' => 'select_from_array',
            'label' => 'Status',
            'options' => [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'inactive' => 'Inactive',
            ],
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'type' => 'datetime',
            'label' => 'Registered',
        ]);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation([
            'name' => 'required|min:2|max:255',
            'email' => 'nullable|email',
            'user_id' => 'required|exists:users,id',
        ]);

        $this->addBusinessFields();
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function addBusinessFields()
    {
        $this->crud->addField([
            'name' => 'user_id',
            'type' => 'select',
            'label' => 'Owner',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => 'App\Models\User',
        ]);

        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Business Name',
        ]);

        $this->crud->addField([
            'name' => 'slug',
            'type' => 'text',
            'label' => 'Slug',
            'hint' => 'Leave blank to auto-generate from name',
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description',
        ]);

        $this->crud->addField([
            'name' => 'separator1',
            'type' => 'custom_html',
            'value' => '<h5 class="mt-4 mb-2">Contact Information</h5><hr>',
        ]);

        $this->crud->addField([
            'name' => 'address',
            'type' => 'text',
            'label' => 'Address',
        ]);

        $this->crud->addField([
            'name' => 'city',
            'type' => 'text',
            'label' => 'City',
            'default' => 'Bellefontaine',
        ]);

        $this->crud->addField([
            'name' => 'state',
            'type' => 'text',
            'label' => 'State',
            'default' => 'OH',
        ]);

        $this->crud->addField([
            'name' => 'zip',
            'type' => 'text',
            'label' => 'ZIP Code',
        ]);

        $this->crud->addField([
            'name' => 'phone',
            'type' => 'text',
            'label' => 'Phone',
        ]);

        $this->crud->addField([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Email',
        ]);

        $this->crud->addField([
            'name' => 'website',
            'type' => 'url',
            'label' => 'Website',
        ]);

        $this->crud->addField([
            'name' => 'separator2',
            'type' => 'custom_html',
            'value' => '<h5 class="mt-4 mb-2">Media</h5><hr>',
        ]);

        $this->crud->addField([
            'name' => 'logo',
            'type' => 'upload',
            'label' => 'Logo',
            'upload' => true,
            'disk' => 'public',
        ]);

        $this->crud->addField([
            'name' => 'featured_image',
            'type' => 'upload',
            'label' => 'Featured Image',
            'upload' => true,
            'disk' => 'public',
        ]);

        $this->crud->addField([
            'name' => 'separator3',
            'type' => 'custom_html',
            'value' => '<h5 class="mt-4 mb-2">Status</h5><hr>',
        ]);

        $this->crud->addField([
            'name' => 'status',
            'type' => 'select_from_array',
            'label' => 'Status',
            'options' => [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'inactive' => 'Inactive',
            ],
            'default' => 'pending',
        ]);
    }
}
