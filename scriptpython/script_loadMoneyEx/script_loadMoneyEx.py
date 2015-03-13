import sys
import os
import json
import urllib.request
from pprint import pprint
import datetime
from datetime import timedelta
import codecs
from html.parser import HTMLParser

class MyHTMLParser(HTMLParser):
    def handle_starttag(self, tag, attrs):
        global printLigne,gardien
        if(gardien == False) and (tag == "tr") :
            gardien = True
    def handle_endtag(self, tag):
        global printLigne,gardien,nbElement,tab
        if (gardien == True) and (tag == "tr") :
            if ( nbElement > 3 )  :
                tab.append(printLigne)

            gardien = False
            printLigne = ""
            nbElement = 0
                
    def handle_data(self, data):
        global printLigne,gardien,nbElement
        if (gardien == True) and (len(data) > 1) :
            myTextData = data.replace('\r', '<br />')
            printLigne += '"'+myTextData+'";'
            nbElement += 1

#append the relative location you want to import from
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), "../../API/python/")))

#import your module stored in '../common'
import MyLib
import MyLogger

#Déclaration
arguments = dict() #dict
i = 0 #Int
config_json = {} #Json

gardien = False;
printLigne = ""
nbElement = 0
tab = []

#####################################################################
#Les definitions

#Procedure
def lunch() :
    global config_json
    try:
        MyLogger.logger.info("Début lunch pour "+config_json["parameters"]["labelInstance"])

        datas = ""
        MyLogger.logger.warning("ATTENTION remplace euro et quote le 4 sous word")
        fileDatas=codecs.open(config_json["parameters"]["path.datasMoneyEx"], 'r','utf-8')
        with fileDatas as myfile:
            datas+=myfile.read().replace('\n', '')
        
        parser = MyHTMLParser(strict=False)
        parser.feed(datas)

        now = datetime.datetime.now()
        dateTime = now.strftime("%y%m%d_%H%M")

        myfile = open(config_json["parameters"]["path.extracts"]+"extract_"+dateTime+".csv", 'w')
        for ligne in tab:
            myfile.write(ligne+"\n")

        MyLogger.logger.warning("ATTENTION penser a convertir en utf8 le csv")
        MyLogger.logger.info("Fin lunch")
    except Exception as e:
        MyLogger.logger.error("Erreur pendant lunch : ("+format(e)+")")

#Procedure Say More
def more() :
    MyLogger.logger.info("Les options disponible sont : 'more','lunch'.")
    MyLogger.logger.info("Exemple de syntax pour 'lunch' : 'python ...\script.py ...\exemple.config.script.json lunch'.")
    MyLogger.logger.info("Exemple de syntax pour 'more' : '...\script.py more'.")  

#####################################################################
#Le programme

#Message de bienvenu.
MyLogger.logger.info ("Bienvenue dans le script pour redescendre des tables entre bases.")

#Récupération des arguments.
for x in sys.argv :
    i += 1
    if i == 2 :
        arguments["jsonFile"] = x
    elif i == 3 :
        arguments["action"] = x
        if x not in ["lunch","more"] :
            MyLogger.logger.warning("Votre premier argument ("+x+") est incorrect, seul 'more','lunch' sont aurorisés.")
            sys.exit("Erreur")
        else :
            MyLogger.logger.info("Mode d'action choisi : "+x+".")
            arguments["action"] = x
            
    if len(arguments) == 0 :
        arguments["action"] = "more"

#Initialisation
config_json = MyLib.charger_config(arguments["jsonFile"]) 

#Affichage        
if arguments["action"] == "lunch" :
    lunch()
elif arguments["action"] == "more" :
    more()

#Message de fin.
MyLogger.logger.info ("Fin du script.")
sys.exit(0)

    
