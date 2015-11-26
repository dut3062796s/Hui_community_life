<?php
/**
 * @name IndexController
 * @author desktop-uabd7nl\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class HomeController extends AdminBase {
	public function init(){
//		if(!$this->_session->has('username')){
//			$this->redirect('/index/');
//		}
	}
	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/Sample/home/home/home/name/desktop-uabd7nl\administrator 的时候, 你就会发现不同
     */
	public function indexAction($name = "Stranger") {
		$this->getView()->assign("title", "H+ 后台主题UI框架 - 主页");
		$this->getView()->assign("keywords", "H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台");
		$this->getView()->assign("description", "H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术");
		//1. fetch query
		//$get = $this->getRequest()->getQuery("get", "default value");

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

	/**
	 * 登录页面
	 * @param string $name
	 * @return bool
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
