<?php

/**
 * Fabricator language strings.
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
   'invalidModel'      => 'fabrication을 위해 제공된 모델이 적절하지 않습니다.', // CI v4.0.4기준: CodeIginter\framework-4.0.4\system\Test\Fabricator.php 생성자용 메시지. Fabricator 클래스는 Fzaninotto의 Faker를 통해서 테스트 데이터를 생성함. @See: https://codeigniter.com/user_guide/testing/fabricator.html - Loading Fabricators // 'Invalid model supplied for fabrication.',
   'missingFormatters' => '유효한 formatter가 정의되지 않았습니다.', // CI v4.0.4기준: CodeIginter\framework-4.0.4\system\Test\Fabricator.php makeArray()용 메시지 @See: https://codeigniter.com/user_guide/testing/fabricator.html - Defining Formatters // 'No valid formatters defined.',
];
