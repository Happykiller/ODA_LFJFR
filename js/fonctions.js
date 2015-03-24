// Library of tools for the exemple

/**
 * @author FRO
 * @date 14/07/11
 */

(function() {
    'use strict';

    var
        /* version */
        VERSION = '0.1',

        _var = {
            'hello' : 12
        };


    ////////////////////////// PRIVATE METHODS ////////////////////////

	/**
	 * @name _init
	 * @desc Initialize
	 */
    function _init() {
        //
    }

    ////////////////////////// PUBLIC METHODS /////////////////////////

    $.functionsApp = {
        /* Version number */
        version: VERSION,

        logLfjfr : function() {
            try {
                var get_cle = "";
                get_cle = $.functionsLib.getParamGet("cle");

                var tabInput = { cle : get_cle };
                var retour_json = $.functionsLib.callRest("phpsql/getLogin.php", {}, tabInput);
                if(!$.functionsLib.isUndefined(retour_json.data.login)){
                    var retour_login = $.functionsLib.login(retour_json.data.login, get_cle);
                    if(retour_login.statut == "ko"){
                        $.functionsLib.log(0, "ERROR(logLfjfr): ko, wrong auth by key ("+get_cle+")");
                        $.functionsLib.currentWindow.location = "api_page_error.html?msg=Wrong Key&mili="+$.functionsLib.getMilise();
                        return false;
                    }else{
                        $.functionsLib.currentWindow.location = "page_home.html?mili="+$.functionsLib.getMilise();
                        return true;
                    }
                }else{
                    $.functionsLib.log(0, "ERROR(logLfjfr): ko, user unknown with key ("+get_cle+")");
                    $.functionsLib.currentWindow.location = "api_page_error.html?msg=Wrong Key&mili="+$.functionsLib.getMilise();
                    return false;
                }
            } catch (er) {
                $.functionsLib.log(0, "ERROR(logLfjfr):" + er.message);
            }
        }
    };

    // Initialize
    _init();

})();