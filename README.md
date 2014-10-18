![vume](http://80.240.132.120/vume/github_head.jpg)

* [Overview](#overview)
* [Get started](#get-started)
* [Config](#config)
* [Controller](#controller)
* [Views](#views)
* [Model](#model)
* [Routing](#routing)
* [Redirect](#redirect)
* [Validation](#validation)
* [Sessions](#sessions)
* [Helpers](#helpers)

## Overview

Current version: 0.1.0

##### Be careful

This framework is intended to work for small webapps and websites only. Currently there are

* No User Authentication
* No Caching
* No Security
* No Testing

##### Requirements
* PHP 5.4+
* [PDO extension](http://de2.php.net/pdo)

## Get started

The most important is that you add a correct **url** in `/config/app.php`. This must point to the public directory.
An example for a typical MAMP installation: `http://localhost:8888/vume/public/`.

Make sure that the `RewriteBase` in the `.htaccess` file (public folder) is correct. This must point to the public directory too. An example for a typical MAMP installation: `RewriteBase /vume/public`.

Check out the [Basic Taskapp Demo](http://80.240.132.120/vume/vume_taskapp.zip) to see the code in a small app.

## Config

By default, there a three config files in the `config` directory. `app.php`, `database.php` and `paths.php`.

The `database.php` stores your database informations including different environments. 

In the `paths.php` all the paths are stored.

Let's take a closer look to the `app.php` config file:

```php
return [

  'debug' => true,

  'database' => true,
  'environment' => 'development',

  'url' => 'http://dev.vume/',
  'img_url' => 'http://img.dev.vume/',

];
```

All config files are arrays. If you want to add a new config file, make sure that the syntax is the same.

Using `debug`, you can control whether error messages are displayed. Set it to `false` in production mode.
Make `database` to `false` if you do not use a database. You can choose different environments for your database. They are stored in the `database.php`.

##### How to use configs

All configs are converted to constants. Use them case insensitive.

```php
// http://dev.vume/
echo URL;
echo url;
```

## Controller

The controllers are stored in the `app/controllers` directory. They have `NameController` as naming convention for the class and file name.

An example of a `Task` controller in `app/controllers/TaskController.php`:
```php
class TaskController extends vume\Controller {

  public function index()
  {
    echo 'Hello Github!';
  }
}
```

##### Return a view

Let's return `resources/views/home.php`:

```php
return $this->view('home');
```

You can use dotnotation for nested view files. Return `resources/views/tasks/all.php`:

```php
return $this->view('tasks.all');
```

##### Passing data to a view

Concatenate the `with()` method. First parameter is the **name** you want to access in your view. The second parameter is the **data**.
```php
return $this->view('home')->with('name', $data);
```

Let's pass more data to our view and access them with `$my_house` and `$my_words`.
```php
$house = 'Bolton';
$words = 'Our Blades are Sharp';

return $this->view('asoiaf')
  ->with('my_house', $house)
  ->with('my_words', $words);
```

Planned: Make second parameter optional and allow arrays for the first parameter.

##### Share data for all views

If you want to have access from all views to the same data, use `share()`.

```php
public function __construct()
{
  $tasks = 'many tasks here';

  $this->share('tasks', $tasks);
}
```

Planned: Create option for **excepted** and **only** views.

## Views

All view files are placed in the `resources/views` directory. If you load a view on the controller, the file must end with `.php`.

There is a **master** view. This is the structure & boilerplate of the html code. Your specific views are loaded into this file.

Planned: Small template engine.

## Model

The models are stored in the `app/models` directory. They have `Name` as naming convention for the class and file name.

An example of a `Tasks` model in `app/models/Tasks.php`:

```php
class Tasks extends vume\Model {

  public function getTitle($title)
  {
    $sql = 'SELECT * FROM tasks WHERE title = :title';
    $query = $this->db->prepare($sql);
    $query->execute([':title' => $title]);

    return $query->fetch();
  }
}
```

The model class used [PDO](http://de2.php.net/pdo) for databased work. Make sure that the PDO extension is activated and you know how to use PDO.

Access the database layer in your models with `$this->db`.

##### Table names and primary key

The framework used standard names for the table and the primary key. They need it for the prefabricated methods.

Standard name for a table is the name from the used model in lowercase. For example: If the model named `Tasks`, the table name is `tasks`.

The standard name for the primary key is `id`.

You can change both in every model:

```php
class Tasks extends vume\Model {

  protected $table = 'my_tasks';

  protected $primaryKey = 'Number';
  
  // ...
}
```

##### Available methods

The `Model` superclass has a few prefabricated methods, so you do not have to write some methods over and over.

```php
// Return all from model.
all();

// Find data by primary key.
find($id);

// Delete model by primary key.
delete($id);

// Get last inserted primary key. Useful for redirect to current created data.
function id();
```

Planned: Add more prefabricated methods.

##### Models in controllers

Instantiate the model class in your controller and use them:

```php
class TaskController extends vume\Controller {

  public function index()
  {
    $task = new Task();
    $tasksAll = $task->all();
    $taskById = $task->find(1);
    
    return $this->view('home')
      ->with('tasksAll', $tasksAll)
      ->with('taskById', $taskById);
  }
}
```

## Routing

Your routings are stored in `app/routes.php`. Currently there are only `get` and `post` methods available.

Let's see an example for both:

```php
$route->get('/', 'TaskController@index');
$route->get('/new', 'TaskController@create');

$route->post('/', 'TaskController@store');
```

The first line register the home url (e.g. http://example.com). The controller is `TaskController`, the method inside this controller is `index()`. The second line register the `/new` url (e.g. http://example.com/new). The controller is again `TaskController`, and the method is `create()`.

This works for `post` same. The route is only triggered by a post request.

##### Parameters

Use parameters in your routes with `{}`:

```php
$route->get('/show/{id}', 'TaskController@show');
$route->get('/api/{first}-{second}-{third}', 'APIController@update');
```

In your methods you need to enter the same number of parameters. Name they as you want, but they must in the same order.

Planned: Resources and Closures.

## Redirect

Currently there are two functions for redirect: `$this->redirect()->to('url')` and `$this->redirect()->back()`. 

There are alias helper functions available:
`to('url')` redirects the user to specific url, `back()` to previous url within your website.

Use them in your controllers:

```php
class TaskController extends vume\Controller {

  public function destroy($id)
  {
    $task = new Task();
    
    if($task->delete($id)) {
      return $this-redirect()->to('url');
      // or return to('url');
    }
    
    return $this->redirect()->back();
    // or return back();
  }
}
```

## Validation

You can validate the users input in your controllers with `$this->validate(input)`.

**Caution:** Currently it validates only **required** fields.

You can pass the `$_POST` variable in the parameter. Or a array with desired fields.

```php
public function store()
{
  $validate = $this->validate($_POST);

  if($validate->fails()) {
    return back();
  }

  return to('url);
}
```

The validation class has currently three methods to check:

```php
$validate = $this->validate(['fieldname', 'otherfieldname']);

if($validate->fails()) {
  echo 'There are blank fields';
}

if($validate->passes()) {
  echo 'There are no blank fields';
}

var_dump($validate->errors());
```

##### Show errors in views

There are a helper function: `error()`.

```php
<?php echo error('title'); ?>
<input type="text" name="title" placeholder="Required">

<?php echo error('description'); ?>
<textarea name="description" placeholder="Required"></textarea>
```

The helper function need the same value as `name=` in a input/textarea field.

##### Save old input

When a validation fails, it is a common way to redirect the user back to the form. But usually his other inputs would be empty.

There is a helper function to avoid this: `inputOld()`.

```php
<input type="text" name="title" placeholder="Required" value="<?php echo inputOld('title'); ?>">

<textarea name="description" placeholder="Required"><?php echo inputOld('description'); ?></textarea>
```

If the field has no errors (e.g. by first load of this form), you can store a default value: `inputOld('name', 'default value')`.

Planned: More validation rules. Improved array support. Add exceptions and only fields. Custom error messages.

## Sessions

The framework has a small helper function for work with sessions.

```php
// $_SESSION['key'];
session('key')->get();

// $_SESSION['key'] = $data;
session('key')->set($data);
```

Read more about the [Readable Session Helper](https://github.com/devfake/Readable-Session-Helper).

## Helpers

The framework has few helper functions. Some of them you have already seen.

```php
// Check if request is ajax.
ajax();

// Return formatting print_r.
pp($content);

// Automatic cache-busting files.
// An example is in the <head> area of the master view.
autoCache($file);

// Alias for compact().
c($name);

// Alias for $_POST[].
input($input = null);

// Alias for redirect()->to().
to($url);

// Alias for redirect()->to().
back();

// Return input error message.
error($name);

// Return old input. Useful when the user is redirected back.
inputOld($name, $default = null);

// Alias for $_SESSION[].
session($keys = null);
```