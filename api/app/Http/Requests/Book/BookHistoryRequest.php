<?php

declare(strict_types=1);

namespace App\Http\Requests\Book;

use App\Utils\JsonResponseUtil;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookHistoryRequest
 */
class BookHistoryRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'age-group' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'contributor' => 'nullable|string|max:255',
            'isbn' => [
                'nullable',
                'string',
                'regex:/^(\d{10}|\d{13})$/'
            ],
            'offset' => 'nullable|integer|min:0|multiple_of:20',
            'price' => [
                'nullable',
                'string',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'publisher' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            JsonResponseUtil::errorResponse(
                $validator->errors()->toArray(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
