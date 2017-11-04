<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 09/10/2017
 * Time: 12:24
 */

namespace Labs\ApiBundle;


final class ApiEvents
{
    /**
     * The SET_AUTO_USER_ROLE events when post data user for signIn
     * This Event set defaut User Role in function value field props form action_named (default fields)
     * @Event("Labs\ApiBundle\Event\UserEvent")
     */
    const SET_AUTO_USER_ROLE = "api.set_auto_user_role";

    /**
     * The SET_PHONE_VALUE events when post data user for signIn
     * This Event set defaut Convert phone string in phoneNumber Type
     * @Event("Labs\ApiBundle\Event\UserEvent")
     */
    const SET_PHONE_VALUE = "api.set_set_phone_value";

    /**
     * The SET_VALIDATION_CODE_USER events when post data user for signIn
     * This Event set code validation user
     * @Event("Labs\ApiBundle\Event\UserEvent")
     */
    const SET_VALIDATION_CODE_USER = "api.set_validation_code_user";

    /**
     * The API_SEND_VALIDATION_CODE events when post data user for signIn
     * This Event send api send sms code
     * @Event("Labs\ApiBundle\Event\UserEvent")
     */
    const API_SEND_VALIDATION_CODE = 'api.send_validation_code';

    const API_SET_USERNAME = 'api.set_username';
}