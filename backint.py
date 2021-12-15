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
        gettersAndSetters += "\tpublic function set" + variable.capitalize() + "($field){\n"
        gettersAndSetters += "\t\t$this->" + variable + " = $field;\n"
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
    file.write('namespace backint\\models;\n\n')
    file.write('use backint\\core\\Model;\n')
    file.write('use SQL;\n\n')
    file.write('class Model' + name.capitalize() + ' extends Model {\n')
    file.write('\t//itk autocomplete start' + os.linesep)
    file.write('\t//itk autocomplete end' + os.linesep)
    file.write('\tpublic function __construct() {\n')
    file.write('\t\tparent::__construct();\n')
    file.write('\t\t$this->setTableName("table");\n')
    file.write('\t\t$this->setPKFieldName("id");\n\n')
    file.write('\t}\n')
    file.write('}\n')
    file.write('?>')
    file.close()

def createControllerObject(name):
    file = open('./app/controllers/Controller' + name.capitalize() + '.php', "w")
    file.write('<?php\n')
    file.write('namespace backint\\app\\controllers;\n\n')
    file.write('use backint\\core\\QuickQuery;\n')
    file.write('use backint\\core\\QueryBuilder;\n')
    file.write('use backint\\models\\Model' + name.capitalize() + ';\n')
    file.write('use backint\\core\\Http;\n')
    file.write('use backint\\core\\Json;\n')
    file.write('use backint\\core\\ControllerBase;\n')
    file.write('use backint\\core\\ObjQL;\n\n')
    file.write('class Controller' + name.capitalize() + ' extends ControllerBase {' + os.linesep)
    file.write('\tprivate $model' + name.capitalize() + ';\n\n')
    file.write('\tprivate QuickQuery $quickQuery;\n\n')
    file.write('\tpublic function __construct(QuickQuery $_quickQuery) {\n')
    file.write('\t\t$this->model' + name.capitalize() + ' = new Model' + name.capitalize() + '();\n')
    file.write('\t\t$this->quickQuery = $_quickQuery;\n')
    file.write('\t\t$this->setRouteSettings("GET", "get_by_id", false);\n')
    file.write('\t\t$this->setRouteSettings("POST", "create", false);\n')
    file.write('\t\t$this->setRouteSettings("PUT", "update", false);\n')
    file.write('\t\t$this->setRouteSettings("DELETE", "delete_by_id", false);\n')
    file.write('\t}\n\n')
    file.write('\t/**\n')
    file.write('\t * Get a record passing by param an id\n')
    file.write('\t * \n')
    file.write('\t * @param mixed $params\n')
    file.write('\t * \n')
    file.write('\t * @return void\n')
    file.write('\t */\n')
    file.write('\tpublic function get_by_id($params) {\n')
    file.write('\t\t$builder = new QueryBuilder();\n')
    file.write('\t\t$builder->where()->addPKFilter($this->model' + name.capitalize() + '->getPKFieldName(), $params[0]);\n')
    file.write('\t\t$this->model' + name.capitalize() + ' = $this->quickQuery->selectSimple($this->model' + name.capitalize() + ', $builder);\n')
    file.write('\t\tif(!is_null($this->model' + name.capitalize() + ') && $this->model' + name.capitalize() + '->getPKValue() > 0)\n')
    file.write('\t\t{\n')
    file.write('\t\t\t$json = Json::convertObjectToJSON($this->model' + name.capitalize() + ');\n')
    file.write('\t\t\tHttp::sendResponse(Http::OK, $json);\n')
    file.write('\t\t}\n')
    file.write('\t\telse\n')
    file.write('\t\t{\n')
    file.write('\t\t\tHttp::sendResponse(Http::NO_CONTENT);\n')
    file.write('\t\t}\n')
    file.write('\t}\n\n')
    file.write('\t/**\n')
    file.write('\t * Insert a new record into database passing by body the info\n')
    file.write('\t * \n')
    file.write('\t * @param mixed $params\n')
    file.write('\t * \n')
    file.write('\t * @return void\n')
    file.write('\t */\n')
    file.write('\tpublic function create($params, $requestBody) {\n')
    file.write('\t\t$this->model' + name.capitalize() + ' = Json::fillObjectFromJSON($this->model' + name.capitalize() + ', $requestBody);\n')
    file.write('\t\t$err = $this->quickQuery->insert($this->model' + name.capitalize() + ');\n')
    file.write('\t\tif($err->hasErrors())\n')
    file.write('\t\t\t$err->sendError();\n')
    file.write('\t\telse\n')
    file.write('\t\t\tHttp::sendResponse(Http::CREATED, Json::messageToJSON("Created correctly"));\n')
    file.write('\t}\n\n')
    file.write('\t/**\n')
    file.write('\t * Update an existing record passing by body all information\n')
    file.write('\t * \n')
    file.write('\t * @param mixed $params\n')
    file.write('\t * \n')
    file.write('\t * @return void\n')
    file.write('\t */\n')
    file.write('\tpublic function update($params, $requestBody) {\n')
    file.write('\t\t$this->model' + name.capitalize() + ' = Json::fillObjectFromJSON($this->model' + name.capitalize() + ', $requestBody);\n')
    file.write('\t\t$err = $this->quickQuery->update($this->model' + name.capitalize() + ');\n')
    file.write('\t\tif($err->hasErrors())\n')
    file.write('\t\t\t$err->sendError();\n')
    file.write('\t\telse\n')
    file.write('\t\t\tHttp::sendResponse(Http::CREATED, Json::messageToJSON("Updated correctly"));\n')
    file.write('\t}\n\n')
    file.write('\t/**\n')
    file.write('\t * Delete a record passing by param an id\n')
    file.write('\t * \n')
    file.write('\t * @param mixed $params\n')
    file.write('\t * \n')
    file.write('\t * @return void\n')
    file.write('\t */\n')
    file.write('\tpublic function delete_by_id($params) {\n')
    file.write('\t\t$this->model' + name.capitalize() + '->setPKValue($params[0]);\n')
    file.write('\t\t$err = $this->quickQuery->delete($this->model' + name.capitalize() + ');\n')
    file.write('\t\tif($err->hasErrors())\n')
    file.write('\t\t\t$err->sendError();\n')
    file.write('\t\telse\n')
    file.write('\t\t\tHttp::sendResponse(Http::CREATED, Json::messageToJSON("Deleted correctly"));\n')
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
            if fileType == "model" or fileType == "-m":
                arg = keyWord[3]
                if len(arg) > 0:
                    createModelObject(arg)
                else:
                    print("Invalid command")
            elif fileType == "controller" or fileType == "-c":
                arg = keyWord[3]
                if len(arg) > 0:
                    createControllerObject(arg)
                else:
                    print("Invalid command")
            elif fileType == "update" or fileType == "-u":
                createUpdate()
            elif fileType == "all" or fileType == "-a":
                arg = keyWord[3]
                if len(arg) > 0:
                    createControllerObject(arg)
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