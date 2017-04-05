<?php
/*
 * Plugin Name: VRK Compressor
 * Plugin URI: https://vrkansagara.in/plugin/vrk-compressor
 * Description: Compress all final output
 * Version: 1.0
 * Author: Vallabh Kansagara
 * Author URI: https://vrkansagara.in/author/vrk
 * Author Email: vrkansagara@gmail.com
*/

/*
BSD 3-Clause License

Copyright (c) 2017, Vallabh Kansagara
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of the copyright holder nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

function getCompressedOutPut($buffer)
{
    $additionaly = array('/\>[^\S ]+/s' => '>','/[^\S ]+\</s' => '<','/(\s)+/s' => '\\1','!/\*.*?\*/!s' => '','/\n\s*\n/' => '');
    $buffer = preg_replace('/<!--(.*)-->/Uis', '', $buffer);
    $buffer = preg_replace(array_keys($additionaly), array_values($additionaly), $buffer);
    ini_set("pcre.recursion_limit", "16777");
    $buffer = compress($buffer);
    return $buffer;
}

function compress($buffer)
{
    $regexRemoveWhiteSpace='%(?>[^\S]\s*|\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:textarea|pre)\b))*+)(?:<(?>textarea|pre)\b|\z))%ix';
    $re = '%(?>[^\S]\s*|\s{2,})(?=[^<]*+(?:<(?!/?(?:textarea|pre|script)\b)[^<]*+)*+(?:<(?>textarea|pre|script)\b|\z))%Six';

    $new_buffer = preg_replace('/<!--(.*|\n)-->/Uis', " ", sanitize_output($buffer));
    $new_buffer = preg_replace('/\s+/', " ", sanitize_output($new_buffer));
    $new_buffer = preg_replace($regexRemoveWhiteSpace, " ", sanitize_output($new_buffer));
    $new_buffer = preg_replace($re, " ", sanitize_output($new_buffer));
    if ($new_buffer === null) {
        $new_buffer = $buffer;
    }
    return $new_buffer;
}

function sanitize_output($buffer)
{
    $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s','!/\*.*?\*/!s','/\n\s*\n/');
    $replace = array('>','<','\\1','','');
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}
