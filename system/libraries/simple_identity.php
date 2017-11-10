<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/19
 * Time: 9:31
 */
class identity_db{
    /*
     * 身份证区域数据
     * */
    public $area_array;
    /**
     * 男
     */
    const SEX_MALE = 1;
    /**
     * 女
     */
    const SEX_FEMALE = 0;
    /**
     * @var null 身份证
     */
    public $idCard   = null;
    /**
     * @var null 省份
     */
    public $province = null;
    /**
     * @var null 城市
     */
    public $city  = null;
    /**
     * @var null 地区
     */
    public $area = null;
    /**
     * @var null 生日
     */
    public $birthday = null;

    /**
     * @var null 性别
     */
    public $sex = null;

    /**
     * @var null 年龄
     */
    public $age = null;

    /**
     * @var int 长度
     */
    public $len  = 0;

    /**
     * @var string 顺序码
     */
    protected $sequenceCode = '';
    /**
     * @var string 校验码
     */
    protected $verifyCode   = '';

    public function __construct($identity_id)
    {
        $this->idCard = $identity_id;
        if($this->validate())
        {
            $this->parse();
        }else{
            return false;
        }

    }

    protected function getData()
    {
        $this->area_array = json_decode(file_get_contents(__DIR__ . '/area-code.json'), true);
    }

    protected function parse(){
        $this->getData();
        $this->setProvince();
        $this->setCity();
        $this->setArea();
        $this->setBirthday();
        $this->setSex();
        $this->setLen();
        $this->setType();
        $this->setSequenceCode();
        $this->setVerifyCode();
        $this->setAge();
    }

    /**
     * 获取身份信息
     *
     * @param $provinceCode 省份代码
     * @return array|null 成功数组,失败null
     */
    public function getProvince($provinceCode = false)
    {;
        if(!$provinceCode)
        {
            $provinceCode = $this->province;
        }
        return !empty($this->area_array[$provinceCode]) ? $this->area_array[$provinceCode] : null;
    }

    /**
     * 获取城市信息
     *
     * @param string $provinceCode 省份代码
     * @param string $cityCode 城市代码
     * @return array|null 成功数组,失败null
     */
    public function getCity($provinceCode = false, $cityCode = false)
    {
        if(!$provinceCode)
        {
            $provinceCode = $this->province;
        }
        if(!$cityCode)
        {
            $cityCode = $this->city;
        }

        return !empty($this->getProvince($provinceCode)['items'][$cityCode]) ? $this->getProvince($provinceCode)['items'][$cityCode] : null;
    }

    /**
     * 获取地区信息
     *
     * @param string $provinceCode 省份代码
     * @param string $cityCode 城市代码
     * @param string $areaCode 地区代码
     * @return array|null 成功数组,失败null
     */
    public function getArea($provinceCode = false, $cityCode = false, $areaCode = false)
    {
        if(!$provinceCode)
        {
            $provinceCode = $this->province;
        }
        if(!$cityCode)
        {
            $cityCode = $this->city;
        }
        if(!$areaCode)
        {
            $areaCode = $this->area;
        }

        return !empty($this->getCity($provinceCode, $cityCode)['items'][$areaCode]) ? $this->getCity($provinceCode, $cityCode)['items'][$areaCode] : null;
    }


    protected function validate()
    {
        if(strlen($this->idCard) !== 18)
        {
            return false;
        }
        $number = strtoupper($this->idCard);
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $sigma = 0;
        for($i = 0;$i < 17;$i++){

            $b = (int) $number{$i};
            $w = $wi[$i];
            $sigma += $b * $w;
        }
        $snumber = $sigma % 11;
        $check_number = $ai[$snumber];
        if($number{17} == $check_number){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 设置省份
     */
    protected function setProvince()
    {
        $this->province = substr($this->idCard, 0, 2);
    }


    /**
     * 设置城市
     */
    protected function setCity()
    {
        $this->city = substr($this->idCard, 2, 2);
    }

    /**
     * 设置地区
     */
    protected function setArea()
    {
        $this->area = substr($this->idCard, 4, 2);
    }

    /**
     * 设置身份证长度
     */
    protected function setLen()
    {
        $this->len = strlen($this->idCard);
    }

    protected function setBirthday()
    {
        $birthday[] = substr($this->idCard, 6, 4);
        $birthday[] = substr($this->idCard, 10, 2);
        $birthday[] = substr($this->idCard, 12, 2);
        $this->birthday = implode("-", $birthday);
    }

    protected function setSex()
    {
        $sex = substr($this->idCard, 16, 1);
        $this->sex = $sex%2 == 1 ? self::SEX_MALE : self::SEX_FEMALE;
    }

    protected function setType()
    {
        $this->type = 18;
    }

    protected function setSequenceCode()
    {
        $this->sequenceCode = substr($this->idCard, 14, 4);
    }

    protected function setVerifyCode()
    {
        $this->verifyCode = "";
    }

    protected function setAge()
    {
        $date = strtotime($this->birthday);
        $today = strtotime('today');
        $diff = floor(($today-$date)/86400/365);
        $this->age = intval(strtotime($this->birthday.' +'.$diff.'years')>$today?($diff+1):$diff);
    }


}