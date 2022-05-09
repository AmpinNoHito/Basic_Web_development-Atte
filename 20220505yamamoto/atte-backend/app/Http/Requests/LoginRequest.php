<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|max:191',
            'password' => 'required|min:8|max:191',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスが入力されていません。',
            'email.email' => 'メールアドレスの入力形式が不正です。',
            'email.max' => 'メールアドレスは191文字以内で入力してください。',
            'password.required' => 'パスワードが入力されていません。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは191文字以内で入力してください。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $data = [
            'message' => __('入力されたデータが不正です。'),
            'errors' => $validator->errors()->toArray(),
        ];
        throw new HttpResponseException(response()->json($data, 422));
    }
}
