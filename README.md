TpReport
========
[![Made with passion - RST Group](http://rst-it.com/files/rstwithpassion.png)](https://github.com/rstgroup) 
[![Code Climate](https://codeclimate.com/github/rstgroup/TpReport/badges/gpa.svg)](https://codeclimate.com/github/rstgroup/TpReport)
[![Build Status](https://travis-ci.org/rstgroup/TpReport.png?branch=master)](https://travis-ci.org/rstgroup/TpReport) 
[![Test Coverage](https://codeclimate.com/github/rstgroup/TpReport/badges/coverage.svg)](https://codeclimate.com/github/rstgroup/TpReport)

Simple tool for fetching items from [TargetProcess](http://dev.targetprocess.com/rest/getting_started).

Configuring
-----------
* Use [Composer](https://getcomposer.org) and define dependencies by adding following configuration:
```json
   "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/rstgroup/TpReport"
        }
    ],
    "require": {
        "rstgroup/TpReport": "dev-master"
    }
```
* Use [TpReport\Request](src/Request.php) class according to the [example.php](example.php).

