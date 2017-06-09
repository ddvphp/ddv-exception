<?php

namespace DdvPhp\DdvException;

class NotNewClassError extends \DdvPhp\DdvException\Error
{
  // 魔术方法
  public function __construct( $message = 'This class does not support instantiation', $errorId = 'NotNewClassError' , $code = '500' )
  {
    parent::__construct( $message , $errorId , $code );
  }
}