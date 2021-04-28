<?php


namespace App\Services\RevenueCat\Requests;


use App\Services\RevenueCat\Enums\PaymentMode;
use App\Services\RevenueCat\Interfaces\RequestInterface;

class CreatePurchaseRequest implements RequestInterface
{
    public static function rules(): array
    {
        return [
            'app_user_id' => 'string|required',
            'fetch_token' => 'string|required',
            'product_id' => 'string',
            'price' => 'float',
            'currency' => 'string',
            'payment_mode' => 'in:'.PaymentMode::values(),
            'introductory_price' => 'float',
            'is_restore' => 'string',
            'attributes' => 'array',
            'attributes.*' => 'array',
            'attributes.*.value' => 'string|required|nullable',
            'attributes.*.updated_at_ms' => 'numeric',
        ];
    }
}
