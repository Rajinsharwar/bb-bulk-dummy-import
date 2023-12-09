<?php
/*
Plugin Name: BB Massive Dummy Importer
Description: WordPress plugin for generating a huge amount of dummy data for BuddyBoss.
Version: 1.0
Author: Rajin Sharwar
*/

// Add a menu item under the Tools menu
add_action('admin_menu', 'bb_massive_dummy_plugin_menu');
function bb_massive_dummy_plugin_menu() {
    add_submenu_page(
        'tools.php',
        'BB Massive Dummy',
        'BB Massive Dummy',
        'manage_options',
        'bb-massive-dummy',
        'bb_massive_dummy_page'
    );
}

// Callback function to display the plugin page content.
function bb_massive_dummy_page() {
    ?>
    <div class="wrap">
        <h1>BB Massive Dummy Importer</h1>
        <form method="post" action="">
            <?php wp_nonce_field('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce'); ?>
            <h3>Generate Dummy Users</h3>
            <p>
                <label for="num_users">Number of Users:</label>
                <input type="number" id="num_users" name="num_users" min="1" value="1000">
            </p>
            <p>
                <input type="submit" class="button button-primary" name="generate_dummy_users" value="Generate Dummy Users">
            </p>
            <hr size="3" width="100%" color="ash">
            <h3>Generate Dummy Activity</h3>
            <p>
                <label for="user_id_for_activity">User ID to generate activity for:</label>
                <input type="number" id="user_id_for_activity" name="user_id_for_activity" min="1">
            </p>
            <p>
                <label for="num_activities">Number of Activities to generate:</label>
                <input type="number" id="num_activities" name="num_activities" min="1" value="1000">
            </p>
            <p>
                <input type="submit" class="button button-primary" name="generate_activity" value="Generate Activity for User">
            </p>
            <hr size="3" width="100%" color="ash">
            <h3>Generate Dummy Groups</h3>
            <p>
                <label for="num_groups">Number of Groups:</label>
                <input type="number" id="num_groups" name="num_groups" min="1" value="100">
            </p>
            <p>
                <label for="group_privacy">Group Privacy:</label>
                <select id="group_privacy" name="group_privacy">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                    <option value="hidden">Hidden</option>
                </select>
            </p>
            <p>
                <input type="submit" class="button button-primary" name="generate_groups" value="Generate BuddyBoss Groups">
            </p>
            <hr size="3" width="100%" color="ash">
            <h3>Add Dummy Users to a Group</h3>
            <p>
                <label for="group_id">Group ID to add users:</label>
                <input type="number" id="group_id" name="group_id" min="1">
            </p>
            <p>
                <label for="num_users_to_add_to_group">Number of Users to add to the group:</label>
                <input type="number" id="num_users_to_add_to_group" name="num_users_to_add_to_group" min="1" value="1000">
            </p>
            <em>Make sure you have equal or more than the users you want to add in the group.</em>
            <p>
                <input type="submit" class="button button-primary" name="add_users_to_group" value="Add Users to Group">
            </p>
            <hr size="3" width="100%" color="ash">
            <h3>Generate Dummy Forums</h3>
            <p>
                <label for="num_forums">Number of Forums:</label>
                <input type="number" id="num_forums" name="num_forums" min="1" value="5">
            </p>
            <p>
                <label for="forum_privacy">Forum Privacy:</label>
                <select id="forum_privacy" name="forum_privacy">
                    <option value="publish">Publish</option>
                    <option value="private">Private</option>
                    <option value="hidden">Hidden</option>
                </select>
            </p>
            <p>
                <input type="submit" class="button button-primary" name="generate_forums" value="Generate BuddyBoss Forums">
            </p>
            <hr size="3" width="100%" color="ash">
            <h3>Generate Dummy Discussions for a Forum</h3>
            <p>
                <label for="forum_id">Forum ID for Discussions:</label>
                <input type="number" id="forum_id" name="forum_id" min="1" value="1">
            </p>
            <p>
                <label for="num_discussions">Number of Discussions:</label>
                <input type="number" id="num_discussions" name="num_discussions" min="1" value="10">
            </p>
            <p>
                <input type="submit" class="button button-primary" name="generate_discussions" value="Generate BuddyBoss Discussions">
            </p>
            <hr size="3" width="100%" color="ash">
            <h3>Generate Dummy Replies for a Forum and Discussion</h3>
            <p>
                <label for="forum_id">BuddyBoss Forum ID:</label>
                <input type="number" id="forum_id" name="forum_id" min="1" value="1">
            </p>
            <p>
                <label for="discussion_id">BuddyBoss Discussion ID:</label>
                <input type="number" id="discussion_id" name="discussion_id" min="1" value="1">
            </p>
            <p>
                <label for="num_replies">Number of Replies:</label>
                <input type="number" id="num_replies" name="num_replies" min="1" value="5">
            </p>
            <p>
                <input type="submit" class="button button-primary" name="generate_replies" value="Generate BuddyBoss Replies">
            </p>
            <hr size="3" width="100%" color="ash">
            <h3>Make all users Active</h3>
            <p>
                <input type="submit" class="button button-primary" name="make_all_users_active" value="Make All Users Active">
            </p>
            <em>Note: This will mark all users as active by inserting fake activity items. The activity items will be automatically removed after 2 minutes. </br>This will not trigger actual performance by those users, but only will change the Status to Active.</em>
        </form>
        <?php
        if (isset($_POST['generate_dummy_users']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            $num_users = isset($_POST['num_users']) ? intval($_POST['num_users']) : 1000;

            // Generate dummy users here
            generate_dummy_users($num_users);
            admin_notice_success($num_users . ' dummy users generated successfully!');
        }

        if (isset($_POST['generate_activity']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            $user_id_for_activity = isset($_POST['user_id_for_activity']) ? intval($_POST['user_id_for_activity']) : 0;
            $num_activities = isset($_POST['num_activities']) ? intval($_POST['num_activities']) : 1000;

            if ($user_id_for_activity > 0) {
                // Generate specified number of random BuddyBoss activities for the specified user
                generate_dummy_activities($user_id_for_activity, $num_activities);
                admin_notice_success($num_activities . ' activities generated successfully for user ' . $user_id_for_activity);
            } else {
                admin_notice_success('Please enter a valid User ID to generate activity for.');
            }
        }

        if (isset($_POST['generate_groups']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            $num_groups = isset($_POST['num_groups']) ? intval($_POST['num_groups']) : 100;
            $group_privacy = isset($_POST['group_privacy']) ? sanitize_text_field($_POST['group_privacy']) : 'public';

            // Generate specified number of BuddyBoss groups
            generate_buddyboss_groups($num_groups, $group_privacy);
            admin_notice_success($num_groups . ' BuddyBoss groups generated successfully!');
        }

        if (isset($_POST['add_users_to_group']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
            $num_users_to_add_to_group = isset($_POST['num_users_to_add_to_group']) ? intval($_POST['num_users_to_add_to_group']) : 1000;

            if ($group_id > 0) {
                // Add specified number of random users to the specified BuddyBoss group
                add_users_to_group($group_id, $num_users_to_add_to_group);
                admin_notice_success($num_users_to_add_to_group . ' users added successfully to group ' . $group_id);
            } else {
                admin_notice_success('Please enter a valid Group ID to add users to.');
            }
        }

        if (isset($_POST['generate_forums']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            $num_forums = isset($_POST['num_forums']) ? intval($_POST['num_forums']) : 5;
            $forum_privacy = isset($_POST['forum_privacy']) ? sanitize_text_field($_POST['forum_privacy']) : 'publish';

            // Generate specified number of BuddyBoss forums
            generate_buddyboss_forums($num_forums, $forum_privacy);
            admin_notice_success($num_forums . ' BuddyBoss forums generated successfully!');
        }

        if (isset($_POST['generate_discussions']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            $forum_id = isset($_POST['forum_id']) ? intval($_POST['forum_id']) : 1;
            $num_discussions = isset($_POST['num_discussions']) ? intval($_POST['num_discussions']) : 10;

            // Generate specified number of discussions for the specified forum
            generate_buddyboss_discussions($forum_id, $num_discussions);
            admin_notice_success($num_discussions . ' BuddyBoss discussions generated successfully for Forum ID ' . $forum_id);
        }

        if (isset($_POST['generate_replies']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            $forum_id = isset($_POST['forum_id']) ? intval($_POST['forum_id']) : 1;
            $discussion_id = isset($_POST['discussion_id']) ? intval($_POST['discussion_id']) : 1;
            $num_replies = isset($_POST['num_replies']) ? intval($_POST['num_replies']) : 5;

            // Generate specified number of BuddyBoss replies for the specified discussion
            generate_buddyboss_replies($forum_id, $discussion_id, $num_replies);
            admin_notice_success($num_replies . ' BuddyBoss replies generated successfully for Discussion ID ' . $discussion_id);
        }

        if (isset($_POST['make_all_users_active']) && check_admin_referer('bb_massive_dummy_nonce', 'bb_massive_dummy_nonce')) {
            make_all_users_active();
            admin_notice_success('All users marked as active successfully!');
        }
        ?>
    </div>
    <?php
}

function admin_notice_success($message) {
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
}

// Function to generate dummy users
function generate_dummy_users($num_users) {
    global $wpdb;

    // Get the current counter value from the database
    $counter = get_option('dummy_users_counter', 0);

    // Generate dummy users
    for ($i = 1; $i <= $num_users; $i++) {
        $username = 'dummyuser_' . ($counter + $i);
        $password = wp_generate_password();
        $email = 'dummyuser_' . ($counter + $i) . '@example.com';
        $first_name = 'Dummy';
        $last_name = 'User ' . ($counter + $i);
        $nicename = strtolower(str_replace(' ', '-', $first_name . ' ' . $last_name));

        // Insert user data into the users table
        $wpdb->insert(
            $wpdb->users,
            array(
                'user_login' => $username,
                'user_pass' => wp_hash_password($password),
                'user_email' => $email,
                'user_nicename' => $nicename,
                'display_name' => $username,
                'user_registered' => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s')
        );

        // Retrieve the user ID of the newly inserted user
        $user_id = $wpdb->insert_id;

        // Assign a role to the user (e.g., 'subscriber')
        $wpdb->insert(
            $wpdb->usermeta,
            array(
                'user_id' => $user_id,
                'meta_key' => $wpdb->prefix . 'capabilities',
                'meta_value' => serialize(array('subscriber' => true)),
            )
        );

        // Insert user meta for first name, last name, and nicename
        $wpdb->insert(
            $wpdb->usermeta,
            array(
                'user_id' => $user_id,
                'meta_key' => 'first_name',
                'meta_value' => $first_name,
            ),
            array('%d', '%s', '%s')
        );

        $wpdb->insert(
            $wpdb->usermeta,
            array(
                'user_id' => $user_id,
                'meta_key' => 'last_name',
                'meta_value' => $last_name,
            ),
            array('%d', '%s', '%s')
        );

        $wpdb->insert(
            $wpdb->usermeta,
            array(
                'user_id' => $user_id,
                'meta_key' => 'nickname',
                'meta_value' => $username,
            ),
            array('%d', '%s', '%s')
        );
    }

    // Update the counter in the database
    update_option('dummy_users_counter', $counter + $num_users);
}

// Function to generate dummy activities for a specific user
function generate_dummy_activities($user_id, $num_activities) {
    global $wpdb;

    // Generate specified number of dummy activities for the specified user
    for ($i = 1; $i <= $num_activities; $i++) {
        $wpdb->insert(
            $wpdb->prefix . 'bp_activity',
            array(
                'user_id' => $user_id,
                'component' => 'activity',
                'type' => 'activity_update',
                'action' => 'posted an update',
                'content' => 'This is a dummy activity update #' . $i,
                'primary_link' => '',
                'date_recorded' => current_time('mysql'),
                'hide_sitewide' => 0,
            )
        );
    }
}

function generate_buddyboss_groups($num_groups, $group_privacy) {
    for ($i = 1; $i <= $num_groups; $i++) {
        $group_name = 'Group ' . $i;
        $group_description = 'Description for Group ' . $i;

        // Check if the group name already exists
        if (groups_get_id(array('name' => $group_name))) {
            // If the group name exists, generate a new one
            $group_name = generate_unique_group_name($group_name);
        }

        // Create a new BuddyBoss group with the specified privacy
        $group_id = groups_create_group(array(
            'name' => $group_name,
            'description' => $group_description,
            'status' => $group_privacy,
            'creator_id' => get_current_user_id(), // Set the creator ID as the current user
        ));
    }
}

// Function to generate a unique group name
function generate_unique_group_name($base_group_name) {
    $counter = 1;
    while (groups_get_id(array('name' => $base_group_name))) {
        $counter++;
        $base_group_name = preg_replace('/_\d+$/', '', $base_group_name); // Remove the previous counter
        $base_group_name .= '_' . $counter;
    }
    return $base_group_name;
}

// Function to add users to a specific BuddyBoss group
function add_users_to_group($group_id, $num_users) {
    global $wpdb;

    // Select random user IDs that are not already members of the group
    $selected_user_ids = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT ID 
            FROM $wpdb->users 
            WHERE ID NOT IN (
                SELECT user_id FROM {$wpdb->prefix}bp_groups_members WHERE group_id = %d
            )
            ORDER BY RAND() 
            LIMIT %d",
            $group_id,
            $num_users
        )
    );

    // Add selected users to the specified BuddyBoss group
    foreach ($selected_user_ids as $user_id) {
        groups_join_group($group_id, $user_id);
    }
}

// Function to check if forum with a specific slug already exists
function forum_slug_exists($slug) {
    global $wpdb;
    $forum = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = 'forum' AND post_name = %s", $slug));
    return !empty($forum);
}

// Function to generate BuddyBoss forums with unique names and privacy options
function generate_buddyboss_forums($num_forums, $forum_privacy) {
    for ($i = 1; $i <= $num_forums; $i++) {
        $forum_name = 'Forum ' . $i;
        $forum_description = 'Description for ' . $forum_name;
        $forum_slug = sanitize_title($forum_name);

        // Check if the forum slug already exists
        if (forum_slug_exists($forum_slug)) {
            // If the forum slug exists, generate a new one
            $forum_slug = generate_unique_forum_slug($forum_slug);
        }

        // Check if the forum name already exists
        if (forum_name_exists($forum_name)) {
            // If the forum name exists, generate a new one
            $forum_name = generate_unique_forum_name($forum_name);
            // Update the forum description accordingly
            $forum_description = 'Description for ' . $forum_name;
        }

        // Create a new BuddyBoss forum with the specified privacy
        $forum_id = bbp_insert_forum(array(
            'post_title' => $forum_name,
            'post_content' => $forum_description,
            'post_author' => get_current_user_id(), // Set the author ID as the current user
            'post_name' => $forum_slug,
        ));

        // Set forum visibility in bbPress meta
        update_post_meta($forum_id, '_bbp_forum_visibility', $forum_privacy);

        // Update the post_status directly in wp_posts table for BuddyBoss privacy
        global $wpdb;
        $wpdb->update(
            $wpdb->posts,
            array('post_status' => $forum_privacy),
            array('ID' => $forum_id),
            array('%s'),
            array('%d')
        );
    }
}

// Function to check if forum with a specific name already exists
function forum_name_exists($name) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = 'forum' AND post_title = %s", $name));
}

// Function to generate a unique forum name
function generate_unique_forum_name($base_forum_name) {
    $counter = 1;
    while (forum_name_exists($base_forum_name)) {
        $counter++;
        $base_forum_name = preg_replace('/_\d+$/', '', $base_forum_name); // Remove the previous counter
        $base_forum_name .= '_' . $counter;
    }
    return $base_forum_name;
}

// Function to generate a unique forum slug
function generate_unique_forum_slug($base_forum_slug) {
    $counter = 1;
    while (forum_slug_exists($base_forum_slug)) {
        $counter++;
        $base_forum_slug = preg_replace('/_\d+$/', '', $base_forum_slug); // Remove the previous counter
        $base_forum_slug .= '_' . $counter;
    }
    return $base_forum_slug;
}

// New function to generate BuddyBoss discussions for a specific forum
function generate_buddyboss_discussions($forum_id, $num_discussions) {
    for ($i = 1; $i <= $num_discussions; $i++) {
        $discussion_title = 'Discussion ' . $i;
        $discussion_content = 'Content for ' . $discussion_title;

        // Check if the discussion title already exists
        if (discussion_title_exists($discussion_title)) {
            // If the title exists, generate a new one
            $discussion_title = generate_unique_discussion_title($discussion_title);
        }

        // Check if the discussion content already exists
        if (discussion_content_exists($discussion_content)) {
            // If the content exists, generate new content
            $discussion_content = generate_unique_discussion_content($discussion_content);
        }

        // Create a new BuddyBoss discussion for the specified forum
        $discussion_id = bbp_insert_topic(array(
            'post_title' => $discussion_title,
            'post_content' => $discussion_content,
            'post_status' => 'publish',
            'post_author' => get_current_user_id(), // Set the author ID as the current user
            'post_parent' => $forum_id, // Set the forum ID as the parent
        ));

        // Set the forum ID in the post meta
        update_post_meta($discussion_id, '_bbp_forum_id', $forum_id);
    }
}

// Function to check if discussion title already exists
function discussion_title_exists($title) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = 'topic' AND post_title = %s", $title));
}

// Function to generate a unique discussion title
function generate_unique_discussion_title($base_title) {
    $counter = 1;
    while (discussion_title_exists($base_title)) {
        $counter++;
        $base_title = preg_replace('/_\d+$/', '', $base_title); // Remove the previous counter
        $base_title .= '_' . $counter;
    }
    return $base_title;
}

// Function to check if discussion content already exists
function discussion_content_exists($content) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = 'topic' AND post_content = %s", $content));
}

// Function to generate unique discussion content
function generate_unique_discussion_content($base_content) {
    $counter = 1;
    while (discussion_content_exists($base_content)) {
        $counter++;
        $base_content .= '_' . $counter;
    }
    return $base_content;
}

// New function to generate BuddyBoss replies for a specific discussion
function generate_buddyboss_replies($forum_id, $discussion_id, $num_replies) {
    for ($i = 1; $i <= $num_replies; $i++) {
        $reply_content = 'Reply ' . $i;

        // Check if the reply content already exists
        if (reply_content_exists($reply_content)) {
            // If the content exists, generate new content
            $reply_content = generate_unique_reply_content($reply_content);
        }

        // Create a new BuddyBoss reply for the specified discussion
        $reply_id = bbp_insert_reply(array(
            'post_content' => $reply_content,
            'post_status' => 'publish',
            'post_author' => get_current_user_id(), // Set the author ID as the current user
            'post_parent' => $discussion_id, // Set the discussion ID as the parent
        ));

        // Set the forum ID and discussion ID in the post meta
        update_post_meta($reply_id, '_bbp_forum_id', $forum_id);
        update_post_meta($reply_id, '_bbp_topic_id', $discussion_id);
    }
}

// Function to check if reply content already exists
function reply_content_exists($content) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = 'reply' AND post_content = %s", $content));
}

// Function to generate unique reply content
function generate_unique_reply_content($base_content) {
    $counter = 1;
    while (reply_content_exists($base_content)) {
        $counter++;
        $base_content .= '_' . $counter;
    }
    return $base_content;
}

// New function to make all users active by inserting fake activity items
function make_all_users_active() {
    global $wpdb;

    // Get all user IDs
    $user_ids = $wpdb->get_col("SELECT ID FROM $wpdb->users");

    // Insert fake activity items for each user
    foreach ($user_ids as $user_id) {
        // Insert fake activity item for each user
        $wpdb->insert(
            $wpdb->prefix . 'bp_activity',
            array(
                'user_id' => $user_id,
                'component' => 'members',
                'type' => 'last_activity',
                'action' => 'fake_activity',
                'content' => 'Fake activity content',
                'primary_link' => '',
                'item_id' => $user_id,
                'secondary_item_id' => 0,
                'date_recorded' => current_time('mysql', true),
                'is_spam' => 0,
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%d')
        );
    }

    // Schedule an event to delete fake activities after 2 minutes
    wp_schedule_single_event(time() + 120, 'delete_fake_activities_event');
}

// Add an action to delete fake activities
add_action('delete_fake_activities_event', 'delete_fake_activities');

// Function to delete fake activities
function delete_fake_activities() {
    global $wpdb;

    // Delete fake activities from the bp_activity table
    $wpdb->query("DELETE FROM {$wpdb->prefix}bp_activity WHERE action = 'fake_activity'");
}
