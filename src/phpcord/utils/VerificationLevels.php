<?php

namespace phpcord\utils;

interface VerificationLevels {
	/**
	 * 	unrestricted
	 */
	public const NONE = 0;
	
	/**
	 * 	must have verified email on account
	 */
	public const LOW = 1;
	
	/**
	 * 	must be registered on Discord for longer than 5 minutes
	 */
	public const MEDIUM = 2;
	
	/**
	 * 	must be a member of the server for longer than 10 minutes
	 */
	public const HIGH = 3;
	
	/**
	 * 	must have a verified phone number
	 */
	public const VERY_HIGH = 4;
}