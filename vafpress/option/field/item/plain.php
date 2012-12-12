<?php

class VP_Option_Field_Item_Plain
{

	public $value;

	public $label;

	public function __construct()
	{

	}

	public function value($value)
	{
		$this->value = $value;
		return $this;
	}

	public function label($label)
	{
		$this->label = $label;
		return $this;
	}

}

/**
 * EOF
 */