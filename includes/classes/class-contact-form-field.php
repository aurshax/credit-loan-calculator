<?php
namespace CreditLoanCalculator\Classes;

use DateTime;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use WPCF7_FormTag;
use WPCF7_Submission;

/**
 *  Class Close CF7 Form Integration
 */
class ContactFormField
{

    /**
     *  Constructor
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'init_assets'));

        add_action('wpcf7_init', array($this, 'add_form_field'));

        add_filter('wpcf7_validate_clc_phone', array($this, 'validate_phone'), 10, 2);
        add_filter('wpcf7_validate_clc_phone*', array($this, 'validate_phone'), 10, 2);

        add_filter('wpcf7_validate_clc_name', array($this, 'validate_name'), 10, 2);
        add_filter('wpcf7_validate_clc_name*', array($this, 'validate_name'), 10, 2);

        add_filter('wpcf7_validate_clc_date', array($this, 'validate_date'), 10, 2);
        add_filter('wpcf7_validate_clc_date*', array($this, 'validate_date'), 10, 2);

        add_action('wpcf7_before_send_mail', array($this, 'create_lead'));

    }

    public function init_assets()
    {
        wp_enqueue_style('clc-register');
        wp_enqueue_script('jquery-mask', CLC_ASSETS . '/js/jquery.mask.min.js', array('jquery'), null, true);
    }

    /**
     * Add form Field
     *
     * @return void
     */
    public function add_form_field()
    {
        wpcf7_add_form_tag(
            array('clc_phone*', 'clc_phone'),
            array($this, 'add_form_tag_handler'),
            array('name-attr' => true)
        );

        wpcf7_add_form_tag(
            array('clc_date*', 'clc_date'),
            array($this, 'add_date_form_tag_handler'),
            array('name-attr' => true)
        );

        wpcf7_add_form_tag(
            array('clc_name*', 'clc_name'),
            array($this, 'add_name_form_tag_handler'),
            array('name-attr' => true)
        );

        wpcf7_add_form_tag(
            array('clc_price'),
            array($this, 'add_price_form_tag_handler')
        );
    }

    /**
     * Add Form HTML
     *
     * @param $tag
     * @return string
     */
    public function add_form_tag_handler($tag)
    {
        if (empty($tag->name)) {
            return '';
        }

        $atts = array();

        $validation_error = wpcf7_get_validation_error($tag->name);

        $class = wpcf7_form_controls_class($tag->type);
        $class .= ' clc-phone-form-control-wrap ' . $tag->get_option('class', 'class', true);

        if ($validation_error) {
            $class .= ' wpcf7-not-valid';
        }

        $atts['id'] = $tag->get_id_option();
        $atts['class'] = $tag->get_class_option($class);
        $atts['tabindex'] = $tag->get_option('tabindex', 'int', true);

        $value = (string)reset($tag->values);

        if ($tag->has_option('readonly')) {
            $atts['readonly'] = 'readonly';
        }

        if ($tag->is_required()) {
            $atts['aria-required'] = 'true';
        }

        if ($tag->has_option('placeholder')) {
            $atts['placeholder'] = $value;
            $value = '';

        }

        $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

        $atts = wpcf7_format_atts($atts);

        return sprintf(
            '<span class="wpcf7-form-control-wrap %1$s" data-name="%2$s"><input %3$s value="%4$s" data-mask="+(84) 00 000 0000" type="tel" name="%5$s" />%6$s</span>',
            sanitize_html_class($tag->name), esc_attr($tag->name), $atts, esc_attr($value), esc_attr($tag->name), $validation_error
        );
    }

