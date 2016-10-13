<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Validator as ValidatorFacade;

class ApiBaseController extends Controller
{
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = ValidatorFacade::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {

            $this->throwValidationException($request, $validator);
        }
    }

    protected function throwValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator, $this->buildFailedValidationResponse(
            $request, $this->formatValidationErrors($validator)
        ));
    }

    protected function formatValidationErrors(Validator $validator)
    {
        return [
            'message' => 'Validation fails',
            'data' => $validator->errors()->getMessages()
        ];
    }


}
