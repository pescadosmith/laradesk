<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	// Mass assignment protection - fields passed not in this array will be ignored
	protected $fillable = ['username', 'password', 'email', 'role'];

	public $rules = 
	[
		'username' => 'required',
		'password' => 'required',
		'email' => 'required|unique:users,email'
	];

	public $errors;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	protected $primaryKey = 'users_id';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function isValid($data)
	{
		$validation = Validator::make($data, $this->rules);

		if ($validation->passes())
		{
			return true;
		}

		$this->errors = $validation->messages();

		return false;
	}

	public function isAdmin()
	{
		if(Auth::user()->user_userlevel_fk == "1")
		{
			return true;
		}

		return false;	
	}
	
	public function isStaff()
	{
		if(Auth::user()->user_userlevel_fk == "1" || Auth::user()->user_userlevel_fk == "2")
		{
			return true;
		}

		return false;
	}

}
