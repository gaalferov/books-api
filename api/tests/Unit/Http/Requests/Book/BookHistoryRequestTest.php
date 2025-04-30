<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Requests\Book;

use App\Http\Requests\Book\BookHistoryRequest;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookHistoryRequestTest extends TestCase
{
    #[Test]
    #[DataProvider('validationRulesDataProvider')]
    public function validation_rules(array $input, bool $shouldPass): void
    {
        // Arrange
        $request = new BookHistoryRequest;

        // Act
        $validator = ValidatorFacade::make($input, $request->rules());

        // Assert
        $this->assertEquals($shouldPass, $validator->passes());
    }

    #[Test]
    #[DataProvider('validationErrorsDataProvider')]
    public function validation_errors(array $input, array $expectedErrors): void
    {
        // Arrange
        $request = new BookHistoryRequest;

        // Act
        $validator = ValidatorFacade::make($input, $request->rules());
        $errors = $validator->errors()->toArray();

        // Assert
        $this->assertEquals($expectedErrors, $errors);
    }

    public static function validationRulesDataProvider(): array
    {
        return [
            'valid data' => [
                'input' => [
                    'age-group' => 'Adult',
                    'author' => 'John Doe',
                    'isbn' => '1234567890',
                    'offset' => 20,
                    'price' => '19.99',
                    'publisher' => 'Publisher Name',
                    'title' => 'Book Title',
                ],
                'shouldPass' => true,
            ],
            'invalid isbn' => [
                'input' => [
                    'isbn' => 'invalid_isbn',
                ],
                'shouldPass' => false,
            ],
            'negative offset' => [
                'input' => [
                    'offset' => -10,
                ],
                'shouldPass' => false,
            ],
            'invalid price format' => [
                'input' => [
                    'price' => '19.999',
                ],
                'shouldPass' => false,
            ],
        ];
    }

    public static function validationErrorsDataProvider(): array
    {
        return [
            'invalid isbn format' => [
                'input' => ['isbn' => 'invalid_isbn'],
                'expectedErrors' => [
                    'isbn' => ['The isbn field format is invalid.'],
                ],
            ],
            'negative offset' => [
                'input' => ['offset' => -10],
                'expectedErrors' => [
                    'offset' => [
                        'The offset field must be at least 0.',
                        'The offset field must be a multiple of 20.',
                    ],
                ],
            ],
        ];
    }
}
