<?php

class System_setting {

    CONST USER_COUPON_STATUS_PENDING_USED = 1; #优惠券待使用
    CONST USER_COUPON_STATUS_USED = 2; #优惠券已使用
    CONST USER_COUPON_STATUS_EXPIRED = 3; #优惠券已过期
    const USER_DISABLE = 2; #用户被禁用
    const CHARGE_TYPE_BACKEND = 1;
    const CHARGE_TYPE_APP = 2;
    const USER_BALANCE_ADD = 'add';
    const USER_BALANCE_MINUS = "minus";
    const APPOINT_STATUS_PENDING = 1; #订单状态待完成
    const APPOINT_STATUS_CANCLE = 2; #订单状态已取消
    const APPOINT_STATUS_PENDING_EVALUATION = 3; #订单状态已支付待评价
    const APPOINT_STATUS_FINISH = 4; #订单状态已评价 完成
    const ORDER_TYPE_KEY = 1; #订单类型，一键下单
    const ORDER_TYPE_APPOINTMENT = 2; #订单类型，预约
    const PAYMENT_METHOD_WEIXIN = 1; #微信支付
    const PAYMENT_METHOD_ALIPAY = 2; #支付宝
    const PAYMENT_METHOD_BALANCE = 3; #余额
    const CONSUMPTION_TYPE_ADD = 1; #充值 充值
    const CONSUMPTION_TYPE_MINUS = 2; #充值 消费
    const THIRD_LOGIN_QQ = "qq";
    const THIRD_LOGIN_SINA = "sina";
    const THIRD_LOGIN_WEIXIN = "weixin";

//自动为用户随机生成用户名(长度6-13)

    /**
     * 随机为用户生成密码
     * @param type $pw_length
     * @return type
     */
    public static function createPassword($pw_length = 4) {
        $randpwd = '';
        for ($i = 0; $i < $pw_length; $i++) {
            $randpwd .= chr(mt_rand(33, 126));
        }
        return $randpwd;
    }

