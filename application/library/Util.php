<?php 

class Util
{
	/**
	 * 检测密码
	 * @param  string $pass       [description]
	 * @param  string $salt       [description]
	 * @param  string $input_pass [description]
	 * @return [type]             [description]
	 */
	public static function checkPassword (string $pass,string $salt, string $input_pass)
	{
		return password_verify($input_pass.$salt,$pass);
	}

	/**
	 * 加密密码
	 * @param  string $pass [description]
	 * @param  string $salt [description]
	 * @return [type]       [description]
	 */
	public static function encodePassword(string $pass , string $salt)
	{
		return password_hash($pass.$salt, PASSWORD_DEFAULT);
	}

	/**
	 * 生成随机字符串
	 *
	 * @param  integer $len    [description]
	 * @param  string  $format [description]
	 * @return [type]          [description]
	 */
	public static function randStr($len = 6, $format = "ALL")
    {
        $is_abc = $is_numer = 0;
        $password = $tmp = '';
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars = '0123456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        mt_srand(intval(explode(' ',microtime())[0] * 1000000 * getmypid()));
        $password = "";
        while (strlen($password) < $len)
            $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $password;
    }

    /**
     * 获取客户端的IP地址
     *
     * @return string
     */
    public static function getIp()
    {
        $keys = array('X_FORWARDED_FOR', 'HTTP_X_FORWARDED_FOR', 'CLIENT_IP', 'REMOTE_ADDR');

        foreach($keys as $key)
        {
            if(isset($_SERVER[$key]))
            {
                return $_SERVER[$key];
            }
        }

        return '';
    }


        /**
	 * 用户ID加密
	 *
	 * @param  [type] $user_id  [description]
	 * @return [type]           [description]
	 */
    public static function encodeUID($user_id)
    {
    	$user_id = intval($user_id) + 223156789;
        $user_id = str_replace([1,2,5,6,8,9],['A','B','C','D','E','F'],$user_id);
        return $user_id;
    }
}