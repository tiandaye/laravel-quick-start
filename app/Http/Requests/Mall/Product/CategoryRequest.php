<?php

namespace App\Http\Requests\Mall\Product;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            // Crate
            case 'POST':
                {
                    return [
                        'name' => 'required|max:191',
                    ];
                }
            // UPDATE
            case 'PUT':
            case 'PATCH':
                {
                    $id = $this->route('categories');
                    return [
                        'name' => 'required|max:191',
                    ];
                }
            default:
                break;
        }
    }
}