    /**
     * Add Form HTML
     *
     * @param $tag
     * @return string
     */
    public function add_date_form_tag_handler($tag)
    {
        if (empty($tag->name)) {
            return '';
        }

        $atts = array();

        $validation_error = wpcf7_get_validation_error($tag->name);

        $class = wpcf7_form_controls_class($tag->type);
        $class .= ' clc-date-form-control-wrap ' . $tag->get_option('class', 'class', true);

        if ($validation_error) {
            $class .= ' wpcf7-not-valid';
        }

        $atts['id'] = $tag->get_id_option();
        $atts['class'] = $tag->get_class_option($class);
        $atts['tabindex'] = $tag->get_option('tabindex', 'int', true);

        $value = (string)reset($tag->values);

        if ($tag->has_option('readonly')) {
            $atts['readonly'] = 'readonly';
        }

        if ($tag->is_required()) {
            $atts['aria-required'] = 'true';
        }

        if ($tag->has_option('placeholder')) {
            $atts['placeholder'] = $value;
            $value = '';

        }

        $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

        $atts = wpcf7_format_atts($atts);

        return sprintf(
            '<span class="wpcf7-form-control-wrap %1$s" data-name="%2$s"><input %3$s value="%4$s" type="text" name="%5$s" />%6$s</span>',
            sanitize_html_class($tag->name), esc_attr($tag->name), $atts, esc_attr($value), esc_attr($tag->name), $validation_error
        );
    }

    /**
     * Add Form HTML
     *
     * @param $tag
     * @return string
     */
    public function add_name_form_tag_handler($tag)
    {
        if (empty($tag->name)) {
            return '';
        }

        wp_enqueue_script('clc-register');

        $atts = array();

        $validation_error = wpcf7_get_validation_error($tag->name);

        $class = wpcf7_form_controls_class($tag->type);
        $class .= ' clc-name-form-control-wrap ' . $tag->get_option('class', 'class', true);

        if ($validation_error) {
            $class .= ' wpcf7-not-valid';
        }

        $atts['id'] = $tag->get_id_option();
        $atts['class'] = $tag->get_class_option($class);
        $atts['tabindex'] = $tag->get_option('tabindex', 'int', true);

        $value = (string)reset($tag->values);

        if ($tag->has_option('readonly')) {
            $atts['readonly'] = 'readonly';
        }

        if ($tag->is_required()) {
            $atts['aria-required'] = 'true';
        }

        if ($tag->has_option('placeholder')) {
            $atts['placeholder'] = $value;
            $value = '';

        }

        $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

        $atts = wpcf7_format_atts($atts);

        return sprintf(
            '<span class="wpcf7-form-control-wrap %1$s" data-name="%2$s"><input %3$s value="%4$s" type="text" name="%5$s" />%6$s</span>',
            sanitize_html_class($tag->name), esc_attr($tag->name), $atts, esc_attr($value), esc_attr($tag->name), $validation_error
        );
    }

    /**
     * Add Form HTML
     *
     * @param $tag
     * @return string
     */
    public function add_price_form_tag_handler($tag) {
        return '<span class="wpcf7-form-control-wrap clc-loan-amount-wrap" data-name="clc_loan_amount"><input value="" type="hidden" name="clc_loan_amount" /></span>';
    }

    /**
     * Create the Lead
     *
     * @param $form
     * @return void
     */
    public function create_lead($form)
    {
        $form_id = $form->id();

        $cf_id = get_clc_settings('clc-contact-form-id', 0);

        if ($form_id == $cf_id) {
            //855-569-274
            $submission = WPCF7_Submission::get_instance();

            if ($submission) {
                $posted_data = $submission->get_posted_data();

                $birthday = '';

                $request = [
                    "email" => "",
                    "name" => "",
                    "first_name" => "",
                    "phone" => "",
                    "birthday" => "",
                    "loan" => "",
                    "created_from" => "website",
                ];

                if(isset($posted_data["your-email"])) {
                    $request["email"] = $posted_data["your-email"];
                }

                if(isset($posted_data["full-name"])) {
                    $request["name"] = $posted_data["full-name"];
                    $request["first_name"] = $posted_data["full-name"];
                }

                if(isset($posted_data["your-phone"])) {
                    $request["phone"] = $posted_data["your-phone"];
                }

                if(isset($posted_data["your-birthday"])) {
                    $datetime = new DateTime();

                    $birthday = $posted_data["your-birthday"];

                    list($day, $month, $year) = explode('/', $posted_data["your-birthday"]);

                    $datetime->setDate($year, $month, $day);

                    $request["birthday"] = $datetime->format('y-m-d h:i:s');
                }

                if(isset($posted_data["clc_loan_amount"])) {
                    $request["loan"] = $posted_data["clc_loan_amount"];
                }

                $cookie_data = array(
                    "email" => $request['email'],
                    "name" => $request['name'],
                    "phone" => $request['phone'],
                    "birthday" => $birthday,
                    "loan" => $request["loan"]
                );

                $json_data = json_encode( $cookie_data );

                setcookie( 'register_cf7_data', $json_data, time() + (24 * 3600), '/' );

                $this->save_to_db($request);

                $this->send_to_smally($request);

            }
        }
    }

