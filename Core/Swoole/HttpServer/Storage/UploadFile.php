<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9/009
 * Time: 11:25
 */

namespace Core\Swoole\HttpServer\Storage;
class UploadFile
{
    private $stream;
    private $size;
    private $error;
    private $clientFileName;
    private $clientMediaType;

    function __construct($tempName, $size, $errorStatus, $clientFilename = null, $clientMediaType = null)
    {
        $this->stream = new DataStream(fopen($tempName, "r+"));
        $this->error = $errorStatus;
        $this->size = $size;
        $this->clientFileName = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    public function getStream()
    {
        // TODO: Implement getStream() method.
        return $this->stream;
    }

    public function moveTo($targetPath)
    {
        // TODO: Implement moveTo() method.
        return file_put_contents($targetPath, $this->stream) ? true : false;
    }

    public function getSize()
    {
        // TODO: Implement getSize() method.
        return $this->size;
    }

    public function getError()
    {
        // TODO: Implement getError() method.
        return $this->error;
    }

    public function getClientFilename()
    {
        // TODO: Implement getClientFilename() method.
        return $this->clientFileName;
    }

    public function getClientMediaType()
    {
        // TODO: Implement getClientMediaType() method.
        return $this->clientMediaType;
    }
}