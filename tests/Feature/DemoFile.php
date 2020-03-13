<?php

namespace Tests\Feature;

use Faker\Factory;
use Exception;
use stdClass;

/**
 * @property string $templatePath
 * @property string $template
 * @property string $parsedTemplate
 * @property object $properties
 */
class DemoFile
{
	CONST VARIABLE_CAPTURE_REGEX 		= "~(?'wrapper'\{\{(?'variableName'[A-Z_]+)(?:(\|(?'fakerCommand'[a-zA-Z]+)(?:\((?'fakerArguments'[^)]+)\))?)?)\}\})~ui";
	CONST FUNCTION_ARGUMENT_SPLIT_REGEX	= "~(?:['\"]?(?'argumentList'[^'\",]+)['\"]?)(?:,|\s)*~ui";

	protected $templatePath;
	protected $template;
	protected $parsedTemplate;
	public $properties;

	/**
	 * @param ...string $templatePathArray
	 */
	public function __construct(string ...$templatePathArray)
	{
		$this->properties		= new stdClass();
		$this->parsedTemplate	= '';
		$this->faker			= Factory::create();

		$this->templatePath		= implode(DIRECTORY_SEPARATOR, $templatePathArray);

		$this->template			= file_get_contents($this->templatePath);
	}

	/**
	 * Converts function arguments string to array
	 * 
	 * @param  string $argumentText
	 * @return array
	 */
	protected static function splitFunctionArguments(string $argumentText) : array
	{
		preg_match_all(self::FUNCTION_ARGUMENT_SPLIT_REGEX, $argumentText, $output);

		return $output['argumentList'];
	}

	/**
	 * Replaces variable declarations with variables
	 * 
	 * @param  int         $previousOffset
	 * @param  int|null    $offset
	 * @param  string|null $variableName
	 * @param  string|null $variableWrapper
	 * @return void
	 */
	protected function initializeVariables(int $previousOffset, int $offset = null, string $variableName = null, string $variableWrapper = null) : void
	{
		$variableWrapperLength	= mb_strlen($variableWrapper);

		$this->parsedTemplate	.= mb_substr($this->template, $previousOffset, $offset);

		if( is_null($variableName) )
			return;

		if( empty($this->properties->$variableName) ) {
			throw new Exception('You need to set a value the variable ("' . $variableName . '" at position #' . $offset . ') ');
		}

		$this->parsedTemplate	.= (string)$this->properties->$variableName;
	}

	/**
	 * Renders the template
	 * 
	 * @return bool
	 */
	public function render() : bool
	{
		preg_match_all(self::VARIABLE_CAPTURE_REGEX, $this->template, $output, PREG_OFFSET_CAPTURE);

		$previousWrapperOffset	= 0;

		foreach($output['variableName'] as $i => $variableName)
		{
			$variableName		= $variableName[0];
			$wrapperText		= $output['wrapper'][$i][0];
			$wrapperOffset		= $output['wrapper'][$i][1];
			$fakerArguments		= $output['fakerArguments'][$i][0] ?? null;
			$fakerCommand		= $output['fakerCommand'][$i][0] ?? null;

			if( !empty($fakerArguments) )
			{
				$functionArguments					= self::splitFunctionArguments($fakerArguments);
				$this->properties->$variableName	= $this->faker->$fakerCommand(...$functionArguments);
			}
			else if ( !empty($fakerCommand) )
			{
				$this->properties->$variableName	= $this->faker->$fakerCommand;
			}

			$this->initializeVariables(
				$previousWrapperOffset,
				$wrapperOffset - $previousWrapperOffset,
				$variableName,
				$wrapperText
			);

			$previousWrapperOffset					= $wrapperOffset + mb_strlen($wrapperText);
		}

		$this->initializeVariables($previousWrapperOffset);

		return true;
	}

	public function getParsedTemplate() : string
	{
		return $this->parsedTemplate;
	}
}
