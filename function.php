<?php

/**
* @param string $body 処理対象のテキスト
* @param string|null $link_title リンクテキスト
* @return string
*/

function url2link($body, $link_title = null)
{
    $pattern = '/(href=")?https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
    $body = preg_replace_callback($pattern, function($matches) use ($link_title) {
        // 既にリンクの場合や Markdown style link の場合はそのまま
        if (isset($matches[1])) return $matches[0];
        $link_title = $link_title ?: $matches[0]; //$link_title = nullならurlが代入
        return "<a href=\"{$matches[0]}\">$link_title</a>";
    }, $body);
    return $body;
}

function img_tag_decode($writeline){

  $pattern = "/upload\/([\-_.!~*\'\(\)\[\]\{\}\.a-zA-Z0-9;\/?@&=\+\$,%　# ]+?)\.(jpg|jpeg|gif|png|svg|tiff|JPG|JPEG)/";

  $result = preg_match_all($pattern, $writeline, $matches);

  $imagefiledata = $matches[0];

  $imageArray = [];
  $imgPattern = [];

  foreach ($imagefiledata as $imgdata) {
    $imageArray[] = '<img src ="'.$imgdata.'">';
  }
  foreach ($imagefiledata as $data) {
    $imgPattern[] = '/'.preg_quote($data,"/").'/u';
  }

  $decode_img_writeline = preg_replace($imgPattern,$imageArray,$writeline);

  return $decode_img_writeline;

}

// ?>
