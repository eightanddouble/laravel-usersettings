<?php

return [

	/*
	 * The User model class upon which the per user settings are to be applied
	*/
	'users' => \App\Models\User::class,

	/*
	 * The desired name of the column which will be created once the package is migrated
	*/
	'settings_column' => 'settings',

	/*
	 * The unique identifier of the User model class defined above
	*/
	'default_constraint_key' => 'id',

];
