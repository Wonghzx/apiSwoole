<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/23/023
 * Time: 10:16
 */

namespace Core\Component\Session;

use Core\Component\File;
use Core\Component\IO\FileIO;

class SessionHandler implements \SessionHandlerInterface
{

    /**
     * @var Request Session Name
     */
    private $sessionName;

    /**
     * @var 路径
     */
    private $savePath;

    /**
     * @var 文件流
     */
    private $fileStream;

    /**
     * @var
     */
    private $saveFile;

    /**
     * Close the session
     * @link http://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close(): bool
    {
        // TODO: Implement close() method.
        if ($this->fileStream instanceof FileIO) {
            if ($this->fileStream->getStreamResource()) {
                $this->fileStream->unlock();
            }
            $this->fileStream = null;
            return true;
        } else {
            return true;
        }
    }

    /**
     * Destroy a session
     * @link http://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $session_id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($session_id): bool
    {
        // TODO: Implement destroy() method.
        $this->close();
        if (file_exists($this->savePath)) {
            unlink($this->saveFile);
        }
        return true;
    }

    /**
     * Cleanup old sessions
     * @link http://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $maxlifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($maxlifetime)
    {
        // TODO: Implement gc() method.
        $current = time();
        $res = File::scanDir($this->savePath);
        if (is_array($res)) {
            foreach ($res AS $files) {
                $time = fileatime($files);
                if ($current - $time > $maxlifetime) {
                    unlink($files);
                }

            }
        }
    }

    /**
     * Initialize session
     * @link http://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $name): bool
    {
        // TODO: Implement open() method.
        $this->savePath = $save_path;
        $this->sessionName = $name;
        return true;
    }

    /**
     * Read session data
     * @link http://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $session_id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($session_id)
    {
        // TODO: Implement read() method.
        if (!$this->fileStream) {
            $this->saveFile = $this->savePath . "/{$this->sessionName}_{$session_id}";
            $this->fileStream = new FileIO($this->saveFile);
        }
        if (!$this->fileStream->getStreamResource()) {
            return '';
        } else {
            $this->fileStream->lock();
            return $this->fileStream->__toString();
        }
    }

    /**
     * Write session data
     * @link http://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $session_id The session id.
     * @param string $session_data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function write($session_id, $session_data)
    {
        // TODO: Implement write() method.
        if (!$this->fileStream) {
            print_r($this->saveFile);
            $this->fileStream = new FileIO($this->saveFile);
        }
        if (!$this->fileStream->getStreamResource()) {
            return false;
        } else {
            $this->fileStream->truncate();
            $this->fileStream->rewind();
            return $this->fileStream->write($session_data);
        }
    }
}