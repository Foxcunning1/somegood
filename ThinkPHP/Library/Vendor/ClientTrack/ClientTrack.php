<?php

/**客户端相关信息追踪
 * Created by PhpStorm.
 * User: John
 * Date: 2017/9/18
 * Time: 10:14
 */
class ClientTrack
{
	private $agent = '';//浏览器相关信息
	private $ip = '';//客户端IP

	public function __construct($agent){
		$this->agent = $agent;
	}

	/**获得浏览器名称和版本
	 * Return      String       返回字符串结果
	 * */
	public function getUserBrowser(){
		if (empty($this->agent)) {
			return '';
		}
		$browser     = '';
		$browser_ver = '';
		if (preg_match('/MSIE\s([^\s|;]+)/i', $this->agent, $regs)) {
			$browser     = 'Internet Explorer';
			$browser_ver = $regs[1];
		} elseif (preg_match('/FireFox\/([^\s]+)/i', $this->agent, $regs)) {
			$browser     = 'FireFox';
			$browser_ver = $regs[1];
		} elseif (preg_match('/Maxthon/i', $this->agent, $regs)) {
			$browser     = '(Internet Explorer ' .$browser_ver. ') Maxthon';
			$browser_ver = '';
		} elseif (preg_match('/Opera[\s|\/]([^\s]+)/i', $this->agent, $regs)) {
			$browser     = 'Opera';
			$browser_ver = $regs[1];
		} elseif (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $this->agent, $regs)) {
			$browser     = 'OmniWeb';
			$browser_ver = $regs[2];
		} elseif (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $this->agent, $regs)) {
			$browser     = 'Netscape';
			$browser_ver = $regs[2];
		} elseif (preg_match('/safari\/([^\s]+)/i', $this->agent, $regs)) {
			$browser     = 'Safari';
			$browser_ver = $regs[1];
		} elseif (preg_match('/NetCaptor\s([^\s|;]+)/i', $this->agent, $regs)) {
			$browser     = '(Internet Explorer ' .$browser_ver. ') NetCaptor';
			$browser_ver = $regs[1];
		} elseif (preg_match('/Lynx\/([^\s]+)/i', $this->agent, $regs)) {
			$browser     = 'Lynx';
			$browser_ver = $regs[1];
		}
		if (!empty($browser)) {
			return addslashes($browser . ' ' . $browser_ver);
		} else {
			return 'Unknow browser';
		}
	}

	/**
	 * 判断是否为搜索引擎蜘蛛
	 *
	 * @access  public
	 * @return  string
	 */
	public function isSpider(){
		static $spider = NULL;
		if ($spider !== NULL) {
			return $spider;
		}
		if (empty($this->agent)) {
			$spider = '';
			return '';
		}
		$searchengine_bot = array(
			'googlebot',
			'mediapartners-google',
			'baiduspider+',
			'msnbot',
			'yodaobot',
			'yahoo! slurp;',
			'yahoo! slurp china;',
			'iaskspider',
			'sogou web spider',
			'sogou push spider'
		);
		$searchengine_name = array(
			'GOOGLE',
			'GOOGLE ADSENSE',
			'BAIDU',
			'MSN',
			'YODAO',
			'YAHOO',
			'Yahoo China',
			'IASK',
			'SOGOU',
			'SOGOU'
		);
		$spider = strtolower($this->agent);
		foreach ($searchengine_bot AS $key => $value) {
			if (strpos($spider, $value) !== false) {
				$spider = $searchengine_name[$key];
				return $spider;
			}
		}
		$spider = '';
		return '';
	}

	/**
	 * 获得客户端的操作系统
	 *
	 * @access  Public
	 * @return  String            返回客户端的操作系统
	 */
	public function getOs(){
		if (empty($this->agent)) {
			return 'Unknown';
		}
		$os    = '';
		if (strpos($this->agent, 'win') !== false) {
			if (strpos($this->agent, 'nt 5.1') !== false) {
				$os = 'Windows XP';
			} elseif (strpos($this->agent, 'nt 5.2') !== false) {
				$os = 'Windows 2003';
			} elseif (strpos($this->agent, 'nt 5.0') !== false) {
				$os = 'Windows 2000';
			} elseif (strpos($this->agent, 'nt 6.0') !== false) {
				$os = 'Windows Vista';
			} elseif (strpos($this->agent, 'nt') !== false) {
				$os = 'Windows NT';
			} elseif (strpos($this->agent, 'win 9x') !== false && strpos($this->agent, '4.90') !== false) {
				$os = 'Windows ME';
			} elseif (strpos($this->agent, '98') !== false) {
				$os = 'Windows 98';
			} elseif (strpos($this->agent, '95') !== false) {
				$os = 'Windows 95';
			} elseif (strpos($this->agent, '32') !== false) {
				$os = 'Windows 32';
			} elseif (strpos($this->agent, 'ce') !== false) {
				$os = 'Windows CE';
			}
		} elseif (strpos($this->agent, 'linux') !== false) {
			$os = 'Linux';
		} elseif (strpos($this->agent, 'unix') !== false) {
			$os = 'Unix';
		} elseif (strpos($this->agent, 'sun') !== false && strpos($this->agent, 'os') !== false) {
			$os = 'SunOS';
		} elseif (strpos($this->agent, 'ibm') !== false && strpos($this->agent, 'os') !== false) {
			$os = 'IBM OS/2';
		} elseif (strpos($this->agent, 'mac') !== false && strpos($this->agent, 'pc') !== false) {
			$os = 'Macintosh';
		} elseif (strpos($this->agent, 'powerpc') !== false) {
			$os = 'PowerPC';
		} elseif (strpos($this->agent, 'aix') !== false) {
			$os = 'AIX';
		} elseif (strpos($this->agent, 'hpux') !== false) {
			$os = 'HPUX';
		} elseif (strpos($this->agent, 'netbsd') !== false) {
			$os = 'NetBSD';
		} elseif (strpos($this->agent, 'bsd') !== false) {
			$os = 'BSD';
		} elseif (strpos($this->agent, 'osf1') !== false) {
			$os = 'OSF1';
		} elseif (strpos($this->agent, 'irix') !== false) {
			$os = 'IRIX';
		} elseif (strpos($this->agent, 'freebsd') !== false) {
			$os = 'FreeBSD';
		} elseif (strpos($this->agent, 'teleport') !== false) {
			$os = 'teleport';
		} elseif (strpos($this->agent, 'flashget') !== false) {
			$os = 'flashget';
		} elseif (strpos($this->agent, 'webzip') !== false) {
			$os = 'webzip';
		} elseif (strpos($this->agent, 'offline') !== false) {
			$os = 'offline';
		}elseif (strpos($this->agent, 'Android') !== false) {
			preg_match("/(?<=Android )[\d\.]{1,}/", $this->agent, $version);
			$os = 'Android'.str_replace('_', '.', $version[0]);
		} elseif (strpos($this->agent, 'iPhone') !== false) {
			preg_match("/(?<=CPU iPhone OS )[\d\_]{1,}/", $this->agent, $version);
			$os = 'IOS'.str_replace('_', '.', $version[0]);
		} elseif (strpos($this->agent, 'iPad') !== false) {
			preg_match("/(?<=CPU OS )[\d\_]{1,}/", $this->agent, $version);
			$os = 'iPad'.str_replace('_', '.', $version[0]);
		} else {
			$os = 'Unknown';
		}
		return $os;
	}
}