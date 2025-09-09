<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreate extends FormRequest
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
        $methods = \Route::current()->methods();
        if(in_array('POST',$methods))
            $pass = ['password' => 'required|min:8|confirmed'];
        if(in_array('PATCH', $methods))
            $pass = ['password' => 'nullable|min:8|confirmed'];

        return array_merge([
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'email' => 'required|email:rfc,dns',
            'role_id' => 'required|integer',
            'phone' => [
                'string',
                'nullable',
            ],
        ], $pass);
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Поле имя обязательно для заполнения',
            'firstname.alpha' => 'Поле имя должно состоять только из букв',
            'lastname.required' => 'Поле фамилия обязательно для заполнения',
            'lastname.alpha' => 'Поле фамилия должно состоять только из букв',
            'email.required' => 'Поле email обязательно для заполнения',
            'email.email' => 'Поле email должно быть в формате эл. почты',
            'password.required' => 'Поле пароль обязательно для заполнения',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min' => 'Длина пароля минимум 8 символов',
            'role_id.required' => 'Поле роль обязательно для заполнения',
            'role_id.integer' => 'Поле роль должно быть ссылкой на роль',
        ];
    }
}
