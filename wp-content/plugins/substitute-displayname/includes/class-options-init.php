<?php

new Options();

class Options {
    private $displayNameOptions = array(
        'nickname'           => 'Nickname',
        'email'              => 'Email',
        'username'           => 'Username',
        'firstname_lastname' => 'First Name + Last Name',
        'lastname_firstname' => 'Last Name + First Name',
        'lastname'           => 'Last Name',
        'firstname'          => 'First Name',
    );

    private $settings;

    public function __construct() {

        add_action('admin_enqueue_scripts', array(
            $this,
            'assets'
        ));

        add_action('admin_menu', array(
            $this,
            'register_settings_page'
        ), 5);

        $this->setSettings(json_decode(get_option('display_name_settings', ''), true));
        $this->setAjaxHandlers();
        $this->setDefaultNames();

    }

    private function setSettings($settings) {
        $this->settings = $settings;
    }

    private function setDefaultNames() {
        add_action('user_register', array(
            $this,
            'setUserDefaultName'
        ));
    }

    public function setUserDefaultName($newUserID) {
        $namesSettings = json_decode(get_option('display_name_settings', ''), true);
        $user = get_userdata($newUserID);
        $this->setDisplayName($user, $namesSettings);
    }

    private function setDisplayName($user, $namesSettings) {
        $userRole = $user->roles[0];
        $userDisplayNameSlug = isset($namesSettings[$userRole]['display_name']) ? $namesSettings[$userRole]['display_name'] : 'nickname';

        switch ($userDisplayNameSlug) {
            case "nickname":
                $displayName = $user->nickname;
                break;
            case "email":
                $displayName = $user->user_email;
                break;
            case "username":
                $displayName = $user->user_login;
                break;
            case "firstname_lastname":
                $displayName = $user->user_firstname . ' ' . $user->user_lastname;
                break;
            case "lastname_firstname":
                $displayName = $user->user_lastname . ' ' . $user->user_firstname;
                break;
            case "lastname":
                $displayName = $user->user_lastname;
                break;
            case "firstname":
                $displayName = $user->user_firstname;
                break;
            default:
                $displayName = $user->nickname;
                break;
        }

        wp_update_user(array(
            "ID"           => $user->ID,
            "display_name" => $displayName
        ));
    }

    private function setAjaxHandlers() {
        add_action('wp_ajax_setNewDisplayNamesSettings', array(
            $this,
            'setNewDisplayNamesSettings'
        ));

        add_action('wp_ajax_getPopupContent', array(
            $this,
            'getPopupContent'
        ));


    }

    public function setNewDisplayNamesSettings() {
        $newSettingsString = isset($_POST['settings']) && !empty($_POST['settings']) ? json_encode($_POST['settings']) : '';
        $isUpdated = update_option('display_name_settings', $newSettingsString);
        $newSettings = json_decode($newSettingsString, true);

//        if ($isUpdated) {
            foreach ($newSettings as $role => $roleSettings) {
                if ($roleSettings['apply_to_current'] === 'true') {
                    $users = get_users(array(
                        'role' => $role
                    ));
                    foreach ($users as $user) {
                        $this->setDisplayName($user, $newSettings);
                    }
                }
            }
//        }
        die();
    }

    public function getPopupContent() {
        $messageDeferred = "<h2 style='text-align: center;'>This change will apply to all current users in this role. This action can't be undone.</h2><h4 style='text-align: center;'>Are you sure you want to continue?</h4>";
        $messageDefault = "<h2 style='text-align: center;'><h2 style='text-align: center;'>Are you sure you want to change this settings?</h2>";
        $successButton = '<button style="margin: 0 5px;" class="button button-primary button-large">Ok</button>';
        $cancelButton = '<button style="margin: 0 5px;" class="button button-large" data-popup-close>Cancel</button>';
        print_r(json_encode(array(
            'save_display_names_checked' => array(
                'content'   => '<form id="save_display_names_checked" style="text-align: center;">' . $messageDeferred . $successButton . $cancelButton.'</form>'
            ),
            'save_display_names_unchecked' => array(
                'content'   => '<form id="save_display_names_unchecked" style="text-align: center;">' . $messageDefault . $successButton . $cancelButton.'</form>'
            )
        )));
        die();
    }