    /**
     * Validate Phone
     *
     * @param $result
     * @param $tag
     * @return mixed
     */
    public function validate_phone($result, $tag)
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        $tag = new WPCF7_FormTag($tag);

        // Retrieve the submitted phone number
        $phone = isset($_POST[$tag->name]) ? trim(wp_unslash(str_replace(array("\n", "\r"), '', $_POST[$tag->name]))) : '';

        if(empty($phone)) {
            $result->invalidate($tag, esc_html("Vui lòng điền vào trường này."));
            return $result;
        }

        try {
            $parsedPhoneNumber = $phoneNumberUtil->parse($phone, 'VN');

            // Check if the phone number is valid for the Vietnam
            if (!$phoneNumberUtil->isValidNumber($parsedPhoneNumber)) {
                $result->invalidate($tag, esc_html("Xin vui lòng nhập một số điện thoại hợp lệ.") );
                return $result;
            }
        } catch (NumberParseException $e) {
            $result->invalidate($tag, esc_html("Xin vui lòng nhập một số điện thoại hợp lệ."));
            return $result;
        }

        return $result;
    }

    /**
     * Validate Name
     *
     * @param $result
     * @param $tag
     * @return mixed
     */
    public function validate_name($result, $tag)
    {
        $tag = new WPCF7_FormTag($tag);

        // Retrieve the submitted phone number
        $name = isset($_POST[$tag->name]) ? trim(wp_unslash(str_replace(array("\n", "\r"), '', $_POST[$tag->name]))) : '';

        if(empty($name)) {
            $result->invalidate($tag, esc_html("Vui lòng điền vào trường này."));
            return $result;
        }

        return $result;
    }

    /**
     * Validate Date
     *
     * @param $result
     * @param $tag
     * @return mixed
     */
    public function validate_date($result, $tag)
    {
        $tag = new WPCF7_FormTag($tag);

        // Retrieve the submitted phone number
        $date = isset($_POST[$tag->name]) ? trim(wp_unslash(str_replace(array("\n", "\r"), '', $_POST[$tag->name]))) : '';

        if(empty($date)) {
            $result->invalidate($tag, esc_html("Vui lòng điền vào trường này."));
            return $result;
        }

        list($day, $month, $year) = explode('/', $date);

        if (!checkdate($month, $day, $year)) {
            $result->invalidate($tag, esc_html("Ngày không hợp lệ"));
            return $result;
        }

        $now = new DateTime();
        $birthday    = new DateTime($month . '/' . $day . '/' . $year);

        if ($birthday > $now) {
            $result->invalidate($tag, esc_html("Ngày không hợp lệ"));
            return $result;
        }

        return $result;
    }

    /**
     * Save to DB
     *
     * @param array $data Data.
     * @return void
     */
    public function save_to_db($data) {
        $req = array(
            'post_title'    => wp_strip_all_tags( $data['email'] ),
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'submission',
        );

        $post_id = wp_insert_post($req);

        add_post_meta($post_id, 'name', $data['name'], true);
        add_post_meta($post_id, 'email', $data['email'], true);
        add_post_meta($post_id, 'phone', $data['phone'], true);
        add_post_meta($post_id, 'birthday', $data['birthday'], true);
        add_post_meta($post_id, 'loan', $data['loan'], true);

    }

    /**
     * Sent data to Smally
     *
     * @param array $data Data.
     * @return void
     */
    public function send_to_smally($data) {
        $domain = get_clc_settings('smally-subdomain', '');
        $username = get_clc_settings('smally-username', '');
        $password = get_clc_settings('smally-password', '');

        if(empty($domain) || empty($username) || empty($password)) {
            return;
        }

        $api = new SmailyApiIntegration($domain, $username, $password);

        $api->create_subscriber($data);
    }
}
