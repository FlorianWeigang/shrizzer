<?php

namespace Shrizzer\Transformers;

use League\Fractal\TransformerAbstract;
use Shrizzer\Models\SessionUrl;

class SessionUrlTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'url'
    ];

    /**
     * A Fractal transformer.
     *
     * @param SessionUrl $sessionUrl
     *
     * @return array
     */
    public function transform(SessionUrl $sessionUrl)
    {
        return [
            'id'            => $sessionUrl->id,
            'title'         => $sessionUrl->title,
            'description'   => $sessionUrl->description,
            'voteCount'    => $sessionUrl->vote_count
        ];
    }

    /**
     * @param SessionUrl $sessionUrl
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUrl(SessionUrl $sessionUrl)
    {
        $url = $sessionUrl->url;

        if ($url) {
            return $this->item($url, new UrlTransformer());
        }
    }
}
