<?php

namespace App\Http\Requests\Supporter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "uuid" => "required|string",
            "name" => "required|string",
            "email" => "required|email",
            "data" => "required|array",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            "uuid.required" => __("The UUID is required."),
            "name.required" => __("The name is required."),
            "email.required" => __("The email is required."),
            "data.required" => __("The data is required."),
            "email.email" => __("The email is invalid."),
            "data.array" => __("The data must be an array."),
        ];
    }

    /**
     * Respond with HttpResponseException.
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "success" => false,
            "message" => "The given data was invalid.",
            "errors" => $validator->errors()
        ], 422));
    }
}
