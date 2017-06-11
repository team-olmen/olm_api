# Olm Api

Server component of a suite for students to create multiple-choice questions to test their knowledge of the stuff they've learnt.

## Features

A CRUD API which allows:

* creating and maintaining of an arbitray number of collections of  multiple-choice questions
* filtering of collections
* creating and maintnaining individual test sessions for each user
* markdown / flatfile based note collections to share experiences / tips between users
* creating and maintaing of users (incl. password reset)
* ...

## First Steps

### 0. Install dependencies

You'll need:

* `PHP7`
* `Composer`
* `MySQL`

### 1. Check out the code

```bash
# create a directory for the project
mkdir olm_api
# get the source code
git clone https://github.com/randomchars42/olm_api
cd olm_api
# install dependencies
composer install
```

### 2. Prepare your server

If you are using Apache2 you'll need to enable `mod_rewrite` and `AllowOveride All` in your `sites-available/###-YOURSITE.conf` config. Point the root of your server-configuration to the `web/` directory of the project.

### 3. Prepare a database

1. create a new database
2. tweak `src/config/cfg.php` to suit your setup ;)
3. direct your browser to `/api/setup`

### 4. Go and get 'em

You should be all set up now. If you run into errors, go ahead and fix them (or report them to me ;) ). Same applies to this documentation which will - with your help - improve over time.

## Improving the code

### Versioning

olm_api uses semantic versioning (<http://semver.org/>).

`TL;DR`:

```
MAJOR.MINOR.PATCH

MAJOR version when you make incompatible API changes,
MINOR version when you add functionality in a backwards-compatible manner, and
PATCH version when you make backwards-compatible bug fixes.
```

To mark pre-releases you may add:

* `-rc[0-9]*` for release-candidates
* `-beta[0-9]*` for beta-releases
* `-alpha[0-9]*` for alpha-releases
