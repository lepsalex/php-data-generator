<?php

/* Config */

define(__TOPICS__, 18);
define(__SUBS__, 10);


/* DATA GENERATION */

function generate_topics($subs) {

  // Return a closure that we can call (so we can define number of sub-topics)
  return function($val) use ($subs) {

    // Add one to topic so it display's 1-indexed
    $topic = $val + 1;

    // Create sub-topic generator for this topic (similar to topics generator)
    $generate_sub_topics = generate_sub_topics($topic);

    // Return an array to the calling mapper (resulting in an array of arrays data structure)
    $topics = array(
      "ID"          => $topic * 128 * 1000,
      "title"       => "Topic " . $topic,
      "description" => "Lorem ipsum dolor",
      "url"         => "slug-for-topic-" . $topic,
    );

    // If sub-topics are requested then build that array and attach in "children" key
    if ($subs >= 0) {
      return $topics + array(
          "children"    => array_map( $generate_sub_topics, range(0, $subs) )
      );
    }

    // Default just return the topic array entry
    return $topics;
  };
}

function generate_sub_topics($topic) {

  // Again, closure, just so we can pass in the parent topic number for display
  return function ($val) use ($topic) {

    // Add one to sub-topic so it display's 1-indexed
    $sub_topic = $val + 1;

    // Construct return array, showing relation to parent topic
    return array(
      "ID"          => $sub_topic + 1 * 128 * 1000,
      "title"       => "Sub Topic " . $sub_topic,
      "description" => "Ipsum dolor sit amet belonging to topic " . $topic,
      "url"         => "slug-for-topic-" . $topic . "-sub-topic-" . $sub_topic,
    );
  };
}

function get_data($topics, $subs) {

  // Create array of size $topics with initial values enumerated
  $data = range(0, $topics - 1);

  // Create generator (passing in number of sub topics per topic)
  $generate_topics = generate_topics($subs - 1);

  // Generate the data
  return array_map($generate_topics, $data);
}


/* DATA PARSING */

function parse_data($val) {

  // If the array entry has a children key then build out the subs for that array otherwise return an empty string
  $subs = array_key_exists('children', $val) ? "<ul>" . implode("", array_map( "parse_data", $val['children'] ) ) . "</ul>\n" : "";

  // Return a string value for each array entry containing the parsed html (will be imploded by calling function)
  return "<li><a id='" . $val['ID'] . "'href='" . $val['url'] . "' title='" . $val['description'] . "'>" . $val['title'] . "</a>" . $subs . "</li>\n";
}

function generate_html() {

  // Imploding an array of html elements
  return implode( "", ["<ul>", implode("", array_map( "parse_data", get_data(__TOPICS__, __SUBS__) ) ), "</ul>"] );
}


/* Make it rain */

echo generate_html();
