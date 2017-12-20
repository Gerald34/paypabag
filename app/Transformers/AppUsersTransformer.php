<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\AppUsers;


class AppUsersTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var $resource
     * @return array
     */
    public function transform(AppUsers $appUsers)
    {
        return [

            'id' => $appUsers->id,
            'paypa_id' => $appUsers->paypa_id,
            'username' => $appUsers->username,
            'name' => $appUsers->name,
            'surname' => $appUsers->surname,
            'email' => $appUsers->email,
            'gender' => $appUsers->gender,
            'birth_date' => $appUsers->birth_date,
            'street_number' => $appUsers->street_number,
            'street_name' => $appUsers->street_name,
            'suburb' => $appUsers->suburb,
            'city' => $appUsers->city,
            'province' => $appUsers->province,
            'country' => $appUsers->country,
            'created_at' => $appUsers->created_at->format('d M Y'),
            'last_login_at' => $appUsers->last_login_at->format('d M Y'),
            'logout_at' => $appUsers->logout_at->format('d M Y'),
        ];
    }

    
}
