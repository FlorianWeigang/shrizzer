<?php

namespace Shrizzer\Transformers;

use League\Fractal\TransformerAbstract;
use Shrizzer\Models\Session;

/**
 * Class SessionTransformer
 *
 * @package Shrizzer\Transformers
 */
class SessionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Session $session
     *
     * @return array
     */
    public function transform(Session $session)
    {
        return [
            'key' => $session->key,
            'name' => $session->name,
            'description' => $session->description
        ];
    }
}
