<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
        $rule = [
            'name' => 'required',
            'password' => 'required',
            'cf-password' => 'required|same:password',
            'email' => 'required|email|unique:users,email,'.$this->route('admin'),
            'home_number' => 'required',
            'phone_number' => 'required|min:8|max:15|unique:users,phone_number,'.$this->route('admin'),
        ];
        if ($this->method() == 'PUT'){
            unset($rule['password'], $rule['cf-password']);
        }
        return $rule;
    }
    public function messages()
    {
        return [
            'required' => 'Trường dữ liêu bắt buộc',
            'email' => 'Trường dữ liêu không đúng định dạng email',
            'unique' => 'Dữ liệu đã tồn tại',
            'phone_number.min' => 'Phải nhập ít nhất 09 kí tự',
            'phone_number.max' => 'Dữ liệu tối đa 13 kí tự',
        ];
    }
}
