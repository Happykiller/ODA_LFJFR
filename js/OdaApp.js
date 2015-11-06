/* global er */
//# sourceURL=OdaApp.js
// Library of tools for the exemple
/**
 * @author FRO
 * @date 15/05/08
 */

(function() {
    'use strict';

    var
    /* version */
        VERSION = '0.1'
        ;

    ////////////////////////// PRIVATE METHODS ////////////////////////
    /**
     * @name _init
     * @desc Initialize
     */
    function _init() {
        $.Oda.Event.addListener({name : "oda-fully-loaded", callback : function(e){
            $.Oda.App.startApp();
        }});
    }

    ////////////////////////// PUBLIC METHODS /////////////////////////
    $.Oda.App = {
        /* Version number */
        version: VERSION,

        /**
         * @param {Object} p_params
         * @param p_params.attr
         * @returns {$.Oda.App}
         */
        startApp: function (p_params) {
            try {

                $.Oda.Router.addMiddleWare("quickLogin",function() {
                    $.Oda.Log.debug("MiddleWares : quickLogin");

                    var key = $.Oda.Router.current.args['key'];

                    if(key !== undefined){
                        delete $.Oda.Router.current.args['key'];
                        var tabInput = { "key" : key };
                        $.Oda.Interface.callRest($.Oda.Context.rest+"phpsql/getLogin.php", {functionRetour : function(response){
                            if(response.data){
                                var contact_mail_administrateur = $.Oda.Interface.getParameter("contact_mail_administrateur");

                                var message_html = "";
                                message_html += "<html><head></head><body>";
                                message_html += "Nouvelle visite de "+response.data.code_user;
                                message_html += "</body></html>";

                                var sujet = "[ODA-" + $.Oda.Interface.getParameter("nom_site") + "]Nouvelle visite de "+response.data.code_user;

                                var paramsMail = {
                                    email_mail_ori: contact_mail_administrateur,
                                    email_labelle_ori: "Service Mail ODA",
                                    email_mail_reply: contact_mail_administrateur,
                                    email_labelle_reply: "Service Mail ODA",
                                    email_mails_dest: contact_mail_administrateur,
                                    message_html: message_html,
                                    sujet: sujet
                                };

                                var retour = $.Oda.Interface.sendMail(paramsMail);
                                $.Oda.Security.auth({"login":response.data.code_user, "mdp":key, "reload":true});
                            }
                        }}, tabInput);
                    }
                });

                $.Oda.Router.addRoute("home", {
                    "path" : "partials/home.html",
                    "title" : "home.title",
                    "urls" : ["","home"],
                    "middleWares" : ["support","quickLogin","auth"]
                });

                $.Oda.Router.addRoute("passe", {
                    "path" : "partials/passe.html",
                    "title" : "passe.title",
                    "urls" : ["passe"],
                    "middleWares" : ["support","auth"]
                });

                $.Oda.Router.addRoute("futur", {
                    "path" : "partials/futur.html",
                    "title" : "futur.title",
                    "urls" : ["futur"],
                    "middleWares" : ["support","auth"]
                });

                $.Oda.Router.addRoute("pj", {
                    "path" : "partials/pj.html",
                    "title" : "pj.title",
                    "urls" : ["pj"],
                    "middleWares" : ["support","auth"]
                });

                $.Oda.Router.addRoute("accountNew", {
                    "path" : "partials/manage.html",
                    "title" : "accountNew.title",
                    "urls" : ["admin_projet"],
                    "middleWares" : ["support","auth"]
                });

                $.Oda.Router.addRoute("encours", {
                    "path" : "partials/encours.html",
                    "title" : "encours.title",
                    "urls" : ["budget"],
                    "dependencies" : ["hightcharts"],
                    "middleWares" : ["support","auth"]
                });

                $.Oda.Router.startRooter();

                return this;
            } catch (er) {
                $.Oda.Log.error("$.Oda.App.startApp : " + er.message);
                return null;
            }
        },

        Controler : {
            Home: {
                /**
                 * @returns {$.Oda.App.Controler.Home}
                 */
                start: function () {
                    try {

                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Home.start : " + er.message);
                        return null;
                    }
                }
            },
            Passe: {
                lastFilter : '',
                /**
                 * @returns {$.Oda.App.Controler.Passe}
                 */
                start: function () {
                    try {
                        $.Oda.App.Controler.Passe.loadExp({"filter":""});

                        $.Oda.Scope.Gardian.add({
                            id : "gardianFilter",
                            listElt : ["filter"],
                            function : function(params){
                                var filter = $('#'+params.elt).val();
                                if($.Oda.App.Controler.Passe.lastFilter !== filter){
                                    $.Oda.App.Controler.Passe.lastFilter = filter;
                                    $.Oda.Tooling.debounce(function(){
                                        $.Oda.App.Controler.Passe.loadExp({"filter":filter});
                                    },500);
                                }
                            }
                        });
                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Passe.start : " + er.message);
                        return null;
                    }
                },
                /**
                 * @param {Object} p_params
                 * @param {String} p_params.filter
                 * @returns {$.Oda.App.Controler.Passe}
                 */
                loadExp : function (p_params) {
                    try {
                        var tabInput = { "critere" : p_params.filter };
                        var call = $.Oda.Interface.callRest($.Oda.Context.rest+"phpsql/getExperiences.php", {"functionRetour": function(response){
                            if(response.data.resultat.nombre === 0){
                                $.Oda.Display.Notification.warning('Pas de r&eacute;sultat pour : '+p_params.filter);
                            }else{
                                $('#listExp').html('');
                                $.Oda.Display.Notification.info('Nombre de r&eacute;sultat trouv&eacute; : '+response.data.resultat.nombre);
                                $.each( response.data.resultat.data, function( index, value ) {
                                    var cmt = "";
                                    if(value.commentaire_titre !== ""){
                                        cmt = '<h5>'+ $.Oda.Tooling.replaceAll({"str":value.commentaire_titre, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</h5><hr>';
                                    }

                                    var role = "";
                                    if(value.fonction !== ""){
                                        role = '<dt><b>Fonction</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.fonction, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var secteur = "";
                                    if(value.secteur !== ""){
                                        secteur = '<dt><b>Secteur</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.secteur, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var fonctionnel = "";
                                    if(value.fonctionnel !== ""){
                                        fonctionnel = '<dt><b>Conception fonctionnelle</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.fonctionnel, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var realisation = "";
                                    if(value.realisation !== ""){
                                        realisation = '<dt><b>R&eacute;alisation</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.realisation, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var realisation = "";
                                    if(value.realisation !== ""){
                                        realisation = '<dt><b>R&eacute;alisation</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.realisation, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var language = "";
                                    if(value.language !== ""){
                                        language = '<dt><b>Language</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.language, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var applicatif = "";
                                    if(value.applicatif !== ""){
                                        applicatif = '<dt><b>Applicatif</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.applicatif, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var develloppement = "";
                                    if(value.develloppement !== ""){
                                        develloppement = '<dt><b>Environnement devellopement</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.develloppement, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var bdd = "";
                                    if(value.bdd !== ""){
                                        bdd = '<dt><b>Environnement base de donn&eacute;es</b></dt><dd>'+$.Oda.Tooling.replaceAll({"str":value.bdd, "find":p_params.filter, "by":'<span style="color: red">'+p_params.filter+'</span>', "ignoreCase":true})+'</dd>';
                                    }

                                    var strHtml = $.Oda.Display.TemplateHtml.create({
                                        "template" : "experience"
                                        , "scope" : {
                                            "title" : value.titre_experience,
                                            "cmt" :  cmt,
                                            "customer" : value.employeur,
                                            "begin" : value.debut,
                                            "end" : value.fin,
                                            "fonction" : role,
                                            "secteur" : secteur,
                                            "fonctionnel" : fonctionnel,
                                            "realisation" : realisation,
                                            "language" : language,
                                            "applicatif" : applicatif,
                                            "develloppement" : develloppement,
                                            "bdd" : bdd
                                        }
                                    });

                                    $('#listExp').append(strHtml);
                                });
                            }
                        }}, tabInput);

                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Passe.loadExp : " + er.message);
                        return null;
                    }
                },
            },
            Pj : {
                /**
                 * @param {Object} that
                 * @returns {$.Oda.App.Controler.Pj}
                 */
                getCV : function (that) {
                    try {
                        var url = $.Oda.Tooling.urlDownloadFromServerResources({"strPath": 'FRO/CV_ROSITOF_'+$.Oda.Session.code_user+'.pdf'});
                        $(that).attr("href", url);
                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Pj.getCV : " + er.message);
                        return null;
                    }
                },
            },
            Manage : {
                /**
                 * @returns {$.Oda.App.Controler.Manage}
                 */
                start: function () {
                    try {

                        $.Oda.App.Controler.Manage.takeInputFile({elt:$('#cv')});
                        $('#cv').bind("change",function(elt){
                            $.Oda.App.Controler.Manage.takeInputFile({elt:elt.target});
                        });

                        $.Oda.Scope.Gardian.add({
                            id : "gardianSubmit",
                            listElt : ["name","key","codeUser","mail","desc","cv"],
                            function : function(params){
                                if( ($("#name").data("isOk")) && ($("#key").data("isOk"))  && ($("#codeUser").data("isOk"))  && ($("#mail").data("isOk"))  && ($("#desc").data("isOk"))  && ($("#cv").data("isOk")) ){
                                    $("#submit").removeClass("disabled");
                                    $("#submit").removeAttr("disabled");
                                }else{
                                    $("#submit").addClass("disabled");
                                    $("#submit").attr("disabled", true);
                                }
                            }
                        });
                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Manage.start : " + er.message);
                        return null;
                    }
                },
                /**
                 * @param {Object} p_params
                 * @param p_params.elt
                 * @returns {$.Oda.App.Controler.Manage}
                 */
                takeInputFile : function (p_params) {
                    try {
                        var $elt = $(p_params.elt);
                        var $elt_href = $("#"+$elt[0].id+'_href');
                        var required = !$.Oda.Tooling.isUndefined($elt.attr("required"));
                        if(required && (($elt.val() === undefined) || ($elt.val() === ""))){
                            $elt.data("isOk", false);
                            $($elt_href).removeClass('btn-primary');
                            $($elt_href).removeClass('btn-success');
                            $($elt_href).addClass('btn-warning');
                        }else{
                            $elt.data("isOk", true);
                            $($elt_href).removeClass('btn-primary')
                            $($elt_href).removeClass('btn-warning');
                            $($elt_href).addClass('btn-success');
                        }
                        $.Oda.Scope.Gardian.findByElt({id : $elt[0].id});
                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Manage.takeInputFile : " + er.message);
                        return null;
                    }
                },
                /**
                 * @returns {$.Oda.App.Contoler.Manage}
                 */
                submit: function () {
                    try {
                        var tabInput = { input_nom : $("#name").val(), input_cle : $("#key").val(), input_code_user : $("#codeUser").val(), input_mail : $("#mail").val(), input_description: $("#desc").val(), input_cv : $("#cv").val() };
                        var call = $.Oda.Interface.callRest($.Oda.Context.rest+"phpsql/creerCompte.php", {"functionRetour": function(response){
                            $.Oda.Tooling.uploadFile({idInput : "cv", folder : $("#codeUser").val()+'/', name : 'CV_ROSITOF_' + $("#codeUser").val() + ".pdf"});
                            $.Oda.Router.navigateTo();
                        }}, tabInput);
                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Contoler.Manage.submit : " + er.message);
                        return null;
                    }
                },
            },
            Encours : {
                /**
                 * @returns {$.Oda.App.Controler.Encours}
                 */
                start: function () {
                    try {
                        var call = $.Oda.Interface.callRest($.Oda.Context.rest+"phpsql/stats_budget.php", {"functionRetour": function(response){
                            var series = [];

                            var uneSerie = {
                                name: 'Solde tout compte.',
                                data: []
                            };

                            for (var indice in response["data"]["elements"]["data"]) {
                                var element = response["data"]["elements"]["data"][indice];

                                var date = element["date"];
                                var jour = parseInt(date.substring(8,10));
                                var mois = parseInt(date.substring(5,7)) - 1;
                                var annee = parseInt(date.substring(0,4));

                                var dateDate = Date.UTC(annee, mois, jour);
                                var montant = $.Oda.Tooling.arrondir(parseFloat(response["data"][date]["solde"]),2);

                                var myArrayForSerie = [dateDate, montant];

                                uneSerie.data.push(myArrayForSerie);
                            }

                            series.push(uneSerie);

                            $('#encours').highcharts({
                                chart: {
                                    type: 'spline',
                                    zoomType: 'x'
                                },
                                title: {
                                    text: 'Evolution des solde'
                                },
                                subtitle: {
                                    text: 'Les soldes par compte'
                                },
                                xAxis: {
                                    type: 'datetime',
                                    title: {
                                        text: 'Date'
                                    }
                                },
                                yAxis: {
                                    title: {
                                        text: 'Solde en €'
                                    },
                                    min: 0
                                },
                                tooltip: {
                                    headerFormat: '<b>{series.name}</b><br>',
                                    pointFormat: '{point.x:%e %b %Y}: {point.y:.2f}€'
                                },
                                plotOptions: {
                                    spline: {
                                        marker: {
                                            enabled: false
                                        }
                                    }
                                },
                                series: series
                            });
                        }}, {});
                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Encours.start : " + er.message);
                        return null;
                    }
                },
            }
        }
    };

    // Initialize
    _init();

})();
