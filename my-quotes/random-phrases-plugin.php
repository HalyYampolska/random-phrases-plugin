<?php
/*
Plugin Name: Random Phrases Plugin
Description: Plugin for displaying random phrases on a custom page. Makes the page more dynamic and interesting.
Version: 1.0
Author: Halyna Yampolska
*/

// Choosed random phrase 
function random_phrase_content() {
     
    if (is_singular( 'recipe' )) {
        $phrases = get_phrases_from_json();
        $random_phrase = $phrases[array_rand($phrases)];
        echo '<div class="random-phrase">' . $random_phrase . '</div>';
    }
}

// Readed from JSON
function get_phrases_from_json() {
    // Way to the JSON
    $file_path = plugin_dir_path(__FILE__) . 'phrases.json';

    // Checked JSON file exist 
    if (file_exists($file_path)) {
        // Readed JSON file
        $json_data = file_get_contents($file_path);

        // Turn on JSON file in array
        $phrases = json_decode($json_data);

        if ($phrases) {
            return $phrases;
        }
    }

    return array(); // If empty - return zero
}

function add_quote_admin_page() {
    add_menu_page('Quotes Rules', 'Quotes', 'manage_options', 'quote_admin_page', 'quote_admin_page_callback');
}

function quote_admin_page_callback() {
    if (isset($_POST['quote_text'])) {
        $quote_text = sanitize_text_field($_POST['quote_text']);

        // Give a array
        $file_path = plugin_dir_path(__FILE__) . 'phrases.json';
        $phrases = json_decode(file_get_contents($file_path));

        // Add new quote
        $phrases[] = $quote_text;

        // Write new phrase in array
        file_put_contents($file_path, json_encode($phrases));

        echo '<div class="updated"><p>It is great! You phrase is added to Database.</p></div>';
    }
    ?>
    <div class="wrap">
        <h2>Add new quote</h2>
        <form method="post" action="">
            <label for="quote_text">Quote:</label><br>
            <textarea id="quote_text" name="quote_text" rows="4" style="width: 100%;"></textarea><br>
            <input type="submit" class="button-primary" value="Add quote">
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'add_quote_admin_page');


?>
