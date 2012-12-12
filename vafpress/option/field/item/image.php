<?php

class VP_Option_Field_Item_Image extends VP_Option_Field_Item_Plain
{

	public $img;

	public function __construct()
	{

	}

	public function img($img)
	{
		$this->img = $img;
		return $this;
	}

}

/**
 * EOF
 */