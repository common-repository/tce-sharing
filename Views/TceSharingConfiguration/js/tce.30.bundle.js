(window.webpackJsonp=window.webpackJsonp||[]).push([[30],{223:function(e,t,n){"use strict";n.r(t),n.d(t,"amplify_select_mfa_type",(function(){return d}));var i=n(11),s=n(21),a=n(4),r=n(26),o=n(51),u=n(5),l=n(16),h=function(e,t,n,i){return new(n||(n=Promise))((function(s,a){function r(e){try{u(i.next(e))}catch(e){a(e)}}function o(e){try{u(i.throw(e))}catch(e){a(e)}}function u(e){var t;e.done?s(e.value):(t=e.value,t instanceof n?t:new n((function(e){e(t)}))).then(r,o)}u((i=i.apply(e,t||[])).next())}))},c=function(e,t){var n,i,s,a,r={label:0,sent:function(){if(1&s[0])throw s[1];return s[1]},trys:[],ops:[]};return a={next:o(0),throw:o(1),return:o(2)},"function"==typeof Symbol&&(a[Symbol.iterator]=function(){return this}),a;function o(a){return function(o){return function(a){if(n)throw new TypeError("Generator is already executing.");for(;r;)try{if(n=1,i&&(s=2&a[0]?i.return:a[0]?i.throw||((s=i.return)&&s.call(i),0):i.next)&&!(s=s.call(i,a[1])).done)return s;switch(i=0,s&&(a=[2&a[0],s.value]),a[0]){case 0:case 1:s=a;break;case 4:return r.label++,{value:a[1],done:!1};case 5:r.label++,i=a[1],a=[0];continue;case 7:a=r.ops.pop(),r.trys.pop();continue;default:if(!(s=r.trys,(s=s.length>0&&s[s.length-1])||6!==a[0]&&2!==a[0])){r=0;continue}if(3===a[0]&&(!s||a[1]>s[0]&&a[1]<s[3])){r.label=a[1];break}if(6===a[0]&&r.label<s[1]){r.label=s[1],s=a;break}if(s&&r.label<s[2]){r.label=s[2],r.ops.push(a);break}s[2]&&r.ops.pop(),r.trys.pop();continue}a=t.call(e,r)}catch(e){a=[6,e],i=0}finally{n=s=0}if(5&a[0])throw a[1];return{value:a[0]?a[1]:void 0,done:!0}}([a,o])}}},T=new s.a("SelectMFAType"),d=function(){function e(e){var t=this;Object(i.k)(this,e),this.handleSubmit=function(e){return t.verify(e)},this.TOTPSetup=!1,this.selectMessage=null,this.MFAMethod=null,this.isTOTP=!1,this.isNoMFA=!1,this.isSMS=!1,this.loading=!1,this.isToastVisible=!1}return e.prototype.handleRadioButtonChange=function(e){this.TOTPSetup=!1,this.selectMessage=null,this.isNoMFA=!1,this.isTOTP=!1,this.isSMS=!1,this.isToastVisible=!1;var t=e.target,n=t.value,i=t.type,s=t.checked,a=["radio","checkbox"].includes(i);n===r.c.SMS&&a&&(this.isSMS=s),n===r.c.TOTP&&a&&(this.isTOTP=s),n===r.c.NOMFA&&a&&(this.isNoMFA=s)},e.prototype.verify=function(e){return h(this,void 0,void 0,(function(){var t,n,i,s;return c(this,(function(h){switch(h.label){case 0:if(e&&e.preventDefault(),T.debug("MFA Type Values",{TOTP:this.isTOTP,SMS:this.isSMS,"No MFA":this.isNoMFA}),this.isTOTP?this.MFAMethod=r.c.TOTP:this.isSMS?this.MFAMethod=r.c.SMS:this.isNoMFA&&(this.MFAMethod=r.c.NOMFA),t=this.authData,!o.a||"function"!=typeof o.a.setPreferredMFA)throw new Error(l.d);this.loading=!0,h.label=1;case 1:return h.trys.push([1,3,4,5]),[4,o.a.setPreferredMFA(t,this.MFAMethod)];case 2:return n=h.sent(),T.debug("Set Preferred MFA Succeeded",n),this.selectMessage=a.a.get(u.a.SUCCESS_MFA_TYPE)+" "+this.MFAMethod,[3,5];case 3:return i=h.sent(),(s=i.message)===l.l||s===l.m?(this.TOTPSetup=!0,this.selectMessage=a.a.get(u.a.SETUP_TOTP_REQUIRED)):(T.debug("Set Preferred MFA failed",i),this.selectMessage=a.a.get(u.a.UNABLE_TO_SETUP_MFA_AT_THIS_TIME)),[3,5];case 4:return this.loading=!1,this.isToastVisible=!0,[7];case 5:return[2]}}))}))},e.prototype.contentBuilder=function(){var e=this;if(!this.MFATypes||Object.keys(this.MFATypes).length<2)return T.debug(a.a.get(u.a.LESS_THAN_TWO_MFA_VALUES_MESSAGE)),Object(i.i)("div",null,Object(i.i)("a",null,a.a.get(u.a.LESS_THAN_TWO_MFA_VALUES_MESSAGE)));var t=this.MFATypes,n=t.SMS,s=t.TOTP,r=t.Optional;return Object(i.i)("amplify-form-section",{submitButtonText:a.a.get(u.a.SELECT_MFA_TYPE_SUBMIT_BUTTON_TEXT),headerText:a.a.get(u.a.SELECT_MFA_TYPE_HEADER_TEXT),handleSubmit:function(t){return e.handleSubmit(t)},loading:this.loading},n?Object(i.i)("amplify-radio-button",{key:"sms",name:"MFAType",value:"SMS",label:"SMS",handleInputChange:function(t){return e.handleRadioButtonChange(t)}}):null,s?Object(i.i)("amplify-radio-button",{key:"totp",name:"MFAType",value:"TOTP",label:"TOTP",handleInputChange:function(t){return e.handleRadioButtonChange(t)}}):null,r?Object(i.i)("amplify-radio-button",{key:"noMFA",name:"MFAType",value:"NOMFA",label:"No MFA",handleInputChange:function(t){return e.handleRadioButtonChange(t)}}):null)},e.prototype.renderToast=function(){var e=this;return this.isToastVisible&&this.selectMessage?Object(i.i)("amplify-toast",{message:this.selectMessage,handleClose:function(){e.selectMessage=null,e.isToastVisible=!1}}):null},e.prototype.render=function(){return Object(i.i)("div",null,this.contentBuilder(),this.TOTPSetup?Object(i.i)("amplify-totp-setup",{user:this.authData}):null,this.renderToast())},e}()}}]);