# Olm Api

Server for students to create multiple choice questions to test their knowledge of the stuff they learned.

## Firs Steps

### 0. Install dependencies

You'll need:

* PHP7
* Composer
* MySQL

### 1. Check out the code

```bash
# create a directory for your great project
mkdir olm_api
# get the source code
git clone https://github.com/randomchars42/olm_api
cd olm_api
# install dependencies
composer install
```

### 2. Setup a database

1. create a new database and `src/db/setup.sql` on it
2. tweak `src/config/cfg.php` to suit your setup ;)

### 3. Setup your server

If you are using Apache2 you'll need to enable `mod_rewrite` and `AllowOveride All` in your `sites-available/xxx-yoursite.conf`config. Point the root of your server-configuration to the `web/` directory of the project.

### 4. Go and get 'em

You should be all set up now. If you run into errors, go ahead and fix them (or report them to me ;) ). Same applies to this documentation which will - with your help - improve over time.
