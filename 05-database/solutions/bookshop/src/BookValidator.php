<?php
declare(strict_types=1);

namespace App;

/** Turns raw form input into clean book data, or a list of errors. */
class BookValidator
{
    /**
     * @return array{0: array, 1: array} [clean data, errors by field]
     */
    public static function validate(array $input): array
    {
        $errors = [];

        $title  = trim($input['title'] ?? '');
        $author = trim($input['author'] ?? '');
        $year   = trim($input['year'] ?? '');
        $price  = str_replace(',', '.', trim($input['price'] ?? ''));   // accept "12,50"

        if ($title === '' || mb_strlen($title) > 255) {
            $errors['title'] = 'Title is required (255 characters max).';
        }
        if ($author === '' || mb_strlen($author) > 100) {
            $errors['author'] = 'Author is required (100 characters max).';
        }

        $maxYear = (int) date('Y') + 1;
        if ($year !== '' && filter_var($year, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => $maxYear]]) === false) {
            $errors['year'] = "Year must be a number between 0 and $maxYear (or empty).";
        }

        if (!is_numeric($price) || (float) $price < 0) {
            $errors['price'] = 'Price must be a positive number, e.g. 12.50';
        }

        $data = [
            'title'  => $title,
            'author' => $author,
            'year'   => $year === '' ? null : (int) $year,
            'price'  => is_numeric($price) ? round((float) $price, 2) : 0,
        ];

        return [$data, $errors];
    }
}
