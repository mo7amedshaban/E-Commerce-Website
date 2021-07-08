<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            #required_without:id  photo must insert it in create but not insert it in update

            'photo' => 'required_without:id|mimes:jpg,jpeg,png',
            'category' => 'required|array|min:1', # min -> minumim
            'category.*.name' => 'required', // *  {{$index}}
            'category.*.abbr' => 'required',
            //'category.*.active' => 'required',
        ];
    }
}
