<?php

namespace App\Api\V1\Repositories;

use Dingo\Api\Routing\Helpers;
use Dingo\Api\Exception\ValidationHttpException;
use App\Api\V1\Controllers\HttpStatusResponse;

class BaseRepository
{
    // Interface help call
    use Helpers, HttpStatusResponse;

    // Returns the wrong request
    protected function errorBadRequest($validator)
    {
        // github like error messages
        // if you don't like this you can use code bellow
        //
        //throw new ValidationHttpException($validator->errors());

        $result = [];
        $messages = $validator->errors()->toArray();

        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                        'status' => false
                    ];
                }
            }
        }

        throw new ValidationHttpException($result);
    }
}
