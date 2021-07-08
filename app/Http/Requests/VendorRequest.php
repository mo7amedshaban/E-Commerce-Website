<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
             # unique:vendors,mobile,'.$this->id    ignore unique when user edit his info
             # must uniqe:table,column,'.$this->id
            'mobile' =>'required|max:100|unique:vendors,mobile,'.$this->id,
            'email'  => 'required|email|unique:vendors,email,'.$this->id,
            'category_id'  => 'required|exists:main_categories,id',
            'address'   => 'required|string|max:500',
            #must add hidden filed id in edit html  name="id"
            #this meaning not must change this value in edit html but must in create html
            'password' =>'required_without:id',

        ];
    }


    public function messages(){

        return [
            'required'  => 'هذا الحقل مطلوب ',
            'max'  => 'هذا الحقل طويل',
            'category_id.exists'  => 'القسم غير موجود ',
            'email.email' => 'ضيغه البريد الالكتروني غير صحيحه',
            'address.string' => 'العنوان لابد ان يكون حروف او حروف وارقام ',
            'name.string'  =>'الاسم لابد ان يكون حروف او حروف وارقام ',
            'logo.required_without'  => 'الصوره مطلوبة',
            'email.unique'=> "ادخل ايميل اخر الايميل موجود",
            'mobile.unique'=>'ادخل رقم اخر الرقم موجود',
            'password.required'=>'هذا الحقل مطلوب'
        ];
    }

}
