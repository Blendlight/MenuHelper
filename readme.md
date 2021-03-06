

# Menu Helper
PHP helper functions to help rendering html menus from arrays.

## How to use basics
**Define Your menu Array** 
```php
$menu = Array(MENUT_ITEM1, MENUT_ITEM2, MENUT_ITEM3);
```
The structure of single menu item is
```php
Array(
//Title of menu item
'title' => 'Home',
//Href of the menu item
'link'  => 'home.php',
//children of this menu item
//same structure is used for children
'childs' => array(...),
//Array of extra elements which can be used
//when rendring element
//example: classes, icons ...
'extra'  => array(...),
);
```
But  can use the MenuHelper function `make_menu_link`  which will create the above structure for us.
```php
//make_menu_link($title, $link, $children=[], $extra=[]);
make_menu_link('Home', 'home.php', [], []);
//the children and extra have default value
//if we don't need it we can ignore it
```
**Basic Menu**
```php
$menu = array(
    make_menu_link('Home', 'home.php', [], array('icon'=>'fa-home')),
    make_menu_link('About', 'about.php'),
    make_menu_link('Courses', 'all_courses.php', array(
        make_menu_link('PHP', 'course.php?name=php'),
		make_menu_link('JAVASCRIPT', 'course.php?name=php'),
    )),
);
```
After creating menu we can render it by calling `render_menu($menu, $menu_name='menu')`.
the first argument is the menu array and second is the name of menu.
The name can be used for creating multiple menus.
<small>Default value for __menu_name__ is `menu`.</small>
```php
echo render_menu($menu, 'main');
```
Function `render_menu` will generate HTML markup of the menu.

---
<ul><li class=''><a href='[home.php](http://localhost/menu/home.php)'>Home</a></li><li class='active'><a href='[about.php](http://localhost/menu/about.php)'>About</a></li><li class=''><a href='[all_courses.php](http://localhost/menu/all_courses.php)'>Courses</a><ul><li class=''><a href='[course.php?name=php](http://localhost/menu/course.php?name=php)'>PHP</a></li><li class=''><a href='[course.php?name=php](http://localhost/menu/course.php?name=php)'>JAVASCRIPT</a></li></ul></li></ul>

---
This markup is generated by the MenuHelper functions `_menu_li` and `_menu_ul`.
### _menu_li function
```php
function _menu_li($menu_item, $childs="", $active, $contains_active, $level=0)
{    
    $active_class = $active?'active':'';

    return "<li class='$active_class'><a href='{$menu_item['link']}'>{$menu_item['title']}</a>{$childs}</li>";
}
```
<small>**Arguments for _menu_li**</small>
```php
// this is the item you have created with make_menu_link
$menu_item 

// this is the rendered children of this li element
// the children are rendered first and  then passed to parent li element
$childs

//Boolean value indicating the current menu item is active or not 
//this is controlled by function _menu_is_active($menu_item) if defined else false value is used
$active

//Boolean value indicating the current item is parent or ancestor of the active element
$contains_active

//Level of current menu item starting from 0
//level can be used for generating differnet
//markup for nested elements
$level
```
We will use the above arguments list when creating our `{menu_name}_menu_li` function.

#### Accessing Extra  inside _menu_li function
```php
function menu_menu_li($menu_item, $childs, $active, $contains_active){
	$extra = $menu_item['extra'];
	if(isset($extra['icon'])
	{
		$icon = "<i class='fa fa-{$extra['icon']}'></i>";
	}else{
		$icon = '';
	}
	return "<li><a href='{$menu_item['link']}'>{$menu_item['title']} $icon</a> $childs</li>";
}
```

### _menu_ul Function
```php
function _menu_ul($links, $contains_active,  $level=0)
{
    return "<ul>$links</ul>";
}
```

<small>**Arguments for _menu_ul**</small>
```php
//This is string which is the rendered _menu_li elements
$links

//Boolean value indicating the current item is parent or ancestor of an active element
$contains_active

//Level of current menu item starting from 0
//level can be used for generating differnet
//markup for nested elements
$level
```

### Creating own functions instead of default
If you create functions with {menu_name}_function.
the render_menu will use your function instead of default ones.

#### Create own functions for _menu_li

To use your own function instead of default _menu_li create a function with this pattern {menu_name}_menu_li with same parameters list as _menu_li function.
if you have not used any name for the menu in render_menu function your menu_name will be `menu`.
```php
function menu_menu_li($menu_item, $childs="", $active, $contains_active, $level=0){
    ...
}
```
If you have used menu_name `XYZ` your function will look like this
```php
function XYZ_menu_li($menu_item, $childs="", $active, $contains_active, $level=0){
    ...
}
```



#### Create own functions for _menu_ul

To use your function instead of default _menu_ul use the same methods as for _menu_li

```php
function menu_menu_ul($links, $contains_active,  $level=0){
    ...
}
```

OR

```php
function XYZ_menu_ul($links, $contains_active,   $level=0){
    ...
}
```

### Function for setting _menu_li active
By default the menu helper set the `active` property of every li element to false but if you create function _menu_is_active($menu_item)
The render_menu will use this function.
_menu_is_active is not declared by the MenuHelper you can declare it without name of the menus.
But if you want to use a specific _menu_is_active for menu use the rules for naming functions.
{menu_name}_menu_is_active.
```php
function _menu_is_active($menu_item)
{
    //get global page variable
    global $page;
    if($menu_item['link'] == $page)
    {
        return true;
    }else{
        return false;
    }
}
```

