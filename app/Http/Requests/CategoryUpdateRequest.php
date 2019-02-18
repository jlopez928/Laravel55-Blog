<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
        //dd($this->category);
        return [
            //Validacion de los Campos
            'name' => 'required',
            //valida el unique slug en todos menos en el ID actual
            'slug' => 'required|unique:categories,slug,' . $this->category, 
        ];
    }
}
