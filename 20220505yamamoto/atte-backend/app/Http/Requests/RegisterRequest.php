<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8|max:191',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前が入力されていません。',
            'name.string' => '名前の入力形式が不正です。',
            'name.max' => '名前は191文字以内で入力してください。',
            'email.required' => 'メールアドレスが入力されていません。',
            'email.email' => 'メールアドレスの入力形式が不正です。',
            'email.max' => 'メールアドレスは191文字以内で入力してください。',
            'email.unique' => '入力されたメールアドレスは既に登録されています。',
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
