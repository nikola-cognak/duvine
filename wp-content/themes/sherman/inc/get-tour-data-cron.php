<?php
/*********************************************************
 *
 * This script pulls data from the source API.
 * If no errors are found, then the script backs up
 * current feed and moves new data live.
 *
 * If "critical" errors are found, then data
 * will not be pushed live
 *
 * If there are "critical" or "warning" errors, then
 * script will notify client/tank
 *
 * Meant to run as a cron
 *
 *********************************************************/

