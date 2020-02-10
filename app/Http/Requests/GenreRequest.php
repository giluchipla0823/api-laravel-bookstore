<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreRequest extends FormRequest
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
        switch ($this->method())
        {
            case 'GET':
                return [
                    'includes' => 'without_spaces|exists_relations:books'
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
            'name' => 'required|max:150|' . $this->_uniqueRule($id)
        ];
    }

    private function _uniqueRule($id){
        $rule = 'unique:genres,name';

        if($id){
            $rule .= ',' . $id . ',id';
        }

        return $rule;
    }
}
