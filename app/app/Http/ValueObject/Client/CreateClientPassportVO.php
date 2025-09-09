<?php

namespace App\Http\ValueObject\Client;

use App\Helpers\Date\DateHelper;

class CreateClientPassportVO
{
    public function __construct(
        public string|null $serial_number,
        public string|null $passport_issue_at,
        public string|null $birthday_at,
        public string|null $address,
        public string|null $driving_license,
        public string|null $driver_license_issue_at,
        public string|null $form_owner_id,
    )
    {
        
    }



    public static function fromArray(array $data)
    {
        return new self(
            serial_number:              $data['serial_number'] ?? null,
            passport_issue_at:          DateHelper::getFormatedDate($data['passport_issue_at'] ?? null, 'd.m.Y', 'Y-m-d'),
            birthday_at:                DateHelper::getFormatedDate($data['birthday_at'] ?? null, 'd.m.Y', 'Y-m-d'),
            address:                    $data['address'] ?? null,
            driving_license:            $data['driving_license'] ?? null,
            driver_license_issue_at:    DateHelper::getFormatedDate($data['driver_license_issue_at'] ?? null, 'd.m.Y', 'Y-m-d'),
            form_owner_id:              $data['form_owner_id'] ?? null,
        );
    }
}
