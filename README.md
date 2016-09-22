#php-data-generator

Define number of topics and sub-topics at top of file.

Provides functions to generates a mock data set of nested arrays two-levels deep currently labeled topics and sub-topics - keys
can be changed to suit the project as long as you are consistent between generate_topics, generate_sub_topics and parse_data.
I may make this more general/configurable in the future.

Included is a parser that reads the data structure and created what could be a menu (nested `<ul>`'s with `<a>`'s within `<li>`'s) and
will work for whatever depth the data structure has (currently determined by looking for the key "children")
