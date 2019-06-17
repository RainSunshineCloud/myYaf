<?php
use RainSunshineCloud\JWTException;
use RainSunshineCloud\JWT;

class Response extends JWT
{
    /**
     * 响应类型
     *
     * @var string
     */
    protected $type = 'json';
    protected $token = [
        'public'  => [],
        'private' => [],
    ];

    protected $returnToken = True;
    protected $firstLogin = False;

    protected static $self;
    /**
     * 响应数据
     *
     * @var array
     */
    protected $data;

    /**
     * 字符集
     *
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * 输出的响应头
     *
     * @var array
     */
    protected $header = [];

    public static function instance()
    {
        if (self::$self) {
            return self::$self;
        }

        self::$self = new self();
        return self::$self;
    }

    /**
     * 成功
     * @param  string      $message [description]
     * @param  int|integer $code    [description]
     * @param  string      $data    [description]
     * @return [type]               [description]
     */
    public static function error(string $message,int $code = 500, $data = '') 
    {
         self::instance()->data($data,$message,$code)->send();
    }

    /**
     * 失败
     * @param  string      $data    [description]
     * @param  int|integer $code    [description]
     * @param  string      $message [description]
     * @return [type]               [description]
     */
    public static function success($data = '',int $code = 200,string $message = '')
    {
        self::instance()->data($data,$message,$code)->send();
    }

    /**
     * 构造方法
     *
     * @param  string  $data 发送的数据
     * @param  string  $type 数据类型
     * @param  integer $code 状态码
     */
    protected function __construct(){}

    /**
     * 设置响应头
     *
     * @param  [type] $name 响应类型
     * @param  [type] $val  值
     * @return [type]       [description]
     */
    public static function header($name, $val = null)
    {
        if(is_array($name)){
            self::instance()->header = array_merge($this->header, $name);
        }
        else{
            self::instance()->header[$name] = $val;
        }

        return $this;
    }

    /**
     * 设置token
     * @param  array  $public [description]
     * @param  string $type   [description]
     * @return [type]         [description]
     */
    public function token (array $public)
    {
        $this->token['public'] += $public;
        return $this;
    }

    /**
     * 设置私人token
     * @param  array  $private [description]
     * @return [type]          [description]
     */
    public function priToken (array $private)
    {
        $this->token['private'] += $public;
        return $this;
    }

    /**
     * 设置输出的数据
     *
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function data($data,string $msg = '',int $code = 200)
    {
        $data = ['data' => $data,'msg' => $msg ,'code' => $code];
        $this->data = $data;
        return $this;
    }

    /**
     * 设置响应数据类型
     *
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function type($type)
    {
        $this->type = strtolower($type);

        return $this;
    }

    /**
     * 发送数据
     *
     * @return [type] [description]
     */
    public function send()
    {
        // 获取数据
        $data = $this->getContent();
        if ($this->returnToken && ($this->token['public'] || $this->token['private'])) {
            $token_config = \Yaf\Application::app()->getConfig()->token;
            $this->expire($token_config->expire);
            $this->refresh($token_config->refresh);
            $token = $this->encode($this->token['public'],$this->token['private'],$this->firstLogin);
            $this->header['token'] = $token;
        }

        foreach($this->header as $name => $val) {
            is_null($val) ? header($name) : header($name . ':' . $val);
        }
    
        echo $data;
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        Log::record('响应数据',['data' =>$data]);
        exit();
    }

    /**
     * 格式化获取输出数据
     *
     * @return [type] [description]
     */
    protected function getContent()
    {
        switch ($this->type)
        {
            case 'json':
                $content = $this->toJson();
                break;
            case 'html':
                $content = $this->toHTML();
                break;
        }

        return $content;
    }

    /**
     * 将数据转换为HTML数据
     * @return [type] [description]
     */
    protected function toHTML()
    {
        return $this->data;
    }

    /**
     * 数据转换为json
     * @return [type] [description]
     */
    protected function toJson()
    {
        $data = json_encode($this->data, JSON_UNESCAPED_UNICODE);

        // 转换失败，抛出错误信息
        if($data === false){
            return '{"data":"","msg":"json加密失败","code":404}';
        }

        return $data;
    }

    public function firstLogin($first)
    {
        $this->firstLogin = $first;
        return $this;
    }

    public function returnToken(bool $return)
    {
        $this->returnToken = $return;
        return $this;
    }
}
