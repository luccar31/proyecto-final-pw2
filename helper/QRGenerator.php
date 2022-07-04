<?php

require_once('third-party/phpqrcode/qrlib.php');

class QRGenerator
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getQrPng($content)
    {

        $filename = 'qr-' . md5($content) . '.png';

        $absoluteFilePath = $this->path . $filename;

        if (file_exists($absoluteFilePath)) {
            return null;
        }

        QRcode::png($content, $absoluteFilePath, QR_ECLEVEL_L, 10);
        return $absoluteFilePath;
    }
}