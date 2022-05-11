<?php

/**
 * Core language strings.
 *
 * @package    CodeIgniter
 * @author     CodeIgniter Dev Team
 * @copyright  2019-2020 CodeIgniter Foundation
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://codeigniter.com
 * @since      Version 4.0.0
 * @filesource
 *
 * @codeCoverageIgnore
 */

return [
   'copyError'                    => '파일({0}) 교체를 시도하는 도중에 에러가 발생하였습니다. 파일 디렉터리에 쓰기 권한이 있는지 확인해주십시오.', // 'An error was encountered while attempting to replace the file({0}). Please make sure your file directory is writable.',
   'enabledZlibOutputCompression' => 'zlib.output_compression ini 설정에서 지시자가 "On"으로 켜져 있습니다. 이것은 출력 버퍼에서 잘 동작하지 않을 것입니다.', // 'Your zlib.output_compression ini directive is turned on. This will not work well with output buffers.',
   'invalidFile'                  => '유효하지 않은 파일: {0}', // 'Invalid file: {0}',
   'missingExtension'             => '{0} 확장이 로드되지 않았습니다.', // '{0} extension is not loaded.',
   'noHandlers'                   => '{0} : 적어도 하나 이상의 Handler가 반드시 제공되어야 합니다.', // CI4 기준 : {0}에는 class 또는 handler명이 들어감. // '{0} must provide at least one Handler.',
];
