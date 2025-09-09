<?php

namespace App\Http\Requests\Worksheet\Reserve;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      required={"reserve_id", "client_id"}
 * )
 */ 
class LisingerCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Property(format="integer", description="Reserve ID", property="reserve_id", type="string"),
     * @OA\Property(format="integer", description="Client ID", property="client_id", type="integer")
     */
    public function rules(): array
    {
        return [
            'reserve_id' => 'required',
            'client_id' => 'required',
        ];
    }
}
