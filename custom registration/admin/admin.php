
 

<?php

/**
* 
* Backend Functionality
*
**/

function wordpress_custom_registration_form( $first_name, $last_name, $username, $password, $email) {
    global $username, $password, $email, $first_name, $last_name;
   echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    First Name :
    <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
    
    Last Name:
    <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
    
Email: <strong>*</strong>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">

    Phone no: <strong>*</strong>
    <input type="text" name="phoneno" value="' . ( isset( $_POST['phoneno']) ? $phoneno : null ) . '">
    
    Password <strong>*</strong>
    <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
    
    <br>


    <legend>Choose your gender:</legend>
        <label for="male">Male</label>
        <input type="radio" name="gender" id="male" value="male" checked>
        <label for="female">Female</label>
        <input type="radio" name="gender" id="female" value="female"><br>


<legend>Choose your Skills:</legend>
<input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
  <label for="vehicle1"> skill 1</label>
  <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
  <label for="vehicle2"> skill 2</label>
  <input type="checkbox" id="vehicle3" name="vehicle3" value="Boat">
  <label for="vehicle3"> skill 3</label>

        <label>BIO</label>
<textarea id="w3review" name="w3review" rows="4" cols="50">
  
  </textarea>

   
   <input type="submit" name="submit" value="Register"/>
    </form>
    ';
}
function wp_reg_form_valid( $username, $password, $email)  {
    global $customize_error_validation;
    $customize_error_validation = new WP_Error;
    if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
        $customize_error_validation->add('field', ' Please Fill the filed of WordPress registration form');
    }
    if ( username_exists( $username ) )
        $customize_error_validation->add('user_name', ' User Already Exist');
    if ( is_wp_error( $customize_error_validation ) ) {
        foreach ( $customize_error_validation->get_error_messages() as $error ) {
         echo '<strong>Hold</strong>:';
         echo $error . '<br/>';
        }
    }
}
 
function wordpress_user_registration_form_completion() {
    global $customize_error_validation, $username, $password, $email, $first_name, $last_name;
    if ( 1 > count( $customize_error_validation->get_error_messages() ) ) {
        $userdata = array(
         'first_name' =>   $first_name,
         'last_name' =>   $last_name,
         'user_login' =>   $username,
         'user_email' =>   $email,
         'user_pass' =>   $password,
 
        );
        $user = wp_insert_user( $userdata );
        echo 'Complete WordPress Registration. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
    }
}
function wordpress_custom_registration_form_function() {
    global $first_name, $last_name,$username, $password, $email ;
    if ( isset($_POST['submit'] ) ) {
        wp_reg_form_valid(
         $_POST['username'],
         $_POST['password'],
         $_POST['email'],
         $_POST['fname'],
         $_POST['lname']
       );
 
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email   =   sanitize_email( $_POST['email'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
       wordpress_user_registration_form_completion(
         $username,
         $password,
         $email,
         $first_name,
         $last_name
        );
    }
    wordpress_custom_registration_form(
        $username,
        $password,
        $email,
        $first_name,
        $last_name
    );
}
 
add_shortcode( 'custom_registration', 'custom_registration_shortcode' );
 
function custom_registration_shortcode() {
    ob_start();
    wordpress_custom_registration_form_function();
    return ob_get_clean();
}
 

add_filter( 'registration_errors', 'custom_validation_error_method', 10, 2 );
function custom_validation_error_method( $errors, $lname, $last_name ) {
 
    if ( empty( $_POST['fname'] ) || ( ! empty( $_POST['fname'] ) && trim( $_POST['fname'] ) == '' ) ) {
        $errors->add( 'fname_error', __( '<strong>Error</strong>: Enter Your First Name.' ) );
    }
 
    if ( empty( $_POST['lname'] ) || ( ! empty( $_POST['lname'] ) && trim( $_POST['lname'] ) == '' ) ) {
        $errors->add( 'lname_error', __( '<strong>Error</strong>: Enter Your Last Name.' ) );
    }
    return $errors;
}

