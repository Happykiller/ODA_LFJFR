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
                $.Oda.Router.addRoute("home", {
                    "path" : "partials/home.html",
                    "title" : "home.title",
                    "urls" : ["","home"],
                    "middleWares" : ["support","auth"]
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
                /**
                 * @returns {$.Oda.App.Controler.Passe}
                 */
                start: function () {
                    try {

                        return this;
                    } catch (er) {
                        $.Oda.Log.error("$.Oda.App.Controler.Passe.start : " + er.message);
                        return null;
                    }
                }
            }
        }
    };

    // Initialize
    _init();

})();
