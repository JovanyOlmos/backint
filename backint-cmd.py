import os
import datetime
import re

def autocompleteGettersAndSetters(name):
    numLines = len(open('./app/models/Model' + name.capitalize() + '.php', "r").readlines())
    newFile = ""
    file = open('./app/models/Model' + name.capitalize() + '.php', "r")
    enabled = False
    stringVariables = ""
    counter = 1
    for line in file:
        if counter < numLines - 1:
            newFile += line
        if "itk autocomplete end" in line:
            enabled = False
        if enabled:
            line = line.replace("\n", "").replace(" ", "").replace("\t","").replace("private", "").replace(",", "").replace(";","")
            stringVariables += line
        if "itk autocomplete start" in line:
            enabled = True
        counter = counter + 1
    stringVariables = stringVariables[1:]
    arrayVariables = stringVariables.split("$")
    gettersAndSetters = ""
    for variable in arrayVariables: 
        gettersAndSetters += "\tpublic function get" + variable.capitalize() + "(){\n"
        gettersAndSetters += "\t\treturn $this->" + variable + ";\n"
        gettersAndSetters += "\t}\n\n"
        gettersAndSetters += "\tpublic function set" + variable.capitalize() + "($iField){\n"
        gettersAndSetters += "\t\t$this->" + variable + " = $iField;\n"
        gettersAndSetters += "\t}\n\n"
    newFile += "\n" + gettersAndSetters + "}\n?>"
    file = open('./app/models/Model' + name.capitalize() + '.php', "w")
    file.write(newFile)
    file.close

def createUpdate():
    file = open('./updates/updates/Update' + re.sub("-|:|\s|\.", "", str(datetime.datetime.now())) + '.php', "w")
    file.write('<?php\n')
    file.write('namespace backint\\update;\n\n')
    file.write('require_once(__DIR__."/../IUpdate.php");\n\n')
    file.write('class Update' + re.sub("-|:|\s|\.", "", str(datetime.datetime.now())) + ' implements iUpdate {\n')
    file.write('\tpublic function script() {\n')
    file.write('\t\treturn "";\n')
    file.write('\t}\n\n')
    file.write('\tpublic function version() {\n')
    file.write('\t\treturn 1;\n')
    file.write('\t}\n')
    file.write('}\n')
    file.write('?>')
    
def createModelObject(name):
    file = open('./app/models/Model' + name.capitalize() + '.php', "w")
    file.write('<?php\n')
    file.write('namespace backint\\interfaces;\n')
    file.write('require_once("./core/ControllerTools/Model.php");\n')
    file.write('use backint\\core\\OInterface;\n\n')
    file.write('class OI' + name.capitalize() + ' extends OInterface {\n')
    file.write('\t//itk autocomplete start' + os.linesep)
    file.write('\t//itk autocomplete end' + os.linesep)
    file.write('\tpublic function __construct($tableName, $PKFieldName) {\n')
    file.write('\t\tparent::__construct($tableName, $PKFieldName);' + os.linesep)
    file.write('\t}\n')
    file.write('}\n')
    file.write('?>')
    file.close()

