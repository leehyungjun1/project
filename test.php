<?php

class UserDTO
{
	/**
	* @var int
	*/
	private $id;

	/**
	* @var string
	*/
	private $firstName;

	/**
	* @var string
	*/
	private $lastName;

	/**
	* @var dateTime
	*/
	private $birthDate;

	/**
	* @var int[]
	*/
	private $booksIds;
	
	public function myMethod() {
		return "123";
	}
}

$myObject = new UserDto();

$rst = $myObject->myMethod();
echo $rst;


