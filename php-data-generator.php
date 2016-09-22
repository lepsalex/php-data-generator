<?php

/* DATA GENERATION */

function generate_topics($subs) {
  return function($val) use ($subs) {

    $topic = $val + 1;
    $generate_sub_topics = generate_sub_topics($topic);

    return array(
      "ID"          => $topic * 128 * 1000,
      "title"       => "Topic " . $topic,
      "description" => "Lorem ipsum dolor",
      "url"         => "slug-for-topic-" . $topic,
      "children"    => array_map($generate_sub_topics, range(0, $subs)),
    );
  };
}

function generate_sub_topics($topic) {
  return function ($val) use ($topic) {

    $sub_topic = $val + 1;

    return array(
      "ID"          => $sub_topic + 1 * 128 * 1000,
      "title"       => "Sub Topic " . $sub_topic,
      "description" => "Ipsum dolor sit amet belonging to topic " . $topic,
      "url"         => "slug-for-topic-" . $topic . "-sub-topic-" . $sub_topic,
    );
  };
}

function get_data($topics, $subs) {
  $data = range(0, $topics - 1);
  $generate_topics = generate_topics($subs - 1);
  return array_map($generate_topics, $data);
}


/* DATA PARSING */

function parse_data($val) {
  if (array_key_exists('children', $val)) {
    $subs = "<ul>" . implode("", array_map( "parse_data", $val['children'] ) ) . "</ul>\n";
  }
  return "<li><a id='" . $val['ID'] . "'href='" . $val['url'] . "' title='" . $val['description'] . "'>" . $val['title'] . "</a>" . $subs . "</li>\n";
}

function generate_html() {
  return implode( "", ["<ul>", implode("", array_map( "parse_data", get_data(18, 10) ) ), "</ul>"] );
}


/* Make it rain */

echo generate_html();
