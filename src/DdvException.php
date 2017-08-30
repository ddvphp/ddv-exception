<?php

  namespace DdvPhp;

  /**
   * Class DdvException
   *
   * @package DdvPhp\DdvException
   */
  class DdvException extends \Exception
  {
    /* 属性 */
    protected $errorId ;
    protected $responseData ;
    // 魔术方法
    public function __construct( $message = '' , $errorId = 'UNKNOWN_ERROR' , $code = '500', $responseData = array() )
    {
      parent::__construct($message,$code);
      $this->setErrorId($errorId)->setResponseData($responseData);
    }
    public function getErrorId(){
      $errorId = empty($this->errorId)?'UNKNOWN_ERROR':$this->errorId;
      return $errorId;
    }
    public function setErrorId($errorId = null){
      if (empty($errorId)) {
        $this->errorId = empty($this->errorId)?'UNKNOWN_ERROR':$this->errorId;
      }else{
        $this->errorId = $errorId;
      }
      return $this;
    }
    public function setMessage($message = null){
      if (empty($message)) {
        $this->message = empty($this->message)?'Unknown Error':$this->message;
      }else{
        $this->message = $message;
      }
      return $this;
    }
    public function setCode($code = null){
      if (empty($code)) {
        $this->code = empty($this->code)?0:$this->code;
      }else{
        $this->code = $code;
      }
      return $this;
    }
    public function setResponseData($responseData = null){
      empty($responseData)||$this->responseData = $responseData;
      return $this;
    }
    public function getResponseData(){
      return empty($this->responseData)?array():$this->responseData;
    }

  }
