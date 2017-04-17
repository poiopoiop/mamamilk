<?php

class Charset {

const ONE_OCTET_BASE          = 0x00;    /* 0xxxxxxx */
const ONE_OCTET_MASK          = 0x7F;    /* x1111111 */
const CONTINUING_OCTET_BASE   = 0x80;    /* 10xxxxxx */
const CONTINUING_OCTET_MASK   = 0x3F;    /* 00111111 */
const TWO_OCTET_BASE          = 0xC0;    /* 110xxxxx */
const TWO_OCTET_MASK          = 0x1F;    /* 00011111 */
const THREE_OCTET_BASE        = 0xE0;    /* 1110xxxx */
const THREE_OCTET_MASK        = 0x0F;    /* 00001111 */
const FOUR_OCTET_BASE         = 0xF0;    /* 11110xxx */
const FOUR_OCTET_MASK         = 0x07;    /* 00000111 */
const FIVE_OCTET_BASE         = 0xF8;    /* 111110xx */
const FIVE_OCTET_MASK         = 0x03;    /* 00000011 */
const SIX_OCTET_BASE          = 0xFC;    /* 1111110x */
const SIX_OCTET_MASK          = 0x01;    /* 00000001 */

    public function __construct() {
        return;
    }

    /*
     * brief: check char is_utf_1st_of_1
     * param: $c = ord($char)
     */
    public function is_utf_1st_of_1($c) {
        if (($c&(~self::ONE_OCTET_MASK)) == self::ONE_OCTET_BASE) {
            return true;
        }
        return false;
    }

    /*
     * brief: check char is_utf_1st_of_2
     * param: $c = ord($char)
     */
    public function is_utf_1st_of_2($c) {
        if (($c&(~self::TWO_OCTET_MASK)) == self::TWO_OCTET_BASE) {
            return true;
        }
        return false;
    }

    /*
     * brief: check char is_utf_1st_of_3
     * param: $c = ord($char)
     */
    public function is_utf_1st_of_3($c) {
        if (($c&(~self::THREE_OCTET_MASK)) == self::THREE_OCTET_BASE) {
            return true;
        }
        return false;
    }

    /*
     * brief: check char is_utf_1st_of_4
     * param: $c = ord($char)
     */
    public function is_utf_1st_of_4($c) {
        if (($c&(~self::FOUR_OCTET_MASK)) == self::FOUR_OCTET_BASE) {
            return true;
        }
        return false;
    }

    /*
     * brief: check char is_utf_1st_of_5
     * param: $c = ord($char)
     */
    public function is_utf_1st_of_5($c) {
        if (($c&(~self::FIVE_OCTET_MASK)) == self::FIVE_OCTET_BASE) {
            return true;
        }
        return false;
    }

    /*
     * brief: check char is_utf_1st_of_6
     * param: $c = ord($char)
     */
    public function is_utf_1st_of_6($c) {
        if (($c&(~self::SIX_OCTET_MASK)) == self::SIX_OCTET_BASE) {
            return true;
        }
        return false;
    }

    /*
     * UTF8 BOM
     * 0xEF 0xBB 0xBF
     */

};
