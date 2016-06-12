<?php

//
class String
{
    static public function uuid()
    {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);
        $uuid = chr(123) . substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12) . chr(125);
        return $uuid;
    }

    static public function keyGen()
    {
        return str_replace('-', '', substr(String::uuid(), 1, -1));
    }

    static public function isUtf8($str)
    {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($str);

        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);

            if (128 < $c) {
                if (254 <= $c) {
                    return false;
                } else if (252 <= $c) {
                    $bits = 6;
                } else if (248 <= $c) {
                    $bits = 5;
                } else if (240 <= $c) {
                    $bits = 4;
                } else if (224 <= $c) {
                    $bits = 3;
                } else if (192 <= $c) {
                    $bits = 2;
                } else {
                    return false;
                }

                if ($len < ($i + $bits)) {
                    return false;
                }

                while (1 < $bits) {
                    $i++;
                    $b = ord($str[$i]);
                    if (($b < 128) || (191 < $b)) {
                        return false;
                    }

                    $bits--;
                }
            }
        }

        return true;
    }

    static public function msubstr($str, $start = 0, $length, $charset = 'utf-8', $suffix = true)
    {
        if (function_exists('mb_substr')) {
            $slice = mb_substr($str, $start, $length, $charset);
        } else if (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = '/[-]|[?�][�-�]|[?�][�-�]{2}|[?�][�-�]{3}/';
            $re['gb2312'] = '/[-]|[?�][?�]/';
            $re['gbk'] = '/[-]|[?�][@-�]/';
            $re['big5'] = '/[-]|[?�]([@-~]|?�])/';
            preg_match_all($re[$charset], $str, $match);
            $slice = join('', array_slice($match[0], $start, $length));
        }

        return $suffix ? $slice . '...' : $slice;
    }

    static public function randString($len = 6, $type = '', $addChars = '')
    {
        $str = '';

        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;

            case 1:
                $chars = str_repeat('0123456789', 3);
                break;

            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;

            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;

            case 4:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;

            default:
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }

        if (10 < $len) {
            $chars = ($type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5));
        }

        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            for ($i = 0; $i < $len; $i++) {
                $str .= self::msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1, 'utf-8', false);
            }
        }

        return $str;
    }

    static public function buildCountRand($number, $length = 4, $mode = 1)
    {
        if (($mode == 1) && ($length < strlen($number))) {
            return false;
        }

        $rand = array();

        for ($i = 0; $i < $number; $i++) {
            $rand[] = self::randString($length, $mode);
        }

        $unqiue = array_unique($rand);

        if (count($unqiue) == count($rand)) {
            return $rand;
        }

        $count = count($rand) - count($unqiue);

        for ($i = 0; $i < ($count * 3); $i++) {
            $rand[] = self::randString($length, $mode);
        }

        $rand = array_slice(array_unique($rand), 0, $number);
        return $rand;
    }

    static public function buildFormatRand($format, $number = 1)
    {
        $str = array();
        $length = strlen($format);

        for ($j = 0; $j < $number; $j++) {
            $strtemp = '';

            for ($i = 0; $i < $length; $i++) {
                $char = substr($format, $i, 1);

                switch ($char) {
                    case '*':
                        $strtemp .= String::randString(1);
                        break;

                    case '#':
                        $strtemp .= String::randString(1, 1);
                        break;

                    case '$':
                        $strtemp .= String::randString(1, 2);
                        break;

                    default:
                        $strtemp .= $char;
                        break;
                }
            }

            $str[] = $strtemp;
        }

        return $number == 1 ? $strtemp : $str;
    }

    static public function randNumber($min, $max)
    {
        return sprintf('%0' . strlen($max) . 'd', mt_rand($min, $max));
    }

    static public function autoCharset($string, $from = 'gbk', $to = 'utf-8')
    {
        $from = (strtoupper($from) == 'UTF8' ? 'utf-8' : $from);
        $to = (strtoupper($to) == 'UTF8' ? 'utf-8' : $to);
        if ((strtoupper($from) === strtoupper($to)) || empty($string) || (is_scalar($string) && !is_string($string))) {
            return $string;
        }

        if (is_string($string)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($string, $to, $from);
            } else if (function_exists('iconv')) {
                return iconv($from, $to, $string);
            } else {
                return $string;
            }
        } else if (is_array($string)) {
            foreach ($string as $key => $val) {
                $_key = self::autoCharset($key, $from, $to);
                $string[$_key] = self::autoCharset($val, $from, $to);

                if ($key != $_key) {
                    unset($string[$key]);
                }
            }

            return $string;
        } else {
            return $string;
        }
    }
}