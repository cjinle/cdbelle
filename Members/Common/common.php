<?php
/**
 * common.php 
 * common functions library
 * @author Lok Sun Sep 16 00:51:29 CST 2012
 */

/**
 * 验证输入的邮件地址是否合法
 * @param   string      $email 
 * @return boolean
 */
function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false) {
        if (preg_match($chars, $user_email)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * get user real ip address
 * @access  public
 * @return  string
 */
function real_ip() {
    static $realip = NULL;
    if ($realip !== NULL) {
        return $realip;
    }
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    return $realip;
}

/**
 * 截取UTF-8编码下字符串的函数
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 * @return  string
 */
function sub_str($str, $length = 0, $append = true) {
    $str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength) {
        return $str;
    } elseif ($length < 0) {
        $length = $strlength + $length;
        if ($length < 0) {
            $length = $strlength;
        }
    }
    if (function_exists('mb_substr')) {
        $newstr = mb_substr($str, 0, $length, 'utf-8');
    } elseif (function_exists('iconv_substr')) {
        $newstr = iconv_substr($str, 0, $length, 'utf-8');
    } else {
        $newstr = substr($str, 0, $length);
    }
    if ($append && $str != $newstr) {
        $newstr .= '...';
    }
    return $newstr;
}

/**
 * get user informations by user_id
 * @param integer $user_id
 * @return array
 */
function get_user_info($user_id, $user_name = '') {
	$User = M('users');
	if ($user_id) {
		$user_info = $User->where("user_id='$user_id'")->find();
	} elseif (!empty($user_name)) {
		$user_info = $User->where("user_name='$user_name'")->find();
	}
	if ($user_info) {
		$user_info['reg_time'] = date('Y-m-d H:i:s', $user_info['reg_time']);
		return $user_info;
	}
}

/**
 * get user list by type
 * @param integer $user_priv
 * @return array
 */
function get_users_by_type($user_priv = 4) {
	if ($user_priv) {
		
	}
}

/**
 * get user name by user_id
 * @param integer $user_id
 * @return string
 */
function get_user_name($user_id) {
	if ($user_id) {
		return M('users')->where(array('user_id'=>$user_id))->getField('user_name');
	}
}

/**
 * privilege check
 * @param string $code
 * @param integer $return_type
 * @return boolean
 */
function user_priv($code = '', $return_type = 1) {
	$action_list = session('action_list');
	if ('all' === $action_list) {
		return true;
	} elseif (strpos(',' . $action_list . ',', ',' . $code . ',') === false) {
		if ($return_type) {
			echo '<div style="border:1px solid #6595D6;margin:100px;padding:50px;text-align:center;line-height:50px;background-color:white;font-size:14px;">';
			echo '权限拒绝！<br />';
			echo '<a href="javascript:history.back(-1)">后退</a>';
			echo '</div>';
			exit;
		}
		return false;
	} else {
		return true;
	}
}

/**
 * get region name
 * @param integer $region_id
 * @return string
 */
function get_region_name($region_id) {
	if ($region_id) {
		return M('region')->where(array('region_id'=>$region_id))->getField('region_name');
	}
}

/**
 * get address list
 * @param integer $parent_id
 * @param integer $region_type
 * @return array
 */
function get_region($parent_id, $region_type) {
	if ($parent_id) {
		return M('region')->where(array('parent_id'=>$parent_id, 'region_type'=>$region_type))->select();
	}
}


/**
 * get user address
 * @param integer $user_id
 * @return array
 */
function get_user_address($user_id) {
	if ($user_id) {
		$user_address = M('user_address')->where(array('user_id'=>$user_id))->find();
		if ($user_address) {
			$user_address['province_id'] = $user_address['province'];
			$user_address['city_id'] = $user_address['city'];
			$user_address['district_id'] = $user_address['district'];
			$user_address['province'] = get_region_name($user_address['province']);
			$user_address['city'] = get_region_name($user_address['city']);
			$user_address['district'] = get_region_name($user_address['district']);
			return $user_address;
		}
	}
}

/**
 * check card number available
 * @param string $card_no
 * @return integer
 */
function check_card_no_is_available($card_no) {
	// 返回： 0：可用  1：已经绑定  2：不存在卡号
	$card_no = addslashes(trim($card_no));
	$card_info = M('card')->field('card_id,card_no,user_id')->where(array('card_no'=>$card_no))->find();
	if ($card_info['card_id']) {
		$result = $card_info['user_id'] ? 1 : 0;
	} else {
		$result = 2;
	}
	return strval($result);
}

/**
 * get card number which is banded.
 * @param integer $user_id
 * @return string
 */
function get_card_no($user_id) {
	if ($user_id) {
		return M('card')->where(array('user_id'=>$user_id))->getField('card_no');
	}
}

/**
 * get next agent user name
 * @param integer $region_id
 * @param integer $user_priv
 * @return string
 */
function get_next_agents_username($region_id, $user_priv) {
	if ($region_id && $user_priv) {
		$where['region_id&user_priv'] = array($region_id, $user_priv, '_multi'=>true);
		$last_num = M('last_username')->where($where)->getField('last_num');
		$prefix = M('region')->where(array('region_id'=>$region_id))->getField('region_pinyin');
		if (is_null($last_num)) {
			$last_num = $user_priv == C('AGENTS') ? $prefix . '001' : '';
		} else {
			$last_num = $prefix . sprintf("%03d", intval($last_num) + 1);
		}
		return $last_num;
	}
}

/**
 * get next (franchise/member) user name
 * @param integer $parent_id
 * @param integer $user_priv
 * @return string
 */
function get_next_username($parent_id, $user_priv) {
	if ($parent_id && $user_priv) {
		$where['user_id&user_priv'] = array($parent_id, $user_priv, '_multi'=>true);
		$last_name = M('last_username')->where($where)->field('user_name,last_num')->find();
		return $last_name ? $last_name['user_name'] . sprintf("%03d", intval($last_name['last_num']) + 1) : null;
	}
}

/**
 * update the last user name
 * @param integer $user_id 
 * @param string $user_name 
 * @param integer $region_id
 * @param integer $user_priv 
 * @return void
 */
function update_last_username($user_id, $user_name, $region_id, $user_priv) {
	$LastUsername = M('last_username');
	$lu_id = $LastUsername->where(array('region_id'=>$region_id, 'user_priv'=>$user_priv))->getField('lu_id');
	$data['user_id'] = $user_id;
	$data['user_name'] = $user_name;
	if ($lu_id) { // if record is exist, let $last_num add 1
		$last_num = $LastUsername->where(array('lu_id'=>$lu_id))->getField('last_num');
		$data['last_num'] = ++$last_num;
		$LastUsername->where(array('lu_id'=>$lu_id))->save($data);
	} else { // if record is not exist, add new record.
		$data['last_num'] = 1;
		$data['region_id'] = $region_id;
		$data['user_priv'] = $user_priv;
		$LastUsername->add($data);
	}
}
































