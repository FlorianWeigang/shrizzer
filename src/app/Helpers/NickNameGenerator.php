<?php

namespace Shrizzer\Helpers;

/**
 * Class NickNameGenerator
 *
 * Example:
 *
 * email@example.com => EM
 * email.demo@example.com => ED
 * email-first-demo@example.com => EF
 *
 * Supported delimiter: ",", ".", "_", "-"
 *
 * @author Florian Weigang
 */
class NickNameGenerator
{
    /**
     * @param $email
     * @return string|void
     */
    public static function generateNicknameByEmail($email)
    {
        $arr = explode("@", $email);

        if(count($arr) < 2) {
            return $email;
        }

        $firstPart = $arr[0];

        if (strlen($firstPart) < 3) {
            return strtoupper($firstPart);
        }

        foreach ([',', '.', '_', '-'] as $delimiter) {
            $nick = self::tryExplodeBy($firstPart, $delimiter);

            if ($nick) {
                return $nick;
            }
        }

        return strtoupper(substr($firstPart, 0, 2));
    }

    /**
     * @param $string
     * @param $delimiter
     *
     * @return bool
     */
    private static function tryExplodeBy($string, $delimiter)
    {
        $parts = explode($delimiter, $string);

        if (count($parts) < 2) {
            return false;
        }

        $first = $parts[0];
        $second = $parts[1];

        if (strlen($first) < 1 || strlen($second) < 1) {
            return false;
        }

        return strtoupper(substr($first, 0, 1) . substr($second, 0, 1));
    }
}