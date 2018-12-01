<?php
/*

*/
function make_menu_link($title, $link="", $childs=[], $extra=[])
{
    return array(
        'link'=>$link,
        'title'=>$title,
        'childs'=>$childs,
        'extra'=>$extra
    );
}

function render_menu($menu, $menu_name='menu')
{
    $menu_data = _render_menu($menu, $menu_name);
    return $menu_data['output'];
}


function _menu_li($menu_item, $childs="", $active, $contains_active)
{    
    $active_class = $active?'active':'';

    return "<li class='$active_class'><a href='{$menu_item['link']}'>{$menu_item['title']}</a>{$childs}</li>";
}

function _menu_ul($links,$contains_active, $level=0)
{
    return "<ul>$links</ul>";
}



/*
output: rendered menu <ul><li/>*</ul>
contains_active:true,false
*/
function _render_menu($menu, $menu_name, $level=0)
{
    $li_output =  "";
    $contains_active = false;

    $fn_li = "_menu_li";
    $fn_ul = "_menu_ul";
    $fn_active = "_menu_is_active";
    $fn_access = "_menu_access";

    if(function_exists($menu_name.'_menu_li'))
    {
        $fn_li = $menu_name.'_menu_li';
    }

    if(function_exists($menu_name.'_menu_ul'))
    {
        $fn_ul = $menu_name.'_menu_ul';
    }

    if(function_exists($menu_name.'_menu_is_active'))
    {
        $fn_active = $menu_name.'_menu_is_active';
    }
    
    if(function_exists($menu_name.'_menu_access'))
    {
        $fn_access = $menu_name.'_menu_access';
    }

    if(function_exists($menu_name.'_menu_li_l'.$level))
    {
        $fn_li = $menu_name.'_menu_li_l'.$level;
    }elseif(function_exists('_menu_li_l'.$level))
    {
        $fn_li = 'menu_menu_li_l'.$level;
    }

    if(function_exists($menu_name.'_menu_ul_l'.$level))
    {
        $fn_ul = $menu_name.'_menu_ul_l'.$level;
    }elseif(function_exists('_menu_ul_l'.$level))
    {
        $fn_ul = 'menu_menu_ul_l'.$level;
    }

    if(function_exists($menu_name.'_menu_is_active_l'.$level))
    {
        $fn_active = $menu_name.'_menu_is_active_l'.$level;
    }elseif(function_exists('_menu_is_active_l'.$level))
    {
        $fn_active = 'menu_menu_is_active_l'.$level;
    }




    foreach($menu as $key=>$menu_item)
    {
        $link = $menu_item['link'];
        $childs = $menu_item['childs'];
        $extra = $menu_item['extra'];
        $title = $menu_item['title'];
        
        //if access function exists
        //call it if it returns false ignore the menu_item
        if(function_exists($fn_access))
        {
            $access = $fn_access($menu_item);
            if($access == false)
            {
                continue;
            }
        }

        $active = false;
        if(function_exists($fn_active))
        {
            $active = $fn_active($menu_item);
        }


        $parent_of_active = false;

        $child_output = "";
        if(count($childs)>0)
        {
            $child_data = _render_menu($childs, $menu_name, $level+1);
            $child_output = $child_data['output'];
            $parent_of_active = $child_data['contains_active'];

        }

        if($active || $parent_of_active)
        {
            $contains_active  = true;
        }



        $li_output .= $fn_li($menu_item, $child_output, $active, $parent_of_active, $level);


    }

    $ul_output = $fn_ul($li_output, $contains_active, $level);
    return array(
        'output'=>$ul_output,
        'contains_active'=>$contains_active
    );
}


