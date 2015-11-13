<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author desktop-uabd7nl\administrator
 */
class ErrorController extends Yaf_Controller_Abstract {
	private $_config;
	public function init(){
		$this->_config = Yaf_Application::app()->getConfig();
	}
	//从2.1开始, errorAction支持直接通过参数获取异常
	public function errorAction($exception) {
		//1. assign to view engine
		$this->getView()->assign("exception", $exception);
		//5. render by Yaf

		Yaf_Dispatcher::getInstance()->autoRender(false);
		if ($this->_config->application->showErrors) {
			switch ($exception->getCode()) {
				case YAF_ERR_AUTOLOAD_FAILED:
				case YAF_ERR_NOTFOUND_MODULE:
				case YAF_ERR_NOTFOUND_CONTROLLER:
				case YAF_ERR_NOTFOUND_ACTION:
				case YAF_ERR_NOTFOUND_VIEW:
					if (strpos($this->getRequest()->getRequestUri(), '.css') !== false ||
						strpos($this->getRequest()->getRequestUri(), '.jpg') !== false ||
						strpos($this->getRequest()->getRequestUri(), '.js') !== false ||
						strpos($this->getRequest()->getRequestUri(), '.png') !== false ||
						strpos($this->getRequest()->getRequestUri(), '.ico') !== false ||
						strpos($this->getRequest()->getRequestUri(), '.gif') !== false
					) {
						header('HTTP/1.1 404 Not Found');
					}
				default:
					//记录错误日志
					Log::error('error',$exception->getMessage() . ' IN FILE ' . $exception->getFile() . ' ON LINE ' . $exception->getLine());
					//显示错误信息
					$this->_view->exception =  $exception;
					echo $this->getView()->render(APPLICATION_PATH."/application".'/views/error/error.phtml');
			}
		} else {
			//禁止输出视图内容
			Yaf_Dispatcher::getInstance()->enableView();
			switch ($exception->getCode()) {
				case YAF_ERR_AUTOLOAD_FAILED:
				case YAF_ERR_NOTFOUND_MODULE:
				case YAF_ERR_NOTFOUND_CONTROLLER:
				case YAF_ERR_NOTFOUND_ACTION:
				case YAF_ERR_NOTFOUND_VIEW:
					header('HTTP/1.1 404 Not Found');
					//记录日志
					Log::error('error',$exception->getMessage() . ' IN FILE ' . $exception->getFile());
					$this->_view->type='err404';
					$this->_view->display(APPLICATION_PATH."/application".'/views/error/error.phtml');
					break;
				default:
					header('HTTP/1.1 500 Internal Server Error');
					//记录文件错误日志
					Log::error('error',$exception->getMessage() . ' IN FILE ' . $exception->getFile() . ' ON LINE ' . $exception->getLine());
					//记录sentry错误日志
					$this->_view->type='error';
					$this->_view->display(APPLICATION_PATH."/application".'/views/error/error.phtml');
					break;

			}
		}
	}
}
