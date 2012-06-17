<?php

/**
 * globals for passkit.
 *
 * @author Laks Gandikota <laks@wow.com>
 * @copyright Copyright (c) 2012, laksg.com
 * @license Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */
 
define("PRGM", "PASSKIT");
$package_path = DOCUMENT_ROOT . 'package';
$keys_path = DOCUMENT_ROOT . 'keys';
$certificate = $keys_path . '/certificate.pem';
$key = $keys_path . '/key.pem';
$key_password = 'kanth386';
$signature = $package_path . '/signature';
$passname = DOCUMENT_ROOT. 'pass/pass.pkpass';
$manifestfile = $package_path . '/manifest.json';
