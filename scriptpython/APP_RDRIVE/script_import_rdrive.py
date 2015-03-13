import sys
import os
import json
import datetime
import codecs
import urllib.request

import MyLogger
import MyLib

#Déclaration
arguments = dict() #dict
i = 0 #Int
config_json = {} #Json

#Procedure chargement du fichier config JSON
def charger_config() :
    global config_json
    try:
        MyLogger.logger.debug("Début chargement configuration")
        config=codecs.open(arguments["param"], 'r','utf-8')
        config_json = json.load(config)
        MyLogger.logger.debug("Chargement configuration réussi")
    except Exception as e:
        MyLogger.logger.error("Erreur pendant le chargement du fichier de configuration ("+format(e)+")")
        sys.exit("Erreur")

#Procedure Mise à jour
def synchro() :
    global config_json
    try:
        MyLogger.logger.debug("Début synchro")

        now = datetime.datetime.now()
        dateTime = now.strftime("%y%m%d_%H%M")

        proxies = {'http': 'http://fr-proxy.groupinfra.com:3128'}
        opener = urllib.request.FancyURLopener(proxies)

        url = config_json["config"]["urlListe"]
        MyLogger.logger.debug("ici : " + url)
        response = opener.open(url)
        page = response.read().decode('utf-8')
        liste_json = json.loads(page)
        MyLogger.logger.debug("ici : " + liste_json["code"])

        if(liste_json["code"] == 'ok') :
            for fichierTor in liste_json["message"]:
                url = config_json["config"]["dossierOrigine"]+fichierTor
                MyLogger.logger.debug("ici : " + url)
                response = opener.open(url)
                page = response.read()

                file = 'Archives/' + dateTime + "_" + fichierTor

                fo = open(file, "wb")
                fo.write(page)
                fo.close()

                file = config_json["config"]["dossierDistination"]+fichierTor
                MyLogger.logger.debug("ici : " + file)

                fo = open(file, "wb")
                fo.write(page)
                fo.close()

                url = config_json["config"]["urlSuppression"]+fichierTor
                response = opener.open(url)
                page = response.read().decode('utf-8')
                MyLogger.logger.debug("ici : " + page)
        
        MyLogger.logger.debug("Fin synchro")
    except Exception as e:
        MyLogger.logger.error("Erreur pendant synchro ("+format(e)+")")
        sys.exit("Erreur")

#Procedure Say More
def more() :
    MyLogger.logger.info("Les options disponible sont : 'synchro'.")
    MyLogger.logger.info("Exemple de syntax pour 'decode' : 'python script_import_rdrive.py exemple.config.rdrive.json synchro'.")
    MyLogger.logger.info("Exemple de syntax pour 'more' : 'python script_import_rdrive.py more'.")
    

#Message de bienvenu.
MyLogger.logger.info ("Bienvenue dans le script de synchro GDrive.")

#Récupération des arguments.
for x in sys.argv :
    i += 1
    if i == 2 :
        arguments["param"] = x
    elif i == 3 :
        arguments["action"] = x
        if x not in ["synchro"] :
            MyLogger.logger.warning("Votre premier argument ("+x+") est incorrect, seul 'synchro' sont aurorisés.")
            sys.exit("Erreur")
        else :
            MyLogger.logger.info("Mode d'action choisi : "+x+".")
            arguments["action"] = x
            
    if len(arguments) == 0 :
        arguments["action"] = "more"

#Affichage        
if arguments["action"] == "synchro" :
    charger_config()
    synchro()
elif arguments["action"] == "more" :
    more()

#Message de fin.
MyLogger.logger.info ("Fin du script.")
sys.exit(0)

    
