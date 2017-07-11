<?php

namespace Shrizzer\Repositories;
use Curl\Curl;
use MediaEmbed\MediaEmbed;
use Shrizzer\Models\Url;

/**
 * Class UrlRepository
 *
 * @package Shrizzer\Repositories
 */
class UrlRepository
{
    /**
     * @var MediaEmbed
     */
    private $mediaEmbedService;

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @param MediaEmbed $mediaEmbed
     * @param Curl $curl
     */
    public function __construct(MediaEmbed $mediaEmbed, Curl $curl)
    {
        $this->mediaEmbedService = $mediaEmbed;
        $this->curl = $curl;
    }

    /**
     * @param $id
     *
     * @return Url
     */
    public function getUrlById($id)
    {
        return Url::find($id);
    }

    /**
     * @param $address
     *
     * @return Url
     */
    public function findOrCreateByUrl($address)
    {
        $identifier = $this->createUrlIdentifier($address);

        $url = Url::firstOrNew([
            'url_identifier' => $identifier,
        ]);

        if (!$url->exists) {
            $url->url = $address;
            $this->extendUrlWithMetaData($url);

            $url->save();
        }

        return $url;
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function createUrlIdentifier($url)
    {
        return md5($url);
    }

    /**
     * @param $url
     *
     * @return Url
     */
    public function extendUrlWithMetaData(Url $url)
    {
        $body = $this->getUrlContent($url);

        $meta = $this->getMetaData($url, $body);

        $parsedData = parse_url($url->url);


        $url->favicon_url   = isset($meta['favicon']) ? $meta['favicon'] : null;
        $url->image_url     = isset($meta['openGraph']['image']) ? $meta['openGraph']['image'] : null;
        $url->title         = isset($meta['title']) ? $meta['title'] : null;
        $url->descriptions  = isset($meta['meta']['description']) ? $meta['meta']['description'] : null;
        $url->embed         = $this->getEmbedData($url->url);
        $url->open_graph    = isset($meta['openGraph']) ? json_encode($meta['openGraph']) : null;
        $url->base_url      = isset($parsedData['host']) ? $parsedData['host'] : null;
        $url->type          = isset($meta['openGraph']['type']) ? $meta['openGraph']['type'] : null;

        return $url;
    }

    /**
     * @param Url $url
     *
     * @return string
     */
    private function getUrlContent(Url $url)
    {
        $data = $this->curl->get($url->url);

        return $data;
    }

    /**
     * @param Url $url
     * @param string $body
     *
     * @return array
     */
    private function getMetaData(Url $url, $body)
    {
        $parser = new \MetaParser($body, $url->url);

        return $parser->getDetails();
    }

    /**
     * @param $url
     *
     * @return string
     */
    private function getEmbedData($url)
    {
        $parseResult = $this->mediaEmbedService
            ->parseUrl($url);

        if (!$parseResult) {
            return null;
        }

        $parseResult->setWidth(false);
        $parseResult->setHeight(false);

        return $parseResult->getEmbedCode();
    }
}