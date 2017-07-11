<?php

namespace Shrizzer\Transformers;

use League\Fractal\TransformerAbstract;
use Shrizzer\Models\Url;

/**
 * Class UrlTransformer
 *
 * @package Shrizzer\Transformers
 */
class UrlTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Url $url
     *
     * @return array
     */
    public function transform(Url $url)
    {
        return [
            'id' => $url->id,
            'url' => $url->url,
            'baseUrl' => $url->base_url,
            'faviconUrl' => $url->favicon_url,
            'title' => $url->title,
            'description' => $url->descriptions,
            'imageUrl' => $url->image_url
        ];
    }
}
