<?php
/**
 * @name IndexController
 * @author desktop-uabd7nl\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class HomeController extends Yaf_Controller_Abstract {
	public function init(){
		$this->_config = Yaf_Registry::get('config');
		$this->_req = $this->getRequest();
		$this->_session = Yaf_Session::getInstance();
		$this->_session->start();
		$this->_base = new base();
		$this->_model =new Test1Model();
//		if(!$this->_session->has('username')){
//			$this->redirect('/index/');
//		}
	}
	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/Sample/home/home/home/name/desktop-uabd7nl\administrator 的时候, 你就会发现不同
     */
	public function indexAction() {
		//1. fetch query
		//$get = $this->getRequest()->getQuery("get", "default value");

		//2. fetch model
		//$model = new SampleModel();

		//3. assign
		//$this->getView()->assign("content", "Admin登录");
		//$this->getView()->assign("name", "AdminName登录");
		//在控制器里手动调用的方式有2种
		//$this->display('hello');
		//$this->getView()->display('test/world.phtml');

		//获取信息
		//$this->getView()->assign("content", $this->_model->GetAll());
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return TRUE;
	}
	public function index2Action($pageindex=1) {
		//1. fetch query
		//$get = $this->getRequest()->getQuery("get", "default value");

		//2. fetch model
		//$model = new SampleModel();

		//3. assign
		//$this->getView()->assign("content", "Admin登录");
		//$this->getView()->assign("name", "AdminName登录");
		//在控制器里手动调用的方式有2种
		//$this->display('hello');
		//$this->getView()->display('test/world.phtml');

		//获取信息
		//$this->getView()->assign("content", $this->_model->GetAll());
		$pagesize=15;
		$this->getView()->assign("content", $this->_model->PageList(null,$pagesize,($pageindex-1)*$pagesize,null));
		$this->getView()->assign("Count", $this->_model->Count());
		$this->getView()->assign("pageindex", $pageindex);
		$this->getView()->assign("pagesize", $pagesize);
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
		return TRUE;
	}
	/**
	 *添加修改页面
	 * @param $id
	 * @return bool
	 */
	public function editAction($id) {
		if (empty($id)) {
			$this->getView()->assign("Test", "");
		}else{
			$this->getView()->assign("Test", $this->_model->GetByID($id));
		}

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
		return TRUE;
	}

	/**ge't
	 * 添加修改操作
	 * @param $id 为null的时候添加，否则修改
	 * @return bool
	 */
	public function editdoAction($id) {
		if ($this->_req->isXmlHttpRequest()) {
			$Posts = $this->_req->getPost();
			if (empty($id)) {
				if($Posts['testcol3']>1)
				{
					$batch=false;
					for($i=0;$i<$Posts['testcol3'];$i++)
					{
						$testcol1=$Posts['testcol1'].$i;
						$testcol2=$Posts['testcol2'].$i;
						$params =  array('testcol1'=>$testcol1,'testcol2'=>$testcol2);
						$batch = $this->_model->Create($params);
					}
					if($batch)
					{
						$this->_base->show_json(array('errno'=>0,'errmsg'=>'添加成功!'));
					}else{
						$this->_base->show_json(array('errno'=>1,'errmsg'=>'添加失败!'));
					}
				}else{
					$params =  array('testcol1'=>$Posts['testcol1'],'testcol2'=>$Posts['testcol2']);
					if($this->_model->Create($params))
					{
						$this->_base->show_json(array('errno'=>0,'errmsg'=>'添加成功!'));
					}else
					{
						$this->_base->show_json(array('errno'=>1,'errmsg'=>'添加失败!'));
					}
				}
			}else
			{
				$params =  array('testcol1'=>$Posts['testcol1'],'testcol2'=>$Posts['testcol2']);
				//$where=array("test_id"=>$id,"testcol1"=>"aaaa");//多条件
				$where=array("test_id"=>$id);//多条件
				if($this->_model->Update($params,$where))
				{
					$this->_base->show_json(array('errno'=>0,'errmsg'=>'修改成功!'));
				}else
				{
					$this->_base->show_json(array('errno'=>1,'errmsg'=>'修改失败!'));
				}
			}
		}
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
		//return false;
	}

	/**
	 *删除
	 * @param $id
	 */
	public function deleteAction()
	{
		if ($this->_req->isXmlHttpRequest()) {
			$Posts = $this->_req->getPost();
			if (!empty($Posts["id"])) {
				if($this->_model->Delete(array("test_id"=>$Posts["id"])))
				{
					$this->_base->show_json(array('errno'=>0,'errmsg'=>'删除成功!'));
				}else
				{
					$this->_base->show_json(array('errno'=>1,'errmsg'=>'删除失败!'));
				}
			}
		}
	}
}
