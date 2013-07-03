<?php
/*
 * This file is part of the WSGeneratorBundle package.
*
* (c) Benjamin Georgeault <https://github.com/WedgeSama/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace WS\GeneratorBundle\Command\Helper;

use Symfony\Component\Console\Helper\DialogHelper as BaseHelper;

class DialogHelper extends BaseHelper {

	/**
	 * Demande une information à l'utilisateur et la valide
	 */
	protected function askVar($output, $question, $validator, $default = null) {
		if ($default !== null)
			$question .= ' [<comment>' . $default . '</comment>]';
		
		$question .= ' : ';
		
		$var = $this->askAndValidate($output, $question, 
									function ($var) use($validator) {
										return $validator($var);
									}, false, $default);
		
		return $var;
	}

}