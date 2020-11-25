<?php

class Constant
{
    const PREFIX_API = 'api';
    const RESPONSE_KEY_DATA = 'data';
    const RESPONSE_KEY_SUCCESS = 'succes';
    const RESPONSE_KEY_ERROR = 'error';
    const RESPONSE_KEY_STATUS = 'status';
    const RESPONSE_KEY_MESSAGE = 'message';
    const RESPONSE_KEY_MESSAGE_TYPE = 'message_type';
    const WEB_REQUEST_PATH_KEY = 'web_request_path';
    const API_REQUEST_PATH_KEY = 'api_request_path';
    const API_VERSION_PATTERN = '#^([v])([\-,\*,\w]+[\.]?)+(\w)*#i';
    const X_API_TOKEN = 'X-API-TOKEN';
}
