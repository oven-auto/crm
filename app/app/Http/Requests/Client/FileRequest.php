<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
        $arr = [];

        if(in_array('GET',$methods))
            $data = [
                'client_id' => 'required'
            ];

        if(in_array('POST',$methods))
            $data = [
                'client_id' => 'required',
                //'title' => 'string|required',
                //'file' => 'required|file|mimes:jpg,png,doc,docx,xls,xlsx,txt,pdf'
            ];

        if(in_array('PATCH',$methods))
            $data = [
                'title' => 'string|nullable',
            ];

        return array_merge($arr, $data);
    }

    public function messages()
    {
        return [
            'client_id.required' => 'Идентификатор клиента не указан',
            //'title.required' => 'Название фаила обязательно',
            //'title.string' => 'Название фаила должно быть строкой',
            //'file.required' => 'Загрузка фаила обязательна',
            //'file.file' => 'Загруженные данные должны быть фаилом',
            //'file.mimes' => 'Загруженный фаил должен быть с одним из следующих расширений: jpg,jpeg,png,doc,docx,xls,xlsx,txt,pdf'
        ];
    }
}
