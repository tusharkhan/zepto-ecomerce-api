<?php
/**
 * created by: tushar Khan
 * email : tushar.khan0122@gmail.com
 * date : 3/30/2022
 */


namespace App\Helper\HelperTraits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait RequestHelper
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            sendError(
                'Validation Error',
                $validator->errors()->toArray(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
