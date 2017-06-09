<?php
 namespace DdvPhp\DdvException;
/**
*
*/
final class Handler
{
  private static $onHandler = null;
  private static $handlerDir =  null;
  //app请求标识
  public static $isDevelopment = false ;
  private static $isSetErrorHandlerInit = false ;
  private static $isSetExceptionHandlerInit = false ;
  public function __construct()
  {
    throw new NotNewClassError("This Handler class does not support instantiation");
  }
  public static function setHandler(\Closure $method, $isDevelopment = false){
    self::$handlerDir =  __DIR__.'/../handler/';
    self::$onHandler = $method;
    self::$isDevelopment = &$isDevelopment;
    self::setErrorHandlerInit();
    self::setExceptionHandlerInit();
  }
  public static function setErrorHandlerInit(){
    if (self::$isSetErrorHandlerInit!==false) {
      return;
    }
    self::$isSetErrorHandlerInit = true;
    // 设置用户定义的错误处理函数
    if (function_exists('set_error_handler')) {
      set_error_handler(array(Handler::class,'errorHandler'));
    }
    require_once self::$handlerDir.'error.handler.php';
  }
  public static function setExceptionHandlerInit(){
    if (self::$isSetExceptionHandlerInit!==false) {
      return;
    }
    self::$isSetExceptionHandlerInit = true;
    //设置异常处理
    if (function_exists('set_exception_handler')) {
      set_exception_handler(array(Handler::class,'exceptionHandler'));
    }
    require_once self::$handlerDir.'exception.handler.php';
  }
  public static function emitHandler($r, $e){
    $onHandler=self::$onHandler;
    $r = is_array($r)?$r:array();
    if (intval($r['statusCode'])<100) {
      $r['statusCode'] = 500;
    }
    $r['message'] = empty($r['message'])?'':$r['message'];
    $onHandler($r, $e);
  }
  public static function exceptionHandler($e){
    //默认错误行数
    $errline = 0 ;
    if (method_exists($e,'getLine')) {
      $errline =$e->getLine();
    }
    $r = array();
    if (method_exists($e,'getCode')) {
      $r['statusCode'] = $e->getCode();
    }else{
      $r['statusCode'] = 500;
    }
    if (method_exists($e,'getErrorId')) {
      $r['errorId'] =$e->getErrorId();
    }else{
      $r['errorId'] ='UNKNOWN_ERROR';
    }
    if (method_exists($e,'getMessage')) {
      $r['message'] = $e->getMessage();
    }else{
      $r['message'] ='UNKNOWN_ERROR';
      $r['errorId'] =empty($r['errorId'])?'Unknown Error':$r['errorId'];
    }
    if (method_exists($e,'getResponseData')) {
      $r['responseData'] =$e->getResponseData();
    }else{
      $r['responseData'] = array();
    }
    if (method_exists($e,'getResponseData')) {
      $r = array_merge($e->getResponseData(), $r);
    }
    //调试模式
    if (self::$isDevelopment) {
      $r['debug'] = array();
      $r['debug']['type'] = get_class($e);
      $r['debug']['line'] = $errline;
      $r['debug']['file'] = $e->getFile();
      $r['debug']['trace'] = $e->getTraceAsString();
      $r['debug']['trace'] = explode("\n", $r['debug']['trace']);
      $r['debug']['isError'] = false;
      $r['debug']['isIgnoreError'] = false;
    }
    self::emitHandler($r, $e);
  }
  // 用户定义的错误处理函数
  public static function errorHandler($errorCode, $message, $errfile, $errline, $errcontext){
    $isError = (((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $errorCode) === $errorCode);
    $r = array();
    $r['errorCode'] =$errorCode;
    $r['statusCode'] =500;
    $r['errorId'] ='UNKNOWN_ERROR';
    $r['message'] = $message;
    $r['isIgnoreError'] = (($errorCode & error_reporting()) !== $errorCode);
    $r['responseData'] = array();
    $e = new \Exception($message, $errorCode);
    //调试模式
    if (self::$isDevelopment) {
      $r['debug'] = array();
      $r['debug']['type'] = 'Error';
      $r['debug']['line'] = $errline;
      $r['debug']['file'] = $errfile;
      $r['debug']['trace'] = '';
      $r['debug']['isError'] = $isError;
      $r['debug']['isIgnoreError'] = $r['isIgnoreError'];
      $r['debug']['trace'] = $e->getTraceAsString();
      $r['debug']['trace'] = explode("\n", $r['debug']['trace']);
      if (count($r['debug']['trace'])>0) {
        $r['debug']['trace'] = array_splice($r['debug']['trace'],2);
      }
    }
    self::emitHandler($r, $e);
  }
}