def createModelObject(name):
    file = open('./server/api-models/APIModel' + name.capitalize() + '.php', "w")
    file.write('<?php\n')
    file.write('namespace backint\\server\\api;\n')
    file.write('require_once("./core/OController.php");\n')
    file.write('require_once("./core/QueryBuilder/QueryBuilder.php");\n')
    file.write('require_once("./core/http.php");\n')
    file.write('require_once("./core/json.php");\n')
    file.write('require_once("./core/ObjQL.php");\n')
    file.write('require_once("./interfaces/OI' + name.capitalize() + '.php");\n')
    file.write('require_once("./core/iController.php");\n' + os.linesep)
    file.write('use backint\\core\\OController;\n')
    file.write('use backint\\core\\QueryBuilder;\n')
    file.write('use backint\\interfaces\\OI' + name.capitalize() + ';\n')
    file.write('use backint\\core\\Http;\n')
    file.write('use backint\\core\\Json;\n')
    file.write('use backint\\core\\ObjQL;\n\n')
    file.write('class APIModel' + name.capitalize() + ' implements iController {' + os.linesep)
    file.write('\tprivate $oi' + name.capitalize() + ';\n\n')
    file.write('\tpublic function __construct() {\n')
    file.write('\t\t$this->oi' + name.capitalize() + ' = new OI' + name.capitalize() + '("' + name + '", "id");\n')
    file.write('\t}\n\n')
    file.write('\tpublic function getById($params) {\n')
    file.write('\t\t$helper = new QueryBuilder();\n')
    file.write('\t\t$helper->where()->addPKFilter($this->oi' + name.capitalize() + '->getPKFieldName(), $params[0]);\n')
    file.write('\t\t$this->oi' + name.capitalize() + ' = OController::selectSimple($this->oi' + name.capitalize() + ', $helper);\n')
    file.write('\t\tif($this->oi' + name.capitalize() + '->getPKValue() > 0)\n')
    file.write('\t\t{\n')
    file.write('\t\t\t$json = Json::convertObjectToJSON($this->oi' + name.capitalize() + ');\n')
    file.write('\t\t\tHttp::sendResponse(OK, $json);\n')
    file.write('\t\t}\n')
    file.write('\t\telse\n')
    file.write('\t\t{\n')
    file.write('\t\t\tHttp::sendResponse(NO_CONTENT, Json::messageToJSON("Resource does not exist"));\n')
    file.write('\t\t}\n')
    file.write('\t}\n\n')
    file.write('\tpublic function create($params, $requestBody) {\n')
    file.write('\t\t$this->oi' + name.capitalize() + ' = Json::fillObjectFromJSON($this->oi' + name.capitalize() + ', $requestBody);\n')
    file.write('\t\t$err = OController::insert($this->oi' + name.capitalize() + ');\n')
    file.write('\t\tif($err->hasErrors())\n')
    file.write('\t\t\t$err->sendError();\n')
    file.write('\t\telse\n')
    file.write('\t\t\tHttp::sendResponse(CREATED, Json::messageToJSON("Created correctly"));\n')
    file.write('\t}\n\n')
    file.write('\tpublic function update($params, $requestBody) {\n')
    file.write('\t\t$this->oi' + name.capitalize() + ' = Json::fillObjectFromJSON($this->oi' + name.capitalize() + ', $requestBody);\n')
    file.write('\t\t$err = OController::update($this->oi' + name.capitalize() + ');\n')
    file.write('\t\tif($err->hasErrors())\n')
    file.write('\t\t\t$err->sendError();\n')
    file.write('\t\telse\n')
    file.write('\t\t\tHttp::sendResponse(CREATED, Json::messageToJSON("Updated correctly"));\n')
    file.write('\t}\n\n')
    file.write('\tpublic function deleteById($params) {\n')
    file.write('\t\t$this->oi' + name.capitalize() + '->setPKValue($params[0]);\n')
    file.write('\t\t$err = OController::delete($this->oi' + name.capitalize() + ');\n')
    file.write('\t\tif($err->hasErrors())\n')
    file.write('\t\t\t$err->sendError();\n')
    file.write('\t\telse\n')
    file.write('\t\t\tHttp::sendResponse(CREATED, Json::messageToJSON("Deleted correctly"));\n')
    file.write('\t}\n')
    file.write('}\n')
    file.write('?>')
    file.close()


command = ""
while command != "exit":
    command = input("Type your command:")
    command = command.lower()
    keyWord = command.split(" ")
    if keyWord[0] == "itk":
        action = keyWord[1]
        if action == "-g" or action == "generate":
            fileType = keyWord[2]
            if fileType == "interface" or fileType == "-i":
                arg = keyWord[3]
                if len(arg) > 0:
                    createInterfaceObject(arg)
                else:
                    print("Invalid command")
            elif fileType == "model" or fileType == "-m":
                arg = keyWord[3]
                if len(arg) > 0:
                    createModelObject(arg)
                else:
                    print("Invalid command")
            elif fileType == "update" or fileType == "-u":
                createUpdate()
            elif fileType == "all" or fileType == "-a":
                arg = keyWord[3]
                if len(arg) > 0:
                    createInterfaceObject(arg)
                    createModelObject(arg)
                else:
                    print("Invalid command")
            elif fileType == "-h" or fileType == "help" or fileType == "?":
                print(os.linesep + "Welcome to backint cmd help guide.")
                print("\nYou can choose these next options: \n\n")
                print("\tinterface [arg] | -i [arg] -> to generate an Interface object file\n\n")
                print("\tmodel [arg] | -m [arg] -> to generate an API Model object file\n\n")
                print("\tupdate | -u -> to generate an Update object file\n\n")
                print("\tall [arg] | -a [arg] -> to generate both API Model and Interface object\n\n")
                print("You can type also 'exit' to get out.\n")
            else:
                print("Invalid command")
        elif action == "autocomplete" or action == "auto" or action == "-a":
            arg = keyWord[2]
            if len(arg) > 0:
                autocompleteGettersAndSetters(arg)
            else:
                print("Invalid command")
        elif action == "-h" or action == "help" or action == "?":
            print(os.linesep + "Welcome to backint cmd help guide.")
            print("\nYou can choose these next options: \n\n")
            print("\tautocomplete [arg] | auto [arg] | -a [arg] -> to autocomplete with setters and getters some file\n\n")
            print("\tgenerate | -g -> to generate an object file\n\n")
            print("You can type also 'exit' to get out.\n")
        else:
            print("Invalid command")
    elif keyWord[0] == "exit":
        print("See you later!")
    else:
        print("Invalid command")