    public function register_settings_page() {
        $parent_slug = 'users.php';
        $page_title = __('Publicity Names');
        $menu_title = __('Publicity Names');
        $capability = 'manage_options';
        $menu_slug = 'publicity-names';
        $function = array(
            $this,
            'renderContent'
        );

        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
    }

    public function assets($suffix) {
        if ($suffix == 'user-edit.php' || $suffix == 'profile.php') {

            $userID = isset($_GET['user_id']) ? $_GET['user_id'] : get_current_user_id();
            $user_info = get_userdata($userID);
            $user_mail = $user_info->user_email;
            $role = $user_info->roles[0];
            $options = json_decode(get_option('display_name_settings'), true);
            wp_enqueue_script('main-js', plugins_url('/substitute-displayname/assets/scripts/main.js'), '', false, true);
            wp_localize_script('main-js', 'userInfo', array(
                'id'    => $userID,
                'email' => $user_mail
            ));
        }
        if ($suffix === 'users_page_publicity-names') {
            wp_enqueue_script('popup-handler', plugins_url('/substitute-displayname/assets/libs/popup-handler.js'), array('jquery'), false, true);
            wp_enqueue_script('set-names-js', plugins_url('/substitute-displayname/assets/scripts/set-names.js'), array('jquery'), false, true);
            wp_localize_script('set-names-js', 'ajax', array(
                'url' => admin_url('admin-ajax.php')
            ));
        }
    }

    public function renderContent() {
        ?>
        <div class = "wrapp">
            <h1>Publicity Names</h1>
            <div class = "table__inner">
                <h2>Change default Publisity Display Name As:</h2>
                <table class = "form-table display-names-table">
                    <tr>
                        <th>Roles</th>
                        <th>Names</th>
                        <th style = "text-align: center;">Apply to current users?</th>
                    </tr>

                    <?php
                    $userRoles = $this->getUserRoles();
                    $options = $this->getUserOptions();
                    foreach ($userRoles as $slug => $name) {
                        $selectedName = isset($this->settings[$slug]['display_name']) ? $this->settings[$slug]['display_name'] : '';
                        $isChecked = isset($this->settings[$slug]['apply_to_current']) && $this->settings[$slug]['apply_to_current'] === 'true' ? 'checked' : '';
                        ?>
                        <tr data-user-role = "<?php echo $slug; ?>">
                                <td><?php echo $name; ?></td>
                                <td>
                                    <select data-val="<?php echo $selectedName; ?>" name = "<?php echo $slug; ?>_display_name" data-display-name
                                            id = "<?php echo $slug; ?>_display_name">
                                        <?php echo str_replace("value='$selectedName'", "value='$selectedName' selected", $options); ?>
                                    </select>
                                </td>
                                <td style = "text-align: center;">
                                    <input data-apply-to-current type = "checkbox" data-val="<?php echo $isChecked; ?>" name = "<?php echo $slug; ?>_current_users"
                                           value = "true" <?php echo $isChecked; ?> />
                                </td>
                            </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <div style = "text-align: center;margin-top: 20px;">
                <button id = "save_display_names" class = "button button-large" data-deferred-popup="save_display_names_checked" data-popup = "save_display_names_unchecked">Save</button>
            </div>
        </div>
        <?php
    }

    private function getUserRoles() {
        return wp_roles()->role_names;
    }

    private function getUserOptions() {
        $options = $this->displayNameOptions;
        $output = '';
        foreach ($options as $option_slug => $option_name) {
            $output .= "<option value='$option_slug'>$option_name</option>";
        }

        return $output;
    }

}