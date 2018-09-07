Docker Dev Environment
----------------
The purpose of this project is to provide as bare-bones a dev environment as possible. Since each project will doubtless have its own unique requirements, the aim here is to have a base file set that can be extended to match whatever a given project requires.

The standard name throughout this file set is docker-dev and docker_dev_db. These are the only two names used throughout the project to specify url, database name, error log path, etc. 

Once again, the purpose of this project is to provide a foundation that can be easily built upon so that the ramp up time for future projects is minimal.

## Docker Development Environment

The CorpStruct application utilizes Docker for developers to set up and manage their development environment. Below is some provided information for running and customizing this environment.

### General Vocabulary

* **Build** - A set of scripts that assembles a new image from a Dockerfile. 
* **Container** - A lightweight virtual environment that runs from a basic image and utilizes the services of the host machine.
* **Docker** - An engine that provides the power to run lightweight containers that use the services of the host machine, rather than full virtual machines.
* **Docker-compose** - An extension of Docker, offering the power of multi-container management and simple configuration through a single YML file. 
* **Dockerfile** - A file containing commands in the Docker markdown language that provides instructions for building a new image.
* **Image** - A read-only template with built-in instructions for creating a Docker container.
* **Make** - A library available on *nix based systems that allows for sets of instructions to be executed, such as installation scripts and configurations.

### Setup and Dependencies

This project has several dependencies that must be met in order for the application to run. Follow the below guides for setting up these dependencies on your system.

#### [Docker Compose](https://docs.docker.com/compose/install/)

Docker Engine and Docker Compose are necessary components of this application, so that the containers can be built and managed from the command line. Follow the link above for instructions on installing both Docker Engine and Docker Compose.

#### OdinCA

VMWare Workstation or VMWare Fusion are necessary in order to run a virtual machine for the Odin authentication server. There are currently no Docker solutions for the Windows Active Directory on Linux-based machines.

A guide is forthcoming for setting up and configuring the OdinCA virtual instance.

**Important:** You will need to reset the password for the "keyjobs" user on the Active Directory for the VM, as well as rename the "roliv" user to reflect the user name you will be using on your local containers.

#### docker-compose.yml.dist

Once dependency applications are installed, copy the docker-compose.yml.dist into a new file docker-compose.yml in the same directory. This file is where you will make any settings changes needed in order to run the application to your specifications.

See [the guide for the docker-compose](docker-compose) for instructions on how to modify this file.

#### Keyserver

The Keyserver git repository must be checked out on your host system in preparation to be mounted. See [the guide for the docker-compose](docker-compose) for instructions on how to mount the keyserver app from a custom directory.

### Make Commands

A Docker Compose file has been built for use with the application, and a Makefile included with this Compose to smoothly assist in the setup and/or deletion of containers related to the CorpStruct app. What this means for developers is that the CorpStruct application environment can be built on your local machine with the execution of a single command, and customized through the editing of a single file (docker-compose.yml).

The make command can be run within the directory where the Makefile is found (the Git repo’s root directory), however if you want to run it from another directory use `make -C /path/to/dir`.

`make` - Displays a Usage guide with the below commands.

`sudo make compose-up` - Builds Docker images, starts the containers, and adds their IP addresses to your /etc/hosts file.

`sudo make compose-destroy-cont` - Stops Docker containers that were started by the Docker Compose, and deletes them.

`sudo make compose-destroy-all` - Stops Docker containers that were started by the Docker Compose, deletes them, and also deletes the images that were built.

### Basic Commands

The following Docker commands provide some general utilities for managing and viewing your Docker containers.

`docker ps -a` - Displays a list of all currently running containers. Add the `-q` parameter to list only the hex IDs for the containers.

`docker images` - Displays a list of all images that have been built on the system. Add the `-q` parameter to list only the hex IDs for the containers.

`docker build -t <new image name> </path/to/folder>` - Builds a new image, utilizing the dockerfile in the specified folder and assigning the given name to that image.

`docker exec -it <container name> /bin/bash` - Connects to an actively running container for access to Bash commands. (Substitute /bin/bash with /bin/sh if you would prefer SH commands.)

`docker run -it <image name> -v <local folder>:<remote folder>` - Creates a new container from an existing image and attaches to that container’s command line. Substitute `-it` for `-d` in order to run in detached mode, without a command line, and you can use `docker exec` above to attach to the container at will.

`CTRL+P, CTRL+Q` - Detaches from a currently attached container without stopping it.

`exit` - Exits from a currently attached container, while also stopping that container.

`docker stop <container name>` - Stops an actively running container.

`docker rm <container name>` - Deletes an actively running container.

`docker rmi <image name>` - Deletes an image that has been built from a Dockerfile or repository.

### Docker Compose

