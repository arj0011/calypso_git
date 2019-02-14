function Login(type,logEleId,pwdEleId)
{
    this.logintype = type;
    this.loginEleId = logEleId;
    this.pwdEleId = pwdEleId;
}
Login.prototype = {
    logintype: 'email',
    loginEleId : 'email',
    loginPassword: 'pwd',
    constructor: function   (){
        console.log(this.logintype);
        console.log(this.loginEleId);
        console.log(this.loginPassword);
    }
} 

var test = Login('user','a','b');