    /**
     * 随机为用户生成用户名
     * @param type $length
     * @return string
     */
    public static function generateUsername($length = 10) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $username = '';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组$chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $username .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $username;
    }

    /**
     * 随机生成字符串
     * @param type $len
     * @return string
     */
    public static function generateRandStr($len) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 随机生成数字字符串
     * @param type $len
     * @return string
     */
    public static function generateRandNumber($len) {
        $chars = array(
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 生成加密字符串
     * @param type $id
     * @return type
     */
    public static function createToken($id) {
        $time = time();
        $token_obj = new Tc_encryption();
        $random = $token_obj->getRandSkey();
        $psw_token = $token_obj->encode($time . 'token' . $id . '&' . $random);
        $psw_token = $psw_token > 255 ? substr($psw_token, 0, 255) : $psw_token;
        return $psw_token;
    }

    /**
     * 解码加密字符串
     * @param type $token
     * @return type
     */
    public static function decodeToken($token) {
        $token_obj = new Tc_encryption();
        $decode_token = $token_obj->decode($token);
        $pa = '/(.+)token(.+)/';
        preg_match_all($pa, $decode_token, $match);
        $result = array();
        if ($match) {
            $arr_uid_rand = explode('&', $match[2][0]);
            $time = $match[1][0];
            $id = $arr_uid_rand[0];
            $result = array('time' => $time, 'id' => $id);
        }
        return $result;
    }

    /**
     * 手机号码的有效性
     * @param string $mobile
     * @return boolean
     */
    public static function checkMobile($mobile) {
        if (strlen($mobile) != 11 || !preg_match('/^1[3|4|5|7|8][0-9]\d{4,8}$/', $mobile)) {
            return false;
        } else {
            return true;
        }
    }

    //生成24位唯一订单号码，格式：YYYY-MMDD-HHII-SS-NNNN,NNNN-CC，其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
//    public static function createOrderId() {
//        while (true) {
//
//            //订购日期
//
//            $order_date = date('Y-m-d');
//
//            //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
//
//            $order_id_main = date('YmdHis') . rand(10000000, 99999999);
//
//            //订单号码主体长度
//
//            $order_id_len = strlen($order_id_main);
//
//            $order_id_sum = 0;
//
//            for ($i = 0; $i < $order_id_len; $i++) {
//
//                $order_id_sum += (int) (substr($order_id_main, $i, 1));
//            }
//
//            //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
//
//            $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
//
//            return $order_id;
//        }
//    }
    /**
     * 该方法用上了英文字母、年月日、Unix 时间戳和微秒数、随机数，重复的可能性大大降低，还是很不错的。使用字母很有代表性，一个字母对应一个年份，总共16位
     */
    public static function createOrderId() {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }
    /**
     * 
     * @param type $s0
     * @return string|null
     */
    public static function getFirstChar($s0) {
        $firstchar_ord = ord(strtoupper($s0{0}));
        if (($firstchar_ord >= 65 and $firstchar_ord <= 91)or ( $firstchar_ord >= 48 and $firstchar_ord <= 57))
            return $s0{0};
        $s = iconv("UTF-8", "gb2312", $s0);
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 and $asc <= -20284)
            return "A";
        if ($asc >= -20283 and $asc <= -19776)
            return "B";
        if ($asc >= -19775 and $asc <= -19219)
            return "C";
        if ($asc >= -19218 and $asc <= -18711)
            return "D";
        if ($asc >= -18710 and $asc <= -18527)
            return "E";
        if ($asc >= -18526 and $asc <= -18240)
            return "F";
        if ($asc >= -18239 and $asc <= -17923)
            return "G";
        if ($asc >= -17922 and $asc <= -17418)
            return "H";
        if ($asc >= -17417 and $asc <= -16475)
            return "J";
        if ($asc >= -16474 and $asc <= -16213)
            return "K";
        if ($asc >= -16212 and $asc <= -15641)
            return "L";
        if ($asc >= -15640 and $asc <= -15166)
            return "M";
        if ($asc >= -15165 and $asc <= -14923)
            return "N";
        if ($asc >= -14922 and $asc <= -14915)
            return "O";
        if ($asc >= -14914 and $asc <= -14631)
            return "P";
        if ($asc >= -14630 and $asc <= -14150)
            return "Q";
        if ($asc >= -14149 and $asc <= -14091)
            return "R";
        if ($asc >= -14090 and $asc <= -13319)
            return "S";
        if ($asc >= -13318 and $asc <= -12839)
            return "T";
        if ($asc >= -12838 and $asc <= -12557)
            return "W";
        if ($asc >= -12556 and $asc <= -11848)
            return "X";
        if ($asc >= -11847 and $asc <= -11056)
            return "Y";
        if ($asc >= -11055 and $asc <= -10247)
            return "Z";
        return null;
    }

    public static function distance($lat1, $lng1, $lat2, $lng2, $miles = true) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        return ($miles ? ($km * 0.621371192) : $km);
    }

    /**
     * 获取根目录
     * @return type
     */
    public static function getRootDir() {
        return dirname(dirname(dirname(__FILE__)));
    }

    /**
     * 获取上传目录
     * @return type
     */
    public static function getWapUploadDir() {
        return self::getRootDir() . DIRECTORY_SEPARATOR . "public_source" . DIRECTORY_SEPARATOR . "wap" . DIRECTORY_SEPARATOR . "upload";
    }

    /**
     * 支付方法
     * @return type
     */
    public static function getPaymentMethod() {
        return array(
            self::PAYMENT_METHOD_WEIXIN,
            self::PAYMENT_METHOD_ALIPAY,
            self::PAYMENT_METHOD_BALANCE
        );
    }

    /**
     * 第三方登录
     * @return type
     */
    public static function getThirdLoginMethod() {
        return array(
            self::THIRD_LOGIN_QQ,
            self::THIRD_LOGIN_SINA,
            self::THIRD_LOGIN_WEIXIN
        );
    }

}
