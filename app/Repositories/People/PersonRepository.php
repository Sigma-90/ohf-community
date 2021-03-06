<?php

namespace App\Repositories\People;

use App\Models\People\Person;

class PersonRepository
{
    public function findByCardNumber($cardNo)
    {
        return Person::where('card_no', $cardNo)
            ->first();
    }

    public function filterByTerms(array $terms, ?int $perPage = null)
    {
        $query = Person::filterByTerms($terms)
            ->orderBy('name', 'asc')
            ->orderBy('family_name', 'asc');

        return $perPage != null ? $query->paginate($perPage) : $query->get();
    }
}
