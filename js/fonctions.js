///////////////////
//BLOCK FONCTIONS METIER
///////////////////
//function init_commun()
//function show_commun()
//function getUserCode() 

///////////////////
//BLOCK FONCTIONS PRESENTATION
///////////////////

///////////////////
//BLOCK FONCTIONS TECHNIQUES
///////////////////
//function checkAuth()

///////////////////
//BLOCK FONCTIONS AJAX JS/PHP/MYSQL
///////////////////
//function getAuth(p_cle) 


//---------------------------------------------------------

///////////////////
//BLOCK Variable globale
///////////////////

///////////////////
//BLOCK FONCTIONS METIER
///////////////////
function getUserCode() {
    try {
        return get_cookie("user");
    } catch (er) {
        log(0, "ERROR(getUserCode):" + er.message);
        return "";
    }
}

///////////////////
//BLOCK FONCTIONS PRESENTATION
///////////////////

///////////////////
//BLOCK FONCTIONS TECHNIQUES
///////////////////
function logLfjfr() {
    try {
        var get_cle = "";
        get_cle = getParamGet("cle");
        
        if(get_cle != null){
            var retourLog = getAuthLfjfr(get_cle);
            var retourLevel = parseInt(retourLog["profile"]);
            if (retourLevel > -1) {
                set_cookie("auth",retourLog["code_user"].toUpperCase());
                set_cookie("key",retourLog["keyAuthODA"]);
                addStat(retourLog["code_user"],"index.html","checkAuth : ok");
                window.location = "./page_home.html?mili="+getMilise();
            } else {
                addStat(retourLog["code_user"],"index.html","checkAuth : ko("+get_cle+")");
                window.location = "page_error.html?msg=Clé incorrecte&mili="+getMilise();
            }
        } else{
            addStat("INC","index.html","checkAuth : ko("+get_cle+")");
            window.location = "page_error.html?msg=Clé absente&mili="+getMilise();
        } 
    }
    catch (er) {
        log(0, "ERROR(logLfjfr):" + er.message);
    }
}

///////////////////
//BLOCK FONCTIONS AJAX JS/PHP/MYSQL
///////////////////

/**
 * @name getAuthLfjfr
 * @param {string} p_cle
 * @returns {Array}
 */
function getAuthLfjfr(p_cle) {
    try {
        var tabInput = { cle : p_cle };
        var retour_json = callBD("phpsql/mysql_getAuth.php", "POST", "json", tabInput);
        var data = retour_json["data"]["resultat"];
        return data;
    } catch (er) {
        log(0, "ERROR(getAuthLfjfr):" + er.message);
    }
}