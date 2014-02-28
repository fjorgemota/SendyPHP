SendyPHP [![Build Status](https://travis-ci.org/fjorgemota/SendyPHP.png?branch=master)](https://travis-ci.org/fjorgemota/SendyPHP)
=================

A PHP class built to interface with the Sendy API ([http://sendy.co](http://sendy.co))

## Installation

### Using Composer

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `jacobbennett/sendyphp`.

	"require": {
		"jacobbennett/sendyphp": "dev-master"
	}

Next, update Composer from the Terminal:

    composer update

#Usage

Create an instance of the class while passing in an array including your API key, installation URL, and the List ID you wish to work with.
```php

	use \SendyPHP\SendyPHP;
	
	$sendy = new SendyPHP('http://updates.mydomain.com', 'yourapiKEYHERE'm 'your_list_id_goes_here');
	
	//you can change the list_id you are referring to at any point
	$sendy->setListId("a_different_list_id");
```

#Methods
After creating a new instance of SendyPHP call any of the methods below 

##Return Values
The return value of any of the functions which interact directly with Sendy will always be an instance of `SendyResponse`, which have two public methods:

 - isSuccessful() - Indicating if the request will be successful
 - getResult() - Return the raw response informed by Sendy

This library have PHPDoc comments, which make the code easy to understand at all.

##subscribe(array $values)

This method takes an array of `$values` and will attempt to add the `$values` into the list specified in `$list_id`

```php
	$result = $sendy->subscribe(array(
						'name'=>'Jim',
						'email' => 'Jim@gmail.com', //this is the only field required by sendy
						'customfield1' => 'customValue'
						));
```
__Note:__ Be sure to add any custom fields to the list in Sendy before utilizing them inside this library.
__Another Note:__ If a user is already subscribed to the list, the library will return a SendyResponse which return 'Already Subscribed' on his call to getResult().

##unsubscribe($email)

Unsubscribes the provided e-mail address (if it exists) from the current list.

```php
	$result = $sendy->unsubscribe('test@testing.com');
```

##getStatus($email)

Returns the status of the user with the provided e-mail address (if it exists) in the current list.
```php
	$results = $sendy->getStatus('test@testing.com');
```
__Note:__ refer to the code or see http://sendy.co/api for the types of return messages you can expect.

##getSubscribersCount()

Returns the number of subscribers to the current list.
```php
	$result = $sendy->getSubscribersCount();
	echo $result->getResult(); // The number of subscribers in the list
```

##setListId($list_id) and getListId()

Change or get the list you are currently working with.

```php
	
	//set or switch the list id
	$sendy->setListId('another_list_id');
	
	//get the current list id
	echo $sendy->getListId();
```
