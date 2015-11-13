<?php
/**
 * @name IndexController
 * @author desktop-uabd7nl\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
use Test\Test1Model;
class HomeController extends Yaf_Controller_Abstract {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/Sample/home/home/home/name/desktop-uabd7nl\administrator 的时候, 你就会发现不同
     */
	public function loginAction($name = "Stranger") {
		//1. fetch query
		$get = $this->getRequest()->getQuery("get", "default value");

		//2. fetch model
		$model = new SampleModel();

		//3. assign
		$this->getView()->assign("content", "Admin登录");
		$this->getView()->assign("name", "AdminName登录");
		//在控制器里手动调用的方式有2种
		//$this->display('hello');
		//$this->getView()->display('test/world.phtml');
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return TRUE;
	}
}
