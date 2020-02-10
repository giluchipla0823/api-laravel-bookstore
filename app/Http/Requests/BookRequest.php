<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method())
        {
            case 'GET':
                return [
                    'includes' => 'without_spaces|exists_relations:author,publisher,genres'
                ];
            case 'DELETE':
                return [];
            case 'POST':
                return $this->_rules();
            case 'PUT':
            case 'PATCH':
                return $this->_rules();
            default:break;
        }
    }

    private function _rules(){
        $id = $this->segment(3);

        return [
            'author_id' => 'required|integer|exists:authors,id',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'title' => 'required|max:200|' . $this->_uniqueRule($id),
            'summary' => 'required|max:255',
            'description' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
            "genres"    => "required|array|min:1",
            "genres.*"  => "required|integer|distinct|min:1|exists:genres,id",
        ];
    }

    private function _uniqueRule($id){
        $rule = 'unique:books,title';

        if($id){
            $rule .= ',' . $id . ',id';
        }

        return $rule;
    }
}
