<?php
class admin extends spController{
	function __construct(){
		parent::__construct();
		$this->mailbody = file_get_contents('http://www.yimiaofengqiang.com/?c=mkhtml');
		$this->emailPDO = spClass("m_dingyue");
	}
	function index(){
		echo count($this->emailPDO->findAll());
	}
	public function sendemail($smtpemailto,$mailsubject,$mailbody){
		set_time_limit(0);
		import("smtp.php");
		$smtpserverport = 25;//SMTP服务器端口
		
		$smtpserver = "smtp.163.com";//SMTP服务器
		$smtpusermail = "yimiaofengqiang@163.com";//SMTP服务器的用户邮箱
		
		$smtpuser = "yimiaofengqiang@163.com";//SMTP服务器的用户帐号
		$smtppass = "z123456";//SMTP服务器的用户密码
		
		$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
		$smtpemailto = $smtpemailto?$smtpemailto:"460063564@qq.com";//发送给谁
		$mailsubject = $mailsubject?$mailsubject:"一秒疯抢根据您的偏好【精选多款单品】任您选！";//邮件主题
		$mailbody = $mailbody?$mailbody:$this->mailbody;//邮件内容
		
//		##########################################
		$smtp = spClass("smtp");
		$smtp->smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
		$smtp->debug = FALSE;//是否显示发送的调试信息
		
//		$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);	
		for($i=0;$i<30;$i++){
			$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);	
			usleep(2000000);
		}
		
	}
	public function getemail(){
		$datalist = list_dir('./tmp/email/');
		$data = array();
		foreach($datalist as $v){
			$file[] = file($v);
		}
		foreach($file as $iv){
			foreach($iv as &$line){
				$data[] = trim($line);
			}
		}
		return $data;
	}
	public function importDataTOSql(){
		$emails = $this->getemail();
		foreach($emails as $v){
			$info = array(
				'email'=>trim($v),
				'isfeed'=>1
			);
			$isinThere = $this->emailPDO->find(array('email'=>trim($v)));
			if(!$isinThere){
				if($this->emailPDO->create($info))
					echo trim($v).'添加成功！';
				else
					echo trim($v).'添加失败！';
			}else{
				echo trim($v).'已经存在！';
			}
			
		}
		
	}
}
?>