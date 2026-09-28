<?php

// Nederlandse validatieberichten voor de regels die in dit project gebruikt worden.
// Ontbrekende regels vallen terug op de Engelse teksten van Laravel.

return [
    'accepted' => ':Attribute moet geaccepteerd zijn.',
    'after' => ':Attribute moet een datum na :date zijn.',
    'after_or_equal' => ':Attribute moet een datum op of na :date zijn.',
    'alpha_dash' => ':Attribute mag alleen letters, cijfers, streepjes en liggende streepjes bevatten.',
    'array' => ':Attribute moet een lijst zijn.',
    'between' => [
        'numeric' => ':Attribute moet tussen :min en :max liggen.',
        'string' => ':Attribute moet tussen :min en :max tekens lang zijn.',
    ],
    'date' => ':Attribute is geen geldige datum.',
    'date_format' => ':Attribute moet het formaat :format hebben.',
    'email' => ':Attribute moet een geldig e-mailadres zijn.',
    'exists' => 'De gekozen :attribute bestaat niet.',
    'in' => 'De gekozen :attribute is ongeldig.',
    'integer' => ':Attribute moet een geheel getal zijn.',
    'max' => [
        'numeric' => ':Attribute mag niet groter zijn dan :max.',
        'string' => ':Attribute mag niet langer zijn dan :max tekens.',
    ],
    'min' => [
        'numeric' => ':Attribute moet minimaal :min zijn.',
        'string' => ':Attribute moet minimaal :min tekens lang zijn.',
    ],
    'not_in' => 'De gekozen :attribute is niet toegestaan.',
    'numeric' => ':Attribute moet een getal zijn.',
    'required' => ':Attribute is verplicht.',
    'string' => ':Attribute moet tekst zijn.',
    'unique' => ':Attribute is al in gebruik.',
    'url' => ':Attribute moet een geldige URL zijn.',

    'attributes' => [],
];