The following is a breakdown of the directives found in the `docker-compose.yml.dist` file that is included at the base of this repo. Many of the settings in this file can be modified in order to customize the usage of these applications, and some information on how to do so may be found below. In order to use this file in your docker-compose, you should copy `docker-compose.yml.dist` into `docker-compose.yml`, where you may edit the directives to your preference.

Also refer to the [Compose file reference](https://docs.docker.com/compose/compose-file/) and the [Dockerfile reference](https://docs.docker.com/engine/reference/builder/) for official documentation on the various commands and top-level keys available when building Docker containers.

#### Basic Structure Syntax

1. `version: '3'` - This denotes what version of the Docker Compose engine to use. It's unlikely that you will need to change it.

2. `services:` - This header designates what containers will be defined by the docker-compose. There are two containers in this project, `main` and `keyserver`, which are detailed below.

#### Containers

1. `main:` - The `main` section executes the Dockerfile for the application. This application is dependent on the keyserver application for authentication and privilege grants.

2. `keyserver:` - The `keyserver` section executes the Dockerfile for the Keyserver application. Keyserver is dependent on one of the CA servers, such as Odin.

#### Sections

##### `build:`

This header defines how the container is built. It's possible to use this as a single top-level key without any parameters, e.g. `build: main/.`. This will simply run the Dockerfile specified in the path or build the image from the registry, without any additional configurations.

* `context: main/.` - The context defines where Docker Compose can find the Dockerfile for this specific container. In this case, it will be found in the `main/` folder within this repository.

* `args:` - Arguments are passed into the container as environment variables during the initial build of the image.

  * `MYSQL_ROOT_PASSWORD:` - Defines the root password to be set when MySQL is installed on the container. Rather than change this after the build, it's best to define this at the beginning.

##### `stdin_open` and `tty`

These two values define whether the running container should be started with an interactive shell. They should not need to be changed.

##### `environment`

This section defines a set of environment variables that will be stored in the container once it has started. Each variable is used in different places as part of the startup scripts, and determines how the containers are to be configured.

* `MYSQL_HOSTNAME`, `MYSQL_PORT`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_DATABASE` - These will determine the settings for connecting to and populating the MySQL database that will be used by each container. By default, both containers will have MySQL Server 5.7 and MySQL Client 5.7 installed, and their respective databases will be imported into those local servers. However, it is possible if you prefer to configure settings to connect to an external MySQL server. Be advised that the `rollup` database on the specified server will be blown away and rebuilt by the build script, so **do not** use the test or production database settings here.
* `KEYSERVER_URL`, `KEYSERVER_SSL`, `KEYSERVER_API_KEY` - Defines the settings for connecting to the Keyserver. By default this is designed to connect to the `keyserver` container that is created by the Docker Compose, but it can be configured to connect to one of the other keyservers instead.
* `AD_SERVER_IP`, `AD_SERVER_HOST_NAME` - Use these settings to define the address of the Active Directory server of your choice. This can be set to a VMWare that has been created on your local machine, or to one of the other Active Directory servers of your choice.
* `LOCAL_CONFIG_FILE_PATH` - This specifies the path to the directory that contains the `local.php` and `local.php.dist` files used by the Zend Framework application. Change this path to reflect whatever has been defined as the mount point in `volumes`.
* `LOCAL_CONFIG_FILE_DIST`, `LOCAL_CONFIG_FILE_ACTIVE` - Specifies the .dist file that contains the base settings for the Zend Framework application. The build scripts will use this file to create a new file defined by `LOCAL_CONFIG_FILE_ACTIVE`, and uses `sed` commands to replace the values in that file with the configuration settings defined above.
* `LOCAL_AUTH_CONFIG_FILE_DIST`, `LOCAL_AUTH_CONFIG_FILE_ACTIVE` - Specifies the .dist file that contains the base settings for authenticating with the Odin server. The build will use this file to create a new file defined by `LOCAL_AUTH_CONFIG_FILE_ACTIVE` and uses `sed` commands to replace the values in that file with the configuration settings.
* `DB2_USER`, `DB2_PASSWORD` - Specifies the credentials for connecting to the DB2 database on the iSeries.
* `REDIRECT_URL` - After the CA server authenticates a user, it will redirect the browser to the URL specified in this environment variable.
* `TEST_ROLES_USER`, `TEST_ROLES_EMAIL` - Use these settings to identify the user you create on your CA server environment to generate the roles you'll need during development.

##### `volumes`

The directives provided under this section heading will create mount points from the directory on the host machine to the target directory on the container, as such: `- <host directory>:<target directory>`. Currently this is defaulted to mount the applications from `./main/app` and `./keyserver/app` respectively, however if you have checked out the Git repositories for these two applications in other directories on your machine, you can specify those directories instead.
