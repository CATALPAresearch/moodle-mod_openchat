<br>
<div align="center">

<img src="pix/openchat.png" width="500" />



curl -X POST http://localhost/moodle413/mod/openchat/llm_stream.php -d "model=phi3:latest" -d "prompt=Hello world"  -d "hostname=http://localhost:11434/api/generate"

</div>

<br>
<h1 align="center">openchat</h1>

## *OpenChat* is a Moodle activity plugin for chatting with open-source large language models.

*openchat* (mod_openchat) is a ready to use Moodle activity plugin that enables students to chat with an open-source large language model. Currently, the plugin is compatible with the API of OpenWebUI (former Ollama). Thus, all models available for OpenWebUI can be selected by the teacher for chat communication of students. 
 

<!-- development-related badges -->
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://github.com/CATALPAresearch/moodle-mod_openchat/commit-activity)
[![github latest commit](https://badgen.net/github/last-commit/CATALPAresearch/moodle-mod_openchat)](https://github.com/CATALPAresearch/mod_openchat/commit/)
[![github contributors](https://badgen.net/github/contributors/CATALPAresearch/moodle-mod_openchat)](https://github.com/CATALPAresearch/mod_openchat/contributors/)
[![github issues](https://img.shields.io/github/issues/CATALPAresearch/moodle-mod_openchat.svg)](https://github.com/CATALPAresearch/moodle-mod_openchat/issues/)
[![GPLv3 license](https://img.shields.io/badge/License-GPLv3-green.svg)](http://perso.crans.org/besson/LICENSE.html)

![https://img.shields.io/badge/any_text-you_like-blue](https://img.shields.io/badge/Tested_Moodle_versions-3.5_to_3.11-green)
![](https://img.shields.io/badge/PHP-7.4_to_8.0.29-green)
![](https://img.shields.io/badge/NPM-~10.2.3-green)
![](https://img.shields.io/badge/node.js-~18.17.0-green)
![](https://img.shields.io/badge/vue.js-2-green)

<!-- Maturity-related badges 
see: https://github.com/mkenney/software-guides/blob/master/STABILITY-BADGES.md
-->
[![stability-alpha](https://img.shields.io/badge/stability-alpha-f4d03f.svg)](https://github.com/mkenney/software-guides/blob/master/STABILITY-BADGES.md#alpha)



<!-- AI-related and LA-related badges -->
<!-- 
https://nutrition-facts.ai/

Privacy Ladder Level
Feature is Optional
Model type
Base model
Base Model Trained with Customer Data
Customer Data is Shared with Model Vendor
Training Data Anonymized
Data Deletion
Human in the Loop
Data Retention
Compliance
-->
![](https://img.shields.io/badge/collects_clickstream_data-no-blue)
![](https://img.shields.io/badge/collects_playback_data-no-blue)
![](https://img.shields.io/badge/collects_scroll_data-no-blue)
![](https://img.shields.io/badge/collects_mouse_data-no-blue)
![](https://img.shields.io/badge/collects_audio_data-no-blue)
![](https://img.shields.io/badge/collects_video_data-no-blue)
![](https://img.shields.io/badge/data_shared_with_vendor-no-blue)



<br><br>
<p align="center" hidden>
  
</p>

<p align="center">
  <a href="#key-features">Key Features</a> •
  <a href="#how-to-use">How To Use</a> •
  <a href="#download">Download</a> •
  <a href="#credits">Credits</a> •
  <a href="#related">Related</a> •
  <a href="#citation">Citation</a> •
  <a href="#license">License</a>
</p>


## Key Features

* server-side communication between PHP and OpenWebUI API. 
* customizable host url, API end points, and API key.
* definition of the proposed LLM model in the plugin settings
* minimal user interface
* (optional) A purely javascript-based interface to OpenWebUI. However, this is not recommended if API is secured by an API key.


## Roadmap and Limitations
**Roadmap**
* binding to a RAG webservice
  * proof of concept
  * add to settings
* support continuous chat sessions => endpoint /chat
* making LLM model selectable by the user (student)
* In future, teachers should be able to define prompt templates that encapsulate student LLM requests. These encapsilations can be used to enrich the prompt with additional instructions or to instruct the LLM to withdraw certain information. 
* UI redesign

nth
* add further language strings and its translations 
* log user interactions, optional als the text input
* compatability with LLM servers other then OpenWebUI, e.g. ChatGPT

**Limitations**
- quickly coded without much testing

## How To Use

To clone and run this application, you'll need [Git](https://git-scm.com) and [Node.js](https://nodejs.org/en/download/) (which comes with [npm](http://npmjs.com)) installed on your computer. From your command line:

```bash
# Clone this repository
1. Clone  the repository to /your-moodle/mod/
$ git clone git@github.com:catalparesearch/moodle-mod_openchat.git

# Rename the folder to 'openchat'
$ mv moodle-mod_openchat openchat

# Go into the repository
$ cd openchat

# Install dependencies
$ cd vue
$ npm install

# Build the plugin by transpiling the vue code into javascript
$ npm run build

# Open the page https://<moodle>/admin/index.php?cache=1 and follow the install instructions for the plugin or
$ php admin/cli/uninstall_plugins.php --plugins=mod_openchat --run

# To install the *openchat* plugin afterwards, copy the repository downloaded in the 1. step into the `mod` folder in the folder your Moodle installation is located in replacing the current `mod/openchat` folder containing the regular *Page* plugin. Now, login to your Moodle running as an administrator. The install/update GUI should open automatically. Just follow the steps the GUI presents to you and you should have installed the *openchat* plugin successfully afterwards. As an alternative to using the GUI for installation, you can also run the update script from within the folder of your Moodle installation:
$ php admin/cli/upgrade.php

# Open a Moodle course of you choice and add openchat as an activity to your course.

# Provide a host URL and API key of your OpenWebUI instance.

```

## Download

You can [download](https://github.com/catalparesearch/moodle-mod_openchat/releases/tag/latest) the latest installable version of *openchat* for Moodle 3.11. or 4.x.

## Getting into Development
Client-side code is located in the folder vue/. The file view.php contains the root DOM element of the video player. The webservice for accessing Moodle database can be found at db/external.php. 


## Emailware

*openchat* is an [emailware](https://en.wiktionary.org/wiki/emailware). Meaning, if you liked using this plugin or it has helped you in any way, I'd like you send me an email at <niels.seidel@fernuni-hagen.de> about anything you'd want to say about this software. I'd really appreciate it!

## Credits

This software uses the following open source packages:
[vue.js](https://vuejs.org/), 
[node.js](https://nodejs.org/).

## Related

* [https://moodle.org/plugins/block_openai_chat](block_openai_chat)

## Citation

```
@misc{Seidel2024-OpenChat,
author = {Seidel, Niels},
doi = {},
title = {{OpenChat - Moodle activity plugin for LLM chats with OpenWebUI}},
url = {https://github.com/CATALPAresearch/moodle-mod{\_}openchat},
year = {2024}
}
```

## You may also like ...

...

## License

[GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html)


## Contributors
* Niels Seidel [@nise81](https://twitter.com/nise81)

---
<a href="https://www.fernuni-hagen.de/english/research/clusters/catalpa/"><img src="pix/promotion/catalpa.jpg" width="300" /></a>
<a href="https://www.fernuni-hagen.de/"><img src="pix/promotion/fernuni.jpg" width="250" /></a>


