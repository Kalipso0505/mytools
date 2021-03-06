<?php

  namespace Simplon\Border;

  /**
   * @link http://en.wikipedia.org/wiki/List_of_HTTP_header_fields
   * @link http://en.wikipedia.org/wiki/MIME_type
   */

  class Headers
  {
    /** @var Headers */
    private static $_instance;

    /** @var array */
    private $_headers = array();

    /** @var array */
    private $_statusCodes = array(
      100 => array('HTTP/1.1', 'Continue'),
      101 => array('HTTP/1.1', 'Switching Protocols'),
      200 => array('HTTP/1.1', 'OK'),
      201 => array('HTTP/1.1', 'Created'),
      202 => array('HTTP/1.1', 'Accepted'),
      203 => array('HTTP/1.1', 'Non-Authoritative Information'),
      204 => array('HTTP/1.1', 'No Content'),
      205 => array('HTTP/1.1', 'Reset Content'),
      206 => array('HTTP/1.1', 'Partial Content'),
      300 => array('HTTP/1.1', 'Multiple Choices'),
      301 => array('HTTP/1.1', 'Permanently at another address - consider updating link'),
      302 => array('HTTP/1.1', 'Found at new location - consider updating link'),
      303 => array('HTTP/1.1', 'See Other'),
      304 => array('HTTP/1.1', 'Not Modified'),
      305 => array('HTTP/1.1', 'Use Proxy'),
      306 => array('HTTP/1.1', 'Switch Proxy'),
      // No longer used, but reserved
      307 => array('HTTP/1.1', 'Temporary Redirect'),
      400 => array('HTTP/1.1', 'Bad Request'),
      401 => array('HTTP/1.1', 'Authorization Required'),
      402 => array('HTTP/1.1', 'Payment Required'),
      403 => array('HTTP/1.1', 'Forbidden'),
      404 => array('HTTP/1.1', 'Not Found'),
      405 => array('HTTP/1.1', 'Method Not Allowed'),
      406 => array('HTTP/1.1', 'Not Acceptable'),
      407 => array('HTTP/1.1', 'Proxy Authentication Required'),
      408 => array('HTTP/1.1', 'Request Timeout'),
      409 => array('HTTP/1.1', 'Conflict'),
      410 => array('HTTP/1.1', 'Gone'),
      411 => array('HTTP/1.1', 'Length Required'),
      412 => array('HTTP/1.1', 'Precondition Failed'),
      413 => array('HTTP/1.1', 'Request Entity Too Large'),
      414 => array('HTTP/1.1', 'Request-URI Too Long'),
      415 => array('HTTP/1.1', 'Unsupported Media Type'),
      416 => array('HTTP/1.1', 'Requested Range Not Satisfiable'),
      417 => array('HTTP/1.1', 'Expectation Failed'),
      449 => array('HTTP/1.1', 'Retry With'),
      // Microsoft extension
      500 => array('HTTP/1.1', 'Internal Server Error'),
      501 => array('HTTP/1.1', 'Not Implemented'),
      502 => array('HTTP/1.1', 'Bad Gateway'),
      503 => array('HTTP/1.1', 'Service Unavailable'),
      504 => array('HTTP/1.1', 'Gateway Timeout'),
      505 => array('HTTP/1.1', 'HTTP Version Not Supported'),
      509 => array('HTTP/1.1', 'Bandwidth Limit Exceeded') // not an official HTTP status code
    );

    // ##########################################

    /**
     * @return Headers
     */
    public static function getInstance()
    {
      if(! isset(Headers::$_instance))
      {
        Headers::$_instance = new Headers();
      }

      return Headers::$_instance;
    }

    // ##########################################

    /**
     * @return array
     */
    public function getAll()
    {
      return $this->_headers;
    }

    // ##########################################

    /**
     * @return Headers
     */
    public function release()
    {
      $headers = $this->getAll();

      foreach($headers as $key => $value)
      {
        $this->send($key, $value);
      }

      $this->reset();

      return $this;
    }

    // ##########################################

    /**
     * @param $key
     * @return bool
     */
    public function getByKey($key)
    {
      $headers = $this->getAll();

      if(! isset($headers[$key]))
      {
        return FALSE;
      }

      return $headers[$key];
    }

    // ##########################################

    /**
     * @return Headers
     */
    public function reset()
    {
      $this->_headers = array();

      return $this;
    }

    // ##########################################

    /**
     * @param $key
     * @param $value
     * @return string
     */
    protected function _format($key, $value)
    {
      return $key . ': ' . $value;
    }

    // ##########################################

    /**
     * @param $key
     * @param $value
     * @return Headers
     */
    protected function add($key, $value)
    {
      $this->_headers[$key] = $value;

      return $this;
    }

    // ##########################################

    /**
     * @param $key
     * @param $value
     */
    public function send($key, $value)
    {
      header($this->_format($key, $value));
    }

    // ##########################################

    /**
     * @param $code
     * @return Headers
     */
    public function setStatusCode($code)
    {
      header($this->_getStatusCodeDescription($code));

      return $this;
    }

    // ##########################################

    /**
     * @param $code
     * @return bool|string
     */
    protected function _getStatusCodeDescription($code)
    {
      if(! isset($this->_statusCodes[$code]))
      {
        return FALSE;
      }

      $statusParts = array(
        $this->_statusCodes[$code][0],
        $code,
        $this->_statusCodes[$code][1],
      );

      return implode(' ', $statusParts);
    }

    // ##########################################

    public function setNoCache()
    {
      $this->add('Pragma', 'no-cache');
      $this->add('Cache-Control', 'no-store, no-cache');
    }

    // ##########################################

    /**
     * @param $filePath
     * @param $mimeType
     */
    public function setFileData($filePath, $mimeType)
    {
      // set filename
      $fileName = basename($filePath);

      // set mime type if not set
      if(is_null($mimeType))
      {
        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath);
      }

      // no caching
      $this->setNoCache();

      // file based header
      $this->setContentType($mimeType);
      $this->setContentLenght(filesize($filePath));
      $this->setContentDisposition($fileName);
    }

    // ##########################################

    /**
     * @param $type
     * @return Headers
     */
    public function setContentType($type)
    {
      $this->add('Content-type', $type);

      return $this;
    }

    // ##########################################

    /**
     * @param $length
     * @return Headers
     */
    public function setContentLenght($length)
    {
      $this->add('Content-lenght', $length);

      return $this;
    }

    // ##########################################

    /**
     * @param $fileName
     * @return Headers
     */
    public function setContentDisposition($fileName)
    {
      $this->add('Content-disposition', 'attachment; filename="' . $fileName . '"');

      return $this;
    }

    // ##########################################

    /**
     * @param $encoding
     * @return Headers
     */
    public function setContentEncoding($encoding)
    {
      $this->add('Content-encoding', $encoding);

      return $this;
    }

    // ##########################################

    /**
     * @param $encoding
     * @return Headers
     */
    public function setTransferEncoding($encoding)
    {
      $this->add('Transfer-encoding', $encoding);

      return $this;
    }

    // ##########################################

    /**
     * @param $url
     * @return Headers
     */
    public function setRedirect($url)
    {
      $this->add('Location', $url);

      return $this;
    }
  }
