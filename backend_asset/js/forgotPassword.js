(function($) {
// Forgot password prototype
  // Define our constructor
  this.ForgotPassword = function() {

    // Create global element references
    this.error = false;
    this.error_message = null;
    this.success_message = null;
    
    // Define option defaults
    var defaults = {
      sendType:   "email",
      forgotEleId: "email",
      forgotForm : "formC",
    }

    // Create options by extending defaults with the passed in arugments
    if (arguments[0] && typeof arguments[0] === "object") {
      this.options = extendDefaults(defaults, arguments[0]);
    }
    this.check();
  }


  ForgotPassword.prototype.check = function(){

    var email = $('#'+this.options.forgotEleId).val();
    var validEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(this.options.sendType=='email')
    {
      //validation code as per email address
      var forgotEleId = $('#'+this.options.forgotEleId);
      var forgotForm = $('#'+this.options.forgotForm);

      if((forgotEleId.length) && (forgotForm.length))
      {
        if(email=="")
        {
          this.requiredError();  

        }
        else if(!validEmail.test(email))
        {
          this.emailError();  
        }
        
        else
        {

        }
      }

      else
      { //Either Form Id or ElementIds are wrong
        this.devError();
      }
    }
    else if(this.options.sendType=='mobile')
      //validation code as per user
    {

      if(email=="")
      {
        this.requiredError();  

      }

      else if(isNaN(email))
      {
       this.invalidPhoneError();
     }
     else if (!(/^\d{10}$/.test(email)))
     {
      this.phoneError();
    }

  }
  else
  {
    this.devError();  
  }

}

ForgotPassword.prototype.devError = function(){
  this.error = true;
  this.error_message = 'Invalid validations has been implemented!';
}
ForgotPassword.prototype.requiredError = function(){
  this.error = true;
  this.error_message = 'Enter your Email!';
}

ForgotPassword.prototype.phoneError = function(){
  this.error = true;
  this.error_message = 'Phone number must be 10 digits!';
}
ForgotPassword.prototype.invalidPhoneError = function(){
  this.error = true;
  this.error_message = 'Phone number should be only numeric value!';
}
ForgotPassword.prototype.emailError = function(){
  this.error = true;
  this.error_message = 'Email is not valid!';
}

   // Change Password Prototype
   this.ChangePassword = function() {

    // Create global element references
    this.error = false;
    this.error_message = null;
    this.success_message = null;
    
    // Define option defaults
    var defaults = {
      oldpassEleId: "old_pass",
      newpassEleId: "new_pass",
      newpassConfirmEleId: "confirm_new_pass",
      passForm : "formchange",
    }

    // Create options by extending defaults with the passed in arugments
    if (arguments[0] && typeof arguments[0] === "object") {
      this.options = extendDefaults(defaults, arguments[0]);
    }
    this.check();
  }


  ChangePassword.prototype.check = function(){

    var oldpassEleId = $('#'+this.options.oldpassEleId);
    var newpassEleId = $('#'+this.options.newpassEleId);
    var newpassConfirmEleId = $('#'+this.options.newpassConfirmEleId);
    var passForm = $('#'+this.options.passForm);
    var validPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/;

    if((oldpassEleId.length) && (newpassEleId.length) && (newpassConfirmEleId.length) && (passForm.length))
    {
      var old_pass = $('#'+this.options.oldpassEleId).val();
      var new_pass = $('#'+this.options.newpassEleId).val();
      var newConfirm_pass = $('#'+this.options.newpassConfirmEleId).val();
      
      if(old_pass=="")
      {
        this.oldPassError();  

      }
      else if(new_pass=="")
      {
        this.newPassError();  
      }
      else if(newConfirm_pass=="")
      {
        this.newConfirmPassError();
      }
      else if(!validPass.test(new_pass))
      {
       this.pwdError();
      }
     else if(new_pass != newConfirm_pass)
     {
      this.pwdMatchError();
    }

    else
    {

    }
  }

  else
      { //Either Form Id or ElementIds are wrong
        this.devError();
      }

    }

    ChangePassword.prototype.devError = function(){
      this.error = true;
      this.error_message = 'Invalid validations has been implemented!';
    }
    ChangePassword.prototype.oldPassError = function(){
      this.error = true;
      this.error_message = 'Old Password field is required!';
    }

    ChangePassword.prototype.newPassError = function(){
      this.error = true;
      this.error_message = 'New Password field is required!';
    }
    ChangePassword.prototype.newConfirmPassError = function(){
      this.error = true;
      this.error_message = 'New Confirm Password field is required!';
    }
    ChangePassword.prototype.pwdError = function(){
      this.error = true;
      this.error_message = 'Incorrect New Password!';
    }
    ChangePassword.prototype.pwdMatchError = function(){
      this.error = true;
      this.error_message = 'Confirm password does not match new password!';
    }


     // Reset Password Prototype
   this.ResetPassword = function() {

    // Create global element references
    this.error = false;
    this.error_message = null;
    this.success_message = null;
    
    // Define option defaults
    var defaults = {
      newpassEleId: "new_pass",
      newpassConfirmEleId: "confirm_new_pass",
      passForm : "formreset",
    }

    // Create options by extending defaults with the passed in arugments
    if (arguments[0] && typeof arguments[0] === "object") {
      this.options = extendDefaults(defaults, arguments[0]);
    }
    this.check();
  }

  ResetPassword.prototype.check = function(){

    var newpassEleId = $('#'+this.options.newpassEleId);
    var newpassConfirmEleId = $('#'+this.options.newpassConfirmEleId);
    var passForm = $('#'+this.options.passForm);
    var validPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/;

    if((newpassEleId.length) && (newpassConfirmEleId.length) && (passForm.length))
    {

      var new_pass = $('#'+this.options.newpassEleId).val();
      var newConfirm_pass = $('#'+this.options.newpassConfirmEleId).val();

      if(new_pass=="")
      {
        this.newPassError();  
      }
      else if(newConfirm_pass=="")
      {
        this.newConfirmPassError();
      }
      else if(!validPass.test(new_pass))
      {
       this.pwdError();
     }
     else if(new_pass != newConfirm_pass)
     {
      this.pwdMatchError();
    }

    else
    {

    }
  }
   else
      { //Either Form Id or ElementIds are wrong
        this.devError();
      }

    }

    ResetPassword.prototype.devError = function(){
      this.error = true;
      this.error_message = 'Invalid validations has been implemented!';
    }

    ResetPassword.prototype.newPassError = function(){
      this.error = true;
      this.error_message = 'New Password field is required!';
    }
    ResetPassword.prototype.newConfirmPassError = function(){
      this.error = true;
      this.error_message = 'New Confirm Password field is required!';
    }
    ResetPassword.prototype.pwdError = function(){
      this.error = true;
      this.error_message = 'Incorrect New Password!';
    }
    ResetPassword.prototype.pwdMatchError = function(){
      this.error = true;
      this.error_message = 'Confirm password does not match new password!';
    }


  // Utility method to extend defaults with user options
  function extendDefaults(source, properties) {
    var property;
    for (property in properties) {
      if (properties.hasOwnProperty(property)) {
        source[property] = properties[property];
      }
    }
    return source;
  }

}(jQuery));