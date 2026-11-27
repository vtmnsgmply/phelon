<?php
declare(strict_types=1);

namespace Phelon\Booking;

use DateTimeZone;
use Phelon\Core\HttpError;

final class BookingRequest
{
    public static function validate(array $body): array
    {
        $values = [];
        foreach (['date'=>10,'start'=>20,'name'=>100,'email'=>254,'phone'=>32,'state'=>2,
            'timezone'=>80,'concern'=>300,'interest'=>150] as $key => $limit) {
            $value = $body[$key] ?? '';
            if (!is_string($value) || strlen(trim($value)) > $limit) {
                throw new HttpError(422, 'Check the booking fields and their lengths.');
            }
            $values[$key] = trim($value);
        }
        $values['state'] = strtoupper($values['state']);
        $states = explode(' ', 'AL AK AZ AR CA CO CT DE DC FL GA HI ID IL IN IA KS KY LA ME MD MA MI MN MS MO MT NE NV NH NJ NM NY NC ND OH OK OR PA RI SC SD TN TX UT VT VA WA WV WI WY AS GU MP PR VI');
        $selection = \phelon_discovery_selection(['interest' => $values['interest']]);
        if ($values['name'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)
            || !preg_match('/\A[+0-9(). -]{7,32}\z/', $values['phone'])
            || strlen(preg_replace('/\D/', '', $values['phone'])) < 7
            || !in_array($values['state'], $states, true)
            || !in_array($values['timezone'], DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC), true)
            || $values['concern'] === '' || $selection === null) {
            throw new HttpError(422, 'Enter valid contact details, state, time zone, and a brief area of concern.');
        }
        return $values + ['service' => $selection['service'], 'subtype' => $selection['subtype']];
    }
}
