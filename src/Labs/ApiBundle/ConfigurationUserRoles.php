<?php
/**
 * Created by IntelliJ IDEA.
 * User: raphael
 * Date: 09/10/2017
 * Time: 11:55
 */

namespace Labs\ApiBundle;


final class ConfigurationUserRoles
{
     const UserRole = [
         'form_action_client'   => 'ROLE_USER',
         'form_action_seller'   => 'ROLE_SELLER',
         'form_action_compagny' => 'ROLE_COMPAGNY',
         'form_action_editor'   => 'ROLE_EDITEUR'
     ];
}