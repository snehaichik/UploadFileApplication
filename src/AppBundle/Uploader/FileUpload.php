<?php

namespace AppBundle\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Gaufrette\Filesystem;

class FileUpload
{
    private static $allowedMimeTypes = array(
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif'
    );

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file)
    {
        if (!in_array($file->getClientMimeType(), self::$allowedMimeTypes)) {
            throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed.', $file->getClientMimeType()));
        }

        $filename = sprintf('%s/%s/%s/%s.%s', date('Y'), date('m'), date('d'), uniqid(), $file->getClientOriginalExtension());

        $adapter = $this->filesystem->getAdapter();
//        $adapter->setMetadata($filename, array('contentType' => $file->getClientMimeType()));
        $adapter->write($filename, file_get_contents($file->getPathname()));

        return $filename;
    }
}