import os
import requests

def createConfigTable(name):
    file = open('./')

def createInterfaceObject(name):
    file = open('./interfaces/OI' + name.capitalize() + '.php', "w")
    file.write('<?php\n')
    file.write('namespace backint\\interfaces;\n')
    file.write('require_once("./core/OInterface.php");\n')
    file.write('require_once("./definitions/SQLFormat.php");\n')
    file.write('use backint\\core\\OInterface;\n\n')
    file.write('class OI' + name.capitalize() + ' extends OInterface {' + os.linesep)
    file.write('\tpublic function __construct(string $DBTableName, string $columnNameFromIdTable) {\n')
    file.write('\t\tparent::__construct($DBTableName, $columnNameFromIdTable);' + os.linesep)
    file.write('\t}\n')
    file.write('}\n')
    file.write('?>')
    file.close()

def createModelObject(name):
    file = open('./server/api-models/APIModel' + name.capitalize() + '.php', "w")
    file.write('<?php\n')
    file.write('namespace backint\\server\\api;\n')
    file.write('use backint\\core\\OController;\n')
    file.write('use backint\\interfaces\\OI' + name.capitalize() + ';\n')
    file.write('use backint\\core\\http;\n')
    file.write('require_once("./core/OController.php");\n')
    file.write('require_once("./core/http.php");\n')
    file.write('require_once("./interfaces/OI' + name.capitalize() + '.php");\n')
    file.write('require_once("./definitions/HTTP.php");' + os.linesep)
    file.write('class APIModel' + name.capitalize() + ' {' + os.linesep)
    file.write('\tprivate OI' + name.capitalize() + ' $oi' + name.capitalize() + ';\n')
    file.write('\tprivate OController $oController;\n')
    file.write('\tprivate http $http;\n\n')
    file.write('\tpublic function __construct() {\n')
    file.write('\t\t$this->oController = new OController();\n')
    file.write('\t\t$this->oi' + name.capitalize() + ' = new OI' + name.capitalize() + '("' + name + '", "id");\n')
    file.write('\t\t$this->http = new http();\n')
    file.write('\t}\n')
    file.write('}\n')
    file.write('?>')
    file.close()

command = input("Type your command:")

keyWord = command.split(" ")

if keyWord[0] == "itk":
    action = keyWord[1]
    if action == "g" or action == "generate":
        fileType = keyWord[2]
        if fileType == "interface":
            arg = keyWord[3]
            if len(arg) > 0:
                createInterfaceObject(arg)
            else:
                print("Invalid command")
        elif fileType == "model":
            arg = keyWord[3]
            if len(arg) > 0:
                createModelObject(arg)
            else:
                print("Invalid command")
        elif fileType == "all":
            arg = keyWord[3]
            if len(arg) > 0:
                createInterfaceObject(arg)
                createModelObject(arg)
            else:
                print("Invalid command")
        else:
            print("Invalid command")
    elif action == "install" or action == "i":
        myfile = requests.get("https://github.com/JovanyOlmos/backint/archive/refs/heads/master.zip")
        open('C:/Users/Jose-/Downloads/backint-master.zip', 'wb').write(myfile.content)
    elif action == "autocomplete":
        print("Autocompletar")
    else:
        print("Invalid command")
else:
    print("Invalid command")