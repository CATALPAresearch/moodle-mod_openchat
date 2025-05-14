<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Language file
 *
 * @package    mod_openchat
 * @copyright  2025 Niels Seidel <niels.seidel@fernuni-hagen.de>, CATALPA, FernUniversität Hagen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

// Naming.
$string['name'] = 'OpenChat';
$string['modulename'] = 'OpenChat';
$string['modulenameplural'] = 'OpenChats';
$string['modulename_help'] = 'OpenChat';
$string['pluginadministration'] = 'OpenChat Administration';
$string['pluginname'] = 'OpenChat';

// Plugin Admin Settings.
$string['enable_llm'] = 'Enable LLM chat';
$string['enable_llm_desc'] = 'If enabled, a chat with a LLM is provided';
$string['llm_host'] = 'Hostname of LLM server';
$string['llm_host_desc'] = 'Hostname of the webservice providing access to the Large Language Model';
$string['llm_apikey'] = 'LLM API key';
$string['llm_apikey_desc'] = 'API key of the LLM webservice';
$string['enable_rag'] = 'Enable document chat using RAG';
$string['enable_rag_desc'] = 'If enabled LLM are used with Retrieval Augmented Generation (RAG) to enable chat about the contents of the provided documents';
$string['rag_webservice_host'] = 'Hostname of RAG webservice';
$string['rag_webservice_host_desc'] = 'Hostname of the webservice providing RAG';
$string['rag_webservice_apikey'] = 'RAG API key';
$string['rag_webservice_apikey_desc'] = 'API key of the RAG webservice';
$string['enable_agent_chat'] = 'Enable SRL chat agent';
$string['enable_agent_chat_desc'] = 'If enabled, a chat with an specific agents is provided';

// Errors.
$string['invcmid'] = 'Invalid cmid';
$string['unknown_error'] = 'unknown error';

$string['confentry'] = 'SomeConfig';
$string['confdescription'] = 'Hier ist die Beschreibung';
$string['confvalue'] = 'SomeValue';


$string['title'] = 'Title';
$string['intro'] = 'Aufgabenbeschreibung';
$string['hostname'] = 'Host URL';
$string['apikey'] = 'API Key';
$string['model'] = 'LLM Model';
$string['prompttemplate'] = 'Prompt Template (not implemented yet)';

$string['privacy:metadata'] = 'The Calendar block only displays existing calendar data.';

// Capabilities.
$string['openchat:addinstance'] = 'Add a new OpenChat instance';
$string['openchat:view'] = 'View OpenChat';
$string['openchat:read'] = 'Read OpenChat content';
$string['openchat:edit'] = 'Edit OpenChat content';


// Privacy API.
$string['privacy:metadata:llm_request'] = 'In order to use an LLM users send requests to the LLM';
$string['privacy:metadata:llm_request:chatrequest'] = 'The request that is sent to the LLM by the user.';
$string['privacy:metadata:logstore_standard_log'] = 'In order to improve the service and to adopt specific needs by the user we are collecting behavioral data.';
$string['privacy:metadata:logstore_standard_log:userid'] = 'To track sequences of chat dialogs and interaction patterns we need to identifiy individual users.';
$string['privacy:metadata:logstore_standard_log:eventname'] = 'The eventname identifies the user activity that was performed by the user.';
$string['privacy:metadata:logstore_standard_log:action'] = 'Similar to the eventname the acton specifies the user activity.';
$string['privacy:metadata:logstore_standard_log:other'] = 'Particular values related to the performed user activity is stored in this field. Examples include the prompts sent to the LLM and the responses received from the LLM.';
$string['privacy:metadata:accepted-informed-consent'] = 'In order to know, whether a user aggreed to the OpenChat Informed consent we store this information as a user specific preference across plugin instances and courses.';

$string['accepted-informed-consent'] = 'User aggreed to the informed consent';
$string['widthdraw-informed-consent'] = 'User did not aggree to the informed consent';


