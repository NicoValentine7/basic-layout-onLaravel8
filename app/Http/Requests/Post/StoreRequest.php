<?php

namespace App\Http\Requests\Post;

use App\Models\Community;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Gate $gate): bool
    {
        // todo 認可処理もここで行うことができる
        $community = $this->route()->parameter('community');
        return $gate->allows('store', [Post::class, $community]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:30',
            'body' => 'required|string|max:10000',
        ];
    }

    public function community(): Community
    {
        return $this->route()->parameter('community');
    }

    public function makePost(): Post
    {
        // バリデーションした値で埋めた Post を取得
        return new Post($this->validated());
    }

    //todo
    /**
     * [override] バリデーション失敗時ハンドリング
     *
     * @param Validator $validator
     * @throw HttpResponseException
     * @see FormRequest::failedValidation()
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = [];
        foreach ($validator->errors()->toArray() as $key => $value) {
            Arr::set($errors, $key, $value[0]);
        }
        throw new HttpResponseException(
            response()->json([
                'errors' => $errors
            ], 422)
        );
    }
}
