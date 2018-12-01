<?php

include 'menu_helper.php';

$page = 'about.php';
$is_user_login = false;

function main_menu_ul($links, $contains_active, $level)
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

$main_menu = array(
    make_menu_link('Home', 'home.php'),
    make_menu_link('About', 'about.php'),
    make_menu_link('Courses', '#', array(
        make_menu_link('PHP', 'course.php?page=php'),
        make_menu_link('JS', 'course.php?page=js'),
    )),
);

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


function user_menu_ul($links, $level)
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

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>

        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">WebSiteName</a>
                </div>
                <?php print render_menu($main_menu, 'main'); ?>
                <?php print render_menu($user_menu, 'user'); ?>

            </div>
        </nav>

        <script
                src="https://code.jquery.com/jquery-3.3.1.min.js"
                integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </body>
</html>