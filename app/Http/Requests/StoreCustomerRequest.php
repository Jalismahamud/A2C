<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize():bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'name' => 'required|string|max:100',
            'address' => 'required|max:255',
            'type' => 'required|in:general,driver,student',
            'phone' => [
                'required',
                'string',
                Rule::unique('customers', 'phone'),
            ],
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'nid_number' => 'nullable|string|max:50',
            'vehicle_type' => 'nullable|required_if:type,driver',
            'license_number' => 'nullable|required_if:type,driver',
            'school_name' => 'nullable|required_if:type,student',
            'teacher_name' => 'nullable|required_if:type,student',
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'This phone number is already in use. Please use a different one.',
            'vehicle_type.required_if' => 'Vehicle type is required for drivers.',
            'license_number.required_if' => 'License number is required for drivers.',
            'school_name.required_if' => 'School name is required for students.',
            'teacher_name.required_if' => 'Teacher name is required for students.',
        ];
    }
}
