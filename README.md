# Backint
PHP Framework. Easier way to do an API.

## Get started
### Configuration
Inside `config` folder you can find a configuration php file. All settings framework can adjusted by this file.
#### URL
Standard route on Backint is `backint\` that means you can access to the server using the route: `https://my-domain.com/backint/some-route`. If ypu want to change this route you must edit `ROUTE_INDEX` with your new route.

#### CONFIGURATION TABLES
Backint was design thinking about building a webpage using backend data. You can configurate all params about the configuration tables.

`TABLE_CONFIG_PREFIX` define a prefix por configuration tables, for example `config` means you will have tables whose name will be 
> config_example

`ROUTE_CONFIG` define the route where you are going to get configuration tables from database. For example, if you define `ROUTE_CONFIG` as `config` your route will be
> https://my-domain.com/backint/config/tableName

`TABLE_CONFIG_STRUCTURE` define whole table's structure. This config params is an array with name, type and size. You should define here how you can create your configuration tables.

#### SPECIAL ROUTES
`SPECIAL_ROUTES` define some routes that does not follow some basic and standard rules routes. On Backint a route is composed by regular params and id params. A regular params is a word and this word define a route. In adition an id params is a number and it will be represented by an '?'. Therefore, if you want to use a word like an id params you must create a special route and define it right here.

#### DATABASE CONNECTION
You must define all SQL params on this section. Params name:
```
DATABASE
HOST
DATABASE_USER
DATABASE_PASSWORD
```

#### SECURITY
On this section you can configurate who can in and what they can do.

### USING backint-cmd.py
backint-cmd is a tool developed on Python. This app will help you to create some objects in a easier and faster way. Some commands are:
```
Create a new api model object using an argument
> itk -g -m arg
Create a new inferface object using an argument
> itk -g -i arg
Create a MySQL sentence to create a configuration table using an argument and `TABLE_CONFIG_STRUCTURE` configuration
> itk -g -c arg
Create a api model and a interface model using an argument
> itk -g -a arg
Create getters and setters using an Interface Object by a name pased by argument
> itk -a arg
Show all options on level command
> itk ?
```

### USING backint-cmd.php
You do not have python? Do it by php.

Created by Interik Team