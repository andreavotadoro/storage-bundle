<?php

declare(strict_types=1);
/**
 * User: Fabiano Roberto <fabiano.roberto@ped.technology>
 * Date: 2019-01-24
 * Time: 16.00.
 */

namespace PaneeDesign\StorageBundle\Twig;

use PaneeDesign\StorageBundle\Entity\Media;
use PaneeDesign\StorageBundle\Handler\MediaHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MediaExtension extends AbstractExtension
{
    /**
     * @var MediaHandler
     */
    protected $uploader;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(MediaHandler $uploader, RouterInterface $router)
    {
        $this->uploader = $uploader;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('image', [$this, 'image']),
            new TwigFunction('document', [$this, 'document']),
            new TwigFunction('video', [$this, 'video']),
            new TwigFunction('audio', [$this, 'audio']),
        ];
    }

    /**
     * @param string $filter
     * @param bool   $fullUrl
     *
     * @throws \Gaufrette\Extras\Resolvable\UnresolvableObjectException
     *
     * @return bool|string
     */
    public function image(Media $image, ?string $filter = null, ?bool $fullUrl = false): string
    {
        if (null !== $filter) {
            if ($image->hasFilter($filter)) {
                return $image->getUrl($filter);
            }

            $urlType = $fullUrl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH;

            return $this->router->generate('ped_storage_image', [
                'key' => $image->getKey(),
                'filter' => $filter,
            ], $urlType);
        }

        return $this->uploader->getFullUrl($image->getFullKey());
    }

    /**
     * @param bool $fullUrl
     */
    public function document(Media $document, ?bool $fullUrl = false): string
    {
        $urlType = $fullUrl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH;

        return $this->router->generate('ped_storage_document', ['key' => $document->getKey()], $urlType);
    }

    /**
     * @param bool $fullUrl
     */
    public function video(Media $video, ?bool $fullUrl = false): string
    {
        $urlType = $fullUrl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH;

        return $this->router->generate('ped_storage_video', ['key' => $video->getKey()], $urlType);
    }

    /**
     * @param bool $fullUrl
     */
    public function audio(Media $audio, ?bool $fullUrl = false): string
    {
        $urlType = $fullUrl ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH;

        return $this->router->generate('ped_storage_audio', ['key' => $audio->getKey()], $urlType);
    }
}
