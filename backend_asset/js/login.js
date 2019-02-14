(function($) {

  // Define our constructor
  this.Login = function() {

    // Create global element references
    this.error = false;
    this.error_message = null;
    this.success_message = null;
    
    // Define option defaults
    var defaults = {
      loginType:"email",
      loginEleId: "email",
      loginPwdId: "pwd",
      loginForm : "formC",
    }

    // Create options by extending defaults with the passed in arugments
    if (arguments[0] && typeof arguments[0] === "object") {
      this.options = extendDefaults(defaults, arguments[0]);
    }
    this.check();
  }


  Login.prototype.check = function(){
    var password = $('#'+this.options.loginPwdId).val();
    var email = $('#'+this.options.loginEleId).val();
    var myStr= this.options.loginType;

    var validPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/;
    var validEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var strArray = myStr.split(",").length;
    
    if(strArray == 1)
    {
      if(this.options.loginType=='email')
      {
      //validation code as per email address
      var loginEleId = $('#'+this.options.loginEleId);
      var loginPwdId = $('#'+this.options.loginPwdId);
      var loginForm = $('#'+this.options.loginForm);

      if((loginEleId.length) && (loginPwdId.length) && (loginForm.length))
      {
        if(email=="")
        {
          this.requiredEmailError();  

        }
        else if(password=="")
        {
          this.requiredPwdError();
        }
        else if(!validEmail.test(email))
        {
          this.emailError();  
        }
        else if(!validPass.test(password))
        {
          this.pwdError();
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
    else if(this.options.loginType=='user')
      //validation code as per user
    {

      var space = /^\S{3,}$/;
      var validUser= /^[a-zA-Z0-9\.\_]*$/;
      if(email=="")
      {
        this.requiredUserError();  

      }
      else if(password=="")
      {
        this.requiredPwdError();
      }
      else if(!space.test(email))
      {
        this.UserError();
      }
      else if(!validUser.test(email))
      {
        this.UserError();
      }
      
      else if(!validPass.test(password))
      {
        this.pwdError();
      }

    }
    else
    {
      this.devError();  
    }
  }
  else{
    
     if(email =="")
     {
      this.requiredError();
     }
     else if(password=="")
      {
        this.requiredPwdError();
      }
      else if(!validPass.test(password))
        {
          this.pwdError();
        }
  }
    

  }

  Login.prototype.devError = function(){
    this.error = true;
    this.error_message = 'Invalid validations has been implemented!';
  }
  Login.prototype.requiredEmailError = function(){
    this.error = true;
    this.error_message = 'Email field is required!';
  }
  Login.prototype.requiredError = function(){
    this.error = true;
    this.error_message = 'Email or username field is required!';
  }
  Login.prototype.requiredPwdError = function(){
    this.error = true;
    this.error_message = 'Password field is required!';
  }
  Login.prototype.requiredUserError = function(){
    this.error = true;
    this.error_message = 'Username field is required!';
  }

  Login.prototype.pwdError = function(){
    this.error = true;
    this.error_message = 'Incorrect password!';
  }
  Login.prototype.UserError = function(){
    this.error = true;
    this.error_message = 'user name can not be contain white spaces and special character!';
  }
  Login.prototype.emailError = function(){
    this.error = true;
    this.error_message = 'Email is not valid!';
  }


  this.Signup = function() {

    // Create global element references
    this.error = false;
    this.error_message = null;
    this.success_message = null;
    
    // Define option defaults
    var defaults = {
      fullNameEleId: 'full_name',
      firstNameEleId: 'first_name',
      lastNameEleId: 'last_name',
      emailId : 'email',
      pwdId: 'password',
      phoneId: 'mobile',
      formId: 'formSignup'
    }

    // Create options by extending defaults with the passed in arugments
    if (arguments[0] && typeof arguments[0] === "object") {
      this.options = extendDefaults(defaults, arguments[0]);
    }
    this.check();
  }

  Signup.prototype.check = function(){
    var password = $('#'+this.options.pwdId).val();
    var email = $('#'+this.options.emailId).val();
    var validPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/;
    var validEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
      //validation code as per email address
      var fullNameEleId = $('#'+this.options.fullNameEleId);
      var firstNameEleId = $('#'+this.options.firstNameEleId);
      var lastNameEleId = $('#'+this.options.lastNameEleId);
      var emailId = $('#'+this.options.emailId);
      var pwdId = $('#'+this.options.pwdId);
      var phoneId = $('#'+this.options.phoneId);
      var formId = $('#'+this.options.formId);

      // if((firstNameEleId.length) && (lastNameEleId.length) && (emailId.length) && (pwdId.length) && (phoneId.length) && (formId.length))
      // {
        var fullName = $('#'+this.options.fullNameEleId).val();
        var firstName = $('#'+this.options.firstNameEleId).val();
        var lastName = $('#'+this.options.lastNameEleId).val();
        var phone = $('#'+this.options.phoneId).val();
        if(firstName=="")
        {
          this.firstNameError();  

        }
        else if(fullName=="")
        {
          this.fullNameError();  
        }
        else if(lastName=="")
        {
         this.lastNameError();  
       }
       else if(email=="")
       {
         this.emailReqError();
       }
       else if(password=="")
       {
         this.pwdError();
       }
       else if(phone=="")
       {
         this.phoneError();
       }
       else if(isNaN(phone))
       {
         this.phoneNumericError();

       }
       else if(phone.length < 10)
       {
         this.phoneValidError();
       }
       else if(!validEmail.test(email))
       {
        this.emailError();  
      }
      else if(!validPass.test(password))
      {
        this.pwdValidError();
      }
      else
      {

      }
    // }

    // else
    //   { //Either Form Id or ElementIds are wrong
    //     this.devError();
    //   }
  }


  Signup.prototype.devError = function(){
    this.error = true;
    this.error_message = 'Invalid validations has been implemented!';
  }
  Signup.prototype.firstNameError = function(){
    this.error = true;
    this.error_message = 'First Name field is required!';
  }
  Signup.prototype.lastNameError = function(){
    this.error = true;
    this.error_message = 'Last Name field is required!';
  }
  Signup.prototype.emailReqError = function(){
    this.error = true;
    this.error_message = 'Email field is required!';
  }
  Signup.prototype.pwdError = function(){
    this.error = true;
    this.error_message = 'Password field is required!';
  }
  Signup.prototype.phoneError = function(){
    this.error = true;
    this.error_message = 'Phone Number field is required!';
  }
  Signup.prototype.phoneNumericError = function(){
    this.error = true;
    this.error_message = 'Phone Number should be numeric!';
  }
  Signup.prototype.phoneValidError = function(){
    this.error = true;
    this.error_message = 'Phone Number should be at least 10 number long!';
  }

  Signup.prototype.pwdValidError = function(){
    this.error = true;
    this.error_message = 'Password field must contain at least 6 characters,one upper case and one number!';
  }

  Signup.prototype.emailError = function(){
    this.error = true;
    this.error_message = 'Email is not valid!';
  }
  Signup.prototype.fullNameError = function(){
    this.error = true;
    this.error_message = 'Full name field is required!';
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