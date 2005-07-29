<?php
/*
Plugin Name: Identify External Links
Plugin URI: http://txfx.net/code/wordpress/identify-external-links/
Description: Searches the text for links outside of the domain of the blog.  To these, it adds <strong>class="extlink"</strong>.  It could very easily be modified to also add <strong>target="_blank"</strong> as well.
Author: Mark Jaquith
Version: 1.2
Author URI: http://txfx.net/
*/

/*  Copyright 2005  Mark Jaquith (email: mark.gpl@txfx.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


function wp_get_domain_name_from_uri($uri){
    preg_match("/^(http:\/\/)?([^\/]+)/i", $uri, $matches);
    $host = $matches[2];
    preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
    return $matches[0];    
}


function parse_external_links($matches){
    if ( wp_get_domain_name_from_uri($matches[3]) != wp_get_domain_name_from_uri($_SERVER["HTTP_HOST"]) ){
        return '<a href="' . $matches[2] . '//' . $matches[3] . '"' . $matches[1] . $matches[4] . ' class="extlink">' . $matches[5] . '</a>';    
    } else {
        return '<a href="' . $matches[2] . '//' . $matches[3] . '"' . $matches[1] . $matches[4] . '>' . $matches[5] . '</a>';
    }
}
    

function wp_external_links($text) {
    $pattern = '/<a (.*?)href="(.*?)\/\/(.*?)"(.*?)>(.*?)<\/a>/i';
    $text = preg_replace_callback($pattern,'parse_external_links',$text);
    
    $pattern2 = '/<a (.*?) class="extlink"(.*?)>(.*?)<img (.*?)<\/a>/i';
    $text = preg_replace($pattern2, '<a $1 $2>$3<img $4</a>', $text);
    
    return $text;
}


add_filter('the_content', 'wp_external_links', 2);
add_filter('the_excerpt', 'wp_external_links', 2);

// delete this one if you don't want it run on comments
add_filter('comment_text', 'wp_external_links', 10);
?>