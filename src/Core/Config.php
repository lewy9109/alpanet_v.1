<?php

namespace App\Core;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'alpanet_help_desk'; //alpanet_crm / alpanet_help_desk

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'alpanet_crm';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'qj2rv3Oa7yg2y'; //qj2rv3Oa7yg2y

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     *
     *Secret key for hashing
     */
    const SECRET_KEY = 'QMG8qBfocyDHCg8TR7yna9s1uEvzlptn';

    /**
     * PHP MAILER CONFIG
     */

     const HOST = "poczta.am1.pl";

     const USERNAME = "pakiet@alpanet.pl";

     const PASSWORD = "wrkdHA3N8dwhv";

     const MAILTO = "biuro@alpanet.pl";

}
