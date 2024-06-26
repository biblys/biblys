<?php

namespace Biblys\Data;

class FileType
{
    public function __construct(
        private readonly string $mediaType,
        private readonly string $name = 'Inconnu',
        private readonly string $extension = '',
        private readonly string $icon = '/common/icons/file.svg',
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    /**
     * @return FileType[]
     */
    private static function getAll(): array
    {
        $unknown = new FileType('application/octet-stream');

        $pdf = new FileType(
            mediaType: 'application/pdf',
            name: 'PDF',
            extension: '.pdf',
            icon: '/common/icons/pdf_16.png'
        );
        $epub = new FileType(
            mediaType: 'application/epub+zip',
            name: 'ePub',
            extension: '.epub',
            icon: '/common/icons/epub_16.png'
        );
        $kindle = new FileType(
            mediaType: 'application/x-mobipocket-ebook',
            name: 'Kindle',
            extension: '.mobi',
            icon: '/common/icons/azw_16.png',
        );

        $mp3 = new FileType(
            mediaType: 'audio/mp3',
            name: 'MP3',
            extension: '.mp3',
            icon: '/common/icons/file_mp3.png',
        );
        $ogg = new FileType(
            mediaType: 'audio/ogg',
            name: 'OGG',
            extension: '.ogg',
            icon: '/common/icons/file_ogg.png',
        );
        $flac = new FileType(
            mediaType: 'audio/flac',
            name: 'FLAC',
            extension: '.flac',
            icon: '/common/icons/file.svg',
        );

        $zip = new FileType(
            mediaType: 'application/zip',
            name: 'ZIP',
            extension: '.zip',
            icon: '/common/icons/file.svg',
        );

        return [$unknown, $pdf, $epub, $kindle, $mp3, $ogg, $flac, $zip];
    }

    public static function getByMediaType(string $mediaType): FileType
    {
        foreach (self::getAll() as $fileType) {
            if ($fileType->getMediaType() === $mediaType) {
                return $fileType;
            }
        }


        return self::getByMediaType("application/octet-stream");
    }
}