```php
function  _menu_is_active($menu_item)
{
    if($menu_item['link'] == $_GET['page'])
    {
        return true;
    }else{
        return false;
    }
}
```
Other names can be used for this function.

```php
function menu_menu_is_active($menu_item)
{
    ...
}

function main_menu_is_active($menu_item)
{
    ...
}

function XYZ_menu_is_active($menu_item)
{
    ...
}
```

### Function for Link access
By default render_menu renders all elements but If we want to specify rules on which menu item will be hidden to user we will use `_menu_access` function if it returns true for a menu_item we will show that menu if it returns false we will not render that menu item and its children.
_menu_access is not declared by the MenuHelper you can declare it without name of the menus.
But if you want to use a specific _menu_access for menu use the rules for naming functions.
{menu_name}_menu_access .
```php
function _menu_access($menu_item)
{
    if($menu_item['extra']['access'] == 'login' && user_is_login())
    {
        return true;
    }else{
        return false;
    }
}
function main_menu_access($menu_item);
function XYZ_menu_access($menu_item);
```
`<small>Use the extra for conditions.</small>`

### Creating functions for specific level

It becomes hard to manage different markup based on the level of menu elememts in one function.
MenuHelper gives an option for using level based functions.
`{menu_name}_menu_li_l{level}` this function can  be used for handling _menu_li element with menu_name and level.
for example.
```php
_menu_li_l0(...)
_menu_li_l3(...)
XYZ_menu_li_l0(...)
main_menu_li_l0(...)
```
the level based functions have high priority if you have used function XYZ_menu_li function and created function _menu_li_l0 the render_menu function will use _menu_li_l0 for rendering _menu_li element you will need to also declare XYZ_menu_li_l0 to use your function.
<small>Maybe in future we change the priority rules</small>

The above level based names for functions can also used for 
`_menu_ul` and `_menu_is_active` function.
`_menu_access` doesn't use level functions.

## Bootstrap Menu example
First specify menus.
```php
$main_menu = array(
    make_menu_link('Home', 'home.php'),
    make_menu_link('About', 'about.php'),
    make_menu_link('Courses', '#', array(
        make_menu_link('PHP', 'course.php?page=php'),
        make_menu_link('JS', 'course.php?page=js'),
    )),
);

$user_menu = array(
    make_menu_link('Sign Up', 'register.php', [], array(
        'icon'=>'user'
    )),
    make_menu_link('Login', 'login.php', [], array(
        'icon'=>'log-in'
    )),
    make_menu_link('Logout', 'logout.php', [], array(
        'access'=>'login'
    ))
);
```
<small>We are using two different menus.</small>

Now create functions for both menus.
```php
//main menu
function main_menu_ul($links, $contains_active,  $level)
{
    if($level == 0)
    {
        return '<ul class="nav navbar-nav">'.$links.'</ul>';
    }elseif($level == 1){
        return '<ul class="dropdown-menu">'.$links.'</ul>';
    }

    return '<ul>'.$links.'</ul>';
}

function main_menu_li($menu_item, $childs="", $active, $contains_active, $level=0)
{
    $title = $menu_item['title'];
    $link = $menu_item['link'];
    $extra = $menu_item['extra'];

    $active_class = $active?'active':'';
    $dropdown_class = $childs!=""?'dropdown':'';

    //if element contains childs
    if($childs != "")
    {
        return '
        <li class="dropdown '.$active_class.'">
            <a class="dropdown-toggle" data-toggle="dropdown" href="'.$link.'">
                '.$title.'
                <span class="caret"></span>
            </a>
            '.$childs.'
        </li>';
    }else{
        return '<li class="'.$active_class.'"><a href="'.$link.'">'.$title.'</a> '.$childs.' </li>';
    }

}

//user menu
function user_menu_ul($links, $contains_active,  $level)
{
    return '<ul class="nav navbar-nav navbar-right">'.$links.'</ul>';
}

function user_menu_li($menu_item, $childs="", $active=false, $contains_active=false, $level=0)
{
    $title = $menu_item['title'];
    $link = $menu_item['link'];
    $extra = $menu_item['extra'];

    $active_class = $active?'active':'';
    $icon = isset($extra['icon'])?"glyphicon glyphicon-{$extra['icon']}":"";
    
    return '<li><a href="'.$link.'"><span class="'.$icon.'"></span> '.$title.'</a></li>';

}
```
`_menu_access` and `_menu_is_active` are used for menus we declare both functions without menu name.
```php
function _menu_is_active($menu_item)
{
    global $page;
    if($page == $menu_item['link'])
    {
        return true;
    }else{
        return false;
    }
}

function _menu_access($menu_item)
{
    global $is_user_login;
    //check if in extra access exists
    if(isset($menu_item['extra']['access']))
    {
        //check the access if access is for login and user is also login return true
        //else return false
        if($menu_item['extra']['access'] == 'login' && $is_user_login==true)
        {
            return true;
        }else{
            return false;
        }
    }

    return true;
}
```
The above functions use two global variables
`$page` and `$is_user_login`.
```php
//current page
$page = 'about.php';
$is_user_login = false;
```
Now its time to render both menus.
```php
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">WebSiteName</a>
        </div>
        <?php print render_menu($main_menu, 'main'); ?>
        <?php print render_menu($user_menu, 'user'); ?>
    </div>
</nav